<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kas;
use Carbon\Carbon;

class NeracaController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->input('tahun', Carbon::now()->year);
        $bulan = $request->input('bulan', '');
        $akun  = $request->input('akun', '');

        // ============================
        // 🔹 Ambil semua data kas sesuai filter
        // ============================
        $kas = Kas::query()
            ->when($tahun, fn($q) => $q->whereYear('tanggal', $tahun))
            ->when($bulan, fn($q) => $q->whereMonth('tanggal', $bulan))
            ->when($akun, fn($q) => $q->where('akun', $akun))
            ->orderBy('tanggal', 'ASC')
            ->get();

        // ============================
        // 🔹 Hitung pemasukan & pengeluaran
        // ============================
        $pemasukan = $kas->where('jenis_transaksi', 'Masuk')->sum('jumlah');
        $pengeluaran = $kas->where('jenis_transaksi', 'Keluar')->sum('jumlah');
        $saldoAkhir = $pemasukan - $pengeluaran;

        // ============================
        // 🔹 Laba Rugi (tanpa kategori)
        // ============================
        $pendapatan = $pemasukan;
        $biaya = $pengeluaran;
        $labaBersih = $pendapatan - $biaya;

        // ============================
        // 🔹 Aktiva & Pasiva sederhana
        // ============================
        $aktiva = $pemasukan;
        $pasiva = $pengeluaran;

        // ============================
        // 🔹 Grafik Per Bulan berdasarkan FILTER
        // ============================
        $dataPerBulan = $kas->groupBy(fn($i) => Carbon::parse($i->tanggal)->format('Y-m'));

        $labels = [];
        $grafikPemasukan = [];
        $grafikPengeluaran = [];

        foreach ($dataPerBulan as $key => $data) {
            $labels[] = Carbon::parse($key . '-01')->translatedFormat('F Y');
            $grafikPemasukan[] = $data->where('jenis_transaksi', 'Masuk')->sum('jumlah');
            $grafikPengeluaran[] = $data->where('jenis_transaksi', 'Keluar')->sum('jumlah');
        }

        // ============================
        // 🔹 Dropdown Filter Tahun & Akun
        // ============================
        $tahunList = Kas::selectRaw('YEAR(tanggal) as tahun')
            ->where('tanggal', '!=', '0000-00-00')
            ->distinct()
            ->pluck('tahun');

        $akunList = Kas::select('akun')
            ->distinct()
            ->pluck('akun')
            ->filter()
            ->values();

        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // ============================================================
        // 🔥 ANALISIS EFEKTIVITAS USAHA (Kambing, Pakan, Operasional)
        // ============================================================

        $modalKambing = Kas::where('akun', 'Kambing')
            ->where('jenis_transaksi', 'Keluar')
            ->sum('jumlah');

        $pakan = Kas::where('akun', 'Pakan')
            ->where('jenis_transaksi', 'Keluar')
            ->sum('jumlah');

        $operasional = Kas::where('akun', 'Operasional')
            ->where('jenis_transaksi', 'Keluar')
            ->sum('jumlah');

        $perawatan = Kas::where('akun', 'Perawatan')
            ->where('jenis_transaksi', 'Keluar')
            ->sum('jumlah');

        // Total biaya
        $totalBiaya = $modalKambing + $pakan + $operasional + $perawatan;

        // Pendapatan (penjualan)
        $penjualan = Kas::where('akun', 'Penjualan')
            ->where('jenis_transaksi', 'Masuk')
            ->sum('jumlah');

        // Laba usaha
        $labaRugi = $penjualan - $totalBiaya;

        // Efektivitas (%)
        $efektivitas = $totalBiaya > 0 ? ($penjualan / $totalBiaya) * 100 : 0;

        // ============================
        // 🔹 KIRIM KE VIEW
        // ============================
        return view('neraca.index', compact(
            'pemasukan',
            'pengeluaran',
            'saldoAkhir',
            'pendapatan',
            'biaya',
            'labaBersih',
            'aktiva',
            'pasiva',
            'labels',
            'grafikPemasukan',
            'grafikPengeluaran',
            'tahunList',
            'bulanList',
            'tahun',
            'bulan',
            'akun',
            'akunList',

            // 🔥 Analisis Usaha
            'modalKambing',
            'pakan',
            'operasional',
            'perawatan',
            'totalBiaya',
            'penjualan',
            'labaRugi',
            'efektivitas'
        ));

   
    }
    
}

