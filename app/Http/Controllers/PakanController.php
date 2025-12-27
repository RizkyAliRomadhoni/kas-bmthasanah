<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\PakanDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PakanController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil semua daftar bulan yang tersedia di tabel kas untuk dropdown filter
        $listBulan = Kas::where('akun', 'Pakan')
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")
            ->groupBy('bulan')
            ->orderBy('bulan', 'desc')
            ->get();

        // 2. Query utama dengan Eager Loading
        $query = Kas::with('pakanDetail')->where('akun', 'Pakan');

        // 3. Logika Filter per Bulan
        $bulanTerpilih = $request->get('bulan');
        if ($bulanTerpilih) {
            $query->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanTerpilih]);
        }

        $data = $query->orderBy('tanggal', 'desc')->get();

        // 4. Hitung Total untuk ditampilkan di footer tabel
        $totalHargaKas = $data->sum('jumlah');
        $totalQty = $data->sum(function($item) {
            return $item->pakanDetail->qty_kg ?? 0;
        });
        
        // Menghitung potensi total nilai jual (Qty * Harga Jual/Kg)
        $totalPotensiJual = $data->sum(function($item) {
            return ($item->pakanDetail->qty_kg ?? 0) * ($item->pakanDetail->harga_jual_kg ?? 0);
        });

        return view('akun.pakan.index', compact(
            'data', 
            'listBulan', 
            'bulanTerpilih', 
            'totalHargaKas', 
            'totalQty',
            'totalPotensiJual'
        ));
    }

    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'kas_id' => 'required|exists:kas,id',
            'qty_kg' => 'required|numeric',
            'harga_kg' => 'required|numeric',
            'harga_jual_kg' => 'required|numeric',
        ]);

        PakanDetail::updateOrCreate(
            ['kas_id' => $request->kas_id],
            [
                'qty_kg' => $request->qty_kg,
                'harga_kg' => $request->harga_kg,
                'harga_jual_kg' => $request->harga_jual_kg,
            ]
        );

        return back()->with('success', 'Data Berhasil Diperbarui');
    }
}