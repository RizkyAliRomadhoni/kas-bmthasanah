<x-app-layout>
     <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body, .main-content { font-family: 'Inter', sans-serif; background-color: #f4f6f9; color: #334155; }
        
        /* Container & Card */
        .card-table { border: none; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); background: #fff; overflow: hidden; }
        
        /* Fix Table Layout */
        .table-hpp { width: 100%; border-collapse: collapse; table-layout: fixed; }
        
        /* Header Styling - Fix Warna Teks & Border */
        .table-hpp thead th { 
            background-color: #1e293b !important; 
            color: #ffffff !important; 
            font-size: 11px; 
            font-weight: 700; 
            padding: 12px 8px; 
            border: 1px solid #334155 !important;
            text-transform: uppercase;
        }

        /* Body Styling */
        .table-hpp tbody td { 
            font-size: 13px; 
            border: 1px solid #e2e8f0 !important; 
            color: #334155 !important;
            height: 45px;
            background: #fff;
        }

        /* Sticky Columns - Fix Background Agar Tidak Transparan */
        .sticky-col-id { position: sticky; left: 0; z-index: 11; background-color: #1e293b !important; }
        .sticky-col-ket { position: sticky; left: 40px; z-index: 11; background-color: #fff !important; text-align: left !important; padding-left: 15px !important; }
        .table-hpp tbody td.sticky-col-ket { background-color: #ffffff !important; border-right: 2px solid #e2e8f0 !important; }

        /* Input Interaction */
        .input-cell { 
            width: 100%; height: 100%; border: none; background: transparent; 
            text-align: center; font-weight: 600; color: #1e293b; 
        }
        .input-cell:focus { outline: none; background-color: #f0f7ff; box-shadow: inset 0 0 0 2px #3b82f6; }

        /* Sidebar Manual */
        .input-sidebar { border: 1px solid #cbd5e1; text-align: right; width: 80px; font-weight: 600; padding: 4px; border-radius: 4px; }
        .card-header-dark { background-color: #1e293b; color: #fff; padding: 12px; }

        /* Action Buttons */
        .btn-edit { background: #fef9c3; color: #854d0e; padding: 5px 8px; border-radius: 6px; border: none; }
        .btn-delete { background: #fee2e2; color: #991b1b; padding: 5px 8px; border-radius: 6px; border: none; }
        
        /* Tombol + Putih */
        .btn-plus-manual { background: #fff !important; color: #000 !important; width: 25px; height: 25px; border-radius: 4px; border: none; font-weight: 900; }

        .bg-stok-awal { background-color: #fffbeb !important; }
    </style>

    <div class="container-fluid py-4">
        
        <!-- HEADER & NAV -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-0">RINCIAN HPP & STOK BULANAN</h4>
                <p class="text-secondary mb-0" style="font-size: 13px;">Hasanah Farm • Periode Aktif</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('neraca.index') }}" class="btn btn-white shadow-sm border-0 rounded-pill px-4" style="font-size: 12px; font-weight: 700;">
                    <i class="fas fa-arrow-left me-2"></i> NERACA
                </a>
                <form action="{{ route('rincian-hpp.tambah-bulan') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-white text-primary shadow-sm border-0 rounded-pill px-4" style="font-size: 12px; font-weight: 700;">
                        <i class="fas fa-calendar-plus me-2"></i> + BULAN
                    </button>
                </form>
                <button class="btn btn-primary shadow-sm border-0 rounded-pill px-4" style="font-size: 12px; font-weight: 700;" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fas fa-plus me-2"></i> BARIS BARU
                </button>
            </div>
        </div>

        <div class="row">
            <!-- TABEL UTAMA -->
            <div class="col-lg-9 col-12 mb-4">
                <div class="card card-table">
                    <div class="table-responsive">
                        <table class="table-hpp">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="sticky-col-id" style="width: 40px;">ID</th>
                                    <th rowspan="2" style="width: 90px;">Tanggal</th>
                                    <th rowspan="2" class="sticky-col-ket" style="width: 180px;">Supplier / Keterangan</th>
                                    <th rowspan="2" style="width: 100px;">Jenis</th>
                                    <th rowspan="2" style="width: 50px;">TAG</th>
                                    <th rowspan="2" style="width: 90px;">Aksi</th>
                                    <th colspan="2" class="bg-stok-awal">STOK AWAL</th>
                                    @foreach($bulanList as $bulan)
                                        <th colspan="2">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('M y') }}</th>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th class="bg-stok-awal" style="font-size: 9px;">HARGA</th>
                                    <th class="bg-stok-awal" style="font-size: 9px;">QTY</th>
                                    @foreach($bulanList as $bulan)
                                        <th style="font-size: 9px;">HARGA</th>
                                        <th style="font-size: 9px;">QTY</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stok as $index => $item)
                                <tr>
                                    <td class="text-center font-weight-bold" style="background: #f8fafc;">{{ $index + 1 }}</td>
                                    <td class="text-center">{{ $item->tanggal->format('d/m/y') }}</td>
                                    <td class="sticky-col-ket fw-bold text-dark">{{ $item->keterangan }}</td>
                                    <td class="text-center text-uppercase" style="font-size: 10px;">{{ $item->jenis }}</td>
                                    <td class="text-center"><span class="badge bg-primary" style="font-size: 10px;">{{ $item->tag ?? '-' }}</span></td>
                                    <td class="text-center px-1">
                                        <div class="d-flex justify-content-center gap-1">
                                            <button class="btn-edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}"><i class="fas fa-pen"></i></button>
                                            <form action="{{ route('rincian-hpp.destroy', $item->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-delete" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="text-end px-2 fw-bold bg-stok-awal">{{ number_format($item->harga_awal, 0, ',', '.') }}</td>
                                    <td class="text-center fw-bold bg-stok-awal">{{ $item->qty_awal }}</td>
                                    
                                    @foreach($bulanList as $bulan)
                                        @php $det = $item->rincian_bulanan->where('bulan', $bulan)->first(); @endphp
                                        <td><input type="number" class="input-cell" value="{{ $det ? (int)$det->harga_update : '' }}" onchange="updateCell('{{ $item->id }}', '{{ $bulan }}', 'harga_update', this.value)"></td>
                                        <td><input type="number" class="input-cell" value="{{ $det ? $det->qty_update : '' }}" onchange="updateCell('{{ $item->id }}', '{{ $bulan }}', 'qty_update', this.value)"></td>
                                    @endforeach
                                </tr>

                                <!-- MODAL EDIT -->
                                <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form action="{{ route('rincian-hpp.update-induk', $item->id) }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                                            @csrf @method('PUT')
                                            <div class="modal-body p-4">
                                                <h6 class="fw-bold mb-4">EDIT DATA BARIS</h6>
                                                <div class="row g-3">
                                                    <div class="col-6"><label class="small fw-bold">TANGGAL</label><input type="date" name="tanggal" class="form-control" value="{{ $item->tanggal->format('Y-m-d') }}"></div>
                                                    <div class="col-6"><label class="small fw-bold">TAG ID</label><input type="text" name="tag" class="form-control" value="{{ $item->tag }}"></div>
                                                    <div class="col-12"><label class="small fw-bold">SUPPLIER</label><input type="text" name="keterangan" class="form-control" value="{{ $item->keterangan }}"></div>
                                                    <div class="col-6"><label class="small fw-bold">JENIS</label><input type="text" name="jenis" class="form-control" value="{{ $item->jenis }}"></div>
                                                    <div class="col-6"><label class="small fw-bold">KLASTER</label><input type="text" name="klaster" class="form-control" value="{{ $item->klaster }}"></div>
                                                    <div class="col-6"><label class="small fw-bold">MODAL</label><input type="number" name="harga_awal" class="form-control" value="{{ (int)$item->harga_awal }}"></div>
                                                    <div class="col-6"><label class="small fw-bold">QTY</label><input type="number" name="qty_awal" class="form-control" value="{{ $item->qty_awal }}"></div>
                                                </div>
                                                <div class="mt-4 d-flex gap-2">
                                                    <button type="button" class="btn btn-light w-100 rounded-pill" data-bs-dismiss="modal">BATAL</button>
                                                    <button type="submit" class="btn btn-primary w-100 rounded-pill shadow">SIMPAN</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @empty
                                <tr><td colspan="100" class="text-center py-5">Belum ada data.</td></tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr style="background: #f8fafc;">
                                    <td colspan="6" class="text-center font-weight-bold py-3 sticky-label">TOTAL KESELURUHAN</td>
                                    <td class="text-end px-2 font-weight-bold">{{ number_format($stok->sum('harga_awal'), 0, ',', '.') }}</td>
                                    <td class="text-center font-weight-bold">{{ $stok->sum('qty_awal') }}</td>
                                    @foreach($bulanList as $bulan)
                                        @php
                                            $tH = 0; $tQ = 0;
                                            foreach($stok as $s) {
                                                $d = $s->rincian_bulanan->where('bulan', $bulan)->first();
                                                if($d) { $tH += $d->harga_update; $tQ += $d->qty_update; }
                                            }
                                        @endphp
                                        <td class="text-end px-2 font-weight-bold text-primary">{{ number_format($tH, 0, ',', '.') }}</td>
                                        <td class="text-center font-weight-bold text-primary">{{ $tQ }}</td>
                                    @endforeach
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR -->
            <div class="col-lg-3 col-12">
                <div class="card card-table mb-4">
                    <div class="card-header-dark d-flex justify-content-between align-items-center">
                        <h6 class="text-white mb-0 small fw-bold">STOCK KANDANG</h6>
                        <button class="btn-plus-manual" data-bs-toggle="modal" data-bs-target="#modalAddStock">+</button>
                    </div>
                    <div class="card-body p-0">
                        @foreach($summaryStock as $s)
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                <form action="{{ route('rincian-hpp.delete-label', $s->id) }}" method="POST" class="m-0">@csrf @method('DELETE')
                                    <button class="btn btn-link text-danger p-0 m-0 me-2"><i class="fas fa-trash-can" style="font-size: 11px;"></i></button>
                                </form>
                                <span class="small fw-bold text-uppercase flex-grow-1">{{ $s->label }}</span>
                                <input type="text" class="input-sidebar" value="{{ $s->nilai }}" onchange="saveSummary('stock', '{{ $s->label }}', this.value)">
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card card-table">
                    <div class="card-header-dark bg-secondary d-flex justify-content-between align-items-center">
                        <h6 class="text-white mb-0 small fw-bold">KLASTER BANGSALAN</h6>
                        <button class="btn-plus-manual" data-bs-toggle="modal" data-bs-target="#modalAddKlaster">+</button>
                    </div>
                    <div class="card-body p-0">
                        @foreach($summaryKlaster as $k)
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                <form action="{{ route('rincian-hpp.delete-label', $k->id) }}" method="POST" class="m-0">@csrf @method('DELETE')
                                    <button class="btn btn-link text-danger p-0 m-0 me-2"><i class="fas fa-trash-can" style="font-size: 11px;"></i></button>
                                </form>
                                <span class="small fw-bold text-uppercase flex-grow-1">{{ $k->label }}</span>
                                <input type="text" class="input-sidebar" style="width: 100px;" value="{{ $k->nilai }}" onchange="saveSummary('klaster', '{{ $k->label }}', this.value)">
                            </div>
                        @endforeach
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
                    <h6 class="fw-bold mb-4">TAMBAH STOK BARU</h6>
                    <div class="row g-3">
                        <div class="col-6"><label class="small fw-bold">TANGGAL</label><input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                        <div class="col-6"><label class="small fw-bold">TAG ID</label><input type="text" name="tag" class="form-control" placeholder="Misal: BB-01"></div>
                        <div class="col-12"><label class="small fw-bold">SUPPLIER</label><input type="text" name="keterangan" class="form-control" required></div>
                        <div class="col-6"><label class="small fw-bold">JENIS</label><input type="text" name="jenis" class="form-control" required></div>
                        <div class="col-6"><label class="small fw-bold">KLASTER</label><input type="text" name="klaster" class="form-control" required></div>
                        <div class="col-6"><label class="small fw-bold">HARGA MODAL</label><input type="number" name="harga_awal" class="form-control" required></div>
                        <div class="col-6"><label class="small fw-bold">QTY AWAL</label><input type="number" name="qty_awal" class="form-control" required></div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill shadow mt-4 fw-bold">SIMPAN DATA</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modals Sidebar -->
    <div class="modal fade" id="modalAddStock" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <form action="{{ route('rincian-hpp.add-label') }}" method="POST" class="modal-content">@csrf <input type="hidden" name="tipe" value="stock">
                <div class="modal-body p-4 text-start"><h6 class="fw-bold mb-3 small">TAMBAH JENIS STOK</h6><input type="text" name="label" class="form-control mb-3 text-uppercase" required><button type="submit" class="btn btn-dark btn-sm w-100 rounded-pill fw-bold">TAMBAH</button></div></form></div></div>
    <div class="modal fade" id="modalAddKlaster" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <form action="{{ route('rincian-hpp.add-label') }}" method="POST" class="modal-content">@csrf <input type="hidden" name="tipe" value="klaster">
                <div class="modal-body p-4 text-start"><h6 class="fw-bold mb-3 small">TAMBAH KLASTER</h6><input type="text" name="label" class="form-control mb-3 text-uppercase" required><button type="submit" class="btn btn-secondary btn-sm w-100 rounded-pill fw-bold">TAMBAH</button></div></form></div></div>

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