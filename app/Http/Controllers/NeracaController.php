<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kas;
use App\Models\HppKambing;
use App\Models\KambingMati;
use App\Models\Penjualan;
use App\Models\PakanDetail;
use App\Models\LabaRugiManual;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class NeracaController extends Controller
{
    public function index(Request $request)
    {
        // ==============================================================
        // 🔹 1. DAFTAR BULAN (OTOMATIS DARI DATA KAS)
        // ==============================================================
        $bulanList = Kas::selectRaw("DATE_FORMAT(tanggal,'%Y-%m') as bulan")
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->pluck('bulan');

        // ==============================================================
        // 🔹 2. PENGATURAN AKUN (STRUKTUR NERACA)
        // ==============================================================
        $akunAktiva = [
            'Kas',
            'Kambing',       
            'Piutang',       
            'Pakan',         
            'Perlengkapan',  
            'Upah',          
            'Kandang',       
            'Operasional',   
        ];

        $akunPasiva = [
            'Hutang',        
            'Titipan',       
            'Penyertaan BMT Hasanah',
            'Penyertaan DF',
        ];

        $manualLR = LabaRugiManual::all()->groupBy('bulan');
        $saldoAwal = [];
        foreach (array_merge($akunAktiva, $akunPasiva) as $akun) {
            $saldoAwal[$akun] = 0;
        }

        $saldo = [];
        $sisaSaldo = [];
        $labaRugiKumulatif = []; // Untuk menyimpan hasil akumulasi
        $totalLabaBerjalan = 0;   // Variabel penampung akumulasi

        // ==============================================================
        // 🔹 3. LOOPING PER BULAN (LOGIKA INTEGRASI & AKUMULASI)
        // ==============================================================
        foreach ($bulanList as $bulan) {
            $akhirBulan = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();

            // --- A. KAS TUNAI (SALDO RIIL) ---
            $sisaSaldo[$bulan] = Kas::where('tanggal', '<=', $akhirBulan)
                ->orderBy('tanggal', 'desc')
                ->orderBy('id', 'desc')
                ->value('saldo') ?? 0;

            // --- B. KALKULASI AKTIVA (ASET) ---
            foreach ($akunAktiva as $akun) {
                if ($akun === 'Kas') {
                    $saldo[$akun][$bulan] = $sisaSaldo[$bulan];
                } 
                elseif ($akun === 'Kambing') {
                    $saldo[$akun][$bulan] = HppKambing::where('created_at', '<=', $akhirBulan)->sum('total_hpp');
                } 
                elseif ($akun === 'Piutang') {
                    $piutangMasuk = Kas::where('akun', 'Piutang')->where('jenis_transaksi', 'Masuk')->where('tanggal', '<=', $akhirBulan)->sum('jumlah');
                    $piutangKeluar = Kas::where('akun', 'Piutang')->where('jenis_transaksi', 'Keluar')->where('tanggal', '<=', $akhirBulan)->sum('jumlah');
                    $saldo[$akun][$bulan] = $piutangKeluar - $piutangMasuk;
                } 
                else {
                    $saldo[$akun][$bulan] = Kas::where('akun', $akun)->where('tanggal', '<=', $akhirBulan)->sum('jumlah');
                }
            }

            // --- C. KALKULASI PASIVA (KEWAJIBAN) ---
            foreach ($akunPasiva as $akun) {
                if ($akun === 'Hutang') {
                    $hutangMasuk = Kas::where('akun', 'Hutang')->where('jenis_transaksi', 'Masuk')->where('tanggal', '<=', $akhirBulan)->sum('jumlah');
                    $hutangKeluar = Kas::where('akun', 'Hutang')->where('jenis_transaksi', 'Keluar')->where('tanggal', '<=', $akhirBulan)->sum('jumlah');
                    $saldo[$akun][$bulan] = $hutangMasuk - $hutangKeluar;
                } 
                elseif (in_array($akun, ['Penyertaan BMT Hasanah', 'Penyertaan DF'])) {
                    $saldo[$akun][$bulan] = Kas::where('keterangan', 'LIKE', '%' . $akun . '%')->where('tanggal', '<=', $akhirBulan)->sum('jumlah');
                } 
                else {
                    $saldo[$akun][$bulan] = Kas::where('akun', $akun)->where('tanggal', '<=', $akhirBulan)->sum('jumlah');
                }
            }

            // --- D. LOGIKA LABA RUGI TAHUN BERJALAN (AKUMULASI) ---
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

            $l_kambing = $getM('laba_kambing', $oto_laba_kambing);
            $l_pakan   = $getM('laba_pakan', $oto_laba_pakan);
            $l_basil   = $getM('laba_basil', $oto_basil);
            $l_adj     = $getM('laba_penyesuaian', $oto_adj);
            $b_upah    = $getM('beban_upah', 0);
            $b_lain    = $getM('biaya_lain', 0);
            $b_mati    = $getM('beban_mati', $oto_beban_mati);

            // Hitung laba murni bulan ini
            $labaBulanIni = ($l_kambing + $l_pakan + $l_basil + $l_adj) - ($b_upah + $b_lain + $b_mati);
            
            // Masukkan ke sistem AKUMULASI
            $totalLabaBerjalan += $labaBulanIni;
            $labaRugiKumulatif[$bulan] = $totalLabaBerjalan;
        }

        return view('neraca.index', compact(
            'bulanList',
            'akunAktiva',
            'akunPasiva',
            'saldoAwal',
            'saldo',
            'sisaSaldo',
            'labaRugiKumulatif'
        ));
    }
}