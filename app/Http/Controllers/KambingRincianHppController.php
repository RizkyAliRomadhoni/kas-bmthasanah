<?php

namespace App\Http\Controllers;

use App\Models\KambingRincianHpp;
use App\Models\KambingRincianHppDetail;
use App\Models\KambingRincianPeriode;
use App\Models\KambingManualSummary;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KambingRincianHppController extends Controller
{
    /**
     * Tampilan Utama
     */
    public function index()
    {
        // 1. Ambil daftar kolom bulan dari database periode
        $bulanList = KambingRincianPeriode::orderBy('bulan', 'asc')->pluck('bulan')->toArray();

        // JIKA KOSONG: Inisialisasi default September 2025 s/d Desember 2025
        if (empty($bulanList)) {
            $start = Carbon::create(2025, 9, 1);
            for ($i = 0; $i < 4; $i++) {
                KambingRincianPeriode::create(['bulan' => $start->copy()->addMonths($i)->format('Y-m')]);
            }
            $bulanList = KambingRincianPeriode::orderBy('bulan', 'asc')->pluck('bulan')->toArray();
        }

        // 2. Ambil data baris (Induk) urut tanggal terbaru
        $stok = KambingRincianHpp::with('rincian_bulanan')->orderBy('tanggal', 'desc')->get();
        
        // 3. Ambil data sidebar manual
        $summaryStock = KambingManualSummary::where('tipe', 'stock')->orderBy('label', 'asc')->get();
        $summaryKlaster = KambingManualSummary::where('tipe', 'klaster')->orderBy('label', 'asc')->get();

        return view('neraca.kambing-rincian-hpp.index', compact('stok', 'bulanList', 'summaryStock', 'summaryKlaster'));
    }

    /**
     * Tambah Label Jenis/Klaster Baru ke Sidebar
     */
    public function addSummaryLabel(Request $request)
    {
        $request->validate([
            'tipe' => 'required|in:stock,klaster',
            'label' => 'required|string|max:50'
        ]);

        KambingManualSummary::create([
            'tipe' => $request->tipe,
            'label' => strtoupper($request->label),
            'nilai' => '0'
        ]);

        return back()->with('success', 'Label baru berhasil ditambahkan.');
    }

    /**
     * Hapus Label Sidebar
     */
    public function deleteSummaryLabel($id)
    {
        KambingManualSummary::findOrFail($id)->delete();
        return back()->with('success', 'Label sidebar berhasil dihapus.');
    }

    /**
     * Simpan Baris Kambing Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required',
            'jenis' => 'required',
            'klaster' => 'required',
            'harga_awal' => 'required|numeric',
            'qty_awal' => 'required|integer',
        ]);

        $induk = KambingRincianHpp::create([
            'tanggal' => $request->tanggal,
            'keterangan' => strtoupper($request->keterangan),
            'jenis' => strtoupper($request->jenis),
            'klaster' => strtoupper($request->klaster),
            'tag' => strtoupper($request->tag),
            'harga_awal' => $request->harga_awal,
            'qty_awal' => $request->qty_awal,
        ]);

        $bulanInput = Carbon::parse($request->tanggal)->format('Y-m');
        
        // Pastikan bulan dari tanggal input terdaftar di kolom
        KambingRincianPeriode::firstOrCreate(['bulan' => $bulanInput]);

        KambingRincianHppDetail::create([
            'kambing_rincian_hpp_id' => $induk->id,
            'bulan' => $bulanInput,
            'harga_update' => $request->harga_awal,
            'qty_update' => $request->qty_awal,
        ]);

        return back()->with('success', 'Baris baru berhasil disimpan.');
    }

    /**
     * Update Data Induk (Edit Modal)
     */
    public function update(Request $request, $id)
    {
        $item = KambingRincianHpp::findOrFail($id);
        $item->update([
            'tanggal' => $request->tanggal,
            'keterangan' => strtoupper($request->keterangan),
            'jenis' => strtoupper($request->jenis),
            'tag' => strtoupper($request->tag),
            'klaster' => strtoupper($request->klaster),
            'harga_awal' => $request->harga_awal,
            'qty_awal' => $request->qty_awal,
        ]);
        return back()->with('success', 'Data induk diperbarui.');
    }

    /**
     * Hapus Baris Total
     */
    public function destroy($id)
    {
        KambingRincianHpp::findOrFail($id)->delete();
        return back()->with('success', 'Data baris telah dihapus.');
    }

    /**
     * Update Sel Tabel (AJAX Live Edit)
     */
    public function updateCell(Request $request)
    {
        KambingRincianHppDetail::updateOrCreate(
            ['kambing_rincian_hpp_id' => $request->id, 'bulan' => $request->bulan],
            [$request->kolom => $request->nilai]
        );
        return response()->json(['status' => 'Success', 'message' => 'Tersimpan Otomatis']);
    }

    /**
     * Update Nilai Sidebar Manual (AJAX Live Edit)
     */
    public function updateSummary(Request $request)
    {
        KambingManualSummary::updateOrCreate(
            ['tipe' => $request->tipe, 'label' => $request->label],
            ['nilai' => $request->nilai]
        );
        return response()->json(['status' => 'Success']);
    }

    /**
     * Tambah Kolom Bulan Baru ke Samping
     */
    public function tambahBulan()
    {
        $terakhir = KambingRincianPeriode::orderBy('bulan', 'desc')->first();
        $bulanBaru = Carbon::parse($terakhir->bulan . "-01")->addMonth()->format('Y-m');
        KambingRincianPeriode::create(['bulan' => $bulanBaru]);
        return back()->with('success', 'Kolom bulan ' . $bulanBaru . ' ditambahkan.');
    }
}