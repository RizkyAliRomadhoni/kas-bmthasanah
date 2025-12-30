<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\Penjualan;
use App\Models\KambingMati;
use App\Models\PakanDetail;
use App\Models\LabaRugiManual; // Pastikan model ini sudah dibuat
use Illuminate\Http\Request;

class LabaRugiController extends Controller
{
    public function index()
    {
        // 1. Ambil list bulan dari semua transaksi
        $bulanList = collect(array_merge(
            Kas::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray(),
            Penjualan::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray(),
            KambingMati::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray()
        ))->unique()->sort()->values();

        // 2. Ambil data yang sudah diinput manual sebelumnya
        $manualEntries = LabaRugiManual::all()->groupBy('bulan');

        $labaRugiData = [];

        foreach ($bulanList as $bulan) {
            // --- DATA OTOMATIS ---
            $penjualan = Penjualan::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])->get();
            $labaJualKambing = 0; $rugiJualKambing = 0;
            foreach ($penjualan as $jual) {
                $selisih = (int)$jual->harga_jual - (int)$jual->hpp;
                $selisih > 0 ? $labaJualKambing += $selisih : $rugiJualKambing += abs($selisih);
            }

            $labaPakan = PakanDetail::whereHas('kas', fn($q) => $q->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan]))
                         ->get()->sum(fn($q) => ($q->harga_jual_kg - $q->harga_kg) * $q->qty_kg);

            $labaBasil = Kas::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                        ->where('keterangan', 'LIKE', '%Basil%')->where('jenis_transaksi', 'Masuk')->sum('jumlah');

            $bebanMati = KambingMati::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])->sum('harga');

            // --- DATA MANUAL (DARI DATABASE) ---
            $bebanUpah = $manualEntries->has($bulan) ? $manualEntries[$bulan]->where('kategori', 'beban_upah')->first()->nilai ?? 0 : 0;
            $biayaLain = $manualEntries->has($bulan) ? $manualEntries[$bulan]->where('kategori', 'biaya_lain')->first()->nilai ?? 0 : 0;

            $totalPnd = $labaJualKambing + $labaPakan + $labaBasil;
            $totalBiaya = $rugiJualKambing + $bebanMati + $bebanUpah + $biayaLain;

            $labaRugiData[$bulan] = [
                'laba_jual_kambing' => $labaJualKambing,
                'laba_jual_pakan'   => $labaPakan,
                'laba_basil'        => $labaBasil,
                'rugi_jual_kambing' => $rugiJualKambing,
                'beban_mati'        => $bebanMati,
                'beban_upah'        => $bebanUpah, // Nilai tersimpan
                'biaya_lain'        => $biayaLain, // Nilai tersimpan
                'total_pendapatan'  => $totalPnd,
                'total_biaya'       => $totalBiaya,
                'net_laba_rugi'     => $totalPnd - $totalBiaya,
            ];
        }

        return view('neraca.laba-rugi.index', compact('bulanList', 'labaRugiData'));
    }

    public function store(Request $request)
    {
        // Fungsi untuk menyimpan semua kotak input manual sekaligus
        if ($request->has('manual')) {
            foreach ($request->manual as $bulan => $data) {
                foreach ($data as $kategori => $nilai) {
                    LabaRugiManual::updateOrCreate(
                        ['bulan' => $bulan, 'kategori' => $kategori],
                        ['nilai' => $nilai ?? 0]
                    );
                }
            }
        }
        return back()->with('success', 'Data Manual Berhasil Disimpan!');
    }
}