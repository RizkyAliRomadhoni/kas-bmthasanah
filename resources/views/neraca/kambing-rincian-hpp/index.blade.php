<x-app-layout>
    <!-- Google Font & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        :root {
            --primary: #4f46e5;      /* Indigo Pro */
            --emerald: #10b981;      /* Emerald Farm */
            --slate-800: #1e293b;    /* Dark Header */
            --slate-100: #f1f5f9;    /* Light Gray */
            --bg-page: #f8fafc;
        }

        body, .main-content { 
            font-family: 'Inter', sans-serif !important; 
            background-color: var(--bg-page);
            color: #334155;
        }

        /* Card Elements */
        .card-elegant { border: none; border-radius: 1rem; background: white; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02), 0 2px 4px -1px rgba(0,0,0,0.01); }
        
        /* Table Styling */
        .table-container-pro { border-radius: 1rem; border: 1px solid #e2e8f0; background: white; overflow-x: auto; }
        .table-pro { width: 100%; border-collapse: separate; border-spacing: 0; }
        
        .table-pro thead th { 
            background: var(--slate-800); 
            color: #ffffff; 
            font-size: 10.5px; 
            font-weight: 600; 
            padding: 14px 10px; 
            text-transform: uppercase; 
            letter-spacing: 0.5px;
            border: 0.5px solid #334155;
            white-space: nowrap;
        }

        .table-pro tbody td { 
            font-size: 13px; 
            border-bottom: 1px solid #f1f5f9; 
            border-right: 1px solid #f1f5f9;
            vertical-align: middle; 
            color: #475569;
        }

        /* Sticky Logic */
        .sticky-col { 
            position: sticky; 
            left: 0; 
            background-color: #ffffff !important; 
            z-index: 10; 
            border-right: 2px solid #f1f5f9 !important; 
        }

        /* Input Cell Styling */
        .input-cell { 
            width: 100%; height: 42px; border: none; background: transparent; 
            text-align: center; font-weight: 600; color: #1e293b; transition: 0.2s;
        }
        .input-cell:focus { outline: none; background: #f0f7ff; box-shadow: inset 0 0 0 2px var(--primary); }

        /* Actions */
        .btn-action { width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: none; transition: 0.2s; font-size: 11px; }
        .btn-edit { background: #fef9c3; color: #854d0e; }
        .btn-delete { background: #fee2e2; color: #991b1b; }

        /* Sidebar Metric Cards */
        .metric-card { border: none; border-radius: 12px; background: white; border: 1px solid #e2e8f0; overflow: hidden; }
        .metric-header { padding: 12px 15px; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: white; }
        .input-metric { border: 1px solid #e2e8f0; text-align: right; width: 85px; font-weight: 700; font-size: 13px; padding: 4px 8px; border-radius: 6px; }

        /* Utilities */
        .btn-pill { border-radius: 50px; font-weight: 700; font-size: 11px; padding: 10px 20px; transition: 0.2s; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid #e2e8f0; }
        .btn-primary-pill { background: var(--primary); color: white; border: none; }
        .btn-plus-sq { background: #fff; color: var(--slate-800); width: 24px; height: 24px; border-radius: 5px; border: none; font-weight: 900; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .badge-tag { background: #eef2ff; color: #4338ca; font-weight: 800; padding: 4px 10px; border-radius: 6px; font-size: 10px; }
    </style>

    <main class="main-content">
        <div class="container-fluid py-4">

            <!-- HEADER SECTION -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3 px-2">
                <div class="text-start">
                    <h4 class="fw-bold text-dark mb-0 uppercase tracking-tighter" style="font-family: 'Inter';">Data Populasi & HPP Bulanan</h4>
                    <p class="text-secondary text-sm mb-0">Hasanah Farm • Periode Aktif September 2025</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('neraca.index') }}" class="btn-pill bg-white shadow-sm text-secondary text-decoration-none">
                        <i class="fas fa-arrow-left me-2"></i> Neraca
                    </a>
                    <form action="{{ route('rincian-hpp.tambah-bulan') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-pill bg-white text-primary shadow-sm">
                            <i class="fas fa-calendar-plus me-2"></i> Tambah Bulan
                        </button>
                    </form>
                    <button class="btn-pill btn-primary-pill shadow" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="fas fa-plus me-2"></i> Baris Baru
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm text-white mb-4 py-3 px-4 mx-2" style="background: var(--emerald); border-radius: 12px; font-size: 14px;">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif

            <!-- TABLE SECTION (FULL WIDTH) -->
            <div class="card-elegant shadow-sm mb-5 mx-2 overflow-hidden">
                <div class="table-container-pro">
                    <table class="table-pro text-center">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width: 45px;">ID</th>
                                <th rowspan="2" style="width: 100px;">Tanggal</th>
                                <th rowspan="2" class="sticky-col text-start px-4">Keterangan</th>
                                <th rowspan="2" style="width: 110px;">Jenis</th>
                                <th rowspan="2" style="width: 65px;">TAG</th>
                                <th rowspan="2" style="width: 80px;">Aksi</th>
                                <th colspan="2" style="background: #fffdf2; color: #92400e; border-left: 1px solid #334155;">STOK AWAL</th>
                                @foreach($bulanList as $bulan)
                                    <th colspan="2" style="border-left: 1px solid #334155;">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('M y') }}</th>
                                @endforeach
                            </tr>
                            <tr>
                                <th style="background: #fffdf2; font-size: 9px; border-left: 1px solid #334155;">HARGA</th>
                                <th style="background: #fffdf2; font-size: 9px;">QTY</th>
                                @foreach($bulanList as $bulan)
                                    <th style="font-size: 9px; border-left: 1px solid #334155;">HARGA</th>
                                    <th style="font-size: 9px;">QTY</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stok as $index => $item)
                            <tr>
                                <td class="font-bold text-slate-400">{{ $index + 1 }}</td>
                                <td class="text-xs">{{ $item->tanggal->format('d/m/y') }}</td>
                                <td class="sticky-col text-start px-4 font-bold text-dark text-uppercase">{{ $item->keterangan }}</td>
                                <td class="text-uppercase text-slate-500" style="font-size: 10px;">{{ $item->jenis }}</td>
                                <td><span class="badge-tag">{{ $item->tag ?? '-' }}</span></td>
                                <td class="px-2">
                                    <div class="d-flex justify-content-center gap-1">
                                        <button class="btn-action btn-edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}"><i class="fas fa-pen"></i></button>
                                        <form action="{{ route('rincian-hpp.destroy', $item->id) }}" method="POST">@csrf @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" onclick="return confirm('Hapus baris ini?')"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                                <td class="text-end px-3 font-bold bg-soft-yellow" style="background: #fffdf2; border-left: 1px solid #e2e8f0;">{{ number_format($item->harga_awal, 0, ',', '.') }}</td>
                                <td class="text-center font-bold" style="background: #fffdf2;">{{ $item->qty_awal }}</td>
                                @foreach($bulanList as $bulan)
                                    @php $det = $item->rincian_bulanan->where('bulan', $bulan)->first(); @endphp
                                    <td style="border-left: 1px solid #f1f5f9;"><input type="number" class="input-cell" value="{{ $det ? (int)$det->harga_update : '' }}" onchange="updateCell('{{ $item->id }}', '{{ $bulan }}', 'harga_update', this.value)"></td>
                                    <td><input type="number" class="input-cell" value="{{ $det ? $det->qty_update : '' }}" onchange="updateCell('{{ $item->id }}', '{{ $bulan }}', 'qty_update', this.value)"></td>
                                @endforeach
                            </tr>

                            <!-- MODAL EDIT -->
                            <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <form action="{{ route('rincian-hpp.update', $item->id) }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 24px;">
                                        @csrf @method('PUT')
                                        <div class="modal-body p-4 text-start">
                                            <h6 class="font-bold text-primary mb-4 text-center uppercase">Edit Data Baris</h6>
                                            <div class="row g-3">
                                                <div class="col-6"><label class="text-xxs font-bold text-slate-400 uppercase">Tanggal</label><input type="date" name="tanggal" class="form-control" value="{{ $item->tanggal->format('Y-m-d') }}"></div>
                                                <div class="col-6"><label class="text-xxs font-bold text-slate-400 uppercase">Tag ID</label><input type="text" name="tag" class="form-control" value="{{ $item->tag }}"></div>
                                                <div class="col-12"><label class="text-xxs font-bold text-slate-400 uppercase">Supplier</label><input type="text" name="keterangan" class="form-control text-uppercase" value="{{ $item->keterangan }}"></div>
                                                <div class="col-6"><label class="text-xxs font-bold text-slate-400 uppercase">Jenis</label><input type="text" name="jenis" class="form-control text-uppercase" value="{{ $item->jenis }}"></div>
                                                <div class="col-6"><label class="text-xxs font-bold text-slate-400 uppercase">Klaster</label><input type="text" name="klaster" class="form-control text-uppercase" value="{{ $item->klaster }}"></div>
                                                <div class="col-6"><label class="text-xxs font-bold text-slate-400 uppercase">Modal</label><input type="number" name="harga_awal" class="form-control" value="{{ (int)$item->harga_awal }}"></div>
                                                <div class="col-6"><label class="text-xxs font-bold text-slate-400 uppercase">Qty</label><input type="number" name="qty_awal" class="form-control" value="{{ $item->qty_awal }}"></div>
                                            </div>
                                            <div class="mt-4 d-flex gap-2">
                                                <button type="button" class="btn btn-light w-100 rounded-pill font-bold" data-bs-dismiss="modal">BATAL</button>
                                                <button type="submit" class="btn btn-primary w-100 rounded-pill border-0 font-bold shadow" style="background: var(--primary);">SIMPAN</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="footer-total">
                                <td colspan="6" class="text-center font-bold py-3 sticky-col" style="background: #f8fafc !important;">TOTAL KESELURUHAN</td>
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
            </div>

            <!-- METRIC CARDS SECTION (DI BAWAH TABEL) -->
            <div class="row g-4 px-2">
                <div class="col-md-6">
                    <div class="card metric-card">
                        <div class="metric-header bg-dark d-flex justify-content-between align-items-center">
                            <span>REKAPITULASI STOCK KANDANG</span>
                            <button class="btn-plus-white" data-bs-toggle="modal" data-bs-target="#modalAddStock">+</button>
                        </div>
                        <div class="card-body p-0">
                            @forelse($summaryStock as $s)
                                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <form action="{{ route('rincian-hpp.delete-label', $s->id) }}" method="POST" class="m-0">@csrf @method('DELETE')
                                            <button class="btn btn-link text-danger p-0 m-0 me-2" onclick="return confirm('Hapus?')"><i class="fas fa-trash-alt" style="font-size: 11px;"></i></button>
                                        </form>
                                        <span class="text-xxs font-bold text-uppercase text-secondary">{{ $s->label }}</span>
                                    </div>
                                    <input type="text" class="input-metric" value="{{ $s->nilai }}" onchange="saveSummary('stock', '{{ $s->label }}', this.value)">
                                </div>
                            @empty
                                <div class="p-4 text-center text-slate-400 text-xs italic">Belum ada data kategori.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card metric-card">
                        <div class="metric-header bg-secondary d-flex justify-content-between align-items-center">
                            <span>REKAPITULASI KLASTER BANGSALAN</span>
                            <button class="btn-plus-white" data-bs-toggle="modal" data-bs-target="#modalAddKlaster">+</button>
                        </div>
                        <div class="card-body p-0">
                            @forelse($summaryKlaster as $k)
                                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <form action="{{ route('rincian-hpp.delete-label', $k->id) }}" method="POST" class="m-0">@csrf @method('DELETE')
                                            <button class="btn btn-link text-danger p-0 m-0 me-2" onclick="return confirm('Hapus?')"><i class="fas fa-trash-alt" style="font-size: 11px;"></i></button>
                                        </form>
                                        <span class="text-xxs font-bold text-uppercase text-secondary">{{ $k->label }}</span>
                                    </div>
                                    <input type="text" class="input-metric" style="width: 180px;" value="{{ $k->nilai }}" onchange="saveSummary('klaster', '{{ $k->label }}', this.value)">
                                </div>
                            @empty
                                <div class="p-4 text-center text-slate-400 text-xs italic">Belum ada data klaster.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- MODAL ADD BARIS UTAMA -->
        <div class="modal fade" id="addModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('rincian-hpp.store') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 24px;">
                    @csrf
                    <div class="modal-body p-4 text-start">
                        <h6 class="font-bold text-primary mb-4 text-center uppercase">Tambah Stok Baru</h6>
                        <div class="row g-3">
                            <div class="col-6"><label class="text-xxs font-bold text-slate-400">TANGGAL</label><input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                            <div class="col-6"><label class="text-xxs font-bold text-slate-400">TAG ID</label><input type="text" name="tag" class="form-control" placeholder="Misal: BB-01"></div>
                            <div class="col-12"><label class="text-xxs font-bold text-slate-400">SUPPLIER</label><input type="text" name="keterangan" class="form-control" required></div>
                            <div class="col-6"><label class="text-xxs font-bold text-slate-400">JENIS</label><input type="text" name="jenis" class="form-control" required></div>
                            <div class="col-6"><label class="text-xxs font-bold text-slate-400">KLASTER</label><input type="text" name="klaster" class="form-control" required></div>
                            <div class="col-6"><label class="text-xxs font-bold text-slate-400">HARGA AWAL</label><input type="number" name="harga_awal" class="form-control" required></div>
                            <div class="col-6"><label class="text-xxs font-bold text-slate-400">QTY AWAL</label><input type="number" name="qty_awal" class="form-control" required></div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill shadow mt-4 py-2 font-bold border-0" style="background: var(--primary);">SIMPAN DATA</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL SIDEBAR KATEGORI -->
        <div class="modal fade" id="modalAddStock" tabindex="-1"><div class="modal-dialog modal-sm modal-dialog-centered"><form action="{{ route('rincian-hpp.add-label') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 15px;">@csrf <input type="hidden" name="tipe" value="stock"><div class="modal-body p-4 text-center"><h6 class="font-bold text-xs mb-3 uppercase">Tambah Jenis</h6><input type="text" name="label" class="form-control mb-3 text-uppercase" required autofocus><button type="submit" class="btn btn-dark btn-sm w-100 rounded-pill font-bold">TAMBAH</button></div></form></div></div>
        <div class="modal fade" id="modalAddKlaster" tabindex="-1"><div class="modal-dialog modal-sm modal-dialog-centered"><form action="{{ route('rincian-hpp.add-label') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 15px;">@csrf <input type="hidden" name="tipe" value="klaster"><div class="modal-body p-4 text-center"><h6 class="font-bold text-xs mb-3 uppercase">Tambah Klaster</h6><input type="text" name="label" class="form-control mb-3 text-uppercase" required autofocus><button type="submit" class="btn btn-secondary btn-sm w-100 rounded-pill font-bold">TAMBAH</button></div></form></div></div>

        <!-- SCRIPTS AJAX -->
        <script>
            function updateCell(id, bulan, kolom, nilai) {
                fetch("{{ route('rincian-hpp.update-cell') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                    body: JSON.stringify({ id, bulan, kolom, nilai })
                });
            }
            function saveSummary(tipe, label, nilai) {
                fetch("{{ route('rincian-hpp.update-summary') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                    body: JSON.stringify({ tipe, label, nilai })
                });
            }
        </script>
    </main>
</x-app-layout>