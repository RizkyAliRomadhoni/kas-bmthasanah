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
        $bulanList = KambingRincianPeriode::orderBy('bulan', 'asc')->pluck('bulan')->toArray();

        if (empty($bulanList)) {
            $start = Carbon::create(2025, 9, 1);
            for ($i = 0; $i < 4; $i++) {
                $month = $start->copy()->addMonths($i)->format('Y-m');
                KambingRincianPeriode::create(['bulan' => $month]);
            }
            $bulanList = KambingRincianPeriode::orderBy('bulan', 'asc')->pluck('bulan')->toArray();
        }

        $stok = KambingRincianHpp::with('rincian_bulanan')->orderBy('tanggal', 'desc')->get();
        
        // Ambil data sidebar dari database
        $summaryStock = KambingManualSummary::where('tipe', 'stock')->get();
        $summaryKlaster = KambingManualSummary::where('tipe', 'klaster')->get();

        return view('neracakambing-rincian-hpp.index', compact('stok', 'bulanList', 'summaryStock', 'summaryKlaster'));
    }

    // FUNGSI BARU: TAMBAH JENIS / KLASTER KE SIDEBAR
    public function addSummaryLabel(Request $request)
    {
        $request->validate([
            'tipe' => 'required',
            'label' => 'required'
        ]);

        KambingManualSummary::create([
            'tipe' => $request->tipe,
            'label' => strtoupper($request->label),
            'nilai' => '0'
        ]);

        return back()->with('success', 'Label baru berhasil ditambahkan ke sidebar.');
    }

    // FUNGSI BARU: HAPUS LABEL SIDEBAR
    public function deleteSummaryLabel($id)
    {
        KambingManualSummary::findOrFail($id)->delete();
        return back()->with('success', 'Label berhasil dihapus.');
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

        $bulanInput = Carbon::parse($request->tanggal)->format('Y-m');
        KambingRincianPeriode::firstOrCreate(['bulan' => $bulanInput]);

        KambingRincianHppDetail::create([
            'kambing_rincian_hpp_id' => $induk->id,
            'bulan' => $bulanInput,
            'harga_update' => $request->harga_awal,
            'qty_update' => $request->qty_awal,
        ]);

        return back()->with('success', 'Data baris berhasil ditambahkan.');
    }

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
        return back()->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        KambingRincianHpp::findOrFail($id)->delete();
        return back()->with('success', 'Data berhasil dihapus.');
    }

    public function updateCell(Request $request)
    {
        KambingRincianHppDetail::updateOrCreate(
            ['kambing_rincian_hpp_id' => $request->id, 'bulan' => $request->bulan],
            [$request->kolom => $request->nilai]
        );
        return response()->json(['status' => 'Success']);
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
        $bulanBaru = Carbon::parse($terakhir->bulan . "-01")->addMonth()->format('Y-m');
        KambingRincianPeriode::create(['bulan' => $bulanBaru]);
        return back();
    }
}