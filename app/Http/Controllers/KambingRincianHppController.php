<?php

namespace App\Http\Controllers;

use App\Models\KambingRincianHpp;
use App\Models\KambingRincianHppDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KambingRincianHppController extends Controller
{
    /**
     * Tampilan Utama Laporan (Excel Style)
     */
    public function index()
    {
        // 1. Tentukan rentang bulan yang muncul di kolom (Contoh: Sep 2025 s/d Des 2025)
        // Anda bisa membuat ini dinamis jika ingin
        $bulanList = ['2025-09', '2025-10', '2025-11', '2025-12'];

        // 2. Ambil data stok urut tanggal terbaru di atas
        $stok = KambingRincianHpp::with('rincian_bulanan')
                ->orderBy('tanggal', 'desc')
                ->get();

        // 3. Ringkasan Stock Kandang (Berdasarkan Jenis)
        $summaryJenis = KambingRincianHpp::selectRaw('jenis, SUM(qty_awal) as total')
                        ->groupBy('jenis')
                        ->get();

        // 4. Ringkasan Klaster Bangsalan
        $summaryKlaster = KambingRincianHpp::selectRaw('klaster, SUM(qty_awal) as total')
                        ->groupBy('klaster')
                        ->get();

        return view('kambing-rincian-hpp.index', compact('stok', 'bulanList', 'summaryJenis', 'summaryKlaster'));
    }

    /**
     * Simpan Baris Supplier Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'jenis' => 'required|string',
            'klaster' => 'required|string',
            'harga_awal' => 'required|numeric',
            'qty_awal' => 'required|integer',
        ]);

        // Simpan Data Induk
        $induk = KambingRincianHpp::create([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'jenis' => strtoupper($request->jenis),
            'klaster' => strtoupper($request->klaster),
            'harga_awal' => $request->harga_awal,
            'qty_awal' => $request->qty_awal,
        ]);

        // Otomatis buat histori di bulan yang sesuai dengan tanggal input
        $bulanInput = Carbon::parse($request->tanggal)->format('Y-m');

        KambingRincianHppDetail::create([
            'kambing_rincian_hpp_id' => $induk->id,
            'bulan' => $bulanInput,
            'harga_update' => $request->harga_awal,
            'qty_update' => $request->qty_awal,
        ]);

        return back()->with('success', 'Data kambing berhasil ditambahkan ke rincian.');
    }

    /**
     * Simpan Update per Sel (AJAX Fetch)
     */
    public function simpanCell(Request $request)
    {
        // Mencari atau membuat data histori bulanan baru
        $detail = KambingRincianHppDetail::updateOrCreate(
            [
                'kambing_rincian_hpp_id' => $request->id, 
                'bulan' => $request->bulan
            ],
            [
                $request->kolom => $request->nilai
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Data otomatis tersimpan pada ' . Carbon::now()->format('H:i:s')
        ]);
    }
}