<?php

namespace App\Http\Controllers;

use App\Models\KambingRincianHpp;
use App\Models\KambingRincianHppDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KambingRincianHppController extends Controller
{
    public function index()
    {
        // 1. Tentukan list bulan yang ingin ditampilkan di kolom (seperti Excel Anda)
        $bulanList = ['2025-09', '2025-10', '2025-11', '2025-12'];

        // 2. Ambil data induk
        $stok = KambingRincianHpp::with('rincian_bulanan')->get();

        // 3. Ringkasan Kanan (Stock Kandang)
        $summaryJenis = KambingRincianHpp::selectRaw('jenis, SUM(qty_awal) as total')
                        ->groupBy('jenis')->get();

        // 4. Ringkasan Kanan (Klaster Bangsalan)
        $summaryKlaster = KambingRincianHpp::selectRaw('klaster, SUM(qty_awal) as total')
                        ->groupBy('klaster')->get();

        return view('kambing-rincian-hpp.index', compact('stok', 'bulanList', 'summaryJenis', 'summaryKlaster'));
    }

    public function store(Request $request)
    {
        $induk = KambingRincianHpp::create($request->all());

        // Otomatis isi kolom bulan pertama agar tidak kosong
        KambingRincianHppDetail::create([
            'kambing_rincian_hpp_id' => $induk->id,
            'bulan' => '2025-09',
            'harga_update' => $request->harga_awal,
            'qty_update' => $request->qty_awal,
        ]);

        return back()->with('success', 'Data berhasil ditambahkan');
    }

    public function simpanCell(Request $request)
    {
        // Logika Simpan Otomatis (AJAX) saat angka di Excel-web diubah
        KambingRincianHppDetail::updateOrCreate(
            ['kambing_rincian_hpp_id' => $request->id, 'bulan' => $request->bulan],
            [$request->kolom => $request->nilai]
        );

        return response()->json(['message' => 'Tersimpan']);
    }
}