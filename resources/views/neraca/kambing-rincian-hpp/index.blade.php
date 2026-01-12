<x-app-layout>
    <!-- Font & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        /* Base Styling */
        body, .main-content { 
            font-family: 'Inter', sans-serif !important; 
            background-color: #f8fafc; 
            color: #1e293b;
        }

        .card-pro { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); background: #fff; }

        /* === TABLE REFINEMENT: PIXEL PERFECT GRID === */
        .table-container-pro { 
            border-radius: 12px; 
            border: 1px solid #e2e8f0; 
            background: white; 
            overflow: auto; 
            max-height: 700px; /* Tinggi maksimal sebelum scroll vertikal */
        }
        
        .table-pro { 
            width: 100%; 
            border-collapse: separate; 
            border-spacing: 0; 
            table-layout: fixed; /* Mengunci lebar kolom agar sejajar */
        }

        /* Header Styling */
        .table-pro thead th { 
            background-color: #1e293b !important; 
            color: #ffffff !important; 
            font-size: 10px; 
            font-weight: 700; 
            text-transform: uppercase; 
            letter-spacing: 0.05em; 
            padding: 12px 8px;
            border: 0.5px solid #334155 !important;
            vertical-align: middle;
            white-space: nowrap;
            position: sticky;
            top: 0;
            z-index: 20;
        }

        /* STICKY COLUMN CALIBRATION (PRESISI TINGGI) */
        .sticky-1 { position: sticky; left: 0; z-index: 30; width: 45px; } /* ID */
        .sticky-2 { position: sticky; left: 45px; z-index: 30; width: 90px; } /* TANGGAL */
        .sticky-3 { position: sticky; left: 135px; z-index: 30; width: 220px; border-right: 2px solid #e2e8f0 !important; } /* SUPPLIER */

        /* Header khusus untuk sticky agar warna tetap dark */
        .table-pro thead th.sticky-1, 
        .table-pro thead th.sticky-2, 
        .table-pro thead th.sticky-3 {
            background-color: #1e293b !important;
        }

        /* Body Row Styling */
        .table-pro tbody td { 
            font-size: 12.5px; 
            border: 0.5px solid #f1f5f9 !important; 
            color: #475569;
            vertical-align: middle;
            background-color: #fff;
            padding: 0 !important;
            height: 45px;
        }

        /* Body Sticky Background Fix */
        .table-pro tbody td.sticky-1, 
        .table-pro tbody td.sticky-2 { background-color: #f8fafc !important; }
        .table-pro tbody td.sticky-3 { background-color: #ffffff !important; text-align: left !important; padding-left: 15px !important; }

        /* Input Table Style */
        .input-cell { 
            width: 100%; height: 100%; border: none; background: transparent; 
            text-align: center; font-weight: 600; color: #1e293b; font-size: 13px;
            transition: 0.2s ease;
        }
        .input-cell:focus { 
            outline: none; background-color: #f0f7ff; 
            box-shadow: inset 0 0 0 2px #3b82f6; 
            z-index: 50; 
        }

        /* Monthly Widths */
        .col-month { min-width: 140px; }
        .col-qty { min-width: 70px; }

        /* Action Buttons */
        .btn-action-mini { width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: none; transition: 0.2s; font-size: 11px; }
        .btn-split { background: #6366f1; color: white; }
        .btn-edit { background: #fef9c3; color: #854d0e; }
        .btn-delete { background: #fee2e2; color: #991b1b; }

        /* Sidebar / Bottom Cards */
        .metric-card { border: none; border-radius: 1rem; border: 1px solid #e2e8f0; overflow: hidden; background: #fff; }
        .metric-header { background: #1e293b; color: #fff; padding: 15px; font-weight: 800; font-size: 11px; display: flex; justify-content: space-between; align-items: center; }
        .input-sidebar { border: 1px solid #e2e8f0; background: #f8fafc; text-align: right; width: 90px; font-weight: 700; font-size: 12px; padding: 6px; border-radius: 8px; }

        .btn-header-pro { background: #fff; color: #475569; font-weight: 700; font-size: 12px; padding: 10px 20px; border-radius: 12px; border: 1px solid #e2e8f0; transition: 0.2s; text-decoration: none !important; }
        .btn-header-pro:hover { background: #f8fafc; color: #3b82f6; border-color: #3b82f6; transform: translateY(-2px); }
        
        .tag-pill { background: #eef2ff; color: #3730a3; font-weight: 800; padding: 3px 8px; border-radius: 5px; font-size: 10px; }
        .bg-soft-yellow { background-color: #fffdf5 !important; }
    </style>

    <main class="main-content">
        <div class="container-fluid py-4">

            <!-- 1. HEADER UTAMA -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3 px-2 text-start">
                <div class="text-start">
                    <h4 class="fw-bold text-dark mb-0 text-uppercase tracking-tighter" style="font-size: 24px;">Rincian HPP Pro</h4>
                    <p class="text-secondary mb-0" style="font-size: 13px;">Hasanah Farm • Mode Full-Width Scroll & Carry Forward</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('neraca.index') }}" class="btn-header-pro shadow-sm">
                        <i class="fas fa-arrow-left me-2"></i> NERACA
                    </a>
                    <form action="{{ route('rincian-hpp.tambah-bulan') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-header-pro shadow-sm text-primary">
                            <i class="fas fa-calendar-plus me-2"></i> + BULAN
                        </button>
                    </form>
                    <button class="btn btn-primary shadow-sm border-0 rounded-pill px-4 font-weight-bold" style="font-size: 12px; background: #3b82f6; height: 40px;" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="fas fa-plus me-2"></i> BARIS BARU
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm text-white mb-4 py-3 px-4 mx-2" style="background: #10b981; border-radius: 12px;">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif

            <!-- 2. TABEL UTAMA (SCROLLABLE & STICKY) -->
            <div class="table-pro-container shadow-sm mx-2 mb-5">
                <table class="table-pro text-center">
                    <thead>
                        <tr>
                            <th rowspan="2" class="sticky-1">ID</th>
                            <th rowspan="2" class="sticky-2">TANGGAL</th>
                            <th rowspan="2" class="sticky-3">SUPPLIER / KETERANGAN</th>
                            <th rowspan="2" style="width: 100px;">JENIS</th>
                            <th rowspan="2" style="width: 60px;">TAG</th>
                            <th rowspan="2" style="width: 110px;">AKSI</th>
                            <th colspan="2" class="bg-soft-yellow" style="color: #92400e; border-left: 1px solid #334155;">STOK AWAL</th>
                            @foreach($bulanList as $bulan)
                                <th colspan="2" style="border-left: 1px solid #4b5b7c;">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('M y') }}</th>
                            @endforeach
                        </tr>
                        <tr>
                            <th class="bg-soft-yellow" style="font-size: 9px; border-left: 1px solid #334155;">HARGA</th>
                            <th class="bg-soft-yellow" style="font-size: 9px;">QTY</th>
                            @foreach($bulanList as $bulan)
                                <th class="col-month" style="font-size: 9px; border-left: 1px solid #4b5b7c;">HARGA</th>
                                <th class="col-qty" style="font-size: 9px;">QTY</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stok as $item)
                        <tr>
                            <td class="font-bold text-secondary sticky-1 text-center">{{ $item->id }}</td>
                            <td class="text-xs sticky-2 text-center">{{ $item->tanggal->format('d/m/y') }}</td>
                            <td class="sticky-3 font-bold text-dark text-uppercase">{{ $item->keterangan }}</td>
                            <td class="text-uppercase text-secondary text-center" style="font-size: 10px;">{{ $item->jenis }}</td>
                            <td class="text-center"><span class="tag-pill">{{ $item->tag ?? '-' }}</span></td>
                            
                            <!-- KOLOM AKSI (DENGAN SPLIT) -->
                            <td class="px-2 text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    @if($item->qty_awal > 1)
                                    <form action="{{ route('rincian-hpp.split', $item->id) }}" method="POST">
                                        @csrf 
                                        <button type="submit" class="btn-action-mini btn-split" title="Pecah menjadi satuan"><i class="fas fa-scissors"></i></button>
                                    </form>
                                    @endif
                                    <button class="btn-action-mini btn-edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}"><i class="fas fa-pen"></i></button>
                                    <form action="{{ route('rincian-hpp.destroy', $item->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-action-mini btn-delete" onclick="return confirm('Hapus baris data ini?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>

                            <td class="text-end px-2 font-bold bg-soft-yellow" style="border-left: 1px solid #e2e8f0;">{{ number_format($item->harga_awal, 0, ',', '.') }}</td>
                            <td class="text-center font-bold bg-soft-yellow">{{ $item->qty_awal }}</td>
                            
                            @foreach($bulanList as $bulan)
                                @php $det = $item->rincian_bulanan->where('bulan', $bulan)->first(); @endphp
                                <td style="border-left: 1px solid #f1f5f9;"><input type="number" class="input-cell" value="{{ $det ? (int)$det->harga_update : '' }}" onchange="updateCell('{{ $item->id }}', '{{ $bulan }}', 'harga_update', this.value)"></td>
                                <td><input type="number" class="input-cell" value="{{ $det ? $det->qty_update : '' }}" onchange="updateCell('{{ $item->id }}', '{{ $bulan }}', 'qty_update', this.value)"></td>
                            @endforeach
                        </tr>

                        <!-- MODAL EDIT BARIS -->
                        <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <form action="{{ route('rincian-hpp.update', $item->id) }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                                    @csrf @method('PUT')
                                    <div class="modal-body p-4 text-start">
                                        <h6 class="font-bold text-primary mb-4 text-center uppercase">Edit Baris Data</h6>
                                        <div class="row g-3">
                                            <div class="col-6"><label class="text-xxs font-bold">TANGGAL</label><input type="date" name="tanggal" class="form-control" value="{{ $item->tanggal->format('Y-m-d') }}"></div>
                                            <div class="col-6"><label class="text-xxs font-bold">TAG ID</label><input type="text" name="tag" class="form-control" value="{{ $item->tag }}"></div>
                                            <div class="col-12"><label class="text-xxs font-bold">SUPPLIER</label><input type="text" name="keterangan" class="form-control text-uppercase" value="{{ $item->keterangan }}"></div>
                                            <div class="col-6"><label class="text-xxs font-bold">JENIS</label><input type="text" name="jenis" class="form-control text-uppercase" value="{{ $item->jenis }}"></div>
                                            <div class="col-6"><label class="text-xxs font-bold">KLASTER</label><input type="text" name="klaster" class="form-control text-uppercase" value="{{ $item->klaster }}"></div>
                                            <div class="col-6"><label class="text-xxs font-bold">MODAL</label><input type="number" name="harga_awal" class="form-control" value="{{ (int)$item->harga_awal }}"></div>
                                            <div class="col-6"><label class="text-xxs font-bold">QTY</label><input type="number" name="qty_awal" class="form-control" value="{{ $item->qty_awal }}"></div>
                                        </div>
                                        <div class="mt-4 d-flex gap-2"><button type="button" class="btn btn-light w-100 rounded-pill font-bold" data-bs-dismiss="modal">BATAL</button><button type="submit" class="btn btn-primary w-100 rounded-pill border-0 font-bold shadow text-white">SIMPAN</button></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-light">
                            <td colspan="7" class="text-center font-bold py-4 sticky-1 sticky-2 sticky-3" style="background: #f8fafc !important; left: 0; z-index: 35; border-right: 2px solid #e2e8f0;">TOTAL KESELURUHAN</td>
                            <td class="text-center font-bold bg-soft-yellow" style="background: #fffdf2 !important;">{{ $stok->sum('qty_awal') }}</td>
                            @foreach($bulanList as $bulan)
                                @php
                                    $tH = 0; $tQ = 0; 
                                    foreach($stok as $s) {
                                        $d = $s->rincian_bulanan->where('bulan', $bulan)->first();
                                        if($d) { $tH += $d->harga_update; $tQ += $d->qty_update; }
                                    }
                                @endphp
                                <td class="text-center font-bold text-primary border-start" style="font-size: 14px; background: #fff !important;">{{ number_format($tH, 0, ',', '.') }}</td>
                                <td class="text-center font-bold text-primary" style="font-size: 14px; background: #fff !important;">{{ $tQ }}</td>
                            @endforeach
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- 3. METRIC CARDS (BAWAH TABEL) -->
            <div class="row g-4 px-2">
                <div class="col-md-6">
                    <div class="card metric-card shadow-sm">
                        <div class="metric-header">
                            <span>STOCK KANDANG (MANUAL)</span>
                            <button class="btn-plus-manual" data-bs-toggle="modal" data-bs-target="#modalAddStock"> + </button>
                        </div>
                        <div class="card-body p-0">
                            <div class="row g-0">
                                @foreach($summaryStock as $s)
                                    <div class="col-6 d-flex justify-content-between align-items-center p-3 border-bottom border-end">
                                        <div class="d-flex align-items-center">
                                            <form action="{{ route('rincian-hpp.delete-label', $s->id) }}" method="POST" class="m-0">@csrf @method('DELETE')
                                                <button class="btn btn-link text-danger p-0 m-0 me-2"><i class="fas fa-trash-alt" style="font-size:11px;"></i></button>
                                            </form>
                                            <span class="text-xxs font-bold text-secondary text-uppercase text-start">{{ $s->label }}</span>
                                        </div>
                                        <input type="text" class="input-sidebar" value="{{ $s->nilai }}" onchange="saveSummary('stock', '{{ $s->label }}', this.value)">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card metric-card shadow-sm">
                        <div class="metric-header" style="background:#475569;">
                            <span>KLASTER BANGSALAN (MANUAL)</span>
                            <button class="btn-plus-manual" data-bs-toggle="modal" data-bs-target="#modalAddKlaster"> + </button>
                        </div>
                        <div class="card-body p-0 bg-white">
                            @foreach($summaryKlaster as $k)
                                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <form action="{{ route('rincian-hpp.delete-label', $k->id) }}" method="POST" class="m-0">@csrf @method('DELETE')
                                            <button class="btn btn-link text-danger p-0 m-0 me-2"><i class="fas fa-trash-alt" style="font-size:11px;"></i></button>
                                        </form>
                                        <span class="text-xxs font-bold text-secondary text-uppercase">{{ $k->label }}</span>
                                    </div>
                                    <input type="text" class="input-sidebar" style="width: 200px;" value="{{ $k->nilai }}" onchange="saveSummary('klaster', '{{ $k->label }}', this.value)">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- MODAL TAMBAH BARIS -->
        <div class="modal fade" id="addModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('rincian-hpp.store') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 24px;">@csrf
                    <div class="modal-body p-4 text-start">
                        <h5 class="fw-bold text-primary mb-4 text-center">Tambah Stok Baru</h5>
                        <div class="row g-3 text-start">
                            <div class="col-6"><label class="text-xxs font-bold">TANGGAL</label><input type="date" name="tanggal" class="form-control rounded-3" value="{{ date('Y-m-d') }}" required></div>
                            <div class="col-6"><label class="text-xxs font-bold">TAG ID</label><input type="text" name="tag" class="form-control rounded-3" placeholder="Misal: BB-01"></div>
                            <div class="col-12"><label class="text-xxs font-bold text-uppercase">Supplier</label><input type="text" name="keterangan" class="form-control text-uppercase" required></div>
                            <div class="col-6"><label class="text-xxs font-bold text-uppercase">Jenis</label><input type="text" name="jenis" class="form-control text-uppercase" required></div>
                            <div class="col-6"><label class="text-xxs font-bold text-uppercase">Klaster</label><input type="text" name="klaster" class="form-control text-uppercase" required></div>
                            <div class="col-6"><label class="text-xxs font-bold text-uppercase">Harga Modal (Rp)</label><input type="number" name="harga_awal" class="form-control rounded-3" required></div>
                            <div class="col-6"><label class="text-xxs font-bold text-uppercase">Qty</label><input type="number" name="qty_awal" class="form-control rounded-3" required></div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill shadow mt-4 py-2 font-bold border-0 text-white" style="background:#3b82f6;">SIMPAN DATA</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- MODALS SIDEBAR LABEL -->
        <div class="modal fade" id="modalAddStock" tabindex="-1"><div class="modal-dialog modal-sm modal-dialog-centered"><form action="{{ route('rincian-hpp.add-label') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 15px;">@csrf <input type="hidden" name="tipe" value="stock"><div class="modal-body p-4 text-center"><h6 class="font-bold text-xs mb-3 uppercase">Tambah Jenis</h6><input type="text" name="label" class="form-control mb-3 text-uppercase" required autofocus><button type="submit" class="btn btn-dark btn-sm w-100 rounded-pill font-bold">TAMBAH</button></div></form></div></div>
        <div class="modal fade" id="modalAddKlaster" tabindex="-1"><div class="modal-dialog modal-sm modal-dialog-centered"><form action="{{ route('rincian-hpp.add-label') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 15px;">@csrf <input type="hidden" name="tipe" value="klaster"><div class="modal-body p-4 text-center"><h6 class="font-bold text-xs mb-3 uppercase">Tambah Klaster</h6><input type="text" name="label" class="form-control mb-3 text-uppercase" required autofocus><button type="submit" class="btn btn-secondary btn-sm w-100 rounded-pill font-bold">TAMBAH</button></div></form></div></div>

    </main>

    <script>
        function updateCell(id, bulan, kolom, nilai) {
            fetch("{{ route('rincian-hpp.update-cell') }}", { method: "POST", headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" }, body: JSON.stringify({ id, bulan, kolom, nilai }) });
        }
        function saveSummary(tipe, label, nilai) {
            fetch("{{ route('rincian-hpp.update-summary') }}", { method: "POST", headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" }, body: JSON.stringify({ tipe, label, nilai }) });
        }
    </script>
</x-app-layout>