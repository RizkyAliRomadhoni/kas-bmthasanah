<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\Penjualan;
use App\Models\KambingMati;
use App\Models\PakanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class LabaRugiController extends Controller
{
    public function index()
    {
        // 1. Ambil daftar bulan unik dari semua tabel sumber (Otomatis update jika ada input baru)
        $bulanKas = Kas::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray();
        $bulanJual = Penjualan::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray();
        $bulanMati = KambingMati::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")->pluck('bulan')->toArray();

        // Menggabungkan semua bulan, hapus duplikat, dan urutkan dari yang lama ke baru
        $bulanList = collect(array_merge($bulanKas, $bulanJual, $bulanMati))
            ->unique()
            ->sort()
            ->values();

        $labaRugiData = [];

        foreach ($bulanList as $bulan) {
            // ==========================================
            // 🔹 A. PENDAPATAN
            // ==========================================

            // 1. Laba Penjualan Kambing (Hanya keuntungan bersih per ekor)
            $penjualan = Penjualan::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])->get();
            $labaJualKambing = 0;
            $rugiJualKambing = 0;

            foreach ($penjualan as $jual) {
                $selisih = (int)$jual->harga_jual - (int)$jual->hpp;
                if ($selisih > 0) {
                    $labaJualKambing += $selisih;
                } else {
                    // Jika jual rugi, kumpulkan untuk seksi biaya
                    $rugiJualKambing += abs($selisih);
                }
            }

            // 2. Laba Penjualan Pakan (Margin Pakan)
            $labaJualPakan = PakanDetail::whereHas('kas', function($q) use ($bulan) {
                $q->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan]);
            })->get()->sum(fn($q) => ($q->harga_jual_kg - $q->harga_kg) * $q->qty_kg);

            // 3. Laba Basil (Dari Kas keterangan "Basil")
            $labaBasil = Kas::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                ->where('keterangan', 'LIKE', '%Basil%')
                ->where('jenis_transaksi', 'Masuk')
                ->sum('jumlah');

            // 4. Laba Penyesuaian (Jika ada transaksi khusus akun penyesuaian)
            $labaPenyesuaian = Kas::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                ->where('akun', 'Penyesuaian')
                ->sum('jumlah');

            $totalPendapatan = $labaJualKambing + $labaJualPakan + $labaBasil + $labaPenyesuaian;

            // ==========================================
            // 🔹 B. BIAYA
            // ==========================================

            // 1. Kerugian Kambing Mati
            $bebanMati = KambingMati::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                ->sum('harga');

            // Total Biaya (Kerugian Jual + Kematian)
            $totalBiaya = $rugiJualKambing + $bebanMati;

            // ==========================================
            // 🔹 C. LABA BERSIH
            // ==========================================
            $netLabaRugi = $totalPendapatan - $totalBiaya;

            // Simpan ke array Data
            $labaRugiData[$bulan] = [
                'laba_jual_kambing' => $labaJualKambing,
                'laba_jual_pakan'   => $labaJualPakan,
                'laba_penyesuaian'  => $labaPenyesuaian,
                'laba_basil'        => $labaBasil,
                'total_pendapatan'  => $totalPendapatan,
                'rugi_jual_kambing' => $rugiJualKambing,
                'beban_mati'        => $bebanMati,
                'total_biaya'       => $totalBiaya,
                'net_laba_rugi'     => $netLabaRugi,
            ];
        }

        return view('neraca.laba-rugi.index', compact('bulanList', 'labaRugiData'));
    }

    /**
     * FUNGSI HITUNG ULANG (REFRESH)
     */
    public function refresh()
    {
        // Bersihkan cache Laravel agar data ditarik murni dari database terbaru
        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        return redirect()->route('neraca.laba-rugi')->with('success', 'Data Laba Rugi berhasil diperbarui.');
    }
}