<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\OperasionalDetail;
use Illuminate\Http\Request;

class OperasionalController extends Controller
{
    public function index(Request $request)
    {
        // Ambil daftar bulan untuk filter dropdown
        $listBulan = Kas::where('akun', 'Operasional')
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")
            ->groupBy('bulan')
            ->orderBy('bulan', 'desc')
            ->get();

        $query = Kas::with('operasionalDetail')->where('akun', 'Operasional');

        // Logika Filter
        $bulanTerpilih = $request->get('bulan');
        if ($bulanTerpilih) {
            $query->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanTerpilih]);
        }

        $data = $query->orderBy('tanggal', 'asc')->get();

        // Hitung total pengeluaran
        $totalHarga = $data->sum('jumlah');

        return view('neraca.operasional.index', compact('data', 'listBulan', 'bulanTerpilih', 'totalHarga'));
    }

    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'kas_id' => 'required|exists:kas,id',
        ]);

        OperasionalDetail::updateOrCreate(
            ['kas_id' => $request->kas_id],
            ['catatan' => $request->catatan]
        );

        return back()->with('success', 'Data Operasional berhasil disimpan!');
    }
}