<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\Penjualan;
use App\Models\KambingMati;
use App\Models\PakanDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LabaRugiController extends Controller
{
    public function index()
    {
        // 1. Ambil daftar bulan unik dari tabel Kas
        $bulanList = Kas::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->pluck('bulan');

        $labaRugiData = [];

        foreach ($bulanList as $bulan) {
            // --- A. PENDAPATAN ---

            // 1. Laba Penjualan Kambing (Harga Jual - HPP)
            $penjualan = Penjualan::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])->get();
            $labaJualKambing = $penjualan->sum(fn($q) => max(0, $q->harga_jual - $q->hpp));
            
            // Logika kerugian jika HPP > Harga Jual
            $rugiJualKambing = $penjualan->sum(fn($q) => max(0, $q->hpp - $q->harga_jual));

            // 2. Laba Penjualan Pakan (Selisih Harga Jual - Harga Beli * Qty)
            $labaJualPakan = PakanDetail::whereHas('kas', function($q) use ($bulan) {
                $q->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan]);
            })->get()->sum(fn($q) => ($q->harga_jual_kg - $q->harga_kg) * $q->qty_kg);

            // 3. Laba Basil Simpanan (Dari Kas yang Keterangannya mengandung kata 'Basil')
            $labaBasil = Kas::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                ->where('keterangan', 'LIKE', '%Basil%')
                ->sum('jumlah');

            // 4. Laba Penyesuaian Harga (Bisa diambil dari Kas akun 'Penyesuaian')
            $labaPenyesuaian = Kas::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                ->where('akun', 'Penyesuaian')
                ->sum('jumlah');

            $totalPendapatan = $labaJualKambing + $labaJualPakan + $labaBasil + $labaPenyesuaian;

            // --- B. BIAYA ---

            // 1. Beban Kambing Mati (Dari tabel KambingMati)
            $bebanMati = KambingMati::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                ->sum('harga');

            // Total Biaya (Hanya Kerugian Jual dan Kambing Mati)
            $totalBiaya = $rugiJualKambing + $bebanMati;

            // --- C. LABA RUGI BERSIH ---
            $netLabaRugi = $totalPendapatan - $totalBiaya;

            // Simpan ke array
            $labaRugiData[$bulan] = [
                'laba_jual_kambing' => $labaJualKambing,
                'laba_jual_pakan' => $labaJualPakan,
                'laba_penyesuaian' => $labaPenyesuaian,
                'laba_basil' => $labaBasil,
                'total_pendapatan' => $totalPendapatan,
                'rugi_jual_kambing' => $rugiJualKambing,
                'beban_mati' => $bebanMati,
                'total_biaya' => $totalBiaya,
                'net_laba_rugi' => $netLabaRugi,
            ];
        }

        return view('neraca.laba-rugi.index', compact('bulanList', 'labaRugiData'));
    }
}