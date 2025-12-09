<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kas;
use App\Models\KasKambing;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf;

class KasController extends Controller
{
    /**
     * Tampilkan daftar transaksi kas global.
     */
    public function index(Request $request)
    {
        $query = Kas::query();

        // FILTER BULAN
        if ($request->filled('bulan')) {
            try {
                $bulan = Carbon::parse($request->bulan);
                $query->whereMonth('tanggal', $bulan->month)
                      ->whereYear('tanggal', $bulan->year);
            } catch (\Exception $e) {}
        }

        // FILTER AKUN
        if ($request->filled('akun')) {
            $query->where('akun', $request->akun);
        }

        // DATA untuk perhitungan saldo → ASC
        $kasAsc = $query->orderBy('tanggal', 'asc')->orderBy('id', 'asc')->get();

        // ==========================
        // 🔥 HITUNG SALDO DARI AWAL
        // ==========================
        $saldo = 0;
        foreach ($kasAsc as $item) {
            if ($item->jenis_transaksi === 'Masuk') {
                $saldo += $item->jumlah;
            } else {
                $saldo -= $item->jumlah;
            }

            // Simpan saldo ke database
            $item->saldo = $saldo;
            $item->save();
        }

        // KAS KAMBING (tidak diubah)
        $kasKambingMasuk = KasKambing::where('jenis', 'pemasukan')->sum('jumlah');
        $kasKambingKeluar = KasKambing::where('jenis', 'pengeluaran')->sum('jumlah');
        $saldo += ($kasKambingMasuk - $kasKambingKeluar);

        // FILE AKUN
        $akunFile = storage_path('app/akun.json');
        if (!File::exists($akunFile)) {
            File::put($akunFile, json_encode(['Modal','Utang','Piutang','Kambing','Tabungan','Lainnya']));
        }
        $akunList = json_decode(File::get($akunFile), true);

        // RINGKASAN
        $totalMasuk = (clone $query)->where('jenis_transaksi', 'Masuk')->sum('jumlah');
        $totalKeluar = (clone $query)->where('jenis_transaksi', 'Keluar')->sum('jumlah');
        $saldoRingkasan = $totalMasuk - $totalKeluar;

        // ==========================
        // 🔥 TAMPILKAN LIST → DESC  
        // ==========================
        $kas = $kasAsc->sortByDesc('tanggal')->values();

        return view('kas.index', compact(
            'kas',
            'saldo',
            'kasKambingMasuk',
            'kasKambingKeluar',
            'akunList',
            'totalMasuk',
            'totalKeluar',
            'saldoRingkasan'
        ));
    }

    public function create()
    {
        $akunFile = storage_path('app/akun.json');
        if (!File::exists($akunFile)) {
            File::put($akunFile, json_encode(['Modal','Utang','Piutang','Kambing','Tabungan','Lainnya']));
        }
        $akunList = json_decode(File::get($akunFile), true);

        return view('kas.create', compact('akunList'));
    }

