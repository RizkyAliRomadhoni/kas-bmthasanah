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

        // 2. Ambil data manual dari database
        $manualEntries = LabaRugiManual::all()->groupBy('bulan');

        $labaRugiData = [];

        foreach ($bulanList as $bulan) {
            // --- A. PENDAPATAN OTOMATIS ---
            $penjualan = Penjualan::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])->get();
            $labaJualKambing = 0; $rugiJualKambing = 0;
            foreach ($penjualan as $jual) {
                $selisih = (int)$jual->harga_jual - (int)$jual->hpp;
                $selisih > 0 ? $labaJualKambing += $selisih : $rugiJualKambing += abs($selisih);
            }

            $labaPakan = PakanDetail::whereHas('kas', fn($q) => $q->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan]))
                         ->get()->sum(fn($q) => ($q->harga_jual_kg - $q->harga_kg) * $q->qty_kg);

            $labaLain = Kas::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                        ->where(fn($q) => $q->where('keterangan', 'LIKE', '%Basil%')->orWhere('akun', 'Penyesuaian'))
                        ->where('jenis_transaksi', 'Masuk')->sum('jumlah');

            $totalPendapatan = $labaJualKambing + $labaPakan + $labaLain;

            // --- B. BIAYA (OTOMATIS + MANUAL) ---
            $bebanMati = KambingMati::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])->sum('harga');
            
            // Ambil dari tabel manual
            $bebanUpah = $manualEntries->has($bulan) ? $manualEntries[$bulan]->where('kategori', 'beban_upah')->first()->nilai ?? 0 : 0;
            $biayaLain = $manualEntries->has($bulan) ? $manualEntries[$bulan]->where('kategori', 'biaya_lain')->first()->nilai ?? 0 : 0;

            $totalBiaya = $rugiJualKambing + $bebanMati + $bebanUpah + $biayaLain;

            // --- C. FINAL ---
            $labaRugiData[$bulan] = [
                'laba_kambing' => $labaJualKambing,
                'laba_pakan'   => $labaPakan,
                'laba_lain'    => $labaLain,
                'rugi_jual'    => $rugiJualKambing,
                'beban_mati'   => $bebanMati,
                'beban_upah'   => $bebanUpah,
                'biaya_lain'   => $biayaLain,
                'total_pendapatan' => $totalPendapatan,
                'total_biaya' => $totalBiaya,
                'net_laba_rugi' => $totalPendapatan - $totalBiaya,
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

        return back()->with('success', 'Data manual berhasil disimpan!');
    }

    public function refresh()
    {
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return redirect()->route('neraca.laba-rugi')->with('success', 'Data berhasil diperbarui.');
    }
}