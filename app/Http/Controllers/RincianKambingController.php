<?php

namespace App\Http\Controllers;

use App\Models\HppKambing;
use App\Models\KambingMati;
use Illuminate\Http\Request;

class RincianKambingController extends Controller
{
    public function index()
    {
        $dataHpp = HppKambing::orderBy('created_at', 'desc')->get();
        $dataMati = KambingMati::orderBy('tanggal', 'desc')->get();

        return view('neraca.rincian-kambing.index', compact('dataHpp', 'dataMati'));
    }

    public function storeHpp(Request $request)
    {
        $jumlah = $request->qty * $request->harga_satuan;
        $total_hpp = $jumlah + $request->ongkir;

        HppKambing::create([
            'jenis' => $request->jenis,
            'qty' => $request->qty,
            'harga_satuan' => $request->harga_satuan,
            'jumlah' => $jumlah,
            'ongkir' => $request->ongkir,
            'total_hpp' => $total_hpp,
        ]);

        return back()->with('success', 'Data HPP berhasil ditambahkan');
    }

    public function storeMati(Request $request)
    {
        KambingMati::create($request->all());
        return back()->with('success', 'Data kematian berhasil dicatat');
    }

    public function destroyHpp($id) { 
        HppKambing::destroy($id); 
        return back(); 
    }

    public function destroyMati($id) { 
        KambingMati::destroy($id); 
        return back(); 
    }
}