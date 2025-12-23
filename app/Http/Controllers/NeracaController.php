<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class NeracaController extends Controller
{
    /**
     * ======================================================
     * 🔹 METHOD LAMA — JANGAN DIHAPUS
     * ======================================================
     */
    public function index(Request $request)
    {
        // ============================
        // 🔹 Ambil SEMUA BULAN unik (urut)
        // ============================
        $bulanList = Kas::selectRaw("DATE_FORMAT(tanggal,'%Y-%m') as bulan")
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('bulan');

        // ============================
        // 🔹 DAFTAR AKUN AKTIVA
        // ============================
        $akunAktiva = [
            'Kas',
            'Kambing',
            'Pakan',
            'Operasional',
            'Perawatan',
            'Perlengkapan',
            'Kandang',
        ];

        // ============================
        // 🔹 DAFTAR AKUN PASIVA
        // (DITAMBAH PENYERTAAN MODAL)
        // ============================
        $akunPasiva = [
            'Hutang',
            'Titipan',
            'Modal',
            'Penyertaan BMT Hasanah',
            'Penyertaan DF',
        ];

        // ============================
        // 🔹 SALDO AWAL (SEMUA 0)
        // ============================
        $saldoAwal = [];
        foreach (array_merge($akunAktiva, $akunPasiva) as $akun) {
            $saldoAwal[$akun] = 0;
        }

        // ============================
        // 🔹 HITUNG SALDO KUMULATIF
        // ============================
        $saldo = [];

        foreach (array_merge($akunAktiva, $akunPasiva) as $akun) {

            foreach ($bulanList as $bulan) {

                $akhirBulan = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();

                // ============================
                // 🔹 KAS (SALDO AKUMULATIF FINAL)
                // ============================
                if ($akun === 'Kas') {
                    $saldo[$akun][$bulan] = Kas::where('akun', 'Kas')
                        ->where('tanggal', '<=', $akhirBulan)
                        ->sum('jumlah');
                    continue;
                }

                // ============================
                // 🔹 PENYERTAAN MODAL (PASIVA)
                // ============================
                if (in_array($akun, ['Penyertaan BMT Hasanah', 'Penyertaan DF'])) {

                    // Jika kolom keterangan belum ada → 0
                    if (!Schema::hasColumn('kas', 'keterangan')) {
                        $saldo[$akun][$bulan] = 0;
                        continue;
                    }

                    $saldo[$akun][$bulan] = Kas::where('akun', 'Kas')
                        ->where('keterangan', $akun)
                        ->where('tanggal', '<=', $akhirBulan)
                        ->sum('jumlah');

                    continue;
                }

                // ============================
                // 🔹 AKUN LAIN (KUMULATIF MURNI)
                // ============================
                $saldo[$akun][$bulan] = Kas::where('akun', $akun)
                    ->where('tanggal', '<=', $akhirBulan)
                    ->sum('jumlah');
            }
        }

        return view('neraca.index', compact(
            'bulanList',
            'akunAktiva',
            'akunPasiva',
            'saldoAwal',
            'saldo'
        ));
    }

    /**
     * ======================================================
     * 🔹 METHOD BARU (DIBIARKAN)
     * ======================================================
     */
    public function neracaTabel(Request $request)
    {
        // BIARKAN — TIDAK DIHAPUS
    }
}
