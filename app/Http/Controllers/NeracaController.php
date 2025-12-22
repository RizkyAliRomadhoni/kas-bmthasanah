<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kas;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NeracaController extends Controller
{
    /**
     * ======================================================
     * 🔹 METHOD LAMA — TETAP ADA (TIDAK DIHAPUS)
     * ======================================================
     */
    public function index(Request $request)
    {
        // ============================
        // 🔹 FILTER TAHUN
        // ============================
        $tahun = $request->input('tahun', date('Y'));

        /**
         * ======================================================
         * 🔹 DAFTAR AKUN (NERACA)
         * ======================================================
         */
        $akunAktiva = [
            'Piutang',
            'Kambing',
            'Kandang',
            'Perlengkapan',
            'Operasional',
            'Pakan',
            'Upah',
            'Perawatan',
            'Complifit',
        ];

        $akunPasiva = [
            'Penyertaan BMT Hasanah',
            'Penyertaan DF',
            'Titipan',
            'Hutang',
        ];

        /**
         * ======================================================
         * 🔹 AMBIL BULAN OTOMATIS (FORMAT: Y-m)
         * ======================================================
         */
        $bulanList = Kas::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")
            ->whereYear('tanggal', $tahun)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('bulan')
            ->toArray();

        /**
         * ======================================================
         * 🔹 AMBIL TOTAL JUMLAH PER AKUN PER BULAN
         * (TANPA jenis_transaksi)
         * ======================================================
         */
        $rawData = Kas::select(
                'akun',
                DB::raw("DATE_FORMAT(tanggal, '%Y-%m') as bulan"),
                DB::raw("SUM(jumlah) as total")
            )
            ->whereYear('tanggal', $tahun)
            ->groupBy('akun', 'bulan')
            ->get();

        /**
         * ======================================================
         * 🔹 MATRIX SALDO [akun][bulan] = saldo kumulatif
         * ======================================================
         */
        $saldo = [];

        foreach (array_merge($akunAktiva, $akunPasiva) as $akun) {
            $running = 0;

            foreach ($bulanList as $bulan) {
                $nilai = $rawData
                    ->where('akun', $akun)
                    ->where('bulan', $bulan)
                    ->sum('total');

                // 🔥 LOGIKA NERACA
                $running += $nilai;

                $saldo[$akun][$bulan] = $running;
            }
        }

        /**
         * ======================================================
         * 🔹 TOTAL AKTIVA & PASIVA PER BULAN
         * ======================================================
         */
        $totalAktiva = [];
        $totalPasiva = [];

        foreach ($bulanList as $bulan) {
            $totalAktiva[$bulan] = 0;
            $totalPasiva[$bulan] = 0;

            foreach ($akunAktiva as $akun) {
                $totalAktiva[$bulan] += $saldo[$akun][$bulan] ?? 0;
            }

            foreach ($akunPasiva as $akun) {
                $totalPasiva[$bulan] += $saldo[$akun][$bulan] ?? 0;
            }
        }

        return view('neraca.index', compact(
            'tahun',
            'bulanList',
            'akunAktiva',
            'akunPasiva',
            'saldo',
            'totalAktiva',
            'totalPasiva'
        ));
    }
}
