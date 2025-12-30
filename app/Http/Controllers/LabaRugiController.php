<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\Penjualan;
use App\Models\KambingMati;
use App\Models\PakanDetail;
use App\Models\LabaRugiManual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class LabaRugiController extends Controller
{
    public function index()
    {
        // 1. Ambil semua daftar bulan unik dari berbagai transaksi
        $bulanList = collect(array_merge(
            Kas::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray(),
            Penjualan::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray(),
            KambingMati::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray()
        ))->unique()->sort()->values();

        // 2. Ambil data manual dari database
        $manualData = LabaRugiManual::all()->groupBy('bulan');
        $labaRugiData = [];

        foreach ($bulanList as $bulan) {
            // --- A. PERHITUNGAN OTOMATIS SISTEM ---
            
            // Laba Penjualan Kambing (Net) = Total Jual - Total HPP
            $penjualan = Penjualan::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])->get();
            $labaKambingNetOto = $penjualan->count() > 0 ? ($penjualan->sum('harga_jual') - $penjualan->sum('hpp')) : 0;

            // Laba Penjualan Pakan
            $labaPakanOto = PakanDetail::whereHas('kas', fn($q) => $q->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan]))
                             ->get()->sum(fn($q) => ($q->harga_jual_kg - $q->harga_kg) * $q->qty_kg);

            // Laba Basil (Bagi Hasil)
            $labaBasilOto = Kas::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                        ->where('keterangan', 'LIKE', '%Basil%')
                        ->where('jenis_transaksi', 'Masuk')->sum('jumlah');

            // Penyesuaian Harga
            $labaPenyesuaianOto = Kas::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                        ->where('akun', 'Penyesuaian')
                        ->where('jenis_transaksi', 'Masuk')->sum('jumlah');

            // Beban Mati
            $bebanMatiOto = KambingMati::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])->sum('harga');

            // --- B. LOGIKA OVERRIDE (MANUAL) ---
            $getVal = function($kat, $oto) use ($manualData, $bulan) {
                $manual = $manualData->has($bulan) ? $manualData[$bulan]->where('kategori', $kat)->first() : null;
                return $manual ? $manual->nilai : $oto;
            };

            $laba_kambing     = $getVal('laba_kambing', $labaKambingNetOto);
            $laba_pakan       = $getVal('laba_pakan', $labaPakanOto);
            $laba_basil       = $getVal('laba_basil', $labaBasilOto);
            $laba_penyesuaian = $getVal('laba_penyesuaian', $labaPenyesuaianOto);
            $beban_upah       = $getVal('beban_upah', 0);
            $biaya_lain       = $getVal('biaya_lain', 0);
            $beban_mati       = $getVal('beban_mati', $bebanMatiOto);

            // Perhitungan Total
            $total_pnd   = $laba_kambing + $laba_pakan + $laba_basil + $laba_penyesuaian;
            $total_biaya = $beban_upah + $biaya_lain + $beban_mati;

            $labaRugiData[$bulan] = [
                'laba_kambing'     => (float)$laba_kambing,
                'laba_pakan'       => (float)$laba_pakan,
                'laba_basil'       => (float)$laba_basil,
                'laba_penyesuaian' => (float)$laba_penyesuaian,
                'beban_upah'       => (float)$beban_upah,
                'biaya_lain'       => (float)$biaya_lain,
                'beban_mati'       => (float)$beban_mati,
                'total_pnd'        => (float)$total_pnd,
                'total_biaya'      => (float)$total_biaya,
                'net'              => (float)($total_pnd - $total_biaya),
            ];
        }

        return view('neraca.laba-rugi.index', compact('bulanList', 'labaRugiData'));
    }

    public function storeManual(Request $request)
    {
        $request->validate(['bulan' => 'required', 'kategori' => 'required', 'nilai' => 'required|numeric']);
        
        LabaRugiManual::updateOrCreate(
            ['bulan' => $request->bulan, 'kategori' => $request->kategori],
            ['nilai' => $request->nilai]
        );

        return back()->with('success', 'Data Laporan Berhasil Diperbarui!');
    }
}