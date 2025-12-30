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
        // 1. Ambil list bulan unik dari semua transaksi sistem
        $bulanList = collect(array_merge(
            Kas::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray(),
            Penjualan::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray(),
            KambingMati::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray()
        ))->unique()->sort()->values();

        // 2. Ambil data manual yang tersimpan di DB
        $manualData = LabaRugiManual::all()->groupBy('bulan');

        $labaRugiData = [];

        foreach ($bulanList as $bulan) {
            // --- A. HITUNG OTOMATIS DARI SISTEM ---
            
            // Penjualan Kambing
            $penjualan = Penjualan::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])->get();
            $labaKambingOto = 0; $rugiJualOto = 0;
            foreach ($penjualan as $jual) {
                $selisih = (int)$jual->harga_jual - (int)$jual->hpp;
                if ($selisih > 0) { $labaKambingOto += $selisih; } 
                else { $rugiJualOto += abs($selisih); }
            }

            // Penjualan Pakan
            $labaPakanOto = PakanDetail::whereHas('kas', function($q) use ($bulan) {
                $q->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan]);
            })->get()->sum(fn($q) => ($q->harga_jual_kg - $q->harga_kg) * $q->qty_kg);

            // Basil & Penyesuaian
            $labaLainOto = Kas::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                        ->where(function($q) {
                            $q->where('keterangan', 'LIKE', '%Basil%')
                              ->orWhere('akun', 'Penyesuaian');
                        })
                        ->where('jenis_transaksi', 'Masuk')->sum('jumlah');

            // Beban Mati
            $bebanMatiOto = KambingMati::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])->sum('harga') + $rugiJualOto;

            // --- B. LOGIKA OVERRIDE (Gunakan Manual jika ada, jika tidak pakai Otomatis) ---
            $getVal = function($kat, $oto) use ($manualData, $bulan) {
                $manual = $manualData->has($bulan) ? $manualData[$bulan]->where('kategori', $kat)->first() : null;
                return $manual ? $manual->nilai : $oto;
            };

            // Variabel dengan underscore agar konsisten
            $laba_kambing = $getVal('laba_kambing', $labaKambingOto);
            $laba_pakan   = $getVal('laba_pakan', $labaPakanOto);
            $laba_lain    = $getVal('laba_lain', $labaLainOto);
            $beban_upah   = $getVal('beban_upah', 0); // Default 0 jika tidak ada manual
            $biaya_lain   = $getVal('biaya_lain', 0); // Default 0 jika tidak ada manual
            $beban_mati   = $getVal('beban_mati', $bebanMatiOto);

            $total_pnd    = $laba_kambing + $laba_pakan + $laba_lain;
            $total_biaya  = $beban_upah + $biaya_lain + $beban_mati;

            // Simpan ke array Data
            $labaRugiData[$bulan] = [
                'laba_kambing' => $laba_kambing,
                'laba_pakan'   => $laba_pakan,
                'laba_lain'    => $laba_lain,
                'beban_upah'   => $beban_upah,
                'biaya_lain'   => $biaya_lain,
                'beban_mati'   => $beban_mati,
                'total_pnd'    => $total_pnd,
                'total_biaya'  => $total_biaya,
                'net'          => $total_pnd - $total_biaya,
            ];
        }

        return view('neraca.laba-rugi.index', compact('bulanList', 'labaRugiData'));
    }

    public function storeManual(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'kategori' => 'required',
            'nilai' => 'required|numeric'
        ]);

        LabaRugiManual::updateOrCreate(
            ['bulan' => $request->bulan, 'kategori' => $request->kategori],
            ['nilai' => $request->nilai]
        );

        return back()->with('success', 'Laporan Berhasil Diperbarui!');
    }
}