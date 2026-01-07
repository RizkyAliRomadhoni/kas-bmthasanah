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

        // Inisialisasi awal jika kosong (Start September 2025)
        if (empty($bulanList)) {
            $start = Carbon::create(2025, 9, 1);
            for ($i = 0; $i < 4; $i++) {
                KambingRincianPeriode::create(['bulan' => $start->copy()->addMonths($i)->format('Y-m')]);
            }
            $bulanList = KambingRincianPeriode::orderBy('bulan', 'asc')->pluck('bulan')->toArray();
        }

        $stok = KambingRincianHpp::with('rincian_bulanan')->orderBy('id', 'asc')->get();
        $summaryStock = KambingManualSummary::where('tipe', 'stock')->orderBy('label', 'asc')->get();
        $summaryKlaster = KambingManualSummary::where('tipe', 'klaster')->orderBy('label', 'asc')->get();

        return view('neraca.kambing-rincian-hpp.index', compact('stok', 'bulanList', 'summaryStock', 'summaryKlaster'));
    }

    /**
     * TAMBAH BARIS BARU DENGAN AUTO-FILL KE SEMUA BULAN
     */
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

        // Ambil semua periode yang ada di database yang >= bulan input
        $periodes = KambingRincianPeriode::where('bulan', '>=', $bulanInput)->get();

        // JIKA bulan input belum ada di periode, buat dulu kolomnya
        if($periodes->where('bulan', $bulanInput)->count() == 0){
             KambingRincianPeriode::create(['bulan' => $bulanInput]);
             $periodes = KambingRincianPeriode::where('bulan', '>=', $bulanInput)->get();
        }

        // AUTO-FILL: Isi setiap kolom bulan yang tersedia untuk baris baru ini
        foreach ($periodes as $p) {
            KambingRincianHppDetail::create([
                'kambing_rincian_hpp_id' => $induk->id,
                'bulan' => $p->bulan,
                'harga_update' => $request->harga_awal,
                'qty_update' => $request->qty_awal,
            ]);
        }

        return back()->with('success', 'Baris baru berhasil ditambahkan dan otomatis mengisi kolom bulan.');
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

    public function tambahBulan()
    {
        $terakhir = KambingRincianPeriode::orderBy('bulan', 'desc')->first();
        $bulanLama = $terakhir->bulan;
        $bulanBaru = Carbon::parse($bulanLama . "-01")->addMonth()->format('Y-m');

        KambingRincianPeriode::create(['bulan' => $bulanBaru]);

        // Carry forward data dari bulan terakhir ke bulan baru untuk semua baris
        $semuaKambing = KambingRincianHpp::all();
        foreach ($semuaKambing as $kambing) {
            $detailLama = KambingRincianHppDetail::where('kambing_rincian_hpp_id', $kambing->id)
                ->where('bulan', $bulanLama)
                ->first();

            KambingRincianHppDetail::create([
                'kambing_rincian_hpp_id' => $kambing->id,
                'bulan' => $bulanBaru,
                'harga_update' => $detailLama ? $detailLama->harga_update : $kambing->harga_awal,
                'qty_update' => $detailLama ? $detailLama->qty_update : $kambing->qty_awal,
            ]);
        }

        return back()->with('success', 'Kolom bulan baru ditambahkan.');
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
        return back();
    }

    public function deleteSummaryLabel($id)
    {
        KambingManualSummary::findOrFail($id)->delete();
        return back();
    }

    public function updateSummary(Request $request)
    {
        KambingManualSummary::updateOrCreate(
            ['tipe' => $request->tipe, 'label' => $request->label],
            ['nilai' => $request->nilai]
        );
        return response()->json(['status' => 'Success']);
    }
}