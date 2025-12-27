<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\UpahDetail;
use Illuminate\Http\Request;

class UpahController extends Controller
{
    public function index(Request $request)
    {
        // Ambil daftar bulan untuk filter dropdown
        $listBulan = Kas::where('akun', 'Upah')
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")
            ->groupBy('bulan')
            ->orderBy('bulan', 'desc')
            ->get();

        $query = Kas::with('upahDetail')->where('akun', 'Upah');

        // Logika Filter
        $bulanTerpilih = $request->get('bulan');
        if ($bulanTerpilih) {
            $query->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanTerpilih]);
        }

        $data = $query->orderBy('tanggal', 'asc')->get();

        // Hitung total pengeluaran upah
        $totalUpah = $data->sum('jumlah');

        return view('neraca.upah.index', compact('data', 'listBulan', 'bulanTerpilih', 'totalUpah'));
    }

    // Fungsi Simpan
    public function storeOrUpdate(Request $request)
    {
        $request->validate(['kas_id' => 'required|exists:kas,id']);

        UpahDetail::updateOrCreate(
            ['kas_id' => $request->kas_id],
            ['catatan' => $request->catatan]
        );

        return back()->with('success', 'Data Upah berhasil disimpan!');
    }
}