<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\KambingDetail;
use Illuminate\Http\Request;

class KambingAkunController extends Controller
{
    public function index(Request $request)
    {
        $listBulan = Kas::where('akun', 'Kambing')
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")
            ->groupBy('bulan')->orderBy('bulan', 'desc')->get();

        $query = Kas::with('kambingDetails')->where('akun', 'Kambing');

        $bulanTerpilih = $request->get('bulan');
        if ($bulanTerpilih) {
            $query->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanTerpilih]);
        }

        $data = $query->orderBy('tanggal', 'desc')->get();

        // Rekap Stok dari seluruh anak kambing
        $rekapStok = KambingDetail::selectRaw('jenis, SUM(CASE WHEN status = "Kandang" THEN 1 ELSE 0 END) as total')
            ->groupBy('jenis')->pluck('total', 'jenis');

        return view('neraca.kambing.index', compact('data', 'listBulan', 'bulanTerpilih', 'rekapStok'));
    }

    // Fungsi untuk menambah "Anak" kambing ke satu transaksi Kas
    public function storeDetail(Request $request)
    {
        KambingDetail::create([
            'kas_id' => $request->kas_id,
            'jenis' => $request->jenis,
            'harga_beli' => $request->harga_beli,
            'berat_badan' => $request->berat_badan,
            'status' => 'Kandang'
        ]);

        return back()->with('success', 'Kambing berhasil ditambahkan ke transaksi ini');
    }

    // Fungsi hapus satu ekor
    public function destroyDetail($id)
    {
        KambingDetail::destroy($id);
        return back()->with('success', 'Data kambing dihapus');
    }
}