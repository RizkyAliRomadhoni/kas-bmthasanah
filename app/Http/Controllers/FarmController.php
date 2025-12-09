<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FarmAnimal;
use App\Models\FarmAnimalWeight;
use App\Models\FarmAnimalFeed;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class FarmController extends Controller
{
    // Index: ringkasan + tabel + grafik data
    public function index(Request $request)
    {
        $q = FarmAnimal::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $q->where(function($s) use($search) {
                $s->where('nama','like',"%$search%")
                  ->orWhere('kode','like',"%$search%")
                  ->orWhere('jenis','like',"%$search%");
            });
        }

        // recent: tampilkan terbaru (tanggal_masuk atau created_at)
        $animals = $q->orderBy('tanggal_masuk','desc')->orderBy('id','desc')->paginate(15);

        // ringkasan
        $totalAnimals = FarmAnimal::count();
        $avgWeight = round(FarmAnimal::avg('berat_terakhir') ?? 0, 2);
        $totalFeedToday = FarmAnimalFeed::whereDate('created_at', Carbon::today())->sum('jumlah');

        // 3 transaksi terakhir (berat/pakan) untuk ringkasan
        $lastWeights = FarmAnimalWeight::with('animal')->orderBy('tanggal_update','desc')->limit(3)->get();
        $lastFeeds = FarmAnimalFeed::with('animal')->orderBy('created_at','desc')->limit(3)->get();

        // data untuk grafik: pemasukan = kenaikan berat rata2 per bulan, konsumsi pakan per bulan
        $months = collect();
        $pWeight = []; $pFeed = [];

        $start = Carbon::now()->subMonths(5)->startOfMonth();
        for ($i=0;$i<6;$i++) {
            $m = $start->copy()->addMonths($i);
            $label = $m->translatedFormat('M Y');
            $months->push($label);

            $weightSum = FarmAnimalWeight::whereYear('tanggal_update',$m->year)
                ->whereMonth('tanggal_update',$m->month)
                ->sum('berat');

            $feedSum = FarmAnimalFeed::whereYear('created_at',$m->year)
                ->whereMonth('created_at',$m->month)
                ->sum('jumlah');

            $pWeight[] = $weightSum;
            $pFeed[] = $feedSum;
        }

        return view('farm.index', compact(
            'animals','totalAnimals','avgWeight','totalFeedToday',
            'lastWeights','lastFeeds','months','pWeight','pFeed'
        ));
    }

    public function create()
    {
        return view('farm.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'=>'required|string',
            'jenis'=>'nullable|string',
            'gender'=>'nullable',
            'umur'=>'nullable|integer',
            'berat_terakhir'=>'nullable|numeric',
            'tanggal_masuk'=>'nullable|date',
            'status'=>'nullable|string',
            'kesehatan'=>'nullable|string',
            'foto'=>'nullable|image|max:2048'
        ]);

        $data['kode'] = Str::upper('FARM-'.Str::random(6));

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('farm/photos','public');
            $data['foto'] = $path;
        }

        $animal = FarmAnimal::create($data);

        // jika berat awal diberikan, simpan history
        if (!empty($data['berat_terakhir'])) {
            FarmAnimalWeight::create([
                'farm_animal_id' => $animal->id,
                'berat' => $data['berat_terakhir'],
                'tanggal_update' => $data['tanggal_masuk'] ?? now(),
                'catatan' => 'Berat awal'
            ]);
        }

        return redirect()->route('farm.index')->with('success','Data animal berhasil dibuat.');
    }

    public function show($id)
    {
        $animal = FarmAnimal::with(['beratHistory','pakan'])->findOrFail($id);
        return view('farm.show', compact('animal'));
    }

    public function edit($id)
    {
        $animal = FarmAnimal::findOrFail($id);
        return view('farm.edit', compact('animal'));
    }

    public function update(Request $request, $id)
    {
        $animal = FarmAnimal::findOrFail($id);
        $data = $request->validate([
            'nama'=>'required|string',
            'jenis'=>'nullable|string',
            'gender'=>'nullable',
            'umur'=>'nullable|integer',
            'berat_terakhir'=>'nullable|numeric',
            'tanggal_masuk'=>'nullable|date',
            'status'=>'nullable|string',
            'kesehatan'=>'nullable|string',
            'foto'=>'nullable|image|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            // hapus foto lama jika ada
            if ($animal->foto) Storage::disk('public')->delete($animal->foto);
            $data['foto'] = $request->file('foto')->store('farm/photos','public');
        }

        $animal->update($data);
        return redirect()->route('farm.show',$animal->id)->with('success','Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $animal = FarmAnimal::findOrFail($id);
        if ($animal->foto) Storage::disk('public')->delete($animal->foto);
        $animal->delete();
        return redirect()->route('farm.index')->with('success','Data berhasil dihapus.');
    }

    // Update berat (form on show page)
    public function updateWeight(Request $request, $id)
    {
        $request->validate([
            'berat'=>'required|numeric',
            'tanggal_update'=>'nullable|date',
            'catatan'=>'nullable|string'
        ]);

        $animal = FarmAnimal::findOrFail($id);
        $tanggal = $request->tanggal_update ? Carbon::parse($request->tanggal_update) : Carbon::now();

        FarmAnimalWeight::create([
            'farm_animal_id'=>$id,
            'berat'=>$request->berat,
            'tanggal_update'=>$tanggal,
            'catatan'=>$request->catatan
        ]);

        // update berat terakhir
        $animal->update(['berat_terakhir'=>$request->berat]);

        return back()->with('success','Berat berhasil diperbarui.');
    }

    // Tambah pakan
    public function addFeed(Request $request, $id)
    {
        $request->validate([
            'jenis_pakan'=>'required|string',
            'jumlah'=>'required|integer',
            'catatan'=>'nullable|string'
        ]);

        FarmAnimalFeed::create([
            'farm_animal_id'=>$id,
            'jenis_pakan'=>$request->jenis_pakan,
            'jumlah'=>$request->jumlah,
            'catatan'=>$request->catatan
        ]);

        return back()->with('success','Data pakan tersimpan.');
    }
}
