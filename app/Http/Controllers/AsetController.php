<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aset;

class AsetController extends Controller
{
    public function index()
    {
        // Ambil semua data aset
        $asets = Aset::all();
        return view('aset.index', compact('asets'));
    }

    public function create()
    {
        return view('aset.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_aset' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'nilai' => 'nullable|numeric',
            'keterangan' => 'nullable|string',
        ]);

        Aset::create($request->all());

        return redirect()->route('aset.index')->with('success', 'Data aset berhasil ditambahkan!');
    }
}
