<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Google Font: Inter (Standar Dashboard Profesional) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Base Setup */
        .main-content { 
            background-color: #f8f9fc; 
            min-height: 100vh; 
            font-family: 'Inter', sans-serif; /* Ganti font ke Inter */
            color: #2d3436;
        }

        /* Card & UI */
        .card { border: none; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.02); background: #fff; }
        
        /* Table Styling - Lebih kecil & Padat */
        .table-hpp thead th { 
            background-color: #344767; 
            color: #ffffff; 
            font-weight: 700; 
            font-size: 11px; /* Perkecil font header */
            text-transform: uppercase; 
            border: none !important; 
            padding: 10px !important;
            vertical-align: middle;
            letter-spacing: 0.5px;
        }
        .table-hpp tbody td { 
            font-size: 12.5px; /* Perkecil font isi */
            border-bottom: 1px solid #f1f3f5 !important; 
            vertical-align: middle; 
            color: #4a5568;
            padding: 0 !important; 
        }

        /* Sticky Logic */
        .sticky-col { position: sticky; left: 0; background-color: #ffffff !important; z-index: 10; border-right: 1px solid #edf2f7 !important; }
        .sticky-label { position: sticky; left: 0; z-index: 10; background-color: #f8f9fa !important; font-size: 12px; }

        /* TAG Badge - Lebih Elegan */
        .tag-badge { 
            background: #e9f2ff; 
            color: #3a86ff; 
            font-weight: 700; 
            padding: 3px 8px; 
            border-radius: 4px; 
            font-size: 10.5px; 
        }

        /* Action Buttons - Ikon Diperkecil */
        .btn-action-edit { background-color: #fbcf33; color: #fff; border-radius: 6px; padding: 5px 8px; border: none; font-size: 12px; transition: 0.2s; }
        .btn-action-delete { background-color: #f5365c; color: #fff; border-radius: 6px; padding: 5px 8px; border: none; font-size: 12px; transition: 0.2s; }
        .btn-action-edit:hover, .btn-action-delete:hover { opacity: 0.9; transform: scale(1.05); }
        .btn-action-edit i, .btn-action-delete i { font-size: 12px; } /* Ikon diperkecil */

        /* Input Sel */
        .input-cell { 
            width: 100%; 
            height: 40px; 
            border: none; 
            background: transparent; 
            text-align: center; 
            font-weight: 600; 
            color: #2d3748; 
            font-size: 12.5px;
            transition: 0.2s; 
        }
        .input-cell:focus { outline: none; background: #f7fafc; box-shadow: inset 0 0 0 2px #5e72e4; border-radius: 4px; }

        /* Sidebar Manual */
        .input-sidebar { 
            border: 1px solid #e2e8f0; 
            background: #fff; 
            text-align: right; 
            width: 80px; 
            font-weight: 700; 
            font-size: 12px; 
            padding: 4px 8px; 
            border-radius: 6px; 
        }
        .btn-plus-header { 
            background: #fff; 
            color: #344767; 
            border: none; 
            border-radius: 5px; 
            width: 26px; 
            height: 26px; 
            font-weight: 800; 
            font-size: 16px; 
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); 
        }

        /* Utility */
        .bg-light-yellow { background-color: #fffdf5 !important; }
        .btn-header { font-weight: 700; text-transform: uppercase; font-size: 11.5px; border-radius: 50px; padding: 8px 18px; }
        .text-xxs { font-size: 10px !important; font-weight: 700; }
    </style>

    <div class="container-fluid py-4 text-start">
        
        <!-- HEADER -->
        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                <h4 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.5px;">Rincian HPP & Stok</h4>
                <p class="text-secondary mb-0" style="font-size: 13px;">Hasanah Farm • Dashboard Manajemen Keuangan</p>
            </div>
            <div class="col-md-6 text-md-end d-flex justify-content-md-end gap-2 mt-3">
                <a href="{{ route('neraca.index') }}" class="btn btn-outline-primary btn-header bg-white shadow-sm">
                    <i class="fas fa-arrow-left me-2" style="font-size: 11px;"></i>Neraca
                </a>
                <form action="{{ route('rincian-hpp.tambah-bulan') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-dark btn-header bg-white shadow-sm">
                        <i class="fas fa-calendar-plus me-2" style="font-size: 11px;"></i>+ Bulan
                    </button>
                </form>
                <button class="btn btn-primary btn-header shadow" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fas fa-plus me-2" style="font-size: 11px;"></i>Baris Baru
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success text-white border-0 shadow-sm mb-4 py-2 px-3" style="border-radius: 10px; font-size: 13px;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <!-- TABEL UTAMA -->
            <div class="col-lg-9 col-12 mb-4">
                <div class="card shadow-sm border-0 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hpp mb-0 align-items-center text-center">
                            <thead>
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Tanggal</th>
                                    <th rowspan="2" class="sticky-col">Keterangan</th>
                                    <th rowspan="2">Jenis</th>
                                    <th rowspan="2">TAG</th>
                                    <th rowspan="2">Aksi</th>
                                    <th colspan="2" class="bg-light-yellow text-dark border-start">Stok Awal</th>
                                    @foreach($bulanList as $bulan)
                                        <th colspan="2" class="border-start">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('M-y') }}</th>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th class="bg-light-yellow text-xxs text-dark">Harga</th>
                                    <th class="bg-light-yellow text-xxs text-dark">Qty</th>
                                    @foreach($bulanList as $bulan)
                                        <th class="text-xxs">Harga</th>
                                        <th class="text-xxs">Qty</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stok as $index => $item)
                                <tr>
                                    <td class="text-center font-weight-bold" style="color:#adb5bd;">{{ $index + 1 }}</td>
                                    <td class="text-center px-2" style="font-size: 11px;">{{ $item->tanggal->format('d/m/y') }}</td>
                                    <td class="sticky-col fw-bold px-3 text-uppercase text-dark text-start" style="font-size: 12px;">{{ $item->keterangan }}</td>
                                    <td class="text-uppercase text-secondary px-2" style="font-size: 10px;">{{ $item->jenis }}</td>
                                    <td><span class="tag-badge">{{ $item->tag ?? '-' }}</span></td>
                                    
                                    <td class="px-2">
                                        <div class="d-flex justify-content-center gap-1">
                                            <button class="btn-action-edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">
                                                <i class="fas fa-pen-to-square"></i>
                                            </button>
                                            <form action="{{ route('rincian-hpp.destroy', $item->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-action-delete" onclick="return confirm('Hapus baris ini?')">
                                                    <i class="fas fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>

                                    <td class="text-end px-3 font-weight-bold bg-light-yellow text-dark" style="font-size: 12.5px;">{{ number_format($item->harga_awal, 0, ',', '.') }}</td>
                                    <td class="text-center font-weight-bold bg-light-yellow text-dark" style="font-size: 12.5px;">{{ $item->qty_awal }}</td>
                                    
                                    @foreach($bulanList as $bulan)
                                        @php $det = $item->rincian_bulanan->where('bulan', $bulan)->first(); @endphp
                                        <td class="border-start">
                                            <input type="number" class="input-cell" value="{{ $det ? (int)$det->harga_update : '' }}" onchange="updateCell('{{ $item->id }}', '{{ $bulan }}', 'harga_update', this.value)">
                                        </td>
                                        <td>
                                            <input type="number" class="input-cell" value="{{ $det ? $det->qty_update : '' }}" onchange="updateCell('{{ $item->id }}', '{{ $bulan }}', 'qty_update', this.value)">
                                        </td>
                                    @endforeach
                                </tr>

                                <!-- MODAL EDIT -->
                                <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form action="{{ route('rincian-hpp.update-induk', $item->id) }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">
                                            @csrf @method('PUT')
                                            <div class="modal-body p-4 text-start">
                                                <h6 class="fw-bold text-primary mb-3 text-uppercase" style="letter-spacing: 1px;">Edit Data</h6>
                                                <div class="row g-3">
                                                    <div class="col-6"><label class="text-xxs">TANGGAL</label><input type="date" name="tanggal" class="form-control form-control-sm" value="{{ $item->tanggal->format('Y-m-d') }}"></div>
                                                    <div class="col-6"><label class="text-xxs">TAG ID</label><input type="text" name="tag" class="form-control form-control-sm" value="{{ $item->tag }}"></div>
                                                    <div class="col-12"><label class="text-xxs text-uppercase">Supplier</label><input type="text" name="keterangan" class="form-control form-control-sm text-uppercase" value="{{ $item->keterangan }}"></div>
                                                    <div class="col-6"><label class="text-xxs text-uppercase">Jenis</label><input type="text" name="jenis" class="form-control form-control-sm text-uppercase" value="{{ $item->jenis }}"></div>
                                                    <div class="col-6"><label class="text-xxs text-uppercase">Klaster</label><input type="text" name="klaster" class="form-control form-control-sm text-uppercase" value="{{ $item->klaster }}"></div>
                                                    <div class="col-6"><label class="text-xxs text-uppercase">Modal</label><input type="number" name="harga_awal" class="form-control form-control-sm" value="{{ (int)$item->harga_awal }}"></div>
                                                    <div class="col-6"><label class="text-xxs text-uppercase">Qty</label><input type="number" name="qty_awal" class="form-control form-control-sm" value="{{ $item->qty_awal }}"></div>
                                                </div>
                                                <div class="mt-4 d-flex gap-2">
                                                    <button type="button" class="btn btn-light w-100 rounded-pill text-xs font-weight-bold" data-bs-dismiss="modal">BATAL</button>
                                                    <button type="submit" class="btn btn-primary w-100 rounded-pill shadow text-xs font-weight-bold">SIMPAN</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @empty
                                <tr><td colspan="100" class="text-center py-5 text-secondary">Belum ada data baris.</td></tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-total">
                                <tr>
                                    <td colspan="6" class="text-center text-uppercase fw-bold sticky-label py-3">TOTAL KESELURUHAN</td>
                                    <td class="text-end px-3">{{ number_format($stok->sum('harga_awal'), 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $stok->sum('qty_awal') }}</td>
                                    @foreach($bulanList as $bulan)
                                        @php
                                            $tH = 0; $tQ = 0;
                                            foreach($stok as $s) {
                                                $d = $s->rincian_bulanan->where('bulan', $bulan)->first();
                                                if($d) { $tH += $d->harga_update; $tQ += $d->qty_update; }
                                            }
                                        @endphp
                                        <td class="text-center border-start text-primary" style="font-size: 13px;">{{ number_format($tH, 0, ',', '.') }}</td>
                                        <td class="text-center text-primary" style="font-size: 13px;">{{ $tQ }}</td>
                                    @endforeach
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR SUMMARY -->
            <div class="col-lg-3 col-12">
                <div class="card shadow-sm mb-4 border-0 overflow-hidden">
                    <div class="card-header bg-dark p-3 d-flex justify-content-between align-items-center">
                        <h6 class="text-white mb-0 text-xs font-weight-bold text-uppercase">Stock Kandang</h6>
                        <button class="btn-plus-header" data-bs-toggle="modal" data-bs-target="#modalAddStock">+</button>
                    </div>
                    <div class="card-body p-0">
                        @forelse($summaryStock as $s)
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                <form action="{{ route('rincian-hpp.delete-label', $s->id) }}" method="POST" class="m-0">@csrf @method('DELETE')
                                    <button class="btn btn-link text-danger p-0 m-0 me-2" style="font-size: 11px;"><i class="fas fa-trash-can"></i></button>
                                </form>
                                <span class="text-xxs font-weight-bold text-uppercase flex-grow-1" style="color: #4a5568;">{{ $s->label }}</span>
                                <input type="text" class="input-sidebar" value="{{ $s->nilai }}" onchange="saveSummary('stock', '{{ $s->label }}', this.value)">
                            </div>
                        @empty
                            <div class="p-4 text-center text-xxs text-secondary italic">Klik (+) untuk tambah jenis</div>
                        @endforelse
                    </div>
                </div>

                <div class="card shadow-sm mb-4 border-0 overflow-hidden">
                    <div class="card-header bg-secondary p-3 d-flex justify-content-between align-items-center">
                        <h6 class="text-white mb-0 text-xs font-weight-bold text-uppercase">Klaster Bangsalan</h6>
                        <button class="btn-plus-header" data-bs-toggle="modal" data-bs-target="#modalAddKlaster">+</button>
                    </div>
                    <div class="card-body p-0">
                        @forelse($summaryKlaster as $k)
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                <form action="{{ route('rincian-hpp.delete-label', $k->id) }}" method="POST" class="m-0">@csrf @method('DELETE')
                                    <button class="btn btn-link text-danger p-0 m-0 me-2" style="font-size: 11px;"><i class="fas fa-trash-can"></i></button>
                                </form>
                                <span class="text-xxs font-weight-bold text-uppercase flex-grow-1" style="color: #4a5568;">{{ $k->label }}</span>
                                <input type="text" class="input-sidebar" style="width: 100px;" value="{{ $k->nilai }}" onchange="saveSummary('klaster', '{{ $k->label }}', this.value)">
                            </div>
                        @empty
                            <div class="p-4 text-center text-xxs text-secondary italic">Klik (+) untuk tambah klaster</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH BARIS -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('rincian-hpp.store') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">
                @csrf
                <div class="modal-body p-4 text-start">
                    <h6 class="fw-bold text-primary mb-4 text-center text-uppercase" style="letter-spacing: 1px;">Tambah Stok Baru</h6>
                    <div class="row g-3">
                        <div class="col-6"><label class="text-xxs">TANGGAL</label><input type="date" name="tanggal" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" required></div>
                        <div class="col-6"><label class="text-xxs">TAG ID</label><input type="text" name="tag" class="form-control form-control-sm" placeholder="Contoh: BB-01"></div>
                        <div class="col-12"><label class="text-xxs text-uppercase">Supplier</label><input type="text" name="keterangan" class="form-control form-control-sm text-uppercase" required></div>
                        <div class="col-6"><label class="text-xxs text-uppercase">Jenis</label><input type="text" name="jenis" class="form-control form-control-sm text-uppercase" placeholder="Merino" required></div>
                        <div class="col-6"><label class="text-xxs text-uppercase">Klaster</label><input type="text" name="klaster" class="form-control form-control-sm text-uppercase" placeholder="Marto" required></div>
                        <div class="col-6"><label class="text-xxs text-uppercase">Harga Modal</label><input type="number" name="harga_awal" class="form-control form-control-sm" required></div>
                        <div class="col-6"><label class="text-xxs text-uppercase">Qty</label><input type="number" name="qty_awal" class="form-control form-control-sm" required></div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="button" class="btn btn-light w-100 rounded-pill text-xs font-weight-bold" data-bs-dismiss="modal">BATAL</button>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill shadow text-xs font-weight-bold">SIMPAN</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tambah Label Sidebar -->
    <div class="modal fade" id="modalAddStock" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <form action="{{ route('rincian-hpp.add-label') }}" method="POST" class="modal-content border-0 shadow" style="border-radius: 12px;">
                @csrf <input type="hidden" name="tipe" value="stock">
                <div class="modal-body p-4">
                    <h6 class="fw-bold text-xs mb-3 text-center text-uppercase">Tambah Jenis</h6>
                    <input type="text" name="label" class="form-control form-control-sm mb-3 text-uppercase" required autofocus>
                    <button type="submit" class="btn btn-dark btn-sm w-100 rounded-pill fw-bold">TAMBAH</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalAddKlaster" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <form action="{{ route('rincian-hpp.add-label') }}" method="POST" class="modal-content border-0 shadow" style="border-radius: 12px;">
                @csrf <input type="hidden" name="tipe" value="klaster">
                <div class="modal-body p-4">
                    <h6 class="fw-bold text-xs mb-3 text-center text-uppercase">Tambah Klaster</h6>
                    <input type="text" name="label" class="form-control form-control-sm mb-3 text-uppercase" required autofocus>
                    <button type="submit" class="btn btn-secondary btn-sm w-100 rounded-pill fw-bold">TAMBAH</button>
                </div>
            </form>
        </div>
    </div>

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