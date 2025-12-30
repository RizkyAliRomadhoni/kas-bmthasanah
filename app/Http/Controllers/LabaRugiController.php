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
        // 1. Ambil list bulan unik dari semua transaksi
        $bulanList = collect(array_merge(
            Kas::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray(),
            Penjualan::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray(),
            KambingMati::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray()
        ))->unique()->sort()->values();

        // 2. Ambil data manual yang sudah tersimpan
        $manualEntries = LabaRugiManual::all()->groupBy('bulan');

        $labaRugiData = [];

        foreach ($bulanList as $bulan) {
            // --- A. DATA OTOMATIS (Sesuai Logic Anda) ---
            
            // Penjualan Kambing
            $penjualan = Penjualan::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])->get();
            $labaJualKambing = 0; $rugiJualKambing = 0;
            foreach ($penjualan as $jual) {
                $selisih = (int)$jual->harga_jual - (int)$jual->hpp;
                $selisih > 0 ? $labaJualKambing += $selisih : $rugiJualKambing += abs($selisih);
            }

            // Penjualan Pakan
            $labaJualPakan = PakanDetail::whereHas('kas', fn($q) => $q->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan]))
                             ->get()->sum(fn($q) => ($q->harga_jual_kg - $q->harga_kg) * $q->qty_kg);

            // Basil & Penyesuaian
            $labaBasil = Kas::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                        ->where('keterangan', 'LIKE', '%Basil%')->where('jenis_transaksi', 'Masuk')->sum('jumlah');
            $labaPenyesuaian = Kas::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                        ->where('akun', 'Penyesuaian')->sum('jumlah');

            // Beban Mati
            $bebanMati = KambingMati::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])->sum('harga');

            // --- B. DATA MANUAL (Ditarik dari tabel LabaRugiManual) ---
            $bebanUpah = $manualEntries->has($bulan) ? $manualEntries[$bulan]->where('kategori', 'beban_upah')->first()->nilai ?? 0 : 0;
            $biayaLain = $manualEntries->has($bulan) ? $manualEntries[$bulan]->where('kategori', 'biaya_lain')->first()->nilai ?? 0 : 0;

            // --- C. KALKULASI TOTAL ---
            $totalPendapatan = $labaJualKambing + $labaJualPakan + $labaBasil + $labaPenyesuaian;
            $totalBiaya = $rugiJualKambing + $bebanMati + $bebanUpah + $biayaLain;

            $labaRugiData[$bulan] = [
                'laba_jual_kambing' => $labaJualKambing,
                'laba_jual_pakan'   => $labaJualPakan,
                'laba_penyesuaian'  => $labaPenyesuaian,
                'laba_basil'        => $labaBasil,
                'rugi_jual_kambing' => $rugiJualKambing,
                'beban_mati'        => $bebanMati,
                'beban_upah'        => $bebanUpah, // Manual
                'biaya_lain'        => $biayaLain, // Manual
                'total_pendapatan'  => $totalPendapatan,
                'total_biaya'       => $totalBiaya,
                'net_laba_rugi'     => $totalPendapatan - $totalBiaya,
            ];
        }

        return view('neraca.laba-rugi.index', compact('bulanList', 'labaRugiData'));
    }

    public function storeManual(Request $request)
    {
        // Simpan semua input manual dari tabel
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
        return back()->with('success', 'Data berhasil disimpan!');
    }
}