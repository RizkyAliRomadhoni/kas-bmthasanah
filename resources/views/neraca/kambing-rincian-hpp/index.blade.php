<x-app-layout>
     <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- CSS Internal untuk Dashboard Modern -->
    <style>
        .main-content { background-color: #f4f7fe; min-height: 100vh; }
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }

        /* Tabel Excel-Web Style */
        .table-hpp thead th { 
            background-color: #344767; 
            color: #ffffff; 
            font-weight: 700; 
            font-size: 0.65rem; 
            text-transform: uppercase; 
            border: 1px solid #4b5b7c !important; 
            vertical-align: middle; 
        }
        .table-hpp tbody td { 
            font-size: 0.72rem; 
            border: 1px solid #e9ecef !important; 
            vertical-align: middle; 
            padding: 0 !important; 
        }
        .table-hpp tfoot td {
            font-size: 0.75rem;
            font-weight: 800;
            background-color: #f8f9fa !important;
            border: 1px solid #e9ecef !important;
            color: #344767;
            padding: 12px 5px !important;
        }
        
        /* Kolom Nama Supplier Lengket (Sticky) */
        .sticky-col { 
            position: sticky; 
            left: 0; 
            background-color: #ffffff !important; 
            z-index: 10; 
            border-right: 2px solid #e9ecef !important;
        }
        .sticky-label { position: sticky; left: 0; z-index: 10; background-color: #f8f9fa !important; }

        /* Input Sel Tabel */
        .input-cell { 
            width: 100%; 
            height: 38px;
            border: none; 
            background: transparent; 
            text-align: center; 
            font-size: 0.75rem; 
            font-weight: 600; 
            color: #344767;
            transition: 0.2s;
        }
        .input-cell:focus { 
            outline: none; 
            background: #eef1ff; 
            box-shadow: inset 0 0 0 1px #5e72e4; 
        }

        /* Sidebar Manual Input */
        .input-sidebar { 
            border: none; 
            background: #f4f7fe; 
            text-align: right; 
            width: 80px; 
            font-weight: bold; 
            font-size: 11px; 
            padding: 4px 8px; 
            border-radius: 6px; 
        }
        .input-sidebar:focus { outline: 1px solid #5e72e4; background: white; }

        /* PERBAIKAN TOMBOL (+) DI SIDEBAR */
        .btn-plus-manual { 
            background-color: #ffffff !important; 
            color: #344767 !important; 
            border: none; 
            border-radius: 8px; 
            width: 30px; 
            height: 30px; 
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            font-size: 18px; 
            font-weight: 900;
            line-height: 1;
        }
        .btn-plus-manual:hover { background-color: #f2f2f2 !important; transform: scale(1.1); }

        .bg-light-warning { background-color: #fff9e6 !important; }
        .text-xxs { font-size: 0.65rem !important; }
    </style>

    <main class="main-content">
        <div class="container-fluid py-4 text-start">

            <!-- HEADER SECTION -->
            <div class="row align-items-center mb-4">
                <div class="col-md-6">
                    <h4 class="fw-bold mb-0 text-primary text-uppercase">Rincian HPP & Stok Bulanan</h4>
                    <p class="text-sm text-secondary mb-0 font-weight-bold">Hasanah Farm • Sinkronisasi Data Excel</p>
                </div>
                <div class="col-md-6 text-md-end text-center mt-3 mt-md-0 d-flex justify-content-md-end gap-2 flex-wrap">
                    <a href="{{ route('neraca.index') }}" class="btn btn-sm btn-outline-secondary shadow-sm rounded-pill px-4 fw-bold">
                        <i class="fas fa-arrow-left me-2"></i>NERACA
                    </a>
                    <form action="{{ route('rincian-hpp.tambah-bulan') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-primary shadow-sm rounded-pill px-4 fw-bold">
                            + BULAN
                        </button>
                    </form>
                    <button class="btn btn-sm btn-primary shadow-sm rounded-pill px-4 fw-bold" data-bs-toggle="modal" data-bs-target="#addModal">
                        + BARIS BARU
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success text-white text-sm rounded-4 mb-4 border-0 shadow-sm px-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <!-- TABEL UTAMA (COL 9) -->
                <div class="col-lg-9 col-12 mb-4">
                    <div class="card shadow-sm border-0 border-radius-xl overflow-hidden">
                        <div class="table-responsive">
                            <table class="table table-hpp mb-0 align-items-center text-center">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="px-2" style="width: 40px;">No</th>
                                        <th rowspan="2" style="min-width: 90px;">Tanggal</th>
                                        <th rowspan="2" class="sticky-col">Keterangan</th>
                                        <th rowspan="2" style="min-width: 100px;">Jenis</th>
                                        <th rowspan="2" class="bg-primary text-white" style="width: 50px;">TAG</th>
                                        <th rowspan="2" style="min-width: 80px;">Aksi</th>
                                        <th colspan="2" class="bg-light-warning text-dark font-weight-bold border-start">Stok Awal</th>
                                        @foreach($bulanList as $bulan)
                                            <th colspan="2" class="bg-light text-dark border-start">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('M-y') }}</th>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <th class="py-2 bg-light-warning text-xxs">Harga</th>
                                        <th class="py-2 bg-light-warning text-xxs">Qty</th>
                                        @foreach($bulanList as $bulan)
                                            <th class="py-2 bg-light border-start text-xxs">Harga</th>
                                            <th class="py-2 bg-light text-xxs">Qty</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stok as $index => $item)
                                    <tr>
                                        <td class="text-center font-weight-bold text-secondary">{{ $index + 1 }}</td>
                                        <td class="text-center text-xs px-2">{{ $item->tanggal->format('d/m/y') }}</td>
                                        <td class="sticky-col fw-bold px-3 text-uppercase text-dark text-start">{{ $item->keterangan }}</td>
                                        <td class="px-2 text-uppercase text-secondary" style="font-size: 10px;">{{ $item->jenis }}</td>
                                        <td class="text-center fw-bold text-primary">{{ $item->tag ?? '-' }}</td>
                                        
                                        <!-- Tombol Aksi -->
                                        <td class="text-center px-2">
                                            <div class="d-flex justify-content-center gap-1">
                                                <button type="button" class="btn btn-link text-warning p-0 mb-0" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('rincian-hpp.destroy', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger p-0 mb-0" onclick="return confirm('Hapus baris data ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>

                                        <td class="text-end px-2 font-weight-bold text-dark border-start" style="background-color: #fffdf5;">{{ number_format($item->harga_awal, 0, ',', '.') }}</td>
                                        <td class="text-center font-weight-bold text-dark" style="background-color: #fffdf5;">{{ $item->qty_awal }}</td>
                                        
                                        @foreach($bulanList as $bulan)
                                            @php $det = $item->rincian_bulanan->where('bulan', $bulan)->first(); @endphp
                                            <td class="border-start">
                                                <input type="number" class="input-cell" value="{{ $det ? (int)$det->harga_update : '' }}"
                                                    onchange="updateCell('{{ $item->id }}', '{{ $bulan }}', 'harga_update', this.value)">
                                            </td>
                                            <td>
                                                <input type="number" class="input-cell" value="{{ $det ? $det->qty_update : '' }}"
                                                    onchange="updateCell('{{ $item->id }}', '{{ $bulan }}', 'qty_update', this.value)">
                                            </td>
                                        @endforeach
                                    </tr>

                                    <!-- MODAL EDIT BARIS (Diletakkan di dalam perulangan agar ID cocok) -->
                                    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <form action="{{ route('rincian-hpp.update-induk', $item->id) }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                                                @csrf @method('PUT')
                                                <div class="modal-body p-4 text-start">
                                                    <h5 class="fw-bold text-primary mb-3 text-center uppercase">Edit Data Baris</h5>
                                                    <div class="row g-3">
                                                        <div class="col-6"><label class="text-xxs fw-bold">TANGGAL</label><input type="date" name="tanggal" class="form-control" value="{{ $item->tanggal->format('Y-m-d') }}"></div>
                                                        <div class="col-6"><label class="text-xxs fw-bold">TAG ID</label><input type="text" name="tag" class="form-control" value="{{ $item->tag }}"></div>
                                                        <div class="col-12"><label class="text-xxs fw-bold text-uppercase">Supplier</label><input type="text" name="keterangan" class="form-control text-uppercase" value="{{ $item->keterangan }}"></div>
                                                        <div class="col-6"><label class="text-xxs fw-bold text-uppercase">Jenis</label><input type="text" name="jenis" class="form-control text-uppercase" value="{{ $item->jenis }}"></div>
                                                        <div class="col-6"><label class="text-xxs fw-bold text-uppercase">Klaster</label><input type="text" name="klaster" class="form-control text-uppercase" value="{{ $item->klaster }}"></div>
                                                        <div class="col-6"><label class="text-xxs fw-bold text-uppercase">Modal</label><input type="number" name="harga_awal" class="form-control" value="{{ (int)$item->harga_awal }}"></div>
                                                        <div class="col-6"><label class="text-xxs fw-bold text-uppercase">Qty</label><input type="number" name="qty_awal" class="form-control" value="{{ $item->qty_awal }}"></div>
                                                    </div>
                                                    <div class="mt-4 d-flex gap-2">
                                                        <button type="button" class="btn btn-light w-100 rounded-pill fw-bold text-xs" data-bs-dismiss="modal">BATAL</button>
                                                        <button type="submit" class="btn btn-primary w-100 rounded-pill shadow fw-bold text-xs uppercase">Simpan Perubahan</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    @empty
                                    <tr><td colspan="100" class="text-center py-5">Belum ada data baris. Klik "+ Baris Baru".</td></tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6" class="text-center text-uppercase fw-bold sticky-label py-3">TOTAL KESELURUHAN</td>
                                        <td class="text-end px-2 bg-light-warning">{{ number_format($stok->sum('harga_awal'), 0, ',', '.') }}</td>
                                        <td class="text-center bg-light-warning">{{ $stok->sum('qty_awal') }}</td>
                                        @foreach($bulanList as $bulan)
                                            @php
                                                $tH = 0; $tQ = 0;
                                                foreach($stok as $s) {
                                                    $d = $s->rincian_bulanan->where('bulan', $bulan)->first();
                                                    if($d) { $tH += $d->harga_update; $tQ += $d->qty_update; }
                                                }
                                            @endphp
                                            <td class="text-center border-start font-weight-bold text-primary">{{ number_format($tH, 0, ',', '.') }}</td>
                                            <td class="text-center font-weight-bold text-primary">{{ $tQ }}</td>
                                        @endforeach
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- SIDEBAR SUMMARY (KANAN - COL 3) -->
                <div class="col-lg-3 col-12">
                    <!-- CARD STOCK -->
                    <div class="card shadow-sm border-0 mb-4 overflow-hidden bg-white">
                        <div class="card-header bg-dark p-3 d-flex justify-content-between align-items-center">
                            <h6 class="text-white mb-0 text-xs fw-bold text-uppercase">Stock Kandang</h6>
                            <!-- Tombol Plus Putih Kotak -->
                            <button type="button" class="btn-plus-manual" data-bs-toggle="modal" data-bs-target="#modalAddStock">+</button>
                        </div>
                        <div class="card-body p-0">
                            @forelse($summaryStock as $s)
                                <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                                    <form action="{{ route('rincian-hpp.delete-label', $s->id) }}" method="POST" class="m-0">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-link text-danger p-0 m-0 me-2" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                    <span class="text-xxs font-weight-bold text-uppercase text-secondary flex-grow-1">{{ $s->label }}</span>
                                    <input type="text" class="input-sidebar" value="{{ $s->nilai }}" onchange="saveSummary('stock', '{{ $s->label }}', this.value)">
                                </div>
                            @empty
                                <div class="p-3 text-center text-xxs text-secondary italic">Klik (+) untuk tambah jenis</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- CARD KLASTER -->
                    <div class="card shadow-sm border-0 mb-4 overflow-hidden bg-white">
                        <div class="card-header bg-secondary p-3 d-flex justify-content-between align-items-center">
                            <h6 class="text-white mb-0 text-xs fw-bold text-uppercase">Klaster Bangsalan</h6>
                            <!-- Tombol Plus Putih Kotak -->
                            <button type="button" class="btn-plus-manual" data-bs-toggle="modal" data-bs-target="#modalAddKlaster">+</button>
                        </div>
                        <div class="card-body p-0">
                            @forelse($summaryKlaster as $k)
                                <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                                    <form action="{{ route('rincian-hpp.delete-label', $k->id) }}" method="POST" class="m-0">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-link text-danger p-0 m-0 me-2" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                    <span class="text-xxs font-weight-bold text-uppercase text-secondary flex-grow-1">{{ $k->label }}</span>
                                    <input type="text" class="input-sidebar" style="width: 100px;" value="{{ $k->nilai }}" onchange="saveSummary('klaster', '{{ $k->label }}', this.value)">
                                </div>
                            @empty
                                <div class="p-3 text-center text-xxs text-secondary italic">Klik (+) untuk tambah klaster</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL TAMBAH BARIS UTAMA -->
        <div class="modal fade" id="addModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('rincian-hpp.store') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                    @csrf
                    <div class="modal-body p-4 text-start">
                        <h5 class="fw-bold text-primary mb-3 text-center uppercase">Tambah Stok Baru</h5>
                        <div class="row g-2">
                            <div class="col-6"><label class="text-xxs fw-bold">TANGGAL</label><input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                            <div class="col-6"><label class="text-xxs fw-bold">TAG ID</label><input type="text" name="tag" class="form-control" placeholder="BB-01"></div>
                            <div class="col-12"><label class="text-xxs fw-bold">SUPPLIER</label><input type="text" name="keterangan" class="form-control text-uppercase" required></div>
                            <div class="col-6"><label class="text-xxs fw-bold">JENIS</label><input type="text" name="jenis" class="form-control text-uppercase" placeholder="Merino" required></div>
                            <div class="col-6"><label class="text-xxs fw-bold">KLASTER</label><input type="text" name="klaster" class="form-control text-uppercase" placeholder="Marto" required></div>
                            <div class="col-6"><label class="text-xxs fw-bold text-uppercase">Harga Modal</label><input type="number" name="harga_awal" class="form-control" required></div>
                            <div class="col-6"><label class="text-xxs fw-bold text-uppercase">Qty Awal</label><input type="number" name="qty_awal" class="form-control" required></div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill shadow mt-4 fw-bold uppercase">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Tambah Label Sidebar -->
        <div class="modal fade" id="modalAddStock" tabindex="-1">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <form action="{{ route('rincian-hpp.add-label') }}" method="POST" class="modal-content" style="border-radius: 15px;">
                    @csrf <input type="hidden" name="tipe" value="stock">
                    <div class="modal-body p-3 text-start">
                        <h6 class="fw-bold text-xs mb-3 text-center uppercase">Tambah Jenis Stok</h6>
                        <input type="text" name="label" class="form-control form-control-sm mb-3 text-uppercase" placeholder="Contoh: MERINO" required autofocus>
                        <button type="submit" class="btn btn-dark btn-sm w-100 rounded-pill fw-bold">TAMBAH</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="modalAddKlaster" tabindex="-1">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <form action="{{ route('rincian-hpp.add-label') }}" method="POST" class="modal-content" style="border-radius: 15px;">
                    @csrf <input type="hidden" name="tipe" value="klaster">
                    <div class="modal-body p-3 text-start">
                        <h6 class="fw-bold text-xs mb-3 text-center uppercase">Tambah Klaster</h6>
                        <input type="text" name="label" class="form-control form-control-sm mb-3 text-uppercase" placeholder="Contoh: MARTO" required autofocus>
                        <button type="submit" class="btn btn-secondary btn-sm w-100 rounded-pill fw-bold">TAMBAH</button>
                    </div>
                </form>
            </div>
        </div>

    </main>

    <!-- AJAX SCRIPTS -->
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