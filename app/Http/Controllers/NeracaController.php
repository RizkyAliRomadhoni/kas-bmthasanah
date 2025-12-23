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
        // 🔹 DAFTAR AKUN AKTIVA
        // ⚠️ KAS ADALAH RESIDUAL — BUKAN AKUN TRANSAKSI
        // ==================================================
        $akunAktiva = [
            'Kas', // RESIDUAL (DIHITUNG TERAKHIR)
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
        // 🔹 SALDO AWAL (KONSEP MURNI — TIDAK DIPAKAI HITUNG)
        // ==================================================
        $saldoAwal = [];
        foreach (array_merge($akunAktiva, $akunPasiva) as $akun) {
            $saldoAwal[$akun] = 0;
        }

        // ==================================================
        // 🔹 SALDO AKHIR PER BULAN (KUMULATIF)
        // ==================================================
        $saldo = [];

        foreach ($bulanList as $bulan) {

            $akhirBulan = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();

            // ==============================================
            // 🔹 TOTAL AKTIVA (KECUALI KAS)
            // ==============================================
            $totalAktiva = 0;

            foreach ($akunAktiva as $akun) {

                // ⛔ KAS TIDAK BOLEH DIHITUNG DI SINI
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
            // 🔹 TOTAL PASIVA (SEMUA SELAIN KAS)
            // ==============================================
            $totalPasiva = 0;

            foreach ($akunPasiva as $akun) {

                // ------------------------------------------
                // 🔴 PENYERTAAN MODAL (DARI KAS + KETERANGAN)
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
            // 🟢 KAS = SALDO TERSISA (RESIDUAL NERACA)
            //
            // KAS bulan N =
            // TOTAL AKTIVA
            // − TOTAL PASIVA (SELURUHNYA)
            //
            // ⚠️ BUKAN:
            // - transaksi kas
            // - saldo kepakai
            // - sum akun kas
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
