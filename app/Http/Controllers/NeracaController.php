<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class NeracaController extends Controller
{
    /**
     * ======================================================
     * 🔹 METHOD LAMA — JANGAN DIHAPUS
     * ======================================================
     */
    public function index(Request $request)
    {
        // ==================================================
        // 🔹 AMBIL BULAN UNIK DARI DATA KAS
        // ==================================================
        $bulanList = Kas::selectRaw("DATE_FORMAT(tanggal,'%Y-%m') as bulan")
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('bulan');

        // ==================================================
        // 🔹 AKUN AKTIVA (KAS = RESIDUAL)
        // ==================================================
        $akunAktiva = [
            'Kas',
            'Kambing',
            'Pakan',
            'Operasional',
            'Perawatan',
            'Perlengkapan',
            'Kandang',
        ];

        // ==================================================
        // 🔹 AKUN PASIVA
        // ==================================================
        $akunPasiva = [
            'Hutang',
            'Titipan',
            'Modal',
            'Penyertaan BMT Hasanah',
            'Penyertaan DF',
        ];

        // ==================================================
        // 🔹 SALDO AWAL (FORMALITAS)
        // ==================================================
        $saldoAwal = [];
        foreach (array_merge($akunAktiva, $akunPasiva) as $akun) {
            $saldoAwal[$akun] = 0;
        }

        // ==================================================
        // 🔹 HITUNG SALDO BULANAN
        // ==================================================
        $saldo = [];

        foreach ($bulanList as $bulan) {

            $akhirBulan = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();

            // ==============================================
            // 🔹 TOTAL AKTIVA (KECUALI KAS)
            // ==============================================
            $totalAktiva = 0;

            foreach ($akunAktiva as $akun) {

                if ($akun === 'Kas') {
                    continue; // KAS TIDAK DIHITUNG DI SINI
                }

                $nilai = Kas::where('akun', $akun)
                    ->where('tanggal', '<=', $akhirBulan)
                    ->sum('jumlah');

                $saldo[$akun][$bulan] = $nilai;
                $totalAktiva += $nilai;
            }

            // ==============================================
            // 🔹 TOTAL PASIVA
            // ==============================================
            $totalPasiva = 0;

            foreach ($akunPasiva as $akun) {

                // 🔴 PENYERTAAN MODAL DARI KAS + KETERANGAN
                if (in_array($akun, ['Penyertaan BMT Hasanah', 'Penyertaan DF'])) {

                    if (!Schema::hasColumn('kas', 'keterangan')) {
                        $saldo[$akun][$bulan] = 0;
                        continue;
                    }

                    $nilai = Kas::where('akun', 'Kas')
                        ->where('keterangan', $akun)
                        ->where('tanggal', '<=', $akhirBulan)
                        ->sum('jumlah');

                    $saldo[$akun][$bulan] = $nilai;
                    $totalPasiva += $nilai;
                    continue;
                }

                // 🔹 PASIVA NORMAL
                $nilai = Kas::where('akun', $akun)
                    ->where('tanggal', '<=', $akhirBulan)
                    ->sum('jumlah');

                $saldo[$akun][$bulan] = $nilai;
                $totalPasiva += $nilai;
            }

            // ==============================================
            // 🟢 KAS = SALDO TERSISA (FIX FINAL)
            // ==============================================
            $saldo['Kas'][$bulan] = $totalPasiva - $totalAktiva;
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
     * 🔹 METHOD BARU — JANGAN DIHAPUS
     * ======================================================
     */
    public function neracaTabel(Request $request)
    {
        // BIARKAN
    }
}
