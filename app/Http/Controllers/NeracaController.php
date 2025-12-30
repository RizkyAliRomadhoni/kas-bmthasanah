<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kas;
use App\Models\HppKambing;
use App\Models\KambingMati;
use App\Models\Penjualan;
use App\Models\PakanDetail;
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
            'Kambing',       // Nilai dari HPP Kambing (Stok)
            'Piutang',       // Saldo Piutang Penjualan
            'Pakan',         // Akumulasi Pembelian Pakan
            'Perlengkapan',  // Akumulasi Perlengkapan (Complifit)
            'Upah',          // Akumulasi Biaya Tenaga Kerja (Capitalized)
            'Kandang',       // Aset Tetap Kandang
            'Operasional',   // Biaya Operasional
        ];

        $akunPasiva = [
            'Hutang',        // Saldo Hutang BMT/Lainnya
            'Titipan',       // Dana Titipan
            'Penyertaan BMT Hasanah',
            'Penyertaan DF',
        ];

        // Inisialisasi Saldo Awal
        $saldoAwal = [];
        foreach (array_merge($akunAktiva, $akunPasiva) as $akun) {
            $saldoAwal[$akun] = 0;
        }

        $saldo = [];
        $sisaSaldo = [];
        $totalHppPerBulan = [];

        // ==============================================================
        // 🔹 3. LOOPING PER BULAN (LOGIKA INTEGRASI)
        // ==============================================================
        foreach ($bulanList as $bulan) {
            $akhirBulan = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();

            // --- A. KAS TUNAI (SALDO RIIL) ---
            $sisaSaldo[$bulan] = Kas::where('tanggal', '<=', $akhirBulan)
                ->orderBy('tanggal', 'desc')
                ->orderBy('id', 'desc')
                ->value('saldo') ?? 0;

            // --- B. NILAI STOK KAMBING (DARI MODUL RINCIAN HPP) ---
            $totalHppPerBulan[$bulan] = HppKambing::where('created_at', '<=', $akhirBulan)
                ->sum('total_hpp');

            // --- C. KALKULASI AKTIVA (ASET) ---
            foreach ($akunAktiva as $akun) {
                if ($akun === 'Kas') {
                    $saldo[$akun][$bulan] = $sisaSaldo[$bulan];
                } 
                elseif ($akun === 'Kambing') {
                    $saldo[$akun][$bulan] = $totalHppPerBulan[$bulan];
                } 
                elseif ($akun === 'Piutang') {
                    // Logika Piutang: Keluar (Piutang Baru) - Masuk (Pembayaran)
                    $piutangMasuk = Kas::where('akun', 'Piutang')->where('jenis_transaksi', 'Masuk')->where('tanggal', '<=', $akhirBulan)->sum('jumlah');
                    $piutangKeluar = Kas::where('akun', 'Piutang')->where('jenis_transaksi', 'Keluar')->where('tanggal', '<=', $akhirBulan)->sum('jumlah');
                    $saldo[$akun][$bulan] = $piutangKeluar - $piutangMasuk;
                } 
                else {
                    // Akun Umum (Pakan, Upah, Kandang, dll)
                    $saldo[$akun][$bulan] = Kas::where('akun', $akun)
                        ->where('tanggal', '<=', $akhirBulan)
                        ->sum('jumlah');
                }
            }

            // --- D. KALKULASI PASIVA (KEWAJIBAN) ---
            foreach ($akunPasiva as $akun) {
                if ($akun === 'Hutang') {
                    // Logika Hutang: Masuk (Pinjaman Baru) - Keluar (Bayar Cicilan)
                    $hutangMasuk = Kas::where('akun', 'Hutang')->where('jenis_transaksi', 'Masuk')->where('tanggal', '<=', $akhirBulan)->sum('jumlah');
                    $hutangKeluar = Kas::where('akun', 'Hutang')->where('jenis_transaksi', 'Keluar')->where('tanggal', '<=', $akhirBulan)->sum('jumlah');
                    $saldo[$akun][$bulan] = $hutangMasuk - $hutangKeluar;
                } 
                elseif (in_array($akun, ['Penyertaan BMT Hasanah', 'Penyertaan DF'])) {
                    // Ambil dari Kas yang keterangannya mengandung nama penyertaan
                    $saldo[$akun][$bulan] = Kas::where('keterangan', 'LIKE', '%' . $akun . '%')
                        ->where('tanggal', '<=', $akhirBulan)
                        ->sum('jumlah');
                } 
                else {
                    $saldo[$akun][$bulan] = Kas::where('akun', $akun)
                        ->where('tanggal', '<=', $akhirBulan)
                        ->sum('jumlah');
                }
            }
        }

        // ==============================================================
        // 🔹 4. PENGIRIMAN DATA KE VIEW
        // ==============================================================
        return view('neraca.index', compact(
            'bulanList',
            'akunAktiva',
            'akunPasiva',
            'saldoAwal',
            'saldo',
            'sisaSaldo',
            'totalHppPerBulan'
        ));
    }

    public function neracaTabel(Request $request)
    {
        // Placeholder untuk fungsi cetak/export jika dibutuhkan
    }
}