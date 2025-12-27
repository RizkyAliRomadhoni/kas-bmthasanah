<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\PerlengkapanDetail;
use Illuminate\Http\Request;

class PerlengkapanController extends Controller
{
    public function index(Request $request)
    {
        // Dropdown list bulan
        $listBulan = Kas::where('akun', 'Perlengkapan')
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")
            ->groupBy('bulan')
            ->orderBy('bulan', 'desc')
            ->get();

        $query = Kas::with('perlengkapanDetail')->where('akun', 'Perlengkapan');

        $bulanTerpilih = $request->get('bulan');
        if ($bulanTerpilih) {
            $query->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanTerpilih]);
        }

        $data = $query->orderBy('tanggal', 'asc')->get();

        // Total Harga
        $totalHarga = $data->sum('jumlah');

        return view('neraca.perlengkapan.index', compact('data', 'listBulan', 'bulanTerpilih', 'totalHarga'));
    }

    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'kas_id' => 'required|exists:kas,id',
            'qty' => 'required|numeric',
        ]);

        PerlengkapanDetail::updateOrCreate(
            ['kas_id' => $request->kas_id],
            ['qty' => $request->qty]
        );

        return back()->with('success', 'Data Perlengkapan Berhasil Disimpan');
    }
}