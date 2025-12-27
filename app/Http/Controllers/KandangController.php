<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\KandangDetail;
use Illuminate\Http\Request;

class KandangController extends Controller
{
    public function index(Request $request)
    {
        // Ambil daftar bulan untuk filter
        $listBulan = Kas::where('akun', 'Kandang')
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")
            ->groupBy('bulan')
            ->orderBy('bulan', 'desc')
            ->get();

        $query = Kas::with('kandangDetail')->where('akun', 'Kandang');

        $bulanTerpilih = $request->get('bulan');
        if ($bulanTerpilih) {
            $query->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanTerpilih]);
        }

        $data = $query->orderBy('tanggal', 'asc')->get(); // Asc agar urutan seperti Excel Anda

        // Hitung total harga
        $totalHarga = $data->sum('jumlah');

        return view('neraca.kandang.index', compact('data', 'listBulan', 'bulanTerpilih', 'totalHarga'));
    }

    public function storeOrUpdate(Request $request)
    {
        KandangDetail::updateOrCreate(
            ['kas_id' => $request->kas_id],
            ['catatan_tambahan' => $request->catatan_tambahan]
        );

        return back()->with('success', 'Catatan berhasil disimpan');
    }
}