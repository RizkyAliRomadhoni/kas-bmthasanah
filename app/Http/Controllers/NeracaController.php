<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kas;
use App\Models\KambingRincianHpp;
use App\Models\KambingRincianHppDetail;
use App\Models\KambingMati;
use App\Models\Penjualan;
use App\Models\PakanDetail;
use App\Models\LabaRugiManual;
use Carbon\Carbon;

class NeracaController extends Controller
{
    public function index(Request $request)
    {
        // 1. DAFTAR BULAN (OTOMATIS)
        $bulanKas = Kas::selectRaw("DATE_FORMAT(tanggal,'%Y-%m') as bulan")->pluck('bulan');
        $bulanJual = Penjualan::selectRaw("DATE_FORMAT(tanggal,'%Y-%m') as bulan")->pluck('bulan');
        $bulanMati = KambingMati::selectRaw("DATE_FORMAT(tanggal,'%Y-%m') as bulan")->pluck('bulan');
        $bulanManual = LabaRugiManual::pluck('bulan'); 

        $bulanList = collect($bulanKas)->merge($bulanJual)->merge($bulanMati)->merge($bulanManual)
            ->unique()->sort()->values();

        // 2. PENGATURAN AKUN
        $akunAktiva = ['Kas', 'Kambing', 'Piutang', 'Pakan', 'Perlengkapan', 'Upah', 'Kandang', 'Operasional'];
        $akunPasiva = ['Hutang', 'Titipan', 'Penyertaan BMT Hasanah', 'Penyertaan DF'];

        $manualLR = LabaRugiManual::all()->groupBy('bulan');
        $saldoAwal = [];
        foreach (array_merge($akunAktiva, $akunPasiva) as $akun) { $saldoAwal[$akun] = 0; }

        $saldo = [];
        $sisaSaldo = [];
        $labaRugiKumulatif = []; 
        $totalLabaBerjalan = 0;   
        
        // Data untuk Grafik
        $chartDataAset = [];
        $chartDataLaba = [];

        // 3. LOOPING PER BULAN
        foreach ($bulanList as $bulan) {
            $akhirBulan = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();

            // --- A. KAS TUNAI ---
            $sisaSaldo[$bulan] = Kas::where('tanggal', '<=', $akhirBulan)
                ->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->value('saldo') ?? 0;

            // --- B. AKTIVA ---
            $totalAktivaBulan = 0;
            foreach ($akunAktiva as $akun) {
                if ($akun === 'Kas') {
                    $val = $sisaSaldo[$bulan];
                } elseif ($akun === 'Kambing') {
                    $val = KambingRincianHppDetail::where('bulan', $bulan)
                        ->selectRaw('SUM(harga_update * qty_update) as total')
                        ->value('total') ?? 0;
                } elseif ($akun === 'Piutang') {
                    $in = Kas::where('akun', 'Piutang')->where('jenis_transaksi', 'Masuk')->where('tanggal', '<=', $akhirBulan)->sum('jumlah');
                    $out = Kas::where('akun', 'Piutang')->where('jenis_transaksi', 'Keluar')->where('tanggal', '<=', $akhirBulan)->sum('jumlah');
                    $val = $out - $in;
                } else {
                    $val = Kas::where('akun', $akun)->where('tanggal', '<=', $akhirBulan)->sum('jumlah');
                }
                $saldo[$akun][$bulan] = $val;
                $totalAktivaBulan += $val;
            }
            $chartDataAset[] = $totalAktivaBulan;

            // --- C. PASIVA ---
            foreach ($akunPasiva as $akun) {
                if ($akun === 'Hutang') {
                    $in = Kas::where('akun', 'Hutang')->where('jenis_transaksi', 'Masuk')->where('tanggal', '<=', $akhirBulan)->sum('jumlah');
                    $out = Kas::where('akun', 'Hutang')->where('jenis_transaksi', 'Keluar')->where('tanggal', '<=', $akhirBulan)->sum('jumlah');
                    $saldo[$akun][$bulan] = $in - $out;
                } elseif (in_array($akun, ['Penyertaan BMT Hasanah', 'Penyertaan DF'])) {
                    $saldo[$akun][$bulan] = Kas::where('keterangan', 'LIKE', '%' . $akun . '%')->where('tanggal', '<=', $akhirBulan)->sum('jumlah');
                } else {
                    $saldo[$akun][$bulan] = Kas::where('akun', $akun)->where('tanggal', '<=', $akhirBulan)->sum('jumlah');
                }
            }

            // --- D. LABA RUGI (AKUMULASI) ---
            $oto_laba_kambing = Penjualan::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])->sum('laba');
            $oto_beban_mati = KambingMati::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])->sum('harga');
            $oto_laba_pakan = PakanDetail::whereHas('kas', fn($q) => $q->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan]))
                                ->get()->sum(fn($q) => ($q->harga_jual_kg - $q->harga_kg) * $q->qty_kg);
            $oto_basil = Kas::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                                ->where('keterangan', 'LIKE', '%Basil%')->where('jenis_transaksi', 'Masuk')->sum('jumlah');
            $oto_adj = Kas::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                                ->where('akun', 'Penyesuaian')->where('jenis_transaksi', 'Masuk')->sum('jumlah');

            $getM = function($kat, $oto) use ($manualLR, $bulan) {
                $m = $manualLR->has($bulan) ? $manualLR[$bulan]->where('kategori', $kat)->first() : null;
                return ($m && $m->nilai != 0) ? $m->nilai : $oto;
            };

            $labaBulanIni = ($getM('laba_kambing', $oto_laba_kambing) + $getM('laba_pakan', $oto_laba_pakan) + $getM('laba_basil', $oto_basil) + $getM('laba_penyesuaian', $oto_adj)) 
                            - ($getM('beban_upah', 0) + $getM('biaya_lain', 0) + $getM('beban_mati', $oto_beban_mati));
            
            $totalLabaBerjalan += $labaBulanIni;
            $labaRugiKumulatif[$bulan] = $totalLabaBerjalan;
            $chartDataLaba[] = $totalLabaBerjalan;
        }

        // Ambil Nama Bulan untuk Label Grafik
        $chartLabels = $bulanList->map(function($b) {
            return Carbon::parse($b)->translatedFormat('M y');
        });

        return view('neraca.index', compact(
            'bulanList','akunAktiva','akunPasiva','saldoAwal','saldo','sisaSaldo','labaRugiKumulatif',
            'chartLabels', 'chartDataAset', 'chartDataLaba'
        ));
    }
}