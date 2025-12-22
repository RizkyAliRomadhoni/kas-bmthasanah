<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kas;
use Carbon\Carbon;

class NeracaController extends Controller
{
    /**
     * ======================================================
     * 🔹 METHOD LAMA — TETAP ADA (TIDAK DIHAPUS)
     * ======================================================
     */
    public function index(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        // ================================
        // 🔹 Ambil daftar bulan tersedia
        // ================================
        $bulanList = Kas::selectRaw("DATE_FORMAT(tanggal,'%Y-%m') as bulan")
            ->whereYear('tanggal', $tahun)
            ->orderBy('bulan')
            ->pluck('bulan')
            ->values();

        // ================================
        // 🔹 Akun Neraca
        // ================================
        $akunAktiva = [
            'Kas','Piutang','Kambing','Kandang','Perlengkapan',
            'Operasional','Pakan','Upah','Perawatan','Complifit'
        ];

        $akunPasiva = [
            'Penyertaan BMT Hasanah',
            'Penyertaan DF',
            'Titipan',
            'Hutang'
        ];

        // ================================
        // 🔹 Hitung SALDO AWAL (sebelum tahun)
        // ================================
        $saldoAwal = [];

        foreach (array_merge($akunAktiva, $akunPasiva) as $akun) {
            $masuk = Kas::where('akun', $akun)
                ->where('jenis_transaksi', 'Masuk')
                ->whereYear('tanggal', '<', $tahun)
                ->sum('jumlah');

            $keluar = Kas::where('akun', $akun)
                ->where('jenis_transaksi', 'Keluar')
                ->whereYear('tanggal', '<', $tahun)
                ->sum('jumlah');

            $saldoAwal[$akun] = $masuk - $keluar;
        }

        // ================================
        // 🔹 SALDO BERJALAN PER BULAN
        // ================================
        $saldo = [];
        $running = $saldoAwal;

        foreach ($bulanList as $bulan) {
            foreach (array_merge($akunAktiva, $akunPasiva) as $akun) {

                $masuk = Kas::where('akun', $akun)
                    ->where('jenis_transaksi', 'Masuk')
                    ->whereRaw("DATE_FORMAT(tanggal,'%Y-%m') = ?", [$bulan])
                    ->sum('jumlah');

                $keluar = Kas::where('akun', $akun)
                    ->where('jenis_transaksi', 'Keluar')
                    ->whereRaw("DATE_FORMAT(tanggal,'%Y-%m') = ?", [$bulan])
                    ->sum('jumlah');

                // ✅ RUNNING BALANCE
                $running[$akun] += ($masuk - $keluar);
                $saldo[$akun][$bulan] = $running[$akun];
            }
        }

        // ================================
        // 🔹 LABA RUGI AKUMULATIF
        // ================================
        $labaRugi = [];
        $lrRunning = 0;

        foreach ($bulanList as $bulan) {
            $masuk = Kas::where('jenis_transaksi','Masuk')
                ->whereRaw("DATE_FORMAT(tanggal,'%Y-%m') = ?", [$bulan])
                ->sum('jumlah');

            $keluar = Kas::where('jenis_transaksi','Keluar')
                ->whereRaw("DATE_FORMAT(tanggal,'%Y-%m') = ?", [$bulan])
                ->sum('jumlah');

            $lrRunning += ($masuk - $keluar);
            $labaRugi[$bulan] = $lrRunning;
        }

        return view('neraca.index', compact(
            'tahun',
            'bulanList',
            'akunAktiva',
            'akunPasiva',
            'saldoAwal',
            'saldo',
            'labaRugi'
        ));
    }
}
