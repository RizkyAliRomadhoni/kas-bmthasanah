<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kas;
use Carbon\Carbon;

class LabaRugiController extends Controller
{
    /**
     * ============================
     * 🔹 DETAIL LABA RUGI (HALAMAN SENDIRI)
     * ============================
     */
    public function index()
    {
        $bulanList = Kas::selectRaw("DATE_FORMAT(tanggal,'%Y-%m') as bulan")
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('bulan');

        $data = [];

        foreach ($bulanList as $bulan) {

            // =====================
            // 🔹 PENDAPATAN
            // =====================
            $penjualan = Kas::where('akun', 'penjualan')
                ->whereRaw("DATE_FORMAT(tanggal,'%Y-%m')=?", [$bulan])
                ->sum('jumlah');

            $pakan = Kas::where('akun', 'pakan')
                ->whereRaw("DATE_FORMAT(tanggal,'%Y-%m')=?", [$bulan])
                ->sum('jumlah');

            $penyesuaianHarga = Kas::where('keterangan', 'LIKE', '%PENYESUAIAN HARGA%')
                ->whereRaw("DATE_FORMAT(tanggal,'%Y-%m')=?", [$bulan])
                ->sum('jumlah');

            $basil = Kas::where('akun', 'basil')
                ->whereRaw("DATE_FORMAT(tanggal,'%Y-%m')=?", [$bulan])
                ->sum('jumlah');

            $totalPendapatan =
                $penjualan +
                $pakan +
                $penyesuaianHarga +
                $basil;

            // =====================
            // 🔹 BIAYA
            // =====================
            $upah = Kas::where('akun', 'upah')
                ->whereRaw("DATE_FORMAT(tanggal,'%Y-%m')=?", [$bulan])
                ->sum('jumlah');

            $kerugianPenjualan = Kas::where('keterangan', 'LIKE', '%KERUGIAN PENJUALAN%')
                ->whereRaw("DATE_FORMAT(tanggal,'%Y-%m')=?", [$bulan])
                ->sum('jumlah');

            $kerugianMati = Kas::where('keterangan', 'LIKE', '%KERUGIAN KAMBING MATI%')
                ->whereRaw("DATE_FORMAT(tanggal,'%Y-%m')=?", [$bulan])
                ->sum('jumlah');

            $totalBiaya =
                $upah +
                $kerugianPenjualan +
                $kerugianMati;

            // =====================
            // 🔹 LABA RUGI
            // =====================
            $labaRugi = $totalPendapatan - $totalBiaya;

            $data[$bulan] = [
                'penjualan' => $penjualan,
                'pakan' => $pakan,
                'penyesuaian_harga' => $penyesuaianHarga,
                'basil' => $basil,
                'total_pendapatan' => $totalPendapatan,

                'upah' => $upah,
                'kerugian_penjualan' => $kerugianPenjualan,
                'kerugian_mati' => $kerugianMati,
                'total_biaya' => $totalBiaya,

                'laba_rugi' => $labaRugi,
            ];
        }

        return view('neraca.laba-rugi', compact('bulanList', 'data'));
    }

    /**
     * ============================
     * 🔹 HASIL LABA RUGI (UNTUK NERACA)
     * ============================
     */
    public static function hasilPerBulan()
    {
        $bulanList = Kas::selectRaw("DATE_FORMAT(tanggal,'%Y-%m') as bulan")
            ->groupBy('bulan')
            ->pluck('bulan');

        $hasil = [];

        foreach ($bulanList as $bulan) {

            $pendapatan =
                Kas::where('akun', 'penjualan')->whereRaw("DATE_FORMAT(tanggal,'%Y-%m')=?",[$bulan])->sum('jumlah')
              + Kas::where('akun', 'pakan')->whereRaw("DATE_FORMAT(tanggal,'%Y-%m')=?",[$bulan])->sum('jumlah')
              + Kas::where('akun', 'basil')->whereRaw("DATE_FORMAT(tanggal,'%Y-%m')=?",[$bulan])->sum('jumlah')
              + Kas::where('keterangan','LIKE','%PENYESUAIAN HARGA%')
                    ->whereRaw("DATE_FORMAT(tanggal,'%Y-%m')=?",[$bulan])->sum('jumlah');

            $biaya =
                Kas::where('akun', 'upah')->whereRaw("DATE_FORMAT(tanggal,'%Y-%m')=?",[$bulan])->sum('jumlah')
              + Kas::where('keterangan','LIKE','%KERUGIAN PENJUALAN%')
                    ->whereRaw("DATE_FORMAT(tanggal,'%Y-%m')=?",[$bulan])->sum('jumlah')
              + Kas::where('keterangan','LIKE','%KERUGIAN KAMBING MATI%')
                    ->whereRaw("DATE_FORMAT(tanggal,'%Y-%m')=?",[$bulan])->sum('jumlah');

            $hasil[$bulan] = $pendapatan - $biaya;
        }

        return $hasil;
    }
}
