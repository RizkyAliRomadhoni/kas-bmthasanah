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

        // 2. Data Utama - URUT BERDASARKAN ID
        $stok = KambingRincianHpp::with('rincian_bulanan')->orderBy('id', 'asc')->get();
        
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

        $bulanInput = Carbon::parse($request->tanggal)->format('Y-m');
        $periodes = KambingRincianPeriode::where('bulan', '>=', $bulanInput)->get();

        if($periodes->where('bulan', $bulanInput)->count() == 0){
             KambingRincianPeriode::create(['bulan' => $bulanInput]);
             $periodes = KambingRincianPeriode::where('bulan', '>=', $bulanInput)->get();
        }

        foreach ($periodes as $p) {
            KambingRincianHppDetail::create([
                'kambing_rincian_hpp_id' => $induk->id,
                'bulan' => $p->bulan,
                'harga_update' => $request->harga_awal,
                'qty_update' => $request->qty_awal,
            ]);
        }

        return back()->with('success', 'Baris baru berhasil ditambahkan.');
    }

    public function splitRow($id)
    {
        $induk = KambingRincianHpp::findOrFail($id);
        $qty = (int)$induk->qty_awal;

        if ($qty <= 1) return back()->with('error', 'Hanya Qty diatas 1 yang bisa di-split.');

        $hargaSatuan = $induk->harga_awal / $qty;

        DB::transaction(function () use ($induk, $qty, $hargaSatuan) {
            $periodes = KambingRincianPeriode::all();

            for ($i = 1; $i <= $qty; $i++) {
                $baru = KambingRincianHpp::create([
                    'tanggal' => $induk->tanggal,
                    'keterangan' => $induk->keterangan . " (" . $i . ")",
                    'jenis' => $induk->jenis,
                    'klaster' => $induk->klaster,
                    'tag' => $induk->tag,
                    'harga_awal' => $hargaSatuan,
                    'qty_awal' => 1,
                ]);

                foreach ($periodes as $p) {
                    $detailLama = KambingRincianHppDetail::where('kambing_rincian_hpp_id', $induk->id)->where('bulan', $p->bulan)->first();
                    KambingRincianHppDetail::create([
                        'kambing_rincian_hpp_id' => $baru->id,
                        'bulan' => $p->bulan,
                        'harga_update' => $detailLama ? ($detailLama->harga_update / $qty) : $hargaSatuan,
                        'qty_update' => 1,
                    ]);
                }
            }
            $induk->delete();
        });

        return back()->with('success', 'Berhasil memecah transaksi menjadi satuan.');
    }

    public function update(Request $request, $id)
    {
        KambingRincianHpp::findOrFail($id)->update($request->all());
        return back()->with('success', 'Data diperbarui.');
    }

    public function destroy($id)
    {
        KambingRincianHpp::findOrFail($id)->delete();
        return back()->with('success', 'Data dihapus.');
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

    public function addSummaryLabel(Request $request)
    {
        KambingManualSummary::create(['tipe' => $request->tipe, 'label' => strtoupper($request->label), 'nilai' => '0']);
        return back()->with('success', 'Kategori ditambahkan.');
    }

    public function deleteSummaryLabel($id)
    {
        KambingManualSummary::findOrFail($id)->delete();
        return back();
    }

    public function tambahBulan()
    {
        $terakhir = KambingRincianPeriode::orderBy('bulan', 'desc')->first();
        $bulanLama = $terakhir->bulan;
        $bulanBaru = Carbon::parse($bulanLama . "-01")->addMonth()->format('Y-m');

        KambingRincianPeriode::create(['bulan' => $bulanBaru]);

        foreach (KambingRincianHpp::all() as $kambing) {
            $prev = KambingRincianHppDetail::where('kambing_rincian_hpp_id', $kambing->id)->where('bulan', $bulanLama)->first();
            KambingRincianHppDetail::create([
                'kambing_rincian_hpp_id' => $kambing->id,
                'bulan' => $bulanBaru,
                'harga_update' => $prev ? $prev->harga_update : $kambing->harga_awal,
                'qty_update' => $prev ? $prev->qty_update : $kambing->qty_awal,
            ]);
        }
        return back();
    }
}