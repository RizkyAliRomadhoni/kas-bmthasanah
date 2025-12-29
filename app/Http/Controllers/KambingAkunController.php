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
        // 1. Ambil daftar bulan untuk filter
        $listBulan = Kas::where('akun', 'Kambing')
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")
            ->groupBy('bulan')->orderBy('bulan', 'desc')->get();

        $bulanTerpilih = $request->get('bulan', Carbon::now()->format('Y-m'));

        // 2. Query utama data Kas
        $query = Kas::with('kambingDetails')->where('akun', 'Kambing');
        if ($bulanTerpilih) {
            $query->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanTerpilih]);
        }
        $data = $query->orderBy('tanggal', 'desc')->get();

        // 3. Rekap Statistik (Untuk Stats Cards)
        $totalPengeluaranBulanIni = $data->sum('jumlah');
        $totalEkorBulanIni = KambingDetail::whereHas('kas', function($q) use ($bulanTerpilih) {
            $q->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanTerpilih]);
        })->count();

        // 4. Rekap per Jenis (Global/Selamanya untuk stok kandang)
        $rekapStok = KambingDetail::selectRaw('jenis, COUNT(*) as total')
            ->groupBy('jenis')->orderBy('total', 'desc')->get();

        return view('neraca.kambing.index', compact(
            'data', 
            'listBulan', 
            'bulanTerpilih', 
            'totalPengeluaranBulanIni', 
            'totalEkorBulanIni', 
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