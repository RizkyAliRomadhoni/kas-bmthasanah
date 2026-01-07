<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Font Premium: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        :root {
            --primary: #4f46e5;
            --dark: #0f172a;
            --slate: #64748b;
            --emerald: #10b981;
            --bg-page: #f8fafc;
        }

        body, .main-content { 
            font-family: 'Plus Jakarta Sans', sans-serif !important; 
            background-color: var(--bg-page);
            color: var(--dark);
        }

        /* Card Professional Styling */
        .card-pro { border: none; border-radius: 1.25rem; background: white; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02), 0 2px 4px -1px rgba(0,0,0,0.01); }
        
        /* Header Dashboard */
        .header-glass { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); border-bottom: 1px solid #e2e8f0; margin-bottom: 30px; padding: 20px 0; }

        /* Table Design - Ultra Clean */
        .table-responsive-pro { border-radius: 1rem; border: 1px solid #e2e8f0; background: white; overflow: hidden; }
        .table-pro { width: 100%; border-collapse: collapse; }
        .table-pro thead th { 
            background: #f1f5f9; 
            color: #475569; 
            font-size: 11px; 
            font-weight: 700; 
            padding: 14px 10px; 
            text-transform: uppercase; 
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
        }
        .table-pro tbody td { font-size: 13px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        
        /* Fixed/Sticky Columns */
        .sticky-col { position: sticky; left: 0; background: white; z-index: 10; border-right: 2px solid #f1f5f9 !important; }
        .sticky-label { position: sticky; left: 0; z-index: 10; background: #f8fafc !important; }

        /* Input Fields */
        .input-cell { 
            width: 100%; height: 42px; border: none; background: transparent; 
            text-align: center; font-weight: 600; color: var(--dark); transition: 0.3s;
        }
        .input-cell:focus { outline: none; background: #f0f7ff; box-shadow: inset 0 0 0 2px var(--primary); border-radius: 6px; }
        .input-sidebar { border: 1px solid #e2e8f0; background: #fff; text-align: right; width: 75px; font-weight: 700; font-size: 12px; padding: 5px; border-radius: 8px; }

        /* Buttons & Badges */
        .btn-pill { border-radius: 50px; font-weight: 700; font-size: 11px; padding: 10px 20px; transition: 0.3s; text-transform: uppercase; }
        .btn-primary-pro { background: var(--primary); color: white; border: none; }
        .btn-primary-pro:hover { background: #3730a3; transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4); }
        
        .tag-pill { background: #eef2ff; color: #4338ca; font-weight: 800; padding: 4px 10px; border-radius: 8px; font-size: 10px; }
        .btn-action { width: 32px; height: 32px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; border: none; transition: 0.2s; }
        .btn-edit { background: #fef9c3; color: #854d0e; }
        .btn-delete { background: #fee2e2; color: #991b1b; }

        .sidebar-header { background: var(--dark); color: #fff; border-radius: 12px 12px 0 0; padding: 15px; font-weight: 800; font-size: 11px; letter-spacing: 1px; }
        .btn-plus-sq { background: #fff; color: var(--dark); width: 28px; height: 28px; border-radius: 8px; border: none; font-weight: 900; font-size: 16px; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    </style>

    <div class="header-glass">
        <div class="container-fluid d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 px-4">
            <div class="text-start">
                <h4 class="fw-bold text-dark mb-0">Hasanah Farm <span class="text-primary">Stok HPP</span></h4>
                <p class="text-secondary text-sm mb-0 font-medium">Manajemen Riil Aset Terpadu • {{ now()->translatedFormat('F Y') }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('neraca.index') }}" class="btn btn-pill bg-white border shadow-sm" style="color: var(--slate);">
                    <i class="fas fa-arrow-left me-2"></i> Neraca
                </a>
                <form action="{{ route('rincian-hpp.tambah-bulan') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-pill bg-white border text-primary shadow-sm">
                        <i class="fas fa-calendar-plus me-2"></i> + Bulan
                    </button>
                </form>
                <button class="btn btn-pill btn-primary-pro shadow" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fas fa-plus me-2"></i> Baris Baru
                </button>
            </div>
        </div>
    </div>

    <div class="container-fluid px-4 pb-5">
        <div class="row g-4">
            <!-- AREA TABEL UTAMA -->
            <div class="col-lg-9 col-12">
                <div class="card-pro shadow-sm">
                    <div class="table-responsive-pro">
                        <table class="table-pro text-center">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width: 40px;">#</th>
                                    <th rowspan="2" style="width: 100px;">Tanggal</th>
                                    <th rowspan="2" class="sticky-col text-start px-3" style="width: 180px;">Supplier / Ket.</th>
                                    <th rowspan="2" style="width: 110px;">Kategori</th>
                                    <th rowspan="2" style="width: 60px;">TAG</th>
                                    <th rowspan="2" style="width: 90px;">Aksi</th>
                                    <th colspan="2" style="background: #fffbeb; color: #92400e;">STOK AWAL</th>
                                    @foreach($bulanList as $bulan)
                                        <th colspan="2" style="border-left: 1px solid #cbd5e1;">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('M y') }}</th>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th style="background: #fffbeb; font-size: 9px;">HARGA</th>
                                    <th style="background: #fffbeb; font-size: 9px;">QTY</th>
                                    @foreach($bulanList as $bulan)
                                        <th style="font-size: 9px; border-left: 1px solid #cbd5e1;">HARGA</th>
                                        <th style="font-size: 9px;">QTY</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stok as $index => $item)
                                <tr>
                                    <td class="font-bold text-slate-400">{{ $index + 1 }}</td>
                                    <td class="text-xs">{{ $item->tanggal->format('d/m/y') }}</td>
                                    <td class="sticky-col text-start px-3 font-bold text-dark text-uppercase">{{ $item->keterangan }}</td>
                                    <td class="text-uppercase text-slate-500" style="font-size: 10px;">{{ $item->jenis }}</td>
                                    <td><span class="tag-pill">{{ $item->tag ?? '-' }}</span></td>
                                    <td class="px-2">
                                        <div class="d-flex justify-content-center gap-1">
                                            <button class="btn-action btn-edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}"><i class="fas fa-pen"></i></button>
                                            <form action="{{ route('rincian-hpp.destroy', $item->id) }}" method="POST">@csrf @method('DELETE')
                                                <button type="submit" class="btn-action btn-delete" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="text-end px-2 font-bold bg-accent" style="background: #fffbeb;">{{ number_format($item->harga_awal, 0, ',', '.') }}</td>
                                    <td class="text-center font-bold" style="background: #fffbeb;">{{ $item->qty_awal }}</td>
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
                                                <h6 class="font-bold text-dark mb-4 text-center uppercase">Edit Baris Data</h6>
                                                <div class="row g-3">
                                                    <div class="col-6"><label class="small font-bold text-slate-500">TANGGAL</label><input type="date" name="tanggal" class="form-control" value="{{ $item->tanggal->format('Y-m-d') }}"></div>
                                                    <div class="col-6"><label class="small font-bold text-slate-500">TAG ID</label><input type="text" name="tag" class="form-control" value="{{ $item->tag }}"></div>
                                                    <div class="col-12"><label class="small font-bold text-slate-500">SUPPLIER</label><input type="text" name="keterangan" class="form-control text-uppercase" value="{{ $item->keterangan }}"></div>
                                                    <div class="col-6"><label class="small font-bold text-slate-500">JENIS</label><input type="text" name="jenis" class="form-control text-uppercase" value="{{ $item->jenis }}"></div>
                                                    <div class="col-6"><label class="small font-bold text-slate-500">KLASTER</label><input type="text" name="klaster" class="form-control text-uppercase" value="{{ $item->klaster }}"></div>
                                                    <div class="col-6"><label class="small font-bold text-slate-500">MODAL</label><input type="number" name="harga_awal" class="form-control" value="{{ (int)$item->harga_awal }}"></div>
                                                    <div class="col-6"><label class="small font-bold text-slate-500">QTY</label><input type="number" name="qty_awal" class="form-control" value="{{ $item->qty_awal }}"></div>
                                                </div>
                                                <div class="mt-4 d-flex gap-2">
                                                    <button type="button" class="btn btn-light w-100 rounded-pill font-bold" data-bs-dismiss="modal">BATAL</button>
                                                    <button type="submit" class="btn btn-primary w-100 rounded-pill border-0 font-bold" style="background: var(--primary);">SIMPAN</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="footer-total">
                                    <td colspan="6" class="text-center font-bold py-3 sticky-label text-dark uppercase">Total Keseluruhan</td>
                                    <td class="text-end px-2 font-bold">{{ number_format($stok->sum('harga_awal'), 0, ',', '.') }}</td>
                                    <td class="text-center font-bold">{{ $stok->sum('qty_awal') }}</td>
                                    @foreach($bulanList as $bulan)
                                        @php $tH = 0; $tQ = 0; foreach($stok as $s) { $d = $s->rincian_bulanan->where('bulan', $bulan)->first(); if($d) { $tH += $d->harga_update; $tQ += $d->qty_update; } } @endphp
                                        <td class="text-center font-bold text-indigo-600 border-start">{{ number_format($tH, 0, ',', '.') }}</td>
                                        <td class="text-center font-bold text-indigo-600">{{ $tQ }}</td>
                                    @endforeach
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- AREA SIDEBAR KPI -->
            <div class="col-lg-3 col-12">
                <div class="card sidebar-card mb-4 bg-white shadow-sm">
                    <div class="sidebar-header d-flex justify-content-between align-items-center">
                        <span>STOCK KANDANG</span>
                        <button class="btn-plus-white" data-bs-toggle="modal" data-bs-target="#modalAddStock">+</button>
                    </div>
                    <div class="card-body p-0">
                        @forelse($summaryStock as $s)
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                <form action="{{ route('rincian-hpp.delete-label', $s->id) }}" method="POST" class="m-0">@csrf @method('DELETE')
                                    <button class="btn btn-link text-danger p-0 m-0 me-2" onclick="return confirm('Hapus?')"><i class="fas fa-trash" style="font-size: 11px;"></i></button>
                                </form>
                                <span class="text-xs font-bold text-slate-600 text-uppercase flex-grow-1">{{ $s->label }}</span>
                                <input type="text" class="input-sidebar" value="{{ $s->nilai }}" onchange="saveSummary('stock', '{{ $s->label }}', this.value)">
                            </div>
                        @empty
                            <div class="p-4 text-center text-xs text-slate-400 italic">Klik (+) untuk tambah jenis</div>
                        @endforelse
                    </div>
                </div>

                <div class="card sidebar-card bg-white shadow-sm">
                    <div class="sidebar-header bg-slate-600 d-flex justify-content-between align-items-center">
                        <span>KLASTER BANGSALAN</span>
                        <button class="btn-plus-white" data-bs-toggle="modal" data-bs-target="#modalAddKlaster">+</button>
                    </div>
                    <div class="card-body p-0">
                        @forelse($summaryKlaster as $k)
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                <form action="{{ route('rincian-hpp.delete-label', $k->id) }}" method="POST" class="m-0">@csrf @method('DELETE')
                                    <button class="btn btn-link text-danger p-0 m-0 me-2" onclick="return confirm('Hapus?')"><i class="fas fa-trash" style="font-size: 11px;"></i></button>
                                </form>
                                <span class="text-xs font-bold text-slate-600 text-uppercase flex-grow-1">{{ $k->label }}</span>
                                <input type="text" class="input-sidebar" style="width: 100px;" value="{{ $k->nilai }}" onchange="saveSummary('klaster', '{{ $k->label }}', this.value)">
                            </div>
                        @empty
                            <div class="p-4 text-center text-xs text-slate-400 italic">Klik (+) untuk tambah klaster</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL ADD BARIS -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('rincian-hpp.store') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                @csrf
                <div class="modal-body p-4 text-start">
                    <h5 class="font-bold text-primary mb-4 text-center">TAMBAH STOK BARU</h5>
                    <div class="row g-3">
                        <div class="col-6"><label class="text-xxs font-bold text-slate-500">TANGGAL</label><input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                        <div class="col-6"><label class="text-xxs font-bold text-slate-500">TAG ID</label><input type="text" name="tag" class="form-control" placeholder="Misal: BB-01"></div>
                        <div class="col-12"><label class="text-xxs font-bold text-slate-500 text-uppercase">Supplier</label><input type="text" name="keterangan" class="form-control text-uppercase" required></div>
                        <div class="col-6"><label class="text-xxs font-bold text-slate-500 text-uppercase">Jenis</label><input type="text" name="jenis" class="form-control text-uppercase" placeholder="Merino" required></div>
                        <div class="col-6"><label class="text-xxs font-bold text-slate-500 text-uppercase">Klaster</label><input type="text" name="klaster" class="form-control text-uppercase" placeholder="Marto" required></div>
                        <div class="col-6"><label class="text-xxs font-bold text-slate-500 text-uppercase">Harga Modal</label><input type="number" name="harga_awal" class="form-control" required></div>
                        <div class="col-6"><label class="text-xxs font-bold text-slate-500 text-uppercase">Qty</label><input type="number" name="qty_awal" class="form-control" required></div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill shadow mt-4 py-2 font-bold border-0" style="background: var(--primary);">SIMPAN DATA</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL SIDEBAR -->
    <div class="modal fade" id="modalAddStock" tabindex="-1"><div class="modal-dialog modal-sm modal-dialog-centered"><form action="{{ route('rincian-hpp.add-label') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 15px;">@csrf <input type="hidden" name="tipe" value="stock"><div class="modal-body p-4 text-center"><h6 class="font-bold text-xs mb-3 uppercase">Tambah Jenis</h6><input type="text" name="label" class="form-control mb-3 text-uppercase" required autofocus><button type="submit" class="btn btn-dark btn-sm w-100 rounded-pill font-bold">TAMBAH</button></div></form></div></div>
    <div class="modal fade" id="modalAddKlaster" tabindex="-1"><div class="modal-dialog modal-sm modal-dialog-centered"><form action="{{ route('rincian-hpp.add-label') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 15px;">@csrf <input type="hidden" name="tipe" value="klaster"><div class="modal-body p-4 text-center"><h6 class="font-bold text-xs mb-3 uppercase">Tambah Klaster</h6><input type="text" name="label" class="form-control mb-3 text-uppercase" required autofocus><button type="submit" class="btn btn-secondary btn-sm w-100 rounded-pill font-bold">TAMBAH</button></div></form></div></div>

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