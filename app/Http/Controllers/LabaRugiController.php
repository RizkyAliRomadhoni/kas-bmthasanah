<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\Penjualan;
use App\Models\KambingMati;
use App\Models\PakanDetail;
use App\Models\LabaRugiManual;
use Illuminate\Http\Request;

class LabaRugiController extends Controller
{
    public function index()
    {
        $bulanList = collect(array_merge(
            Kas::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray(),
            Penjualan::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray(),
            KambingMati::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray()
        ))->unique()->sort()->values();

        $manualData = LabaRugiManual::all()->groupBy('bulan');
        $labaRugiData = [];

        foreach ($bulanList as $bulan) {
            // --- DATA OTOMATIS DARI SISTEM ---
            $penjualan = Penjualan::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])->get();
            $labaKambingOto = 0; $rugiJualOto = 0;
            foreach ($penjualan as $jual) {
                $selisih = (int)$jual->harga_jual - (int)$jual->hpp;
                $selisih > 0 ? $labaKambingOto += $selisih : $rugiJualOto += abs($selisih);
            }
            $labaPakanOto = PakanDetail::whereHas('kas', fn($q) => $q->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan]))
                             ->get()->sum(fn($q) => ($q->harga_jual_kg - $q->harga_kg) * $q->qty_kg);
            $labaLainOto = Kas::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                        ->where(fn($q) => $q->where('keterangan', 'LIKE', '%Basil%')->orWhere('akun', 'Penyesuaian'))
                        ->where('jenis_transaksi', 'Masuk')->sum('jumlah');
            $bebanMatiOto = KambingMati::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])->sum('harga');

            // --- CEK APAKAH ADA DATA MANUAL (JIKA ADA PAKAI MANUAL, JIKA TIDAK PAKAI OTOMATIS) ---
            $getVal = function($kat, $oto) use ($manualData, $bulan) {
                $manual = $manualData->has($bulan) ? $manualData[$bulan]->where('kategori', $kat)->first() : null;
                return $manual ? $manual->nilai : $oto;
            };

            $labaKambing = $getVal('laba_kambing', $labaKambingOto);
            $labaPakan   = $getVal('laba_pakan', $labaPakanOto);
            $labaLain    = $getVal('laba_lain', $labaLainOto);
            $bebanUpah   = $getVal('beban_upah', 0);
            $biayaLain   = $getVal('biaya_lain', 0);
            $bebanMati   = $getVal('beban_mati', $bebanMatiOto + $rugiJualOto);

            $totalPnd = $labaKambing + $labaPakan + $labaLain;
            $totalBiaya = $bebanUpah + $biayaLain + $bebanMati;

            $labaRugiData[$bulan] = [
                'laba_kambing' => $labaKambing,
                'laba_pakan'   => $labaPakan,
                'laba_lain'    => $labaLain,
                'beban_upah'   => $bebanUpah,
                'biaya_lain'   => $biayaLain,
                'beban_mati'   => $bebanMati,
                'total_pnd'    => $totalPnd,
                'total_biaya'  => $totalBiaya,
                'net'          => $totalPnd - $totalBiaya,
            ];
        }

        return view('neraca.laba-rugi.index', compact('bulanList', 'labaRugiData'));
    }

    public function storeManual(Request $request) {
        if ($request->has('manual')) {
            foreach ($request->manual as $bulan => $kategoriData) {
                foreach ($kategoriData as $kategori => $nilai) {
                    LabaRugiManual::updateOrCreate(
                        ['bulan' => $bulan, 'kategori' => $kategori],
                        ['nilai' => $nilai ?? 0]
                    );
                }
            }
        }
        return back()->with('success', 'Semua laporan berhasil diperbarui!');
    }
}