<?php
namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\PakanDetail;
use Illuminate\Http\Request;

class PakanController extends Controller
{
    public function index()
    {
        // Ambil transaksi Kas yang akunnya adalah 'Pakan'
        $data = Kas::with('pakanDetail')
                    ->where('akun', 'Pakan')
                    ->orderBy('tanggal', 'desc')
                    ->get();

        return view('pakan.index', compact('data'));
    }

    public function storeOrUpdate(Request $request)
    {
        // Simpan atau update detail pakan
        PakanDetail::updateOrCreate(
            ['kas_id' => $request->kas_id],
            [
                'qty_kg' => $request->qty_kg,
                'harga_kg' => $request->harga_kg,
                'harga_jual_kg' => $request->harga_jual_kg,
            ]
        );

        return back()->with('success', 'Data berhasil diperbarui');
    }
}