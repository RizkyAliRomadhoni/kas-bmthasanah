<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class NeracaController extends Controller
{
    public function index(Request $request)
    {
        // ================================
        // 🔹 BULAN OTOMATIS
        // ================================
        $bulanList = Kas::selectRaw("DATE_FORMAT(tanggal,'%Y-%m') as bulan")
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('bulan');

        // ================================
        // 🔹 AKUN
        // ================================
        $akunAktiva = [
            'Kas',
            'Kambing',
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

        // ================================
        // 🔹 SALDO AWAL
        // ================================
        $saldoAwal = [];
        foreach (array_merge($akunAktiva, $akunPasiva) as $akun) {
            $saldoAwal[$akun] = 0;
        }

        $saldo = [];
        $sisaSaldo = []; // ⬅️ INI YANG BARU

        foreach ($bulanList as $bulan) {

            $akhirBulan = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();

            // ========================================
            // 🔹 HITUNG SISA SALDO NYATA
            // ========================================
            $totalKasMasuk = Kas::where('akun', 'Kas')
                ->where('tanggal', '<=', $akhirBulan)
                ->sum('jumlah');

            $totalPemakaian = Kas::where('akun', '!=', 'Kas')
                ->where('tanggal', '<=', $akhirBulan)
                ->sum('jumlah');

            $sisaSaldo[$bulan] = $totalKasMasuk - $totalPemakaian;

            // ========================================
            // 🔹 AKTIVA
            // ========================================
            foreach ($akunAktiva as $akun) {

                if ($akun === 'Kas') {
                    $saldo[$akun][$bulan] = 0; // KAS NERACA DIABAIKAN
                    continue;
                }

                $saldo[$akun][$bulan] = Kas::where('akun', $akun)
                    ->where('tanggal', '<=', $akhirBulan)
                    ->sum('jumlah');
            }

            // ========================================
            // 🔹 PASIVA
            // ========================================
            foreach ($akunPasiva as $akun) {

                if (in_array($akun, ['Penyertaan BMT Hasanah', 'Penyertaan DF'])) {

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
            'saldo',
            'sisaSaldo' // ⬅️ KIRIM KE VIEW
        ));
    }

    public function neracaTabel(Request $request)
    {
        // BIARKAN
    }
}
