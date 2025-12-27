<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\KambingDetail;
use Illuminate\Http\Request;

class KambingAkunController extends Controller
{
    public function index(Request $request)
    {
        $listBulan = Kas::where('akun', 'Kambing')
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")
            ->groupBy('bulan')->orderBy('bulan', 'desc')->get();

        $query = Kas::with('kambingDetail')->where('akun', 'Kambing');

        $bulanTerpilih = $request->get('bulan');
        if ($bulanTerpilih) {
            $query->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanTerpilih]);
        }

        $data = $query->orderBy('tanggal', 'asc')->get();

        // Rekap Stok per Jenis (Sesuai Box Kanan di Excel)
        $rekapStok = [];
        foreach ($data as $item) {
            $jenis = $item->kambingDetail->jenis ?? 'Lainnya';
            $qty = $item->kambingDetail->qty ?? 0;
            $rekapStok[$jenis] = ($rekapStok[$jenis] ?? 0) + $qty;
        }

        return view('neraca.kambing.index', compact('data', 'listBulan', 'bulanTerpilih', 'rekapStok'));
    }

    public function storeOrUpdate(Request $request)
    {
        KambingDetail::updateOrCreate(
            ['kas_id' => $request->kas_id],
            [
                'jenis' => $request->jenis,
                'qty' => $request->qty,
                'berat_badan' => $request->berat_badan,
                'status' => $request->status,
            ]
        );

        return back()->with('success', 'Data Kambing Berhasil Diperbarui');
    }
}