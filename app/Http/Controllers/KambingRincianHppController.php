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
        // 1. Ambil daftar bulan dari database
        $bulanList = KambingRincianPeriode::orderBy('bulan', 'asc')->pluck('bulan')->toArray();

        // JIKA KOSONG: Otomatis buatkan dari Juli 2025 sampai Desember 2025 (Seperti di Excel)
        if (empty($bulanList)) {
            $start = Carbon::create(2025, 7, 1);
            // Kita buatkan 6 bulan awal (Juli - Desember)
            for ($i = 0; $i < 6; $i++) {
                $month = $start->copy()->addMonths($i)->format('Y-m');
                KambingRincianPeriode::create(['bulan' => $month]);
            }
            $bulanList = KambingRincianPeriode::orderBy('bulan', 'asc')->pluck('bulan')->toArray();
        }

        // 2. Ambil data stok
        $stok = KambingRincianHpp::with('rincian_bulanan')->orderBy('tanggal', 'asc')->get();

        // 3. Summary Kanan
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
        return back()->with('success', 'Kolom bulan ' . $bulanBaru . ' ditambahkan!');
    }

    public function store(Request $request)
    {
        $induk = KambingRincianHpp::create([
            'tanggal' => $request->tanggal,
            'keterangan' => strtoupper($request->keterangan),
            'jenis' => strtoupper($request->jenis),
            'klaster' => strtoupper($request->klaster),
            'harga_awal' => $request->harga_awal,
            'qty_awal' => $request->qty_awal,
        ]);

        $bulanInput = Carbon::parse($request->tanggal)->format('Y-m');
        
        // Pastikan bulan dari tanggal yang diinput tersedia di kolom
        KambingRincianPeriode::firstOrCreate(['bulan' => $bulanInput]);

        // Buat detail awal
        KambingRincianHppDetail::create([
            'kambing_rincian_hpp_id' => $induk->id,
            'bulan' => $bulanInput,
            'harga_update' => $request->harga_awal,
            'qty_update' => $request->qty_awal,
        ]);

        return back()->with('success', 'Data berhasil ditambahkan!');
    }

    public function updateCell(Request $request)
    {
        KambingRincianHppDetail::updateOrCreate(
            ['kambing_rincian_hpp_id' => $request->id, 'bulan' => $request->bulan],
            [$request->kolom => $request->nilai]
        );
        return response()->json(['status' => 'Saved']);
    }
}