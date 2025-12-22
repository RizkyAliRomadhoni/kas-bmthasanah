<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kas;
use Carbon\Carbon;

class NeracaController extends Controller
{
    public function index(Request $request)
    {
        // ============================
        // FILTER
        // ============================
        $tahun = $request->input('tahun', date('Y'));

        // ============================
        // DATA KAS FILTER TAHUN
        // ============================
        $kas = Kas::whereYear('tanggal', $tahun)->get();

        // ============================
        // RINGKASAN UMUM (TETAP ADA)
        // ============================
        $pemasukan   = $kas->where('jenis_transaksi', 'Masuk')->sum('jumlah');
        $pengeluaran = $kas->where('jenis_transaksi', 'Keluar')->sum('jumlah');
        $saldoAkhir  = $pemasukan - $pengeluaran;

        // ============================
        // LIST BULAN (AMAN: Y-m)
        // ============================
        $bulanList = Kas::selectRaw("DATE_FORMAT(tanggal,'%Y-%m') as bulan")
            ->whereYear('tanggal', $tahun)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('bulan')
            ->toArray();

        // ============================
        // AKUN NERACA
        // ============================
        $akunAktiva = [
            'Kas',
            'Piutang',
            'Kambing',
            'Kandang',
            'Perlengkapan',
            'Pakan',
            'Operasional',
            'Upah',
            'Perawatan',
            'Complifit'
        ];

        $akunPasiva = [
            'Penyertaan BMT Hasanah',
            'Penyertaan DF',
            'Titipan',
            'Hutang'
        ];

        // ============================
        // SALDO PER AKUN PER BULAN
        // ============================
        $saldo = [];

        foreach (array_merge($akunAktiva, $akunPasiva) as $akun) {
            foreach ($bulanList as $bulan) {

                $masuk = Kas::where('akun', $akun)
                    ->where('jenis_transaksi', 'Masuk')
                    ->whereRaw("DATE_FORMAT(tanggal,'%Y-%m') = ?", [$bulan])
                    ->sum('jumlah');

                $keluar = Kas::where('akun', $akun)
                    ->where('jenis_transaksi', 'Keluar')
                    ->whereRaw("DATE_FORMAT(tanggal,'%Y-%m') = ?", [$bulan])
                    ->sum('jumlah');

                // LOGIKA NERACA
                $saldo[$akun][$bulan] = $masuk - $keluar;
            }
        }

        // ============================
        // LABA RUGI BULANAN
        // ============================
        $labaRugi = [];

        foreach ($bulanList as $bulan) {
            $totalMasuk = Kas::where('jenis_transaksi', 'Masuk')
                ->whereRaw("DATE_FORMAT(tanggal,'%Y-%m') = ?", [$bulan])
                ->sum('jumlah');

            $totalKeluar = Kas::where('jenis_transaksi', 'Keluar')
                ->whereRaw("DATE_FORMAT(tanggal,'%Y-%m') = ?", [$bulan])
                ->sum('jumlah');

            $labaRugi[$bulan] = $totalMasuk - $totalKeluar;
        }

        return view('neraca.index', compact(
            'tahun',
            'bulanList',
            'akunAktiva',
            'akunPasiva',
            'saldo',
            'labaRugi',
            'pemasukan',
            'pengeluaran',
            'saldoAkhir'
        ));
    }
}
