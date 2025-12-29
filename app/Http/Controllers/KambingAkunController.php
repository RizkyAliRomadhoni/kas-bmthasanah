<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\KambingDetail;
use Illuminate\Http\Request;

class KambingAkunController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data Kas akun 'Kambing' beserta rinciannya
        $query = Kas::with('kambingDetails')->where('akun', 'Kambing');

        if ($request->bulan) {
            $query->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$request->bulan]);
        }

        $data = $query->orderBy('tanggal', 'desc')->get();

        // Rekap stok untuk box informasi kanan
        $rekapStok = KambingDetail::selectRaw('jenis, COUNT(*) as total')
                                    ->groupBy('jenis')->pluck('total', 'jenis');

        return view('neraca.kambing.index', compact('data', 'rekapStok'));
    }

    public function storeDetail(Request $request)
    {
        $request->validate([
            'kas_id' => 'required',
            'jenis' => 'required',
            'harga_satuan' => 'required|numeric',
            'berat_badan' => 'nullable|numeric'
        ]);

        // Simpan ke database
        KambingDetail::create([
            'kas_id' => $request->kas_id,
            'jenis' => $request->jenis,
            'harga_satuan' => $request->harga_satuan,
            'berat_badan' => $request->berat_badan ?? 0,
        ]);

        return back()->with('success', 'Rincian berhasil ditambahkan');
    }

    public function destroyDetail($id)
    {
        KambingDetail::destroy($id);
        return back()->with('success', 'Rincian dihapus');
    }
}