<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NeracaController extends Controller
{
    public function index()
    {
        /**
         * ===============================
         * 1️⃣ BULAN LIST (DINAMIS)
         * ===============================
         */
        $bulanList = DB::table('kas')
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('bulan')
            ->toArray();

        /**
         * ===============================
         * 2️⃣ DAFTAR AKUN
         * ===============================
         */
        $akunAktiva = [
            'Piutang',
            'Inventaris',
            'Aset Lainnya'
        ];

        $akunPasiva = [
            'Hutang Usaha',
            'Hutang Lainnya',
            'Penyertaan BMT Hasanah',
            'Penyertaan DF'
        ];

        /**
         * ===============================
         * 3️⃣ SALDO PER AKUN PER BULAN
         * (TANPA jenis_transaksi)
         * ===============================
         */
        $saldo = [];

        $dataSaldo = DB::table('kas')
            ->selectRaw("
                akun,
                DATE_FORMAT(tanggal, '%Y-%m') as bulan,
                SUM(jumlah) as total
            ")
            ->groupBy('akun', 'bulan')
            ->get();

        foreach ($dataSaldo as $row) {
            $saldo[$row->akun][$row->bulan] = $row->total;
        }

        /**
         * ===============================
         * 4️⃣ SALDO AWAL (SEBELUM BULAN PERTAMA)
         * ===============================
         */
        $saldoAwal = [];
        if (!empty($bulanList)) {
            $bulanPertama = Carbon::createFromFormat('Y-m', $bulanList[0])->startOfMonth();

            $dataSaldoAwal = DB::table('kas')
                ->selectRaw("akun, SUM(jumlah) as total")
                ->where('tanggal', '<', $bulanPertama)
                ->groupBy('akun')
                ->get();

            foreach ($dataSaldoAwal as $row) {
                $saldoAwal[$row->akun] = $row->total;
            }
        }

        /**
         * ===============================
         * 5️⃣ SISA SALDO KAS (INFORMASI)
         * ===============================
         */
        $sisaSaldo = [];
        $runningSaldo = 0;

        $kasPerBulan = DB::table('kas')
            ->selectRaw("
                DATE_FORMAT(tanggal, '%Y-%m') as bulan,
                SUM(jumlah) as total
            ")
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        foreach ($kasPerBulan as $row) {
            $runningSaldo += $row->total;
            $sisaSaldo[$row->bulan] = $runningSaldo;
        }

        return view('neraca.index', compact(
            'bulanList',
            'akunAktiva',
            'akunPasiva',
            'saldo',
            'saldoAwal',
            'sisaSaldo'
        ));
    }
}
