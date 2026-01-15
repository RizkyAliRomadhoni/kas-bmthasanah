<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kas;
use App\Models\KambingRincianHppDetail;
use App\Models\KambingMati;
use App\Models\Penjualan;
use App\Models\PakanDetail;
use App\Models\LabaRugiManual;
use App\Models\NeracaAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NeracaController extends Controller
{
    public function index()
    {
        // 1. Ambil List Bulan Unik dengan proteksi null
        $bulanKas = Kas::whereNotNull('tanggal')->selectRaw("DATE_FORMAT(tanggal,'%Y-%m') as bulan")->pluck('bulan');
        $bulanJual = Penjualan::whereNotNull('tanggal')->selectRaw("DATE_FORMAT(tanggal,'%Y-%m') as bulan")->pluck('bulan');
        $bulanMati = KambingMati::whereNotNull('tanggal')->selectRaw("DATE_FORMAT(tanggal,'%Y-%m') as bulan")->pluck('bulan');
        $bulanManual = LabaRugiManual::pluck('bulan'); 
        
        $bulanList = collect($bulanKas)
            ->merge($bulanJual)
            ->merge($bulanMati)
            ->merge($bulanManual)
            ->filter() // Menghapus nilai null
            ->unique()
            ->sort()
            ->values();

        // 2. Inisialisasi Akun Default
        if (NeracaAccount::count() == 0) {
            $defaults = [
                ['nama_akun' => 'Kas', 'tipe' => 'Aktiva'],
                ['nama_akun' => 'Kambing', 'tipe' => 'Aktiva'],
                ['nama_akun' => 'Piutang', 'tipe' => 'Aktiva'],
                ['nama_akun' => 'Pakan', 'tipe' => 'Aktiva'],
                ['nama_akun' => 'Perlengkapan', 'tipe' => 'Aktiva'],
                ['nama_akun' => 'Upah', 'tipe' => 'Aktiva'],
                ['nama_akun' => 'Kandang', 'tipe' => 'Aktiva'],
                ['nama_akun' => 'Operasional', 'tipe' => 'Aktiva'],
                ['nama_akun' => 'Hutang', 'tipe' => 'Pasiva'],
                ['nama_akun' => 'Titipan', 'tipe' => 'Pasiva'],
            ];
            foreach ($defaults as $d) { NeracaAccount::create($d); }
        }

        $akunAktiva = NeracaAccount::where('tipe', 'Aktiva')->pluck('nama_akun')->toArray();
        $akunPasiva = NeracaAccount::where('tipe', 'Pasiva')->pluck('nama_akun')->toArray();

        $manualLR = LabaRugiManual::all()->groupBy('bulan');
        $saldo = []; $sisaSaldo = []; $labaRugiKumulatif = []; $totalLabaBerjalan = 0;
        $chartLabels = []; $chartDataAset = []; $chartDataLaba = [];

        foreach ($bulanList as $bulan) {
            try {
                $akhirBulan = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();
            } catch (\Exception $e) {
                continue; // Lewati jika format bulan rusak
            }

            $chartLabels[] = Carbon::parse($bulan)->translatedFormat('M y');

            // Saldo Kas Riil
            $sisaSaldo[$bulan] = Kas::where('tanggal', '<=', $akhirBulan)->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->value('saldo') ?? 0;

            // Hitung Aktiva
            $totalAktivaBulan = 0;
            foreach ($akunAktiva as $akun) {
                if ($akun === 'Kas') { 
                    $val = $sisaSaldo[$bulan]; 
                } elseif ($akun === 'Kambing') {
                    $val = KambingRincianHppDetail::where('bulan', $bulan)->selectRaw('SUM(harga_update * qty_update) as total')->value('total') ?? 0;
                } elseif ($akun === 'Piutang') {
                    $in = Kas::where('akun', 'Piutang')->where('jenis_transaksi', 'Masuk')->where('tanggal', '<=', $akhirBulan)->sum('jumlah');
                    $out = Kas::where('akun', 'Piutang')->where('jenis_transaksi', 'Keluar')->where('tanggal', '<=', $akhirBulan)->sum('jumlah');
                    $val = $out - $in;
                } else {
                    $val = Kas::where('akun', $akun)->where('tanggal', '<=', $akhirBulan)->sum('jumlah');
                }
                $saldo[$akun][$bulan] = $val;
                $totalAktivaBulan += $val;
            }
            $chartDataAset[] = $totalAktivaBulan;

            // Hitung Pasiva
            foreach ($akunPasiva as $akun) {
                if ($akun === 'Hutang') {
                    $in = Kas::where('akun', 'Hutang')->where('jenis_transaksi', 'Masuk')->where('tanggal', '<=', $akhirBulan)->sum('jumlah');
                    $out = Kas::where('akun', 'Hutang')->where('jenis_transaksi', 'Keluar')->where('tanggal', '<=', $akhirBulan)->sum('jumlah');
                    $saldo[$akun][$bulan] = $in - $out;
                } else {
                    $saldo[$akun][$bulan] = Kas::where('akun', $akun)->where('tanggal', '<=', $akhirBulan)->sum('jumlah');
                }
            }

            // Hitung Laba Rugi
            $getM = fn($kat, $oto) => (($m = ($manualLR[$bulan] ?? collect())->where('kategori', $kat)->first()) && $m->nilai != 0) ? $m->nilai : $oto;
            
            $untungJual = Penjualan::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])->sum('laba');
            $untungPakan = PakanDetail::whereHas('kas', fn($q) => $q->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan]))->get()->sum(fn($q) => ($q->harga_jual_kg - $q->harga_kg) * $q->qty_kg);
            $bebanMati = KambingMati::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])->sum('harga');
            
            $labaBulanIni = ($getM('laba_kambing', $untungJual) + $getM('laba_pakan', $untungPakan)) - ($getM('beban_upah', 0) + $getM('beban_mati', $bebanMati));
            $totalLabaBerjalan += $labaBulanIni;
            $labaRugiKumulatif[$bulan] = $totalLabaBerjalan;
            $chartDataLaba[] = $totalLabaBerjalan;
        }

        $latestMonth = $bulanList->last();
        return view('neraca.index', compact('bulanList','akunAktiva','akunPasiva','saldo','sisaSaldo','labaRugiKumulatif','chartLabels','chartDataAset','chartDataLaba','latestMonth'));
    }

    // Fungsi Baru: Menghapus data manual pada bulan tertentu
    public function destroyMonth($bulan)
    {
        // Menghapus data di LabaRugiManual untuk bulan tersebut
        LabaRugiManual::where('bulan', $bulan)->delete();

        // Jika Anda juga ingin menghapus transaksi KAS pada bulan itu (Hati-hati!)
        // Kas::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])->delete();

        return back()->with('success', "Data manual bulan $bulan berhasil dihapus.");
    }

    public function addAccount(Request $request)
    {
        $request->validate(['nama_akun' => 'required|string|max:50', 'tipe' => 'required|in:Aktiva,Pasiva']);
        NeracaAccount::create($request->all());
        return back()->with('success', 'Akun Baru Berhasil Ditambahkan!');
    }
}