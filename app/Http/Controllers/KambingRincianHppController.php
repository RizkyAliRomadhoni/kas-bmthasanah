<?php

namespace App\Http\Controllers;

use App\Models\KambingRincianHpp;
use App\Models\KambingRincianHppDetail;
use App\Models\KambingRincianPeriode;
use App\Models\KambingManualSummary;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KambingRincianHppController extends Controller
{
    public function index()
    {
        // 1. Ambil daftar bulan (Start Sep 2025 jika kosong)
        $bulanList = KambingRincianPeriode::orderBy('bulan', 'asc')->pluck('bulan')->toArray();
        if (empty($bulanList)) {
            $start = Carbon::create(2025, 9, 1);
            for ($i = 0; $i < 4; $i++) {
                KambingRincianPeriode::create(['bulan' => $start->copy()->addMonths($i)->format('Y-m')]);
            }
            $bulanList = KambingRincianPeriode::orderBy('bulan', 'asc')->pluck('bulan')->toArray();
        }

        // 2. Data Utama (Baris)
        $stok = KambingRincianHpp::with('rincian_bulanan')->orderBy('tanggal', 'desc')->get();
        
        // 3. Data Sidebar Manual
        $summaryStock = KambingManualSummary::where('tipe', 'stock')->orderBy('label', 'asc')->get();
        $summaryKlaster = KambingManualSummary::where('tipe', 'klaster')->orderBy('label', 'asc')->get();

        return view('neraca.kambing-rincian-hpp.index', compact('stok', 'bulanList', 'summaryStock', 'summaryKlaster'));
    }

    public function store(Request $request)
    {
        $induk = KambingRincianHpp::create([
            'tanggal' => $request->tanggal,
            'keterangan' => strtoupper($request->keterangan),
            'jenis' => strtoupper($request->jenis),
            'klaster' => strtoupper($request->klaster),
            'tag' => strtoupper($request->tag),
            'harga_awal' => $request->harga_awal,
            'qty_awal' => $request->qty_awal,
        ]);

        // Pastikan bulan input terdaftar di periode
        $bulanInput = Carbon::parse($request->tanggal)->format('Y-m');
        KambingRincianPeriode::firstOrCreate(['bulan' => $bulanInput]);

        KambingRincianHppDetail::create([
            'kambing_rincian_hpp_id' => $induk->id,
            'bulan' => $bulanInput,
            'harga_update' => $request->harga_awal,
            'qty_update' => $request->qty_awal,
        ]);

        return back()->with('success', 'Baris baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $item = KambingRincianHpp::findOrFail($id);
        $item->update($request->all());
        return back()->with('success', 'Data induk diperbarui.');
    }

    public function destroy($id)
    {
        KambingRincianHpp::findOrFail($id)->delete();
        return back()->with('success', 'Baris data dihapus.');
    }

    public function updateCell(Request $request)
    {
        KambingRincianHppDetail::updateOrCreate(
            ['kambing_rincian_hpp_id' => $request->id, 'bulan' => $request->bulan],
            [$request->kolom => $request->nilai]
        );
        return response()->json(['status' => 'Success']);
    }

    public function addSummaryLabel(Request $request)
    {
        KambingManualSummary::create([
            'tipe' => $request->tipe,
            'label' => strtoupper($request->label),
            'nilai' => '0'
        ]);
        return back()->with('success', 'Label baru ditambahkan.');
    }

    public function deleteSummaryLabel($id)
    {
        KambingManualSummary::findOrFail($id)->delete();
        return back()->with('success', 'Label dihapus.');
    }

    public function updateSummary(Request $request)
    {
        KambingManualSummary::updateOrCreate(
            ['tipe' => $request->tipe, 'label' => $request->label],
            ['nilai' => $request->nilai]
        );
        return response()->json(['status' => 'Success']);
    }

    public function tambahBulan()
    {
        $terakhir = KambingRincianPeriode::orderBy('bulan', 'desc')->first();
        if($terakhir) {
            $bulanBaru = Carbon::parse($terakhir->bulan . "-01")->addMonth()->format('Y-m');
            KambingRincianPeriode::create(['bulan' => $bulanBaru]);
        }
        return back();
    }
}