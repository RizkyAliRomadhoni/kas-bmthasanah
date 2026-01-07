<x-app-layout>
    
    <!-- Font & Icon Setup -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        :root {
            --primary: #4f46e5;
            --dark: #1e293b;
            --border: #e2e8f0;
            --bg-body: #f8fafc;
        }

        body, .main-content { font-family: 'Inter', sans-serif; background-color: var(--bg-body); color: #334155; }

        /* === SCROLLABLE TABLE LAYOUT === */
        .table-pro-container { 
            background: white; 
            border-radius: 1rem; 
            border: 1px solid var(--border); 
            overflow: auto; 
            max-height: 600px;
        }
        .table-pro { width: 100%; border-collapse: separate; border-spacing: 0; }
        
        .table-pro thead th { 
            position: sticky; top: 0; z-index: 20;
            background: var(--dark) !important; color: #fff !important; 
            font-size: 11px; padding: 15px 12px; text-transform: uppercase; 
            letter-spacing: 0.05em; border: 0.5px solid #334155;
        }

        /* Fixed Left Columns */
        .sticky-col-id { position: sticky; left: 0; z-index: 25; background: #fff !important; min-width: 50px; }
        .sticky-col-date { position: sticky; left: 50px; z-index: 25; background: #fff !important; min-width: 100px; }
        .sticky-col-supplier { position: sticky; left: 150px; z-index: 25; background: #fff !important; border-right: 2px solid var(--border) !important; min-width: 220px; text-align: left !important; }

        /* Monthly Columns Width (Bigger) */
        .col-month-harga { min-width: 150px; }
        .col-month-qty { min-width: 80px; }

        .table-pro tbody td { 
            font-size: 13px; border-bottom: 1px solid var(--border); border-right: 1px solid var(--border);
            padding: 0 !important; vertical-align: middle; 
        }

        /* Header Corners Fix */
        .table-pro thead th.sticky-col-id, 
        .table-pro thead th.sticky-col-date, 
        .table-pro thead th.sticky-col-supplier { z-index: 30; background: var(--dark) !important; }

        /* Inputs */
        .input-cell { width: 100%; height: 45px; border: none; background: transparent; text-align: center; font-weight: 600; color: var(--dark); transition: 0.2s; }
        .input-cell:focus { outline: none; background: #f0f7ff; box-shadow: inset 0 0 0 2px var(--primary); }

        /* Summary Cards Below */
        .metric-card { border: none; border-radius: 1rem; background: white; border: 1px solid var(--border); overflow: hidden; height: 100%; }
        .metric-header { background: var(--dark); color: white; padding: 15px; font-weight: 800; font-size: 11px; letter-spacing: 1px; display: flex; justify-content: space-between; align-items: center; }
        .input-sidebar { border: 1px solid var(--border); background: #f8fafc; text-align: right; width: 100px; font-weight: 700; padding: 6px; border-radius: 8px; }

        /* Buttons */
        .btn-pro { border-radius: 50px; font-weight: 700; font-size: 11px; padding: 10px 22px; transition: 0.2s; border: 1px solid var(--border); display: inline-flex; align-items: center; gap: 8px; text-decoration: none !important; }
        .btn-pro:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .btn-primary-pro { background: var(--primary); color: white; border: none; }
        .btn-plus-sq { background: #fff; color: var(--dark); width: 26px; height: 26px; border-radius: 6px; border: none; font-weight: 900; display: flex; align-items: center; justify-content: center; }

        .tag-badge { background: #eef2ff; color: #4338ca; font-weight: 800; padding: 4px 10px; border-radius: 8px; font-size: 10px; }
        .bg-soft-yellow { background-color: #fffdf2 !important; }
    </style>

    <main class="main-content">
        <div class="container-fluid py-4">

            <!-- 1. TOP HEADER -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5 gap-3 px-2">
                <div class="text-start text-start">
                    <h3 class="fw-bold text-dark mb-0 uppercase tracking-tighter" style="font-family: 'Plus Jakarta Sans'; font-size: 26px;">Rincian HPP Pro</h3>
                    <p class="text-secondary text-sm mb-0">Hasanah Farm • Sinkronisasi Tabel & Rekapitulasi Metrik</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('neraca.index') }}" class="btn-pro bg-white shadow-sm text-secondary"><i class="fas fa-arrow-left"></i> Neraca</a>
                    <form action="{{ route('rincian-hpp.tambah-bulan') }}" method="POST">@csrf
                        <button type="submit" class="btn-pro bg-white text-primary shadow-sm"><i class="fas fa-calendar-plus"></i> + Bulan</button>
                    </form>
                    <button class="btn-pro btn-primary-pro shadow" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus"></i> Baris Baru</button>
                </div>
            </div>

            <!-- 2. MAIN TABLE (SCROLLABLE) -->
            <div class="table-pro-container shadow-sm mb-5 mx-2">
                <table class="table-pro text-center">
                    <thead>
                        <tr>
                            <th rowspan="2" class="sticky-col-id">#</th>
                            <th rowspan="2" class="sticky-col-date">Tanggal</th>
                            <th rowspan="2" class="sticky-col-supplier">Supplier / Keterangan</th>
                            <th rowspan="2" style="min-width: 120px;">Jenis</th>
                            <th rowspan="2" style="width: 65px;">TAG</th>
                            <th rowspan="2" style="width: 90px;">Aksi</th>
                            <th colspan="2" class="bg-soft-yellow" style="color: #92400e; border-left: 1px solid #334155;">STOK AWAL</th>
                            @foreach($bulanList as $bulan)
                                <th colspan="2" style="border-left: 1px solid #4b5b7c;">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('M y') }}</th>
                            @endforeach
                        </tr>
                        <tr>
                            <th class="bg-soft-yellow" style="font-size: 9px; border-left: 1px solid #334155;">HARGA</th>
                            <th class="bg-soft-yellow" style="font-size: 9px;">QTY</th>
                            @foreach($bulanList as $bulan)
                                <th class="col-month-harga" style="font-size: 9px; border-left: 1px solid #4b5b7c;">HARGA</th>
                                <th class="col-month-qty" style="font-size: 9px;">QTY</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stok as $index => $item)
                        <tr>
                            <td class="font-bold text-slate-400 sticky-col-id">{{ $index + 1 }}</td>
                            <td class="text-xs sticky-col-date">{{ $item->tanggal->format('d/m/y') }}</td>
                            <td class="sticky-col-supplier font-bold text-dark text-uppercase px-3">{{ $item->keterangan }}</td>
                            <td class="text-uppercase text-secondary" style="font-size: 10px;">{{ $item->jenis }}</td>
                            <td class="text-center"><span class="tag-pill">{{ $item->tag ?? '-' }}</span></td>
                            <td>
                                <div class="d-flex justify-content-center gap-1 px-2">
                                    <button class="btn btn-action btn-edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}"><i class="fas fa-pen"></i></button>
                                    <form action="{{ route('rincian-hpp.destroy', $item->id) }}" method="POST">@csrf @method('DELETE')
                                        <button type="submit" class="btn btn-action btn-delete" onclick="return confirm('Hapus?')"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </td>
                            <td class="text-end px-3 font-bold bg-soft-yellow border-start">{{ number_format($item->harga_awal, 0, ',', '.') }}</td>
                            <td class="text-center font-bold bg-soft-yellow">{{ $item->qty_awal }}</td>
                            @foreach($bulanList as $bulan)
                                @php $det = $item->rincian_bulanan->where('bulan', $bulan)->first(); @endphp
                                <td style="border-left: 1px solid #f1f5f9;"><input type="number" class="input-cell" value="{{ $det ? (int)$det->harga_update : '' }}" onchange="updateCell('{{ $item->id }}', '{{ $bulan }}', 'harga_update', this.value)"></td>
                                <td><input type="number" class="input-cell" value="{{ $det ? $det->qty_update : '' }}" onchange="updateCell('{{ $item->id }}', '{{ $bulan }}', 'qty_update', this.value)"></td>
                            @endforeach
                        </tr>

                        <!-- MODAL EDIT INDUK -->
                        <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <form action="{{ route('rincian-hpp.update', $item->id) }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                                    @csrf @method('PUT')
                                    <div class="modal-body p-4 text-start">
                                        <h6 class="font-bold text-dark mb-4 text-center uppercase">Update Data Baris</h6>
                                        <div class="row g-3">
                                            <div class="col-6"><label class="text-xxs font-bold text-slate-400">TANGGAL</label><input type="date" name="tanggal" class="form-control" value="{{ $item->tanggal->format('Y-m-d') }}"></div>
                                            <div class="col-6"><label class="text-xxs font-bold text-slate-400">TAG ID</label><input type="text" name="tag" class="form-control" value="{{ $item->tag }}"></div>
                                            <div class="col-12"><label class="text-xxs font-bold text-slate-400">SUPPLIER</label><input type="text" name="keterangan" class="form-control text-uppercase" value="{{ $item->keterangan }}"></div>
                                            <div class="col-6"><label class="text-xxs font-bold text-slate-400">JENIS</label><input type="text" name="jenis" class="form-control text-uppercase" value="{{ $item->jenis }}"></div>
                                            <div class="col-6"><label class="text-xxs font-bold text-slate-400">KLASTER</label><input type="text" name="klaster" class="form-control text-uppercase" value="{{ $item->klaster }}"></div>
                                            <div class="col-6"><label class="text-xxs font-bold text-slate-400">MODAL</label><input type="number" name="harga_awal" class="form-control" value="{{ (int)$item->harga_awal }}"></div>
                                            <div class="col-6"><label class="text-xxs font-bold text-slate-400">QTY</label><input type="number" name="qty_awal" class="form-control" value="{{ $item->qty_awal }}"></div>
                                        </div>
                                        <div class="mt-4 d-flex gap-2">
                                            <button type="button" class="btn btn-light w-100 rounded-pill font-bold" data-bs-dismiss="modal">BATAL</button>
                                            <button type="submit" class="btn btn-primary w-100 rounded-pill border-0 font-bold shadow py-2" style="background: var(--primary);">SIMPAN</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="footer-total">
                            <td colspan="6" class="text-center font-bold py-4 sticky-col-id sticky-col-date sticky-col-supplier" style="background: #f8fafc !important;">TOTAL KESELURUHAN</td>
                            <td class="text-end px-3 font-bold">{{ number_format($stok->sum('harga_awal'), 0, ',', '.') }}</td>
                            <td class="text-center font-bold">{{ $stok->sum('qty_awal') }}</td>
                            @foreach($bulanList as $bulan)
                                @php $tH = 0; $tQ = 0; foreach($stok as $s) { $d = $s->rincian_bulanan->where('bulan', $bulan)->first(); if($d) { $tH += $d->harga_update; $tQ += $d->qty_update; } } @endphp
                                <td class="text-center font-bold text-primary border-start" style="font-size: 14px;">{{ number_format($tH, 0, ',', '.') }}</td>
                                <td class="text-center font-bold text-primary" style="font-size: 14px;">{{ $tQ }}</td>
                            @endforeach
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- 3. SUMMARY METRICS (BELOW TABLE) -->
            <div class="row g-4 px-2 mt-2">
                <div class="col-md-6">
                    <div class="card metric-card">
                        <div class="metric-header text-start">
                            <span>REKAPITULASI STOCK KANDANG</span>
                            <button class="btn-plus-sq" data-bs-toggle="modal" data-bs-target="#modalAddStock"> + </button>
                        </div>
                        <div class="card-body p-0">
                            <div class="row g-0">
                                @forelse($summaryStock as $s)
                                    <div class="col-6 d-flex justify-content-between align-items-center p-3 border-bottom border-end">
                                        <div class="d-flex align-items-center">
                                            <form action="{{ route('rincian-hpp.delete-label', $s->id) }}" method="POST" class="m-0">@csrf @method('DELETE')
                                                <button class="btn btn-link text-danger p-0 m-0 me-2" onclick="return confirm('Hapus?')"><i class="fas fa-times-circle"></i></button>
                                            </form>
                                            <span class="text-xxs font-bold text-secondary text-start">{{ $s->label }}</span>
                                        </div>
                                        <input type="text" class="input-metric" value="{{ $s->nilai }}" onchange="saveSummary('stock', '{{ $s->label }}', this.value)">
                                    </div>
                                @empty
                                    <div class="p-4 text-center text-slate-400 text-xs italic w-100 text-start">Klik (+) di atas untuk menambah kategori.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card metric-card">
                        <div class="metric-header text-start" style="background:#475569;">
                            <span>REKAPITULASI KLASTER BANGSALAN</span>
                            <button class="btn-plus-sq" data-bs-toggle="modal" data-bs-target="#modalAddKlaster"> + </button>
                        </div>
                        <div class="card-body p-0">
                            @forelse($summaryKlaster as $k)
                                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <form action="{{ route('rincian-hpp.delete-label', $k->id) }}" method="POST" class="m-0">@csrf @method('DELETE')
                                            <button class="btn btn-link text-danger p-0 m-0 me-2" onclick="return confirm('Hapus?')"><i class="fas fa-times-circle"></i></button>
                                        </form>
                                        <span class="text-xxs font-bold text-secondary text-start">{{ $k->label }}</span>
                                    </div>
                                    <input type="text" class="input-metric" style="width: 220px;" value="{{ $k->nilai }}" onchange="saveSummary('klaster', '{{ $k->label }}', this.value)">
                                </div>
                            @empty
                                <div class="p-4 text-center text-slate-400 text-xs italic w-100 text-start">Klik (+) di atas untuk menambah klaster.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- MODAL TAMBAH BARIS BARU -->
        <div class="modal fade" id="addModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('rincian-hpp.store') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 24px;">
                    @csrf
                    <div class="modal-body p-4 text-start">
                        <h5 class="fw-bold text-primary mb-4 text-center uppercase">Tambah Stok Baru</h5>
                        <div class="row g-3 text-start text-start">
                            <div class="col-6"><label class="text-xxs font-bold">TANGGAL</label><input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                            <div class="col-6"><label class="text-xxs font-bold">TAG ID</label><input type="text" name="tag" class="form-control" placeholder="Misal: BB-01"></div>
                            <div class="col-12"><label class="text-xxs font-bold text-uppercase">Supplier / Keterangan</label><input type="text" name="keterangan" class="form-control text-uppercase" required></div>
                            <div class="col-6"><label class="text-xxs font-bold text-uppercase">Jenis</label><input type="text" name="jenis" class="form-control text-uppercase" placeholder="Merino" required></div>
                            <div class="col-6"><label class="text-xxs font-bold text-uppercase">Klaster (Kandang)</label><input type="text" name="klaster" class="form-control text-uppercase" placeholder="Marto" required></div>
                            <div class="col-6"><label class="text-xxs font-bold text-uppercase">Harga Modal (Rp)</label><input type="number" name="harga_awal" class="form-control" required></div>
                            <div class="col-6"><label class="text-xxs font-bold text-uppercase">Qty Awal</label><input type="number" name="qty_awal" class="form-control" required></div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill shadow mt-4 py-2 font-bold border-0" style="background: var(--primary);">SIMPAN DATA</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL TAMBAH LABEL SIDEBAR -->
        <div class="modal fade" id="modalAddStock" tabindex="-1"><div class="modal-dialog modal-sm modal-dialog-centered"><form action="{{ route('rincian-hpp.add-label') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 15px;">@csrf <input type="hidden" name="tipe" value="stock"><div class="modal-body p-4 text-center"><h6 class="font-bold text-xs mb-3 uppercase">Tambah Jenis</h6><input type="text" name="label" class="form-control mb-3 text-uppercase" required autofocus><button type="submit" class="btn btn-dark btn-sm w-100 rounded-pill font-bold">TAMBAH</button></div></form></div></div>
        <div class="modal fade" id="modalAddKlaster" tabindex="-1"><div class="modal-dialog modal-sm modal-dialog-centered"><form action="{{ route('rincian-hpp.add-label') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 15px;">@csrf <input type="hidden" name="tipe" value="klaster"><div class="modal-body p-4 text-center"><h6 class="font-bold text-xs mb-3 uppercase">Tambah Klaster</h6><input type="text" name="label" class="form-control mb-3 text-uppercase" required autofocus><button type="submit" class="btn btn-secondary btn-sm w-100 rounded-pill font-bold">TAMBAH</button></div></form></div></div>

    </main>

    <!-- AJAX LOGIC -->
    <script>
        function updateCell(id, bulan, kolom, nilai) {
            fetch("{{ route('rincian-hpp.update-cell') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ id, bulan, kolom, nilai })
            }).then(res => console.log('Cell Updated'));
        }
        function saveSummary(tipe, label, nilai) {
            fetch("{{ route('rincian-hpp.update-summary') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ tipe, label, nilai })
            }).then(res => console.log('Summary Saved'));
        }
    </script>
</x-app-layout>