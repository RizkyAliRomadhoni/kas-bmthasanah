<x-app-layout>
    <!-- Google Fonts: Inter & Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        :root {
            --primary: #4f46e5;
            --dark: #0f172a;
            --slate: #64748b;
            --bg-page: #f8fafc;
        }

        body, .main-content { 
            font-family: 'Inter', sans-serif !important; 
            background-color: var(--bg-page);
            color: var(--dark);
        }

        .card-pro { border: none; border-radius: 1.25rem; background: white; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); }
        
        /* === TABLE REFINEMENT: FULL WIDTH === */
        .table-responsive-pro { 
            border-radius: 1rem; 
            border: 1px solid #e2e8f0; 
            background: white; 
            overflow-x: auto;
        }
        .table-pro { width: 100%; border-collapse: separate; border-spacing: 0; }
        .table-pro thead th { 
            background: #1e293b; 
            color: #ffffff; 
            font-size: 11px; 
            font-weight: 700; 
            padding: 14px 10px; 
            text-transform: uppercase; 
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
        
        /* Sticky Column: Supplier Name tetap di kiri saat scroll ke kanan */
        .sticky-col { 
            position: sticky; 
            left: 0; 
            background-color: #fff !important; 
            z-index: 10; 
            border-right: 2px solid #f1f5f9 !important; 
        }
        .table-pro thead th.sticky-col { z-index: 20; background: #1e293b !important; }

        /* Input Interaction */
        .input-cell { 
            width: 100%; height: 45px; border: none; background: transparent; 
            text-align: center; font-weight: 600; color: var(--dark); transition: 0.3s;
        }
        .input-cell:focus { outline: none; background: #f0f7ff; box-shadow: inset 0 0 0 2px var(--primary); }

        /* Summary Cards di Bawah */
        .summary-card { border-radius: 1rem; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.03); height: 100%; }
        .summary-header { padding: 15px 20px; font-weight: 800; font-size: 12px; letter-spacing: 1px; color: white; border-radius: 1rem 1rem 0 0; }
        .input-summary { border: 1px solid #e2e8f0; background: #fff; text-align: right; width: 100px; font-weight: 700; font-size: 13px; padding: 5px 10px; border-radius: 8px; }

        /* Buttons & Components */
        .btn-pill { border-radius: 50px; font-weight: 700; font-size: 11px; padding: 10px 22px; transition: 0.3s; text-transform: uppercase; border: 1px solid #e2e8f0; }
        .btn-primary-pro { background: var(--primary); color: white; border: none; }
        .btn-primary-pro:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4); }
        .btn-plus-white { background: #fff; color: var(--dark); width: 26px; height: 26px; border-radius: 6px; border: none; font-weight: 900; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        
        .tag-pill { background: #eef1ff; color: #4338ca; font-weight: 800; padding: 4px 10px; border-radius: 8px; font-size: 10px; }
    </style>

    <main class="main-content">
        <div class="container-fluid py-4">

            <!-- 1. HEADER UTAMA -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3 px-2">
                <div class="text-start">
                    <h3 class="fw-bold text-dark mb-0 uppercase tracking-tighter" style="font-family: 'Plus Jakarta Sans';">Dashboard Stok & HPP</h3>
                    <p class="text-secondary text-sm mb-0">Manajemen Aset Hasanah Farm • Mode Tabel Memanjang</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('neraca.index') }}" class="btn-pill bg-white shadow-sm text-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Neraca
                    </a>
                    <form action="{{ route('rincian-hpp.tambah-bulan') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-pill bg-white text-primary shadow-sm">
                            <i class="fas fa-calendar-plus me-2"></i> + Kolom Bulan
                        </button>
                    </form>
                    <button class="btn-pill btn-primary-pro shadow" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="fas fa-plus me-2"></i> Tambah Baris
                    </button>
                </div>
            </div>

            <!-- 2. TABEL UTAMA (FULL WIDTH) -->
            <div class="card-pro shadow-sm mb-5">
                <div class="table-responsive-pro">
                    <table class="table-pro text-center">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width: 45px;">ID</th>
                                <th rowspan="2" style="width: 90px;">Tanggal</th>
                                <th rowspan="2" class="sticky-col text-start px-3" style="width: 200px;">Supplier / Keterangan</th>
                                <th rowspan="2" style="width: 120px;">Kategori</th>
                                <th rowspan="2" style="width: 60px;">TAG</th>
                                <th rowspan="2" style="width: 90px;">Aksi</th>
                                <th colspan="2" style="background: #fffbeb; color: #92400e;">STOK AWAL</th>
                                @foreach($bulanList as $bulan)
                                    <th colspan="2" style="border-left: 1px solid #4b5b7c;">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('M y') }}</th>
                                @endforeach
                            </tr>
                            <tr>
                                <th style="background: #fffbeb; font-size: 9px;">HARGA</th>
                                <th style="background: #fffbeb; font-size: 9px;">QTY</th>
                                @foreach($bulanList as $bulan)
                                    <th style="font-size: 9px; border-left: 1px solid #4b5b7c;">HARGA</th>
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
                                <td class="text-uppercase text-slate-500" style="font-size: 11px;">{{ $item->jenis }}</td>
                                <td><span class="tag-pill">{{ $item->tag ?? '-' }}</span></td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1 px-2">
                                        <button class="btn-icon btn-edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}"><i class="fas fa-pen"></i></button>
                                        <form action="{{ route('rincian-hpp.destroy', $item->id) }}" method="POST">@csrf @method('DELETE')
                                            <button type="submit" class="btn-icon btn-delete" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                                <td class="text-end px-3 font-bold" style="background: #fffdf5;">{{ number_format($item->harga_awal, 0, ',', '.') }}</td>
                                <td class="text-center font-bold" style="background: #fffdf5;">{{ $item->qty_awal }}</td>
                                @foreach($bulanList as $bulan)
                                    @php $det = $item->rincian_bulanan->where('bulan', $bulan)->first(); @endphp
                                    <td style="border-left: 1px solid #f1f5f9;"><input type="number" class="input-cell" value="{{ $det ? (int)$det->harga_update : '' }}" onchange="updateCell('{{ $item->id }}', '{{ $bulan }}', 'harga_update', this.value)"></td>
                                    <td><input type="number" class="input-cell" value="{{ $det ? $det->qty_update : '' }}" onchange="updateCell('{{ $item->id }}', '{{ $bulan }}', 'qty_update', this.value)"></td>
                                @endforeach
                            </tr>

                            <!-- MODAL EDIT -->
                            <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <form action="{{ route('rincian-hpp.update', $item->id) }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                                        @csrf @method('PUT')
                                        <div class="modal-body p-4 text-start">
                                            <h6 class="font-bold text-dark mb-4 text-center uppercase">Edit Data Baris</h6>
                                            <div class="row g-3">
                                                <div class="col-6"><label class="text-xxs font-bold text-slate-500">TANGGAL</label><input type="date" name="tanggal" class="form-control" value="{{ $item->tanggal->format('Y-m-d') }}"></div>
                                                <div class="col-6"><label class="text-xxs font-bold text-slate-500">TAG ID</label><input type="text" name="tag" class="form-control" value="{{ $item->tag }}"></div>
                                                <div class="col-12"><label class="text-xxs font-bold text-slate-500">SUPPLIER</label><input type="text" name="keterangan" class="form-control text-uppercase" value="{{ $item->keterangan }}"></div>
                                                <div class="col-6"><label class="text-xxs font-bold text-slate-500">JENIS</label><input type="text" name="jenis" class="form-control text-uppercase" value="{{ $item->jenis }}"></div>
                                                <div class="col-6"><label class="text-xxs font-bold text-slate-500">KLASTER</label><input type="text" name="klaster" class="form-control text-uppercase" value="{{ $item->klaster }}"></div>
                                                <div class="col-6"><label class="text-xxs font-bold text-slate-500">MODAL</label><input type="number" name="harga_awal" class="form-control" value="{{ (int)$item->harga_awal }}"></div>
                                                <div class="col-6"><label class="text-xxs font-bold text-slate-500">QTY</label><input type="number" name="qty_awal" class="form-control" value="{{ $item->qty_awal }}"></div>
                                            </div>
                                            <div class="mt-4 d-flex gap-2">
                                                <button type="button" class="btn btn-light w-100 rounded-pill font-bold" data-bs-dismiss="modal">BATAL</button>
                                                <button type="submit" class="btn btn-primary w-100 rounded-pill border-0 font-bold" style="background: var(--primary);">UPDATE</button>
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

            <!-- 3. SUMMARY SECTION (DI BAWAH TABEL) -->
            <div class="row g-4 px-2">
                <!-- Stock Kandang -->
                <div class="col-md-6">
                    <div class="card summary-card overflow-hidden">
                        <div class="summary-header bg-dark d-flex justify-content-between align-items-center">
                            <span>REKAPITULASI STOCK KANDANG</span>
                            <button class="btn-plus-white" data-bs-toggle="modal" data-bs-target="#modalAddStock">+</button>
                        </div>
                        <div class="card-body p-0 bg-white">
                            <div class="row g-0">
                                @forelse($summaryStock as $s)
                                    <div class="col-6 d-flex justify-content-between align-items-center p-3 border-bottom border-end">
                                        <div class="d-flex align-items-center">
                                            <form action="{{ route('rincian-hpp.delete-label', $s->id) }}" method="POST" class="m-0">@csrf @method('DELETE')
                                                <button class="btn btn-link text-danger p-0 m-0 me-2" onclick="return confirm('Hapus?')"><i class="fas fa-times-circle"></i></button>
                                            </form>
                                            <span class="text-xxs font-bold text-uppercase text-secondary">{{ $s->label }}</span>
                                        </div>
                                        <input type="text" class="input-summary" value="{{ $s->nilai }}" onchange="saveSummary('stock', '{{ $s->label }}', this.value)">
                                    </div>
                                @empty
                                    <div class="p-4 text-center text-slate-400 w-100 italic">Belum ada kategori stock kandang.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Klaster Bangsalan -->
                <div class="col-md-6">
                    <div class="card summary-card overflow-hidden">
                        <div class="summary-header bg-secondary d-flex justify-content-between align-items-center">
                            <span>REKAPITULASI KLASTER BANGSALAN</span>
                            <button class="btn-plus-white" data-bs-toggle="modal" data-bs-target="#modalAddKlaster">+</button>
                        </div>
                        <div class="card-body p-0 bg-white text-start">
                            <div class="row g-0">
                                @forelse($summaryKlaster as $k)
                                    <div class="col-12 d-flex justify-content-between align-items-center p-3 border-bottom">
                                        <div class="d-flex align-items-center">
                                            <form action="{{ route('rincian-hpp.delete-label', $k->id) }}" method="POST" class="m-0">@csrf @method('DELETE')
                                                <button class="btn btn-link text-danger p-0 m-0 me-2" onclick="return confirm('Hapus?')"><i class="fas fa-times-circle"></i></button>
                                            </form>
                                            <span class="text-xxs font-bold text-uppercase text-secondary">{{ $k->label }}</span>
                                        </div>
                                        <input type="text" class="input-summary" style="width: 200px;" value="{{ $k->nilai }}" onchange="saveSummary('klaster', '{{ $k->label }}', this.value)">
                                    </div>
                                @empty
                                    <div class="p-4 text-center text-slate-400 w-100 italic">Belum ada kategori klaster bangsalan.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- MODALS -->
        <!-- Add Row Modal -->
        <div class="modal fade" id="addModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><form action="{{ route('rincian-hpp.store') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">@csrf<div class="modal-body p-4"><h6 class="font-bold text-dark mb-4 text-center">TAMBAH BARIS BARU</h6><div class="row g-3"><div class="col-6"><label class="small font-bold">TANGGAL</label><input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required></div><div class="col-6"><label class="small font-bold">TAG ID</label><input type="text" name="tag" class="form-control" placeholder="Misal: BB-01"></div><div class="col-12"><label class="small font-bold">SUPPLIER</label><input type="text" name="keterangan" class="form-control" required></div><div class="col-6"><label class="small font-bold">JENIS</label><input type="text" name="jenis" class="form-control" required></div><div class="col-6"><label class="small font-bold">KLASTER</label><input type="text" name="klaster" class="form-control" required></div><div class="col-6"><label class="small font-bold">HARGA AWAL</label><input type="number" name="harga_awal" class="form-control" required></div><div class="col-6"><label class="small font-bold">QTY</label><input type="number" name="qty_awal" class="form-control" required></div></div><button type="submit" class="btn btn-primary w-100 rounded-pill shadow mt-4 border-0 py-2 font-bold">SIMPAN DATA</button></div></form></div></div>
        
        <!-- Add Summary Modals -->
        <div class="modal fade" id="modalAddStock" tabindex="-1"><div class="modal-dialog modal-sm modal-dialog-centered"><form action="{{ route('rincian-hpp.add-label') }}" method="POST" class="modal-content border-0 shadow" style="border-radius: 15px;">@csrf <input type="hidden" name="tipe" value="stock"><div class="modal-body p-4 text-center"><h6 class="font-bold text-xs mb-3 uppercase">Tambah Jenis</h6><input type="text" name="label" class="form-control mb-3 text-uppercase" required autofocus><button type="submit" class="btn btn-dark btn-sm w-100 rounded-pill font-bold border-0">TAMBAH</button></div></form></div></div>
        <div class="modal fade" id="modalAddKlaster" tabindex="-1"><div class="modal-dialog modal-sm modal-dialog-centered"><form action="{{ route('rincian-hpp.add-label') }}" method="POST" class="modal-content border-0 shadow" style="border-radius: 15px;">@csrf <input type="hidden" name="tipe" value="klaster"><div class="modal-body p-4 text-center"><h6 class="font-bold text-xs mb-3 uppercase">Tambah Klaster</h6><input type="text" name="label" class="form-control mb-3 text-uppercase" required autofocus><button type="submit" class="btn btn-secondary btn-sm w-100 rounded-pill font-bold border-0">TAMBAH</button></div></form></div></div>

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