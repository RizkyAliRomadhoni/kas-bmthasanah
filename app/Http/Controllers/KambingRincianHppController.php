<?php

namespace App\Http\Controllers;

use App\Models\KambingRincianHpp;
use App\Models\KambingRincianHppDetail;
use App\Models\KambingRincianPeriode;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KambingRincianHppController extends Controller
{
    public function index()
    {
        // 1. Ambil daftar kolom bulan dari database
        $bulanList = KambingRincianPeriode::orderBy('bulan', 'asc')->pluck('bulan')->toArray();

        // Jika database periode masih kosong, buat otomatis bulan ini sebagai awal
        if (empty($bulanList)) {
            $now = Carbon::now()->format('Y-m');
            KambingRincianPeriode::create(['bulan' => $now]);
            $bulanList = [$now];
        }

        // 2. Ambil data baris beserta rinciannya
        $stok = KambingRincianHpp::with('rincian_bulanan')->orderBy('tanggal', 'desc')->get();

        // 3. Hitung Ringkasan untuk Sidebar Kanan
        $summaryJenis = KambingRincianHpp::selectRaw('jenis, SUM(qty_awal) as total')->groupBy('jenis')->get();
        $summaryKlaster = KambingRincianHpp::selectRaw('klaster, SUM(qty_awal) as total')->groupBy('klaster')->get();

        return view('neraca.kambing-rincian-hpp.index', compact('stok', 'bulanList', 'summaryJenis', 'summaryKlaster'));
    }

    public function tambahBulan()
    {
        // Ambil bulan terakhir, tambah 1 bulan kedepan
        $terakhir = KambingRincianPeriode::orderBy('bulan', 'desc')->first();
        $bulanBaru = Carbon::parse($terakhir->bulan . "-01")->addMonth()->format('Y-m');
        
        KambingRincianPeriode::create(['bulan' => $bulanBaru]);
        return back()->with('success', 'Kolom bulan baru berhasil ditambahkan!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required',
            'jenis' => 'required',
            'klaster' => 'required',
            'harga_awal' => 'required|numeric',
            'qty_awal' => 'required|numeric',
        ]);

        // Simpan baris master
        $induk = KambingRincianHpp::create([
            'tanggal' => $request->tanggal,
            'keterangan' => strtoupper($request->keterangan),
            'jenis' => strtoupper($request->jenis),
            'klaster' => strtoupper($request->klaster),
            'harga_awal' => $request->harga_awal,
            'qty_awal' => $request->qty_awal,
        ]);

        // Pastikan bulan dari tanggal input terdaftar di tabel periode
        $bulanInput = Carbon::parse($request->tanggal)->format('Y-m');
        KambingRincianPeriode::firstOrCreate(['bulan' => $bulanInput]);

        // Buat detail awal di bulan tersebut
        KambingRincianHppDetail::create([
            'kambing_rincian_hpp_id' => $induk->id,
            'bulan' => $bulanInput,
            'harga_update' => $request->harga_awal,
            'qty_update' => $request->qty_awal,
        ]);

        return back()->with('success', 'Data baris baru berhasil disimpan.');
    }

    public function updateCell(Request $request)
    {
        // Fungsi Update Otomatis (AJAX)
        KambingRincianHppDetail::updateOrCreate(
            [
                'kambing_rincian_hpp_id' => $request->id, 
                'bulan' => $request->bulan
            ],
            [
                $request->kolom => $request->nilai
            ]
        );

        return response()->json(['status' => 'Success', 'message' => 'Tersimpan otomatis']);
    }
}