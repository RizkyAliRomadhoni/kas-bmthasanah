<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kas;
use App\Models\RincianKambing;
use App\Models\KambingMati;
use App\Models\Penjualan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class NeracaController extends Controller
{
    public function index(Request $request)
    {
        // ================================
        // 🔹 1. DAFTAR BULAN (OTOMATIS)
        // ================================
        $bulanList = Kas::selectRaw("DATE_FORMAT(tanggal,'%Y-%m') as bulan")
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('bulan');

        // ================================
        // 🔹 2. PENGATURAN AKUN
        // ================================
        $akunAktiva = [
            'Kas',
            'Kambing', // Ini nanti diisi dari data RincianKambing
            'Pakan',
            'Operasional',
            'Perawatan',
            'Perlengkapan',
            'Kandang',
        ];

        $akunPasiva = [
            'Hutang',
            'Titipan',
            'Modal',
            'Penyertaan BMT Hasanah',
            'Penyertaan DF',
        ];

        // Saldo Awal Default
        $saldoAwal = [];
        foreach (array_merge($akunAktiva, $akunPasiva) as $akun) {
            $saldoAwal[$akun] = 0;
        }

        $saldo = [];
        $sisaSaldo = [];
        $totalHppPerBulan = [];

        // ================================
        // 🔹 3. LOOPING PER BULAN
        // ================================
        foreach ($bulanList as $bulan) {
            $akhirBulan = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();

            // --- A. INFO KAS NYATA ---
            $saldoAkhirBulan = Kas::where('tanggal', '<=', $akhirBulan)
                ->orderBy('tanggal', 'desc')
                ->orderBy('id', 'desc')
                ->value('saldo');

            $sisaSaldo[$bulan] = $saldoAkhirBulan ?? 0;

            // --- B. AMBIL DATA DARI MODEL LAIN (Rincian Kambing / Stok) ---
            // Kita anggap total_hpp sebagai nilai Aset Kambing
            $totalHppPerBulan[$bulan] = RincianKambing::where('created_at', '<=', $akhirBulan)
                ->sum('total_hpp');

            // --- C. PROSES AKTIVA ---
            foreach ($akunAktiva as $akun) {
                if ($akun === 'Kas') {
                    $saldo[$akun][$bulan] = 0; // Tidak dihitung di baris ini karena ada di "Kas Info"
                    continue;
                }

                if ($akun === 'Kambing') {
                    // Isi saldo akun Kambing dari data RincianKambing (HPP)
                    $saldo[$akun][$bulan] = $totalHppPerBulan[$bulan];
                    continue;
                }

                $saldo[$akun][$bulan] = Kas::where('akun', $akun)
                    ->where('tanggal', '<=', $akhirBulan)
                    ->sum('jumlah');
            }

            // --- D. PROSES PASIVA ---
            foreach ($akunPasiva as $akun) {
                // Akun Khusus Penyertaan (berdasarkan keterangan)
                if (in_array($akun, ['Penyertaan BMT Hasanah', 'Penyertaan DF'])) {
                    $saldo[$akun][$bulan] = Kas::where('akun', 'Kas')
                        ->where('keterangan', 'LIKE', '%' . $akun . '%')
                        ->where('tanggal', '<=', $akhirBulan)
                        ->sum('jumlah');
                    continue;
                }

                $saldo[$akun][$bulan] = Kas::where('akun', $akun)
                    ->where('tanggal', '<=', $akhirBulan)
                    ->sum('jumlah');
            }
        }

        // ================================
        // 🔹 4. KIRIM DATA KE VIEW
        // ================================
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
        // Fungsi placeholder jika dibutuhkan di masa depan
    }
}