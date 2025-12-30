<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\Penjualan;
use App\Models\KambingMati;
use App\Models\PakanDetail;
use App\Models\LabaRugiManual;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LabaRugiController extends Controller
{
    public function index()
    {
        // 1. Ambil semua bulan unik dari semua tabel (untuk sumbu X tabel)
        $bulanList = collect(array_merge(
            Kas::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray(),
            Penjualan::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray(),
            KambingMati::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray()
        ))->unique()->sort()->values();

        // 2. Ambil data manual yang tersimpan
        $manualData = LabaRugiManual::all()->groupBy('bulan');
        $labaRugiData = [];

        foreach ($bulanList as $bulan) {
            // --- A. DATA OTOMATIS BERDASARKAN STRUKTUR TABEL ANDA ---
            
            // Laba Penjualan Kambing (Sum kolom 'laba' di tabel penjualan)
            $labaKambingOto = Penjualan::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                                ->sum('laba'); // Kolom 'laba' otomatis menyimpan (harga_jual - hpp)

            // Beban Kambing Mati (Sum kolom 'harga' di tabel kambing_matis)
            $bebanMatiOto = KambingMati::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                                ->sum('harga');

            // Laba Pakan (Profit: Jual - Modal)
            $labaPakanOto = PakanDetail::whereHas('kas', fn($q) => $q->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan]))
                                ->get()->sum(fn($q) => ($q->harga_jual_kg - $q->harga_kg) * $q->qty_kg);

            // Laba Basil (Kas dengan keterangan Basil)
            $labaBasilOto = Kas::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                                ->where('keterangan', 'LIKE', '%Basil%')
                                ->where('jenis_transaksi', 'Masuk')->sum('jumlah');

            // Penyesuaian Harga (Kas akun Penyesuaian)
            $labaPenyesuaianOto = Kas::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                                ->where('akun', 'Penyesuaian')
                                ->where('jenis_transaksi', 'Masuk')->sum('jumlah');

            // --- B. LOGIKA OVERRIDE MANUAL ---
            $getVal = function($kat, $oto) use ($manualData, $bulan) {
                $manual = $manualData->has($bulan) ? $manualData[$bulan]->where('kategori', $kat)->first() : null;
                // Jika manual diisi (bukan null dan bukan 0), pakai manual. Jika tidak, pakai sistem.
                return ($manual && $manual->nilai != 0) ? (float)$manual->nilai : (float)$oto;
            };

            $laba_kambing     = $getVal('laba_kambing', $labaKambingOto);
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

        return back()->with('success', 'Data Laporan Berhasil Diupdate!');
    }
}