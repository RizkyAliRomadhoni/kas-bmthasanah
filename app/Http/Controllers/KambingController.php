<?php

namespace App\Http\Controllers;

use App\Models\Kambing;
use App\Models\Kas;
use App\Models\KasKambing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KambingController extends Controller
{
    /**
     * Menampilkan daftar kambing + ringkasan kas_kambing (BUKAN kas global)
     */
    public function index()
    {
        $kambing = Kambing::latest()->get();

        // 🔹 Ambil data dari kas_kambing saja
        $totalMasuk = KasKambing::where('jenis', 'pemasukan')->sum('jumlah');
        $totalKeluar = KasKambing::where('jenis', 'pengeluaran')->sum('jumlah');
        $saldo = $totalMasuk - $totalKeluar;

        return view('kambing.index', compact('kambing', 'totalMasuk', 'totalKeluar', 'saldo'));
    }

    /**
     * Tampilkan form tambah kambing
     */
    public function create()
    {
        return view('kambing.create');
    }

    /**
     * Simpan data kambing baru dan catat transaksi di tabel kas_kambing
     */
   public function store(Request $request)
{
    $request->validate([
        'kode' => 'required|string|max:100|unique:kambing,kode',
        'nama' => 'required|string|max:255',
        'jumlah' => 'required|integer|min:1',
        'berat_awal' => 'required|numeric|min:0',
        'konsumsi_pakan' => 'nullable|string|max:255',
        'harga_beli' => 'required|numeric|min:0',
        'harga_jual' => 'nullable|numeric|min:0',
    ]);

    DB::beginTransaction();

    try {

        /**
         * ======================================================
         * 1. Tentukan transaksi: PEMBELIAN atau PENJUALAN
         * ======================================================
         */
        if ($request->harga_jual > 0) {
            // berarti PEJUALAN
            $jenisKambing = 'keluar';
            $jenisKas = 'pemasukan';
            $jumlahUang = $request->harga_jual * $request->jumlah;
            $keterangan = "Pemasukan - Penjualan kambing: " . $request->nama;
        } else {
            // berarti PEMBELIAN
            $jenisKambing = 'masuk';
            $jenisKas = 'pengeluaran';
            $jumlahUang = $request->harga_beli * $request->jumlah;
            $keterangan = "Pengeluaran - Pembelian kambing: " . $request->nama;
        }

        /**
         * ======================================================
         * 2. Simpan data kambing
         * ======================================================
         */
        $k = new Kambing();
        $k->kode = $request->kode;
        $k->nama = $request->nama;
        $k->jumlah = $request->jumlah;
        $k->berat_awal = $request->berat_awal;
        $k->konsumsi_pakan = $request->konsumsi_pakan;
        $k->harga_beli = $request->harga_beli;
        $k->harga_jual = $request->harga_jual;
        $k->jenis_transaksi = $jenisKambing;
        $k->save();

        /**
         * ======================================================
         * 3. Simpan ke kas_kambing
         * ======================================================
         */
        $lastSaldoKambing = KasKambing::orderBy('id', 'desc')->value('saldo') ?? 0;

        $saldoBaruKambing = $jenisKas === 'pemasukan'
            ? $lastSaldoKambing + $jumlahUang
            : $lastSaldoKambing - $jumlahUang;

        KasKambing::create([
            'kambing_id' => $k->id,
            'jenis' => $jenisKas,
            'jumlah' => $jumlahUang,
            'saldo' => $saldoBaruKambing,
            'keterangan' => $keterangan,
            'tanggal' => now(),
        ]);

        /**
         * ======================================================
         * 4. Simpan ke tabel kas (GLOBAL)
         * ======================================================
         */
        $lastSaldoKas = Kas::orderBy('id', 'desc')->value('saldo') ?? 0;

        $saldoBaruKas = $jenisKas === 'pemasukan'
            ? $lastSaldoKas + $jumlahUang
            : $lastSaldoKas - $jumlahUang;

        Kas::create([
            'akun' => 'kambing',
            'jenis_transaksi' => 'Keluar',        // <<< sudah benar
            'jumlah' => $jumlahUang,
            'saldo' => $saldoBaruKas,
            'keterangan' => $keterangan,
            'tanggal' => now(),
        ]);

        DB::commit();

        return redirect()->route('kambing.index')
            ->with('success', 'Data kambing dan transaksi berhasil disimpan.');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
    }
}


    /**
     * Tampilkan form edit kambing
     */
    public function edit($id)
    {
        $kambing = Kambing::findOrFail($id);
        return view('kambing.edit', compact('kambing'));
    }

    /**
     * Update data kambing (tidak mengubah kas)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'berat_awal' => 'required|numeric|min:0',
            'konsumsi_pakan' => 'nullable|string|max:255',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'nullable|numeric|min:0',
        ]);

        $k = Kambing::findOrFail($id);
        $k->nama = $request->nama;
        $k->jumlah = $request->jumlah;
        $k->berat_awal = $request->berat_awal;
        $k->konsumsi_pakan = $request->konsumsi_pakan;
        $k->harga_beli = $request->harga_beli;
        $k->harga_jual = $request->harga_jual;
        $k->save();

        return redirect()->route('kambing.index')->with('success', 'Data kambing berhasil diperbarui.');
    }

    /**
     * Hapus data kambing (dan transaksi kas_kambing terkait)
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $k = Kambing::findOrFail($id);
            KasKambing::where('kambing_id', $k->id)->delete();
            $k->delete();

            DB::commit();

            return redirect()->route('kambing.index')->with('success', 'Data kambing dan transaksi terkait berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function showJualForm()
    {
        $kambing = Kambing::all();
        return view('kambing.jual', compact('kambing'));
    }

    public function transaksiList($id = null)
    {
        if ($id === null) {
            $transaksi = KasKambing::with('kambing')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $transaksi = KasKambing::where('kambing_id', $id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('kambing.transaksi-list', compact('transaksi'));
    }

    public function prosesJual(Request $request)
 {
    $request->validate([
        'kambing_id' => 'required|exists:kambing,id',
        'harga_jual' => 'required|numeric|min:0',
        'tanggal_jual' => 'nullable|date',
        'keterangan' => 'nullable|string|max:255',
    ]);

    DB::beginTransaction();

    try {
        $kambing = Kambing::findOrFail($request->kambing_id);
        $tanggalTransaksi = $request->tanggal_jual ?? now();

        /**
         * 🔹 Update kas_kambing (pemasukan)
         */
        $lastSaldoKambing = KasKambing::orderBy('id', 'desc')->value('saldo') ?? 0;
        $saldoBaruKambing = $lastSaldoKambing + $request->harga_jual;

        KasKambing::create([
            'kambing_id' => $kambing->id,
            'jenis' => 'pemasukan',
            'jumlah' => $request->harga_jual,
            'saldo' => $saldoBaruKambing,
            'keterangan' => 'Penjualan kambing: ' . $kambing->nama,
            'tanggal' => $tanggalTransaksi,
        ]);


        /**
         * 🔥 TAMBAHAN: Insert ke tabel KAS GLOBAL
         */
        $lastSaldoKas = Kas::orderBy('id', 'desc')->value('saldo') ?? 0;
        $saldoBaruKas = $lastSaldoKas + $request->harga_jual;

        Kas::create([
            'akun' => 'kambing',   // sesuai permintaan Anda
            'jenis_transaksi' => 'Masuk',
            'jumlah' => $request->harga_jual,
            'saldo' => $saldoBaruKas,
            'keterangan' => 'Penjualan kambing: ' . $kambing->nama,
            'tanggal' => $tanggalTransaksi,
        ]);


        /**
         * 🔹 Hapus kambing setelah dijual
         */
        $kambing->delete();

        DB::commit();

        return redirect()->route('kambing.index')
            ->with('success', 'Kambing berhasil dijual dan transaksi kas + kas_kambing tercatat.');
    } catch (\Exception $e) {

        DB::rollBack();
        return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
    }
}
}
