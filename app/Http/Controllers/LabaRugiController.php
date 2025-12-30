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
        // 1. Ambil list bulan unik dari transaksi
        $bulanList = collect(array_merge(
            Kas::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray(),
            Penjualan::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray(),
            KambingMati::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray()
        ))->unique()->sort()->values();

        // 2. Ambil semua data manual
        $manualData = LabaRugiManual::all()->groupBy('bulan');

        $labaRugiData = [];

        foreach ($bulanList as $bulan) {
            // --- HITUNG OTOMATIS (SISTEM) ---
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
            $bebanMatiOto = KambingMati::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])->sum('harga') + $rugiJualOto;

            // --- LOGIKA OVERRIDE (MANUAL) ---
            $getVal = function($kat, $oto) use ($manualData, $bulan) {
                $manual = $manualData->has($bulan) ? $manualData[$bulan]->where('kategori', $kat)->first() : null;
                return $manual ? $manual->nilai : $oto;
            };

            $labaKambing = $getVal('laba_kambing', $labaKambingOto);
            $labaPakan   = $getVal('laba_pakan', $labaPakanOto);
            $labaLain    = $getVal('laba_lain', $labaLainOto);
            $bebanUpah   = $getVal('beban_upah', 0);
            $biayaLain   = $getVal('biaya_lain', 0);
            $bebanMati   = $getVal('beban_mati', $bebanMatiOto);

            $totalPnd = $labaKambing + $labaPakan + $labaLain;
            $totalBiaya = $bebanUpah + $biayaLain + $bebanMati;

            $labaRugiData[$bulan] = [
                'laba_kambing' => $labaKambing,
                'laba_pakan'   => $labaPakan,
                'laba_lain'    => $labaLain,
                'beban_upah'   => $beban_upah,
                'biaya_lain'   => $biayaLain,
                'beban_mati'   => $bebanMati,
                'total_pnd'    => $totalPnd,
                'total_biaya'  => $totalBiaya,
                'net'          => $totalPnd - $totalBiaya,
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

        return back()->with('success', 'Data Laporan Berhasil Diperbarui!');
    }
}