    /**
     * Simpan transaksi baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'jenis_transaksi' => 'required|in:Masuk,Keluar',
            'jumlah' => 'required|numeric|min:0',
            'akun' => 'nullable|string|max:255',
            'akun_baru' => 'nullable|string|max:255',
        ]);

        $akunFile = storage_path('app/akun.json');
        if (!File::exists($akunFile)) {
            File::put($akunFile, json_encode(['Modal','Utang','Piutang','Kambing','Tabungan','Lainnya']));
        }
        $akunList = json_decode(File::get($akunFile), true);

        // akun baru
        if ($request->filled('akun_baru')) {
            $akun = trim($request->akun_baru);
            if (!in_array($akun, $akunList)) {
                $akunList[] = $akun;
                File::put($akunFile, json_encode($akunList, JSON_PRETTY_PRINT));
            }
        } else {
            $akun = $request->akun;
        }

        // saldo terbaru = dari transaksi terakhir
        $lastSaldo = Kas::orderBy('id', 'desc')->value('saldo') ?? 0;
        $saldoBaru = $request->jenis_transaksi === 'Masuk'
            ? $lastSaldo + $request->jumlah
            : $lastSaldo - $request->jumlah;

        Kas::create([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'jenis_transaksi' => $request->jenis_transaksi,
            'jumlah' => $request->jumlah,
            'akun' => $akun,
            'saldo' => $saldoBaru,
            'user_input' => Auth::user()->name ?? 'System',
        ]);

        return redirect()->route('kas.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kas = Kas::findOrFail($id);

        $akunFile = storage_path('app/akun.json');
        $akunList = json_decode(File::get($akunFile), true);

        return view('kas.edit', compact('kas', 'akunList'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'jenis_transaksi' => 'required|in:Masuk,Keluar',
            'jumlah' => 'required|numeric|min:0',
            'akun' => 'required|string',
        ]);

        $kas = Kas::findOrFail($id);

        // saldo sebelum transaksi tersebut
        $saldoSebelumnya = Kas::where('id', '<', $kas->id)
                                ->orderBy('id', 'desc')
                                ->value('saldo') ?? 0;

        // saldo baru
        $saldoBaru = $request->jenis_transaksi === 'Masuk'
            ? $saldoSebelumnya + $request->jumlah
            : $saldoSebelumnya - $request->jumlah;

        // update transaksi ini
        $kas->update([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'jenis_transaksi' => $request->jenis_transaksi,
            'jumlah' => $request->jumlah,
            'akun' => $request->akun,
            'saldo' => $saldoBaru,
        ]);

        // PERBARUI TRANSAKSI SETELAHNYA
        $saldoTemp = $saldoBaru;
        $after = Kas::where('id', '>', $kas->id)->orderBy('id')->get();

        foreach ($after as $item) {
            $saldoTemp = $item->jenis_transaksi === 'Masuk'
                ? $saldoTemp + $item->jumlah
                : $saldoTemp - $item->jumlah;

            $item->update(['saldo' => $saldoTemp]);
        }

        return redirect()->route('kas.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kas = Kas::findOrFail($id);

        // saldo sebelum item dihapus
        $saldoSebelumnya = Kas::where('id', '<', $kas->id)
                              ->orderBy('id', 'desc')
                              ->value('saldo') ?? 0;

        $kas->delete();

        // perbaiki saldo transaksi berikutnya
        $saldoTemp = $saldoSebelumnya;
        $after = Kas::where('id', '>', $id)->orderBy('id')->get();

        foreach ($after as $item) {
            $saldoTemp = $item->jenis_transaksi === 'Masuk'
                ? $saldoTemp + $item->jumlah
                : $saldoTemp - $item->jumlah;

            $item->update(['saldo' => $saldoTemp]);
        }

        return redirect()->route('kas.index')->with('success', 'Data berhasil dihapus.');
    }

    /**
     * Reset saldo dari awal (perhitungan ulang).
     */
    public function resetSaldo()
    {
        $kasList = Kas::orderBy('id')->get();
        $saldo = 0;

        foreach ($kasList as $kas) {
            $saldo = $kas->jenis_transaksi === 'Masuk'
                ? $saldo + $kas->jumlah
                : $saldo - $kas->jumlah;

            $kas->update(['saldo' => $saldo]);
        }

        return redirect()->route('kas.index')
            ->with('success', 'Saldo berhasil dihitung ulang.');
    }

    /**
     * EXPORT PDF SESUAI FILTER
     */
    public function exportPdf(Request $request)
    {
        $query = Kas::query();

        // FILTER
        if ($request->filled('bulan')) {
            try {
                $bulan = Carbon::parse($request->bulan);
                $query->whereMonth('tanggal', $bulan->month)
                      ->whereYear('tanggal', $bulan->year);
            } catch (\Exception $e) {}
        }

        if ($request->filled('akun')) {
            $query->where('akun', $request->akun);
        }

        $kas = $query->orderBy('tanggal', 'asc')->get();

        // ringkasan
        $totalMasuk = (clone $query)->where('jenis_transaksi', 'Masuk')->sum('jumlah');
        $totalKeluar = (clone $query)->where('jenis_transaksi', 'Keluar')->sum('jumlah');
        $saldoRingkasan = $totalMasuk - $totalKeluar;

        $pdf = Pdf::loadView('kas.export-pdf', [
            'kas' => $kas,
            'bulan' => $request->bulan,
            'akun' => $request->akun,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'saldoRingkasan' => $saldoRingkasan
        ]);

        return $pdf->download('laporan-kas.pdf');
    }

    public function show($id)
{
    $kas = Kas::findOrFail($id);

    return view('kas.show', compact('kas'));
}

}


