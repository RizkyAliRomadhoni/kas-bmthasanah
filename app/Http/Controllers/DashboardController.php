<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kas;
use App\Models\FarmAnimal;
use App\Models\FarmAnimalFeed;
use App\Models\FarmAnimalWeight;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        // =============================
        // RINGKASAN KAS
        // =============================
        $totalKasMasuk = Kas::where('jenis_transaksi', 'Masuk')->sum('jumlah');
        $totalKasKeluar = Kas::where('jenis_transaksi', 'Keluar')->sum('jumlah');
        $saldoAkhir = $totalKasMasuk - $totalKasKeluar;

        $totalPendapatan = Kas::where('akun', 'Penjualan')
            ->where('jenis_transaksi', 'Masuk')
            ->sum('jumlah');

        $totalPengeluaran = Kas::whereIn('akun', ['Kambing','Pakan','Operasional','Perawatan'])
            ->where('jenis_transaksi', 'Keluar')
            ->sum('jumlah');

        $labaRugi = $totalPendapatan - $totalPengeluaran;

        $kasBulanan = Kas::selectRaw("
                DATE_FORMAT(tanggal, '%Y-%m') AS bulan, 
                SUM(CASE WHEN jenis_transaksi='Masuk' THEN jumlah ELSE 0 END) AS masuk,
                SUM(CASE WHEN jenis_transaksi='Keluar' THEN jumlah ELSE 0 END) AS keluar
            ")
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $bulan = $kasBulanan->pluck('bulan')->map(function ($b) {
            return Carbon::parse($b . "-01")->translatedFormat('M Y');
        });

        $saldoBulanan = $kasBulanan->map(function ($b) {
            return $b->masuk - $b->keluar;
        });

        $transaksiTerbaru = Kas::orderBy('tanggal', 'DESC')->limit(5)->get();


        // ===============================================================
        // ======================  DATA FARM  ============================
        // ===============================================================

        $totalHewan = FarmAnimal::count();
        $totalPakan = FarmAnimalFeed::sum('jumlah');

        $beratPerHewan = FarmAnimalWeight::select(
                'farm_animal_id',
                DB::raw('berat'),
                DB::raw('MAX(tanggal_update) as max_date')
            )
            ->groupBy('farm_animal_id', 'berat')
            ->pluck('berat', 'farm_animal_id');

        $hewan = FarmAnimal::all();
        $listBeratFinal = [];

        foreach ($hewan as $h) {
            if (isset($beratPerHewan[$h->id])) {
                $listBeratFinal[] = $beratPerHewan[$h->id];
            } else {
                $listBeratFinal[] = $h->berat_terakhir ?? 0;
            }
        }

        $rataRataBerat = count($listBeratFinal) > 0
            ? array_sum($listBeratFinal) / count($listBeratFinal)
            : 0;

        $totalBeratSemua = array_sum($listBeratFinal);


        // ================== TAMBAHAN UNTUK DASHBOARD BLADE ==================

        // 6 data hewan terbaru
        $recentAnimals = FarmAnimal::orderBy('created_at','DESC')->take(6)->get();

        // 3 update berat terakhir
        $lastWeights = FarmAnimalWeight::orderBy('tanggal_update','DESC')->limit(3)->get();

        // 3 pakan terbaru
        $lastFeeds = FarmAnimalFeed::orderBy('created_at','DESC')->limit(3)->get();

        // Chart 6 bulan terakhir
        $months = collect([]);
        $pWeight = collect([]);
        $pFeed = collect([]);

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->format('Y-m');

            $months->push(Carbon::now()->subMonths($i)->translatedFormat('M Y'));

            $pWeight->push(
                FarmAnimalWeight::where(DB::raw("DATE_FORMAT(tanggal_update,'%Y-%m')"), $month)->sum('berat')
            );

            $pFeed->push(
                FarmAnimalFeed::where(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"), $month)->sum('jumlah')
            );
        }


        // RETURN KE VIEW
        return view('dashboard', compact(
            'totalKasMasuk',
            'totalKasKeluar',
            'saldoAkhir',
            'totalPendapatan',
            'totalPengeluaran',
            'labaRugi',
            'bulan',
            'saldoBulanan',
            'transaksiTerbaru',

            // FARM
            'totalHewan',
            'totalPakan',
            'rataRataBerat',
            'totalBeratSemua',

            // ADD FOR VIEW
            'recentAnimals',
            'lastWeights',
            'lastFeeds',
            'months',
            'pWeight',
            'pFeed',
        ));
    }
}
