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
        // 🔹 AMBIL BULAN UNIK DARI DATA KAS (OTOMATIS)
        // ==================================================
        $bulanList = Kas::selectRaw("DATE_FORMAT(tanggal,'%Y-%m') as bulan")
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('bulan');

        // ==================================================
        // 🔹 DAFTAR AKUN AKTIVA (KAS ADALAH RESIDUAL)
        // ==================================================
        $akunAktiva = [
            'Kas',          // RESIDUAL (BUKAN AKUN TRANSAKSI)
            'Kambing',
            'Pakan',
            'Operasional',
            'Perawatan',
            'Perlengkapan',
            'Kandang',
        ];

        // ==================================================
        // 🔹 DAFTAR AKUN PASIVA
        // ==================================================
        $akunPasiva = [
            'Hutang',
            'Titipan',
            'Modal',
            'Penyertaan BMT Hasanah',
            'Penyertaan DF',
        ];

        // ==================================================
        // 🔹 SALDO AWAL (KONSEP MURNI)
        // ==================================================
        $saldoAwal = [];
        foreach (array_merge($akunAktiva, $akunPasiva) as $akun) {
            $saldoAwal[$akun] = 0;
        }

        // ==================================================
        // 🔹 HITUNG SALDO KUMULATIF
        // ==================================================
        $saldo = [];

        foreach ($bulanList as $bulan) {

            $akhirBulan = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();

            // ==============================================
            // 🔹 HITUNG AKTIVA (KECUALI KAS)
            // ==============================================
            $totalAktiva = 0;

            foreach ($akunAktiva as $akun) {

                if ($akun === 'Kas') {
                    continue;
                }

                $nilai = Kas::where('akun', $akun)
                    ->where('tanggal', '<=', $akhirBulan)
                    ->sum('jumlah');

                $saldo[$akun][$bulan] = $nilai;
                $totalAktiva += $nilai;
            }

            // ==============================================
            // 🔹 HITUNG PASIVA (KECUALI KAS)
            // ==============================================
            $totalPasiva = 0;

            foreach ($akunPasiva as $akun) {

                // ------------------------------------------
                // 🔴 PENYERTAAN MODAL (DARI KAS BERKETERANGAN)
                // ------------------------------------------
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

                // ------------------------------------------
                // 🔹 PASIVA NORMAL
                // ------------------------------------------
                $nilai = Kas::where('akun', $akun)
                    ->where('tanggal', '<=', $akhirBulan)
                    ->sum('jumlah');

                $saldo[$akun][$bulan] = $nilai;
                $totalPasiva += $nilai;
            }

            // ==============================================
            // 🟢 KAS = RESIDUAL NERACA (FINAL)
            // ==============================================
            $saldo['Kas'][$bulan] = $totalAktiva - $totalPasiva;
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
