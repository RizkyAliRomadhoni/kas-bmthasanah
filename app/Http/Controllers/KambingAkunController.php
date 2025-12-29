<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\KambingDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KambingAkunController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil semua daftar bulan untuk dropdown filter
        $listBulan = Kas::where('akun', 'Kambing')
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")
            ->groupBy('bulan')->orderBy('bulan', 'desc')->get();

        // 2. Tangkap filter bulan (Jika tidak ada, biarkan null untuk menampilkan semua)
        $bulanTerpilih = $request->get('bulan');

        // 3. Query Utama
        $query = Kas::with('kambingDetails')->where('akun', 'Kambing');
        
        // Logika: Jika bulanTerpilih ada isinya, filter. Jika kosong, tampilkan semua.
        if ($bulanTerpilih) {
            $query->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanTerpilih]);
        }
        
        $data = $query->orderBy('tanggal', 'desc')->get();

        // 4. Rekap Statistik Dinamis
        $totalPengeluaran = $data->sum('jumlah');
        
        // Menghitung jumlah ekor berdasarkan data yang tampil di tabel
        $totalEkorTerdata = 0;
        foreach($data as $k) {
            $totalEkorTerdata += $k->kambingDetails->count();
        }

        // 5. Rekap per Jenis (Stok Kandang Aktif)
        $rekapStok = KambingDetail::selectRaw('jenis, COUNT(*) as total')
            ->groupBy('jenis')->orderBy('total', 'desc')->get();

        return view('neraca.kambing.index', compact(
            'data', 
            'listBulan', 
            'bulanTerpilih', 
            'totalPengeluaran', 
            'totalEkorTerdata', 
            'rekapStok'
        ));
    }

    public function storeDetail(Request $request)
    {
        $request->validate([
            'kas_id' => 'required',
            'jenis' => 'required',
            'harga_satuan' => 'required|numeric'
        ]);

        KambingDetail::create($request->all());
        return back()->with('success', 'Rincian kambing ditambahkan.');
    }

    public function destroyDetail($id)
    {
        KambingDetail::destroy($id);
        return back()->with('success', 'Rincian berhasil dihapus.');
    }
}