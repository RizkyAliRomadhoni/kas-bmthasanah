<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kas;
use Carbon\Carbon;

class NeracaController extends Controller
{
    /**
     * ======================================================
     * 🔹 METHOD LAMA — TETAP DIPAKAI
     * ======================================================
     */
    public function index(Request $request)
    {
        $tahun = $request->input('tahun', Carbon::now()->year);
        $bulan = $request->input('bulan', '');
        $akun  = $request->input('akun', '');

        $kas = Kas::query()
            ->when($tahun, fn($q) => $q->whereYear('tanggal', $tahun))
            ->when($bulan, fn($q) => $q->whereMonth('tanggal', $bulan))
            ->when($akun, fn($q) => $q->where('akun', $akun))
            ->orderBy('tanggal')
            ->get();

        $pemasukan   = $kas->where('jenis_transaksi', 'Masuk')->sum('jumlah');
        $pengeluaran = $kas->where('jenis_transaksi', 'Keluar')->sum('jumlah');
        $saldoAkhir  = $pemasukan - $pengeluaran;

        $pendapatan = $pemasukan;
        $biaya      = $pengeluaran;
        $labaBersih = $pendapatan - $biaya;

        $aktiva = $pemasukan;
        $pasiva = $pengeluaran;

        $tahunList = Kas::selectRaw('YEAR(tanggal) as tahun')->distinct()->pluck('tahun');

        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $akunList = Kas::select('akun')->distinct()->pluck('akun')->filter()->values();

        /**
         * ======================================================
         * 🔹 NERACA TABEL (GANTI EFEKTIVITAS USAHA)
         * ======================================================
         */

        // Header bulan format Y-m (AMAN)
        $bulanHeader = Kas::selectRaw("DATE_FORMAT(tanggal,'%Y-%m') as ym")
            ->whereYear('tanggal', $tahun)
            ->groupBy('ym')
            ->orderBy('ym')
            ->pluck('ym')
            ->toArray();

        // Mapping nama bulan (TIDAK pakai Carbon di Blade)
        $namaBulan = [
            '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April',
            '05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus',
            '09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
        ];

        // Akun Aktiva & Pasiva
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

        // Matrix saldo [akun][bulan]
        $saldo = [];

        foreach (array_merge($akunAktiva, $akunPasiva) as $a) {
            foreach ($bulanHeader as $ym) {
                $masuk = Kas::where('akun', $a)
                    ->where('jenis_transaksi', 'Masuk')
                    ->whereRaw("DATE_FORMAT(tanggal,'%Y-%m') = ?", [$ym])
                    ->sum('jumlah');

                $keluar = Kas::where('akun', $a)
                    ->where('jenis_transaksi', 'Keluar')
                    ->whereRaw("DATE_FORMAT(tanggal,'%Y-%m') = ?", [$ym])
                    ->sum('jumlah');

                $saldo[$a][$ym] = $masuk - $keluar;
            }
        }

        // Laba rugi bulanan (modal)
        $labaRugi = [];
        foreach ($bulanHeader as $ym) {
            $masuk = Kas::where('jenis_transaksi','Masuk')
                ->whereRaw("DATE_FORMAT(tanggal,'%Y-%m') = ?",[$ym])
                ->sum('jumlah');

            $keluar = Kas::where('jenis_transaksi','Keluar')
                ->whereRaw("DATE_FORMAT(tanggal,'%Y-%m') = ?",[$ym])
                ->sum('jumlah');

            $labaRugi[$ym] = $masuk - $keluar;
        }

        return view('neraca.index', compact(
            'pemasukan','pengeluaran','saldoAkhir',
            'pendapatan','biaya','labaBersih',
            'aktiva','pasiva',
            'tahunList','bulanList','tahun','bulan','akun','akunList',

            // NERACA TABEL
            'bulanHeader','namaBulan',
            'akunAktiva','akunPasiva',
            'saldo','labaRugi'
        ));
    }
}
