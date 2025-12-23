<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kas;
use Carbon\Carbon;

class NeracaController extends Controller
{
    /**
     * ============================================
     * NERACA TABEL — LOGIKA NERACA (BUKAN CASHFLOW)
     * ============================================
     */
    public function index(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        /**
         * ============================================
         * HEADER BULAN (YYYY-MM)
         * ============================================
         */
        $bulanList = Kas::selectRaw("DATE_FORMAT(tanggal,'%Y-%m') as bulan")
            ->whereYear('tanggal', $tahun)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('bulan')
            ->toArray();

        /**
         * ============================================
         * AKUN AKTIVA
         * ============================================
         */
        $akunAktiva = [
            'Kas',
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

        /**
         * ============================================
         * AKUN PASIVA
         * ============================================
         */
        $akunPasiva = [
            'Titipan',
            'Hutang',
            'Penyertaan BMT Hasanah',
            'Penyertaan DF',
        ];

        /**
         * ============================================
         * MATRIX SALDO [akun][bulan]
         * LOGIKA:
         * saldo = SUM(jumlah) WHERE tanggal <= akhir bulan
         * TIDAK pakai jenis_transaksi
         * ============================================
         */
        $saldo = [];

        foreach (array_merge($akunAktiva, $akunPasiva) as $akun) {
            foreach ($bulanList as $bulan) {
                $akhirBulan = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();

                // KHUSUS PENYERTAAN → ambil dari akun Kas
                if (in_array($akun, ['Penyertaan BMT Hasanah', 'Penyertaan DF'])) {
                    $nilai = Kas::where('akun', 'Kas')
                        ->where('keterangan', $akun) // jika belum ada → hasil 0
                        ->whereDate('tanggal', '<=', $akhirBulan)
                        ->sum('jumlah');
                }
                // AKUN NORMAL
                else {
                    $nilai = Kas::where('akun', $akun)
                        ->whereDate('tanggal', '<=', $akhirBulan)
                        ->sum('jumlah');
                }

                $saldo[$akun][$bulan] = $nilai;
            }
        }

        /**
         * ============================================
         * TOTAL AKTIVA & PASIVA
         * ============================================
         */
        $totalAktiva = [];
        $totalPasiva = [];

        foreach ($bulanList as $bulan) {
            $totalAktiva[$bulan] = collect($akunAktiva)->sum(fn($a) => $saldo[$a][$bulan] ?? 0);
            $totalPasiva[$bulan] = collect($akunPasiva)->sum(fn($a) => $saldo[$a][$bulan] ?? 0);
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
