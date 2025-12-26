<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use Carbon\Carbon;

class PenjualanController extends Controller
{
    public function index()
    {
        $data = Penjualan::orderBy('tanggal')->get();

        $bulanList = $data->pluck('tanggal')
            ->map(fn($d) => Carbon::parse($d)->format('Y-m'))
            ->unique()
            ->values();

        $grouped = [];
        $totalPerBulan = [];
        $grandTotal = 0;

        foreach ($bulanList as $bulan) {
            $grouped[$bulan] = $data->filter(function ($item) use ($bulan) {
                return Carbon::parse($item->tanggal)->format('Y-m') === $bulan;
            });

            $totalPerBulan[$bulan] = $grouped[$bulan]->sum('laba');
            $grandTotal += $totalPerBulan[$bulan];
        }

        return view('neraca.penjualan.index', compact(
            'data',
            'bulanList',
            'grouped',
            'totalPerBulan',
            'grandTotal'
        ));
    }

    public function store(Request $request)
    {
        $laba = $request->harga_jual - $request->hpp;

        Penjualan::create([
            'tanggal'     => $request->tanggal,
            'keterangan'  => $request->keterangan,
            'tag'         => $request->tag,
            'harga_jual'  => $request->harga_jual,
            'hpp'         => $request->hpp,
            'laba'        => $laba,
        ]);

        return back();
    }
}
