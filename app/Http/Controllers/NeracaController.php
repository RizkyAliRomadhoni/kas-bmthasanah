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
        // BULAN OTOMATIS
        // ================================
        $bulanList = Kas::selectRaw("DATE_FORMAT(tanggal,'%Y-%m') as bulan")
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('bulan');

        // ================================
        // AKUN
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
            'Penyertaan BMT Hasanah',
            'Penyertaan DF',
            'Modal',
        ];

        // ================================
        // SALDO AWAL (DUMMY SESUAI EXCEL)
        // ================================
        $saldoAwal = [];
        foreach (array_merge($akunAktiva, $akunPasiva) as $akun) {
            $saldoAwal[$akun] = 0;
        }

        $saldo = [];
        $sisaSaldo = [];

        foreach ($bulanList as $bulan) {

            $akhirBulan = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();

            // ================================
            // SISA SALDO NYATA (INFORMASI)
            // ================================
            $saldoAkhir = Kas::where('tanggal', '<=', $akhirBulan)
                ->orderBy('tanggal', 'desc')
                ->orderBy('id', 'desc')
                ->value('saldo');

            $sisaSaldo[$bulan] = $saldoAkhir ?? 0;

            // ================================
            // AKTIVA (KUMULATIF)
            // ================================
            foreach ($akunAktiva as $akun) {

                if ($akun === 'Kas') {
                    $saldo[$akun][$bulan] = 0; // Kas tidak dipakai hitung neraca
                    continue;
                }

                $saldo[$akun][$bulan] = Kas::where('akun', $akun)
                    ->where('tanggal', '<=', $akhirBulan)
                    ->sum('jumlah');
            }

            // ================================
            // PASIVA (KUMULATIF)
            // ================================
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
            'sisaSaldo'
        ));
    }

    public function neracaTabel()
    {
        // BIARKAN SESUAI PERMINTAAN
    }
}
