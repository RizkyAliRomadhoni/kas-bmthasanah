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
        // 🔹 1. DAFTAR BULAN (OTOMATIS DARI DATA KAS)
        // ================================
        $bulanList = Kas::selectRaw("DATE_FORMAT(tanggal,'%Y-%m') as bulan")
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('bulan');

        // ================================
        // 🔹 2. PENGATURAN AKUN (AKTIVA & PASIVA)
        // ================================
        $akunAktiva = [
            'Kas',
            'Kambing',
            'Pakan',
            'Perlengkapan',
            'Upah',         // Tambahan Baru
            'Kandang',
            'Operasional',
            'Perawatan',
        ];

        $akunPasiva = [
            'Hutang',
            'Titipan',
            'Modal',
            'Penyertaan BMT Hasanah',
            'Penyertaan DF',
        ];

        // Inisialisasi Saldo Awal (Jika ada saldo sebelum sistem berjalan, isi di sini)
        $saldoAwal = [];
        foreach (array_merge($akunAktiva, $akunPasiva) as $akun) {
            $saldoAwal[$akun] = 0;
        }

        $saldo = [];
        $sisaSaldo = [];
        $totalHppPerBulan = [];

        // ================================
        // 🔹 3. LOOPING PER BULAN (UNTUK MENGHITUNG SALDO AKUMULATIF)
        // ================================
        foreach ($bulanList as $bulan) {
            $akhirBulan = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();

            // --- A. INFO KAS NYATA (DARI SALDO TERAKHIR DI TABEL KAS) ---
            $saldoAkhirBulan = Kas::where('tanggal', '<=', $akhirBulan)
                ->orderBy('tanggal', 'desc')
                ->orderBy('id', 'desc')
                ->value('saldo');

            $sisaSaldo[$bulan] = $saldoAkhirBulan ?? 0;

            // --- B. AMBIL NILAI ASET KAMBING (DARI TABEL RINCIAN KAMBING) ---
            // Mengasumsikan total_hpp di RincianKambing adalah nilai aset ternak
            $totalHppPerBulan[$bulan] = RincianKambing::where('created_at', '<=', $akhirBulan)
                ->sum('total_hpp');

            // --- C. PROSES AKTIVA (ASET) ---
            foreach ($akunAktiva as $akun) {
                // Spesifik untuk akun Kas, ambil dari saldo akhir riil
                if ($akun === 'Kas') {
                    $saldo[$akun][$bulan] = $sisaSaldo[$bulan];
                    continue;
                }

                // Spesifik untuk akun Kambing, ambil dari rincian HPP
                if ($akun === 'Kambing') {
                    $saldo[$akun][$bulan] = $totalHppPerBulan[$bulan];
                    continue;
                }

                // Untuk akun lainnya, hitung akumulasi pengeluaran dari tabel Kas
                // Dalam akuntansi, pengeluaran aset (pembelian pakan/perlengkapan) menambah nilai Aktiva
                $saldo[$akun][$bulan] = Kas::where('akun', $akun)
                    ->where('tanggal', '<=', $akhirBulan)
                    ->sum('jumlah');
            }

            // --- D. PROSES PASIVA (KEWAJIBAN & MODAL) ---
            foreach ($akunPasiva as $akun) {
                // Logika khusus untuk Akun Penyertaan (berdasarkan keterangan di Kas)
                if (in_array($akun, ['Penyertaan BMT Hasanah', 'Penyertaan DF'])) {
                    $saldo[$akun][$bulan] = Kas::where('akun', 'Kas')
                        ->where('keterangan', 'LIKE', '%' . $akun . '%')
                        ->where('tanggal', '<=', $akhirBulan)
                        ->sum('jumlah');
                    continue;
                }

                // Akumulasi saldo untuk Hutang, Titipan, dll
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
        // Fungsi placeholder jika Anda membutuhkan export data di masa depan
    }
}