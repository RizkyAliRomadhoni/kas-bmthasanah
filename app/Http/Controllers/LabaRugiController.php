<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\Penjualan;
use App\Models\KambingMati;
use App\Models\PakanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LabaRugiController extends Controller
{
    public function index()
    {
        // 1. AMBIL SEMUA BULAN DARI KAS DAN PENJUALAN (AGAR OTOMATIS TERUPDATE)
        $bulanKas = Kas::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray();
        $bulanJual = Penjualan::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray();
        $bulanMati = KambingMati::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray();

        // Gabungkan semua bulan, hilangkan duplikat, dan urutkan
        $bulanList = collect(array_merge($bulanKas, $bulanJual, $bulanMati))
            ->unique()
            ->sort()
            ->values();

        $labaRugiData = [];

        foreach ($bulanList as $bulan) {
            // --- A. PENDAPATAN ---

            // 1. Laba Penjualan Kambing (Hanya ambil selisih positif: Jual - HPP)
            $penjualan = Penjualan::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])->get();
            
            $labaJualKambing = 0;
            $rugiJualKambing = 0;

            foreach ($penjualan as $jual) {
                $selisih = $jual->harga_jual - $jual->hpp;
                if ($selisih > 0) {
                    $labaJualKambing += $selisih;
                } else {
                    $rugiJualKambing += abs($selisih); // Masuk ke beban biaya nanti
                }
            }

            // 2. Laba Penjualan Pakan (Selisih margin di modul pakan)
            $labaJualPakan = PakanDetail::whereHas('kas', function($q) use ($bulan) {
                $q->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan]);
            })->get()->sum(fn($q) => ($q->harga_jual_kg - $q->harga_kg) * $q->qty_kg);

            // 3. Laba Basil Simpanan (Dari Kas Keterangan "Basil")
            $labaBasil = Kas::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                ->where('keterangan', 'LIKE', '%Basil%')
                ->where('jenis_transaksi', 'Masuk')
                ->sum('jumlah');

            // 4. Penyesuaian Harga (Jika ada akun khusus penyesuaian)
            $labaPenyesuaian = Kas::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                ->where('akun', 'Penyesuaian')
                ->sum('jumlah');

            $totalPendapatan = $labaJualKambing + $labaJualPakan + $labaBasil + $labaPenyesuaian;

            // --- B. BIAYA / KERUGIAN ---

            // 1. Beban Kambing Mati
            $bebanMati = KambingMati::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                ->sum('harga');

            // Total Biaya = Rugi Jual + Kambing Mati
            $totalBiaya = $rugiJualKambing + $bebanMati;

            // --- C. LABA RUGI BERSIH ---
            $netLabaRugi = $totalPendapatan - $totalBiaya;

            // Simpan ke array
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