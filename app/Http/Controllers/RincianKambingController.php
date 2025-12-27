<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RincianKambing;
use App\Models\KambingMati;

class RincianKambingController extends Controller
{
    public function index()
    {
        $hpp = RincianKambing::all();
        $mati = KambingMati::all();

        $totalHpp = $hpp->sum('total_hpp');
        $totalMati = $mati->sum('harga');

        return view('neraca.rincian-kambing.index', compact(
            'hpp',
            'mati',
            'totalHpp',
            'totalMati'
        ));
    }

    public function storeHpp(Request $request)
    {
        $jumlah = $request->qty * $request->harga_satuan;
        $total = $jumlah + $request->ongkir;

        RincianKambing::create([
            'jenis' => $request->jenis,
            'qty' => $request->qty,
            'harga_satuan' => $request->harga_satuan,
            'jumlah' => $jumlah,
            'ongkir' => $request->ongkir,
            'total_hpp' => $total,
        ]);

        return back();
    }

    public function storeMati(Request $request)
    {
        KambingMati::create($request->all());
        return back();
    }
}
