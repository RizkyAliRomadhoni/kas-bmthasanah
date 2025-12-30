<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kas;
use App\Models\KasKambing;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class KasController extends Controller
{
    /**
     * INDEX — TAMPIL DATA DENGAN FILTER & RINGKASAN
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

        // TAMPILAN: Urutkan Terbaru di Atas (Desc) untuk kenyamanan user
        $kas = $query->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->get();

        // SALDO GLOBAL TERAKHIR (Berdasarkan kronologi terbaru)
        $lastEntry = Kas::orderBy('tanggal', 'desc')->orderBy('id', 'desc')->first();
        $saldo = $lastEntry ? $lastEntry->saldo : 0;

        // KAS KAMBING (Fungsi Asli Anda)
        $kasKambingMasuk = KasKambing::where('jenis', 'pemasukan')->sum('jumlah');
        $kasKambingKeluar = KasKambing::where('jenis', 'pengeluaran')->sum('jumlah');

        // FILE AKUN JSON (Fungsi Asli Anda)
        $akunFile = storage_path('app/akun.json');
        if (!File::exists($akunFile)) {
            File::put($akunFile, json_encode(['Modal','Utang','Piutang','Kambing','Tabungan','Lainnya']));
        }
        $akunList = json_decode(File::get($akunFile), true);

        // RINGKASAN SESUAI FILTER
        $totalMasuk = (clone $query)->where('jenis_transaksi', 'Masuk')->sum('jumlah');
        $totalKeluar = (clone $query)->where('jenis_transaksi', 'Keluar')->sum('jumlah');
        
        // Saldo Ringkasan mengambil saldo baris teratas dari hasil filter
        $saldoRingkasan = $kas->first() ? $kas->first()->saldo : $saldo;

        return view('kas.index', compact(
            'kas', 'saldo', 'kasKambingMasuk', 'kasKambingKeluar', 
            'akunList', 'totalMasuk', 'totalKeluar', 'saldoRingkasan'
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
     * SIMPAN & HITUNG ULANG
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
        $akunList = json_decode(File::get($akunFile), true);

        if ($request->filled('akun_baru')) {
            $akun = trim($request->akun_baru);
            if (!in_array($akun, $akunList)) {
                $akunList[] = $akun;
                File::put($akunFile, json_encode($akunList, JSON_PRETTY_PRINT));
            }
        } else {
            $akun = $request->akun;
        }

        Kas::create([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'jenis_transaksi' => $request->jenis_transaksi,
            'jumlah' => $request->jumlah,
            'akun' => $akun,
            'saldo' => 0, 
            'user_input' => Auth::user()->name ?? 'System',
        ]);

        $this->recalculateSaldo();

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
        $kas->update($request->all());

        $this->recalculateSaldo();

        return redirect()->route('kas.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kas = Kas::findOrFail($id);
        $kas->delete();

        $this->recalculateSaldo();

        return redirect()->route('kas.index')->with('success', 'Data berhasil dihapus.');
    }

    /**
     * FUNGSI RECALCULATE — INI MESINNYA
     */
    private function recalculateSaldo()
    {
        DB::transaction(function () {
            $kasList = Kas::orderBy('tanggal', 'asc')->orderBy('id', 'asc')->get();
            $tempSaldo = 0;

            foreach ($kasList as $item) {
                $tempSaldo = $item->jenis_transaksi === 'Masuk' 
                    ? $tempSaldo + $item->jumlah 
                    : $tempSaldo - $item->jumlah;
                
                DB::table('kas')->where('id', $item->id)->update(['saldo' => $tempSaldo]);
            }
        });
    }

    public function resetSaldo()
    {
        $this->recalculateSaldo();
        return redirect()->route('kas.index')->with('success', 'Saldo berhasil dihitung ulang dari nol.');
    }

    /**
     * KELOLA AKUN (FUNGSI ASLI ANDA)
     */
    public function kelolaAkun(Request $request)
    {
        $akunFile = storage_path('app/akun.json');
        if (!File::exists($akunFile)) {
            File::put($akunFile, json_encode(['Modal','Utang','Piutang','Kambing','Tabungan','Lainnya'], JSON_PRETTY_PRINT));
        }
        $akunList = json_decode(File::get($akunFile), true);

        if ($request->filled('tambah_akun')) {
            $akunBaru = trim($request->tambah_akun);
            if (!in_array($akunBaru, $akunList)) {
                $akunList[] = $akunBaru;
                File::put($akunFile, json_encode($akunList, JSON_PRETTY_PRINT));
            }
            return back()->with('success', 'Akun berhasil ditambahkan');
        }

        if ($request->filled('hapus_akun')) {
            $akun = $request->hapus_akun;
            if (Kas::where('akun', $akun)->exists()) {
                return back()->with('error', 'Akun tidak bisa dihapus karena sudah digunakan transaksi');
            }
            $akunList = array_values(array_filter($akunList, function ($item) use ($akun) { return $item !== $akun; }));
            File::put($akunFile, json_encode($akunList, JSON_PRETTY_PRINT));
            return back()->with('success', 'Akun berhasil dihapus');
        }

        if ($request->filled('akun_lama') && $request->filled('akun_baru')) {
            $akunLama = $request->akun_lama;
            $akunBaru = trim($request->akun_baru);
            foreach ($akunList as &$item) { if ($item === $akunLama) $item = $akunBaru; }
            File::put($akunFile, json_encode($akunList, JSON_PRETTY_PRINT));
            Kas::where('akun', $akunLama)->update(['akun' => $akunBaru]);
            return back()->with('success', 'Nama akun diperbarui');
        }
        return back();
    }

    /**
     * EXPORT PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Kas::query();
        if ($request->filled('bulan')) {
            $bulan = Carbon::parse($request->bulan);
            $query->whereMonth('tanggal', $bulan->month)->whereYear('tanggal', $bulan->year);
        }
        if ($request->filled('akun')) {
            $query->where('akun', $request->akun);
        }

        $kas = $query->orderBy('tanggal', 'asc')->orderBy('id', 'asc')->get();
        $totalMasuk = (clone $query)->where('jenis_transaksi', 'Masuk')->sum('jumlah');
        $totalKeluar = (clone $query)->where('jenis_transaksi', 'Keluar')->sum('jumlah');

        $pdf = Pdf::loadView('kas.export-pdf', [
            'kas' => $kas,
            'bulan' => $request->bulan,
            'akun' => $request->akun,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'saldoRingkasan' => $totalMasuk - $totalKeluar
        ]);

        return $pdf->download('laporan-kas.pdf');
    }

    public function show($id) {
        $kas = Kas::findOrFail($id);
        return view('kas.show', compact('kas'));
    }
}