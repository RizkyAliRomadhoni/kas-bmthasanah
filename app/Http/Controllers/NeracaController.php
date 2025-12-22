<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kas;
use Carbon\Carbon;

class NeracaController extends Controller
{
    /**
     * ======================================================
     * 🔹 METHOD LAMA — JANGAN DIHAPUS (TETAP ADA)
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
            ->orderBy('tanggal', 'ASC')
            ->get();

        $pemasukan = $kas->where('jenis_transaksi', 'Masuk')->sum('jumlah');
        $pengeluaran = $kas->where('jenis_transaksi', 'Keluar')->sum('jumlah');
        $saldoAkhir = $pemasukan - $pengeluaran;

        $pendapatan = $pemasukan;
        $biaya = $pengeluaran;
        $labaBersih = $pendapatan - $biaya;

        $aktiva = $pemasukan;
        $pasiva = $pengeluaran;

        $dataPerBulan = $kas->groupBy(fn($i) => Carbon::parse($i->tanggal)->format('Y-m'));

        $labels = [];
        $grafikPemasukan = [];
        $grafikPengeluaran = [];

        foreach ($dataPerBulan as $key => $data) {
            $labels[] = $key;
            $grafikPemasukan[] = $data->where('jenis_transaksi', 'Masuk')->sum('jumlah');
            $grafikPengeluaran[] = $data->where('jenis_transaksi', 'Keluar')->sum('jumlah');
        }

        $tahunList = Kas::selectRaw('YEAR(tanggal) as tahun')->distinct()->pluck('tahun');
        $akunList = Kas::select('akun')->distinct()->pluck('akun')->filter()->values();

        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $modalKambing = Kas::where('akun', 'Kambing')->where('jenis_transaksi', 'Keluar')->sum('jumlah');
        $pakan = Kas::where('akun', 'Pakan')->where('jenis_transaksi', 'Keluar')->sum('jumlah');
        $operasional = Kas::where('akun', 'Operasional')->where('jenis_transaksi', 'Keluar')->sum('jumlah');
        $perawatan = Kas::where('akun', 'Perawatan')->where('jenis_transaksi', 'Keluar')->sum('jumlah');

        $totalBiaya = $modalKambing + $pakan + $operasional + $perawatan;
        $penjualan = Kas::where('akun', 'Penjualan')->where('jenis_transaksi', 'Masuk')->sum('jumlah');
        $labaRugi = $penjualan - $totalBiaya;
        $efektivitas = $totalBiaya > 0 ? ($penjualan / $totalBiaya) * 100 : 0;

        return view('neraca.index', compact(
            'pemasukan','pengeluaran','saldoAkhir',
            'pendapatan','biaya','labaBersih',
            'aktiva','pasiva',
            'labels','grafikPemasukan','grafikPengeluaran',
            'tahunList','bulanList','tahun','bulan','akun','akunList',
            'modalKambing','pakan','operasional','perawatan',
            'totalBiaya','penjualan','labaRugi','efektivitas'
        ));
    }

    /**
     * ======================================================
     * 🔹 METHOD BARU — NERACA TABEL (EXCEL STYLE)
     * ======================================================
     */
    public function neracaTabel(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        // Header bulan: AMAN (Y-m dari DB)
        $bulanList = Kas::selectRaw("DATE_FORMAT(tanggal,'%Y-%m') as ym")
            ->whereYear('tanggal', $tahun)
            ->groupBy('ym')
            ->orderBy('ym')
            ->pluck('ym')
            ->toArray();

        // Akun
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

        // Matrix saldo
        $saldo = [];

        foreach (array_merge($akunAktiva, $akunPasiva) as $akun) {
            foreach ($bulanList as $ym) {

                // KAS = Masuk - Keluar
                if ($akun === 'Kas') {
                    $masuk = Kas::where('akun','Kas')
                        ->where('jenis_transaksi','Masuk')
                        ->whereRaw("DATE_FORMAT(tanggal,'%Y-%m') = ?", [$ym])
                        ->sum('jumlah');

                    $keluar = Kas::where('akun','Kas')
                        ->where('jenis_transaksi','Keluar')
                        ->whereRaw("DATE_FORMAT(tanggal,'%Y-%m') = ?", [$ym])
                        ->sum('jumlah');

                    $saldo[$akun][$ym] = $masuk - $keluar;
                } else {
                    // AKUN NERACA = SUM(jumlah)
                    $saldo[$akun][$ym] = Kas::where('akun',$akun)
                        ->whereRaw("DATE_FORMAT(tanggal,'%Y-%m') = ?", [$ym])
                        ->sum('jumlah');
                }
            }
        }

        // Laba Rugi bulanan (untuk MODAL)
        $labaRugi = [];
        foreach ($bulanList as $ym) {
            $masuk = Kas::where('jenis_transaksi','Masuk')
                ->whereRaw("DATE_FORMAT(tanggal,'%Y-%m') = ?", [$ym])
                ->sum('jumlah');

            $keluar = Kas::where('jenis_transaksi','Keluar')
                ->whereRaw("DATE_FORMAT(tanggal,'%Y-%m') = ?", [$ym])
                ->sum('jumlah');

            $labaRugi[$ym] = $masuk - $keluar;
        }

        return view('neraca.neraca-tabel', compact(
            'tahun','bulanList','akunAktiva','akunPasiva','saldo','labaRugi'
        ));
    }
}
