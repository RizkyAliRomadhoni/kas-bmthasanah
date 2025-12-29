<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\Penjualan;
use App\Models\KambingMati;
use App\Models\PakanDetail;
use App\Models\HppKambing;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LabaRugiController extends Controller
{
    public function index()
    {
        // 1. Ambil daftar bulan unik dari tabel Kas sebagai acuan periode laporan
        $bulanList = Kas::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->pluck('bulan');

        $labaRugiData = [];

        foreach ($bulanList as $bulan) {
            // ==============================================================
            // 🔹 A. PENDAPATAN (REVENUE)
            // ==============================================================

            // 1. Laba Penjualan Kambing
            // Menghitung keuntungan per transaksi penjualan (Harga Jual - HPP)
            $penjualan = Penjualan::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])->get();
            $labaJualKambing = $penjualan->sum(fn($q) => max(0, $q->harga_jual - $q->hpp));
            
            // 2. Laba Penjualan Pakan
            // Diambil dari modul Kelola Pakan (Selisih Harga Jual/kg dan Harga Beli/kg dikali Qty)
            $labaJualPakan = PakanDetail::whereHas('kas', function($q) use ($bulan) {
                $q->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan]);
            })->get()->sum(fn($q) => ($q->harga_jual_kg - $q->harga_kg) * $q->qty_kg);

            // 3. Laba Basil Simpanan (Bagi Hasil)
            // Otomatis mengambil dari Kas yang keterangannya mengandung kata 'Basil'
            $labaBasil = Kas::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                ->where('keterangan', 'LIKE', '%Basil%')
                ->where('jenis_transaksi', 'Masuk')
                ->sum('jumlah');

            // 4. Laba Penyesuaian Harga
            // Diambil dari akun khusus 'Penyesuaian' di tabel Kas jika Anda menggunakannya
            $labaPenyesuaian = Kas::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                ->where('akun', 'Penyesuaian')
                ->sum('jumlah');

            $totalPendapatan = $labaJualKambing + $labaJualPakan + $labaBasil + $labaPenyesuaian;

            // ==============================================================
            // 🔹 B. BIAYA / KERUGIAN (EXPENSES)
            // ==============================================================

            // 1. Beban Kerugian Penjualan Kambing
            // Terjadi jika Harga Jual lebih rendah dari HPP (Kambing dijual rugi)
            $rugiJualKambing = $penjualan->sum(fn($q) => max(0, $q->hpp - $q->harga_jual));

            // 2. Beban Kerugian Kambing Mati
            // Diambil langsung dari tabel modul Rincian Kambing Mati yang diinput manual
            $bebanMati = KambingMati::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                ->sum('harga');

            // Total Biaya yang diakui di Laba Rugi
            $totalBiaya = $rugiJualKambing + $bebanMati;

            // ==============================================================
            // 🔹 C. LABA RUGI BERSIH (NET PROFIT)
            // ==============================================================
            $netLabaRugi = $totalPendapatan - $totalBiaya;

            // Simpan hasil kalkulasi ke dalam array berdasarkan bulan
            $labaRugiData[$bulan] = [
                'laba_jual_kambing' => $labaJualKambing,
                'laba_jual_pakan'   => $labaJualPakan,
                'laba_penyesuaian'  => $labaPenyesuaian,
                'laba_basil'        => $labaBasil,
                'total_pendapatan'  => $totalPendapatan,
                'rugi_jual_kambing' => $rugiJualKambing,
                'beban_mati'        => $bebanMati,
                'total_biaya'       => $totalBiaya,
                'net_laba_rugi'     => $netLabaRugi,
            ];
        }

        return view('neraca.laba-rugi.index', compact('bulanList', 'labaRugiData'));
    }
}