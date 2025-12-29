<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\KambingDetail;
use Illuminate\Http\Request;

class KambingAkunController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data Kas akun 'Kambing' beserta rinciannya (Eager Loading)
        $query = Kas::with('kambingDetails')->where('akun', 'Kambing');

        // Filter bulan jika ada
        if ($request->bulan) {
            $query->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$request->bulan]);
        }

        $data = $query->orderBy('tanggal', 'desc')->get();

        // Rekap stok per jenis untuk box informasi
        $rekapStok = KambingDetail::selectRaw('jenis, COUNT(*) as total')
                                    ->groupBy('jenis')->pluck('total', 'jenis');

        return view('neraca.kambing.index', compact('data', 'rekapStok'));
    }

    public function storeDetail(Request $request)
    {
        $request->validate([
            'kas_id' => 'required',
            'jenis' => 'required',
            'harga_satuan' => 'required|numeric'
        ]);

        KambingDetail::create($request->all());

        return back()->with('success', 'Rincian kambing berhasil ditambahkan');
    }

    public function destroyDetail($id)
    {
        KambingDetail::destroy($id);
        return back()->with('success', 'Rincian berhasil dihapus');
    }
}