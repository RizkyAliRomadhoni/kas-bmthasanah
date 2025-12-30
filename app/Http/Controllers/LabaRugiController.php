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
        // 1. Ambil daftar bulan unik
        $bulanKas = Kas::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray();
        $bulanJual = Penjualan::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray();
        $bulanMati = KambingMati::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray();

        $bulanList = collect(array_merge($bulanKas, $bulanJual, $bulanMati))
            ->unique()
            ->sort()
            ->values();

        // 2. Ambil data input manual dari database
        $manualEntries = LabaRugiManual::all()->groupBy('bulan');

        $labaRugiData = [];

        foreach ($bulanList as $bulan) {
            // --- A. PENDAPATAN ---
            $penjualan = Penjualan::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])->get();
            $labaJualKambing = 0;
            $rugiJualKambing = 0;

            foreach ($penjualan as $jual) {
                $selisih = (int)$jual->harga_jual - (int)$jual->hpp;
                if ($selisih > 0) { $labaJualKambing += $selisih; } 
                else { $rugiJualKambing += abs($selisih); }
            }

            $labaJualPakan = PakanDetail::whereHas('kas', function($q) use ($bulan) {
                $q->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan]);
            })->get()->sum(fn($q) => ($q->harga_jual_kg - $q->harga_kg) * $q->qty_kg);

            $labaBasil = Kas::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                ->where('keterangan', 'LIKE', '%Basil%')->where('jenis_transaksi', 'Masuk')->sum('jumlah');

            $labaPenyesuaian = Kas::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                ->where('akun', 'Penyesuaian')->sum('jumlah');

            $totalPendapatan = $labaJualKambing + $labaJualPakan + $labaBasil + $labaPenyesuaian;

            // --- B. BIAYA ---
            $bebanMati = KambingMati::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])->sum('harga');

            // Ambil Data Manual (Jika tidak ada di DB, set 0)
            $bebanUpah = 0;
            $biayaLain = 0;
            if (isset($manualEntries[$bulan])) {
                $bebanUpah = $manualEntries[$bulan]->where('kategori', 'beban_upah')->first()->nilai ?? 0;
                $biayaLain = $manualEntries[$bulan]->where('kategori', 'biaya_lain')->first()->nilai ?? 0;
            }

            $totalBiaya = $rugiJualKambing + $bebanMati + $bebanUpah + $biayaLain;

            // --- C. LABA BERSIH ---
            $netLabaRugi = $totalPendapatan - $totalBiaya;

            $labaRugiData[$bulan] = [
                'laba_jual_kambing' => $labaJualKambing,
                'laba_jual_pakan'   => $labaJualPakan,
                'laba_penyesuaian'  => $labaPenyesuaian,
                'laba_basil'        => $labaBasil,
                'total_pendapatan'  => $totalPendapatan,
                'rugi_jual_kambing' => $rugiJualKambing,
                'beban_mati'        => $bebanMati,
                'beban_upah'        => $bebanUpah,
                'biaya_lain'        => $biayaLain,
                'total_biaya'       => $totalBiaya,
                'net_laba_rugi'     => $netLabaRugi,
            ];
        }

        return view('neraca.laba-rugi.index', compact('bulanList', 'labaRugiData'));
    }

    public function storeManual(Request $request)
    {
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
        return redirect()->back()->with('success', 'Input manual berhasil disimpan.');
    }

    public function refresh()
    {
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return redirect()->route('neraca.laba-rugi')->with('success', 'Data Laba Rugi berhasil diperbarui.');
    }
}