<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kas;
use Carbon\Carbon;

class NeracaController extends Controller
{
    /**
     * ======================================================
     * 🔹 METHOD LAMA — JANGAN DIHAPUS
     * ======================================================
     */
    public function index(Request $request)
    {
        // ============================
        // 🔹 Ambil SEMUA BULAN unik (urut)
        // ============================
        $bulanList = Kas::selectRaw("DATE_FORMAT(tanggal,'%Y-%m') as bulan")
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('bulan');

        // ============================
        // 🔹 DAFTAR AKUN NERACA
        // (Kas DISIMPAN tapi BELUM DIPAKAI)
        // ============================
        $akunAktiva = [
            'Kas',
            'Kambing',
            'Pakan',
            'Operasional',
            'Perawatan',
            'Perlengkapan',
            'Kandang',
        ];

        $akunPasiva = [
            'Hutang',
            'Titipan',
            'Modal'
        ];

        // ============================
        // 🔹 SALDO AWAL (SEMUA 0)
        // ============================
        $saldoAwal = [];
        foreach (array_merge($akunAktiva, $akunPasiva) as $akun) {
            $saldoAwal[$akun] = 0;
        }

        // ============================
        // 🔹 HITUNG SALDO KUMULATIF
        // ============================
        $saldo = [];

        foreach (array_merge($akunAktiva, $akunPasiva) as $akun) {

            $running = $saldoAwal[$akun];

            foreach ($bulanList as $bulan) {

                // 🔴 KAS DIABAIKAN DULU
                if ($akun === 'Kas') {
                    $saldo[$akun][$bulan] = null;
                    continue;
                }

                // ============================
                // 🔹 TOTAL NILAI SAMPAI BULAN INI
                // (TIDAK peduli Masuk / Keluar)
                // ============================
                $nilai = Kas::where('akun', $akun)
                    ->whereRaw("DATE_FORMAT(tanggal,'%Y-%m') <= ?", [$bulan])
                    ->sum('jumlah');

                // ============================
                // 🔹 SALDO KUMULATIF
                // ============================
                $running = $nilai;
                $saldo[$akun][$bulan] = $running;
            }
        }

        return view('neraca.index', compact(
            'bulanList',
            'akunAktiva',
            'akunPasiva',
            'saldoAwal',
            'saldo'
        ));
    }

    /**
     * ======================================================
     * 🔹 METHOD BARU (DIBIARKAN)
     * ======================================================
     */
    public function neracaTabel(Request $request)
    {
        // BIARKAN — TIDAK DIHAPUS
    }
}
