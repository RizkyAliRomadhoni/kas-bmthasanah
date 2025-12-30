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
    // 1. Ambil daftar bulan unik dari semua transaksi
    $bulanList = collect(array_merge(
        Kas::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray(),
        Penjualan::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray(),
        KambingMati::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray()
    ))->unique()->sort()->values();

    // 2. Ambil data manual
    $manualData = LabaRugiManual::all()->groupBy('bulan');
    $labaRugiData = [];

    foreach ($bulanList as $bulan) {
        // --- HITUNG OTOMATIS (SISTEM) ---
        
        // Laba Penjualan Kambing (Net)
        $penjualan = Penjualan::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])->get();
        // Pakai perulangan agar casting (int) berjalan benar untuk angka minus
        $netJualOto = 0;
        foreach ($penjualan as $jual) {
            $netJualOto += ((int)$jual->harga_jual - (int)$jual->hpp);
        }

        $labaPakanOto = PakanDetail::whereHas('kas', fn($q) => $q->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan]))
                         ->get()->sum(fn($q) => ((int)$q->harga_jual_kg - (int)$q->harga_kg) * (int)$q->qty_kg);

        $labaBasilOto = Kas::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                        ->where('keterangan', 'LIKE', '%Basil%')
                        ->where('jenis_transaksi', 'Masuk')->sum('jumlah');

        $labaPenyesuaianOto = Kas::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                        ->where('akun', 'Penyesuaian')
                        ->where('jenis_transaksi', 'Masuk')->sum('jumlah');

        $bebanMatiOto = KambingMati::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])->sum('harga');

        // --- LOGIKA CERDAS: PAKAI MANUAL HANYA JIKA TIDAK NOL ---
        $getVal = function($kat, $oto) use ($manualData, $bulan) {
            $manual = $manualData->has($bulan) ? $manualData[$bulan]->where('kategori', $kat)->first() : null;
            // Jika di tabel manual ada isinya dan bukan 0, pakai manual. Jika 0/kosong, pakai hitungan sistem.
            return ($manual && $manual->nilai != 0) ? $manual->nilai : $oto;
        };

        $laba_kambing     = $getVal('laba_kambing', $netJualOto);
        $laba_pakan       = $getVal('laba_pakan', $labaPakanOto);
        $laba_basil       = $getVal('laba_basil', $labaBasilOto);
        $laba_penyesuaian = $getVal('laba_penyesuaian', $labaPenyesuaianOto);
        $beban_upah       = $getVal('beban_upah', 0);
        $biaya_lain       = $getVal('biaya_lain', 0);
        $beban_mati       = $getVal('beban_mati', $bebanMatiOto);

        $total_pnd   = $laba_kambing + $laba_pakan + $laba_basil + $laba_penyesuaian;
        $total_biaya = $beban_upah + $biaya_lain + $beban_mati;

        $labaRugiData[$bulan] = [
            'laba_kambing'     => $laba_kambing,
            'laba_pakan'       => $laba_pakan,
            'laba_basil'       => $laba_basil,
            'laba_penyesuaian' => $laba_penyesuaian,
            'beban_upah'       => $beban_upah,
            'biaya_lain'       => $biaya_lain,
            'beban_mati'       => $beban_mati,
            'total_pnd'        => $total_pnd,
            'total_biaya'      => $total_biaya,
            'net'              => $total_pnd - $total_biaya,
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