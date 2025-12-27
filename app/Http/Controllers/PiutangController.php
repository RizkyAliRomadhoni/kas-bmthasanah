<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\PiutangDetail;
use Illuminate\Http\Request;

class PiutangController extends Controller
{
    public function index(Request $request)
    {
        // List bulan untuk filter
        $listBulan = Kas::where('akun', 'Piutang')
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan")
            ->groupBy('bulan')
            ->orderBy('bulan', 'desc')
            ->get();

        $query = Kas::with('piutangDetail')->where('akun', 'Piutang');

        $bulanTerpilih = $request->get('bulan');
        if ($bulanTerpilih) {
            $query->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanTerpilih]);
        }

        // Ambil data urut asc untuk hitung saldo berjalan
        $dataRaw = $query->orderBy('tanggal', 'asc')->orderBy('id', 'asc')->get();

        // Hitung Saldo Berjalan Piutang (Kredit menambah, Debet mengurangi)
        $runningSaldo = 0;
        foreach ($dataRaw as $item) {
            if ($item->jenis_transaksi == 'Keluar') {
                $runningSaldo += $item->jumlah; // Piutang bertambah (Barang keluar tapi uang belum masuk)
            } else {
                $runningSaldo -= $item->jumlah; // Piutang berkurang (Uang masuk/pembayaran diterima)
            }
            $item->saldo_berjalan = $runningSaldo;
        }

        return view('neraca.piutang.index', compact('dataRaw', 'listBulan', 'bulanTerpilih', 'runningSaldo'));
    }

    public function storeOrUpdate(Request $request)
    {
        $request->validate(['kas_id' => 'required|exists:kas,id']);

        PiutangDetail::updateOrCreate(
            ['kas_id' => $request->kas_id],
            ['catatan' => $request->catatan]
        );

        return back()->with('success', 'Data Piutang berhasil disimpan!');
    }
}