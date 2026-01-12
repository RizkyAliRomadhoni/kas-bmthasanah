<x-app-layout>
    <!-- Font & UI Setup -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body, .main-content { font-family: 'Inter', sans-serif !important; background-color: #f8fafc; color: #1e293b; }
        .card-pro { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); background: #fff; }

        /* === TABLE REFINEMENT: ANTI-TUMPANG TINDIH === */
        .table-pro-container { 
            border-radius: 12px; 
            border: 1px solid #e2e8f0; 
            background: white; 
            overflow: auto; 
            max-height: 700px;
        }
        
        .table-pro { 
            width: max-content; /* Memaksa tabel melebar ke samping */
            min-width: 100%;
            border-collapse: separate; 
            border-spacing: 0; 
        }

        /* Header Styling */
        .table-pro thead th { 
            background-color: #1e293b !important; 
            color: #ffffff !important; 
            font-size: 11px; 
            font-weight: 700; 
            text-transform: uppercase; 
            padding: 15px 10px;
            border: 0.5px solid #334155 !important;
            vertical-align: middle;
            position: sticky;
            top: 0;
            z-index: 20;
        }

        /* STICKY COLUMN CALIBRATION (Fungsi agar ID, Tanggal, Supplier tetap di kiri) */
        .sticky-1 { position: sticky; left: 0; z-index: 30; width: 50px; background: #1e293b !important; }
        .sticky-2 { position: sticky; left: 50px; z-index: 30; width: 100px; background: #1e293b !important; }
        .sticky-3 { position: sticky; left: 150px; z-index: 30; width: 250px; background: #1e293b !important; border-right: 2px solid #334155 !important; }

        /* Body Rows Styling */
        .table-pro tbody td { 
            font-size: 13px; 
            border-bottom: 1px solid #f1f5f9; 
            border-right: 1px solid #f1f5f9;
            color: #475569;
            vertical-align: middle;
            background-color: #fff;
            padding: 8px 10px !important; /* Spasi antar sel */
        }

        /* Body Sticky Background (Agar tidak transparan saat digeser) */
        .table-pro tbody td.sticky-1 { left: 0; background: #f8fafc !important; }
        .table-pro tbody td.sticky-2 { left: 50px; background: #f8fafc !important; }
        .table-pro tbody td.sticky-3 { left: 150px; background: #ffffff !important; text-align: left !important; border-right: 2px solid #e2e8f0 !important; }

        /* Monthly Widths (Setting Lebar Kolom agar rapi) */
        .col-month-header { min-width: 180px; }
        .col-val { width: 110px; }
        .col-qty { width: 60px; }

        /* Input Interaction */
        .input-cell { 
            width: 100%; border: none; background: #f8fafc; 
            text-align: center; font-weight: 700; color: #1e293b; 
            font-size: 13px; border-radius: 6px; padding: 4px; transition: 0.2s;
        }
        .input-cell:focus { outline: none; background-color: #f0f7ff; box-shadow: 0 0 0 2px #3b82f6; }

        /* Action Buttons */
        .btn-action-sm { width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: none; transition: 0.2s; font-size: 11px; }
        .btn-split { background: #6366f1; color: white; }
        .btn-edit { background: #fef9c3; color: #854d0e; }
        .btn-delete { background: #fee2e2; color: #991b1b; }

        .tag-pill { background: #eef2ff; color: #3730a3; font-weight: 800; padding: 3px 8px; border-radius: 5px; font-size: 10px; }
        .bg-soft-yellow { background-color: #fffdf5 !important; }

        /* Sidebar Bawah */
        .metric-card { border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; background: #fff; }
        .metric-header { background: #1e293b; color: #fff; padding: 12px; font-weight: 800; font-size: 11px; display: flex; justify-content: space-between; align-items: center; }
        .input-sidebar { border: 1px solid #e2e8f0; text-align: right; width: 90px; font-weight: 700; font-size: 12px; padding: 5px; border-radius: 8px; }
        .btn-plus-white { background: #ffffff !important; color: #1e293b !important; width: 26px; height: 26px; border-radius: 6px; border: none; font-weight: 900; font-size: 18px; display: flex; align-items: center; justify-content: center; cursor: pointer; }

        /* Hide Number Arrows */
        input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    </style>

    <main class="main-content">
        <div class="container-fluid py-4">

            <!-- HEADER -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3 px-2 text-start">
                <div class="text-start">
                    <h4 class="fw-bold text-dark mb-0 uppercase tracking-tighter">Rincian HPP & Stok Pro</h4>
                    <p class="text-secondary text-sm mb-0">Hasanah Farm • Mode Full-Width Scroll & Precision Grid</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('neraca.index') }}" class="btn border bg-white shadow-sm rounded-pill px-4 text-secondary font-weight-bold" style="font-size: 12px; text-decoration: none;">NERACA</a>
                    <form action="{{ route('rincian-hpp.tambah-bulan') }}" method="POST">@csrf <button class="btn border bg-white text-primary shadow-sm rounded-pill px-4 font-weight-bold" style="font-size: 12px;">+ BULAN</button></form>
                    <button class="btn btn-primary shadow-sm border-0 rounded-pill px-4 font-weight-bold" style="font-size: 12px; background: #3b82f6;" data-bs-toggle="modal" data-bs-target="#addModal">+ BARIS BARU</button>
                </div>
            </div>

            @if(session('success')) <div class="alert alert-success border-0 shadow-sm text-white mb-4 py-2 px-4 mx-2" style="background: #10b981; border-radius: 12px;">{{ session('success') }}</div> @endif

            <!-- TABEL (Horizontal Scroll) -->
            <div class="table-pro-container shadow-sm mx-2 mb-5">
                <table class="table-pro text-center">
                    <thead>
                        <tr>
                            <th rowspan="2" class="sticky-1">ID</th>
                            <th rowspan="2" class="sticky-2">TANGGAL</th>
                            <th rowspan="2" class="sticky-3">SUPPLIER / KETERANGAN</th>
                            <th rowspan="2" style="width: 120px;">JENIS</th>
                            <th rowspan="2" style="width: 60px;">TAG</th>
                            <th rowspan="2" style="width: 100px;">AKSI</th>
                            <th colspan="2" class="bg-soft-yellow" style="color: #92400e; border-left: 2px solid #334155;">STOK AWAL</th>
                            @foreach($bulanList as $bulan)
                                <th colspan="2" class="col-month-header" style="border-left: 1px solid #4b5b7c;">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('M y') }}</th>
                            @endforeach
                        </tr>
                        <tr>
                            <th class="bg-soft-yellow col-val" style="font-size: 9px; border-left: 2px solid #334155;">HARGA</th>
                            <th class="bg-soft-yellow col-qty" style="font-size: 9px;">QTY</th>
                            @foreach($bulanList as $bulan)
                                <th class="col-val" style="font-size: 9px; border-left: 1px solid #4b5b7c;">HARGA</th>
                                <th class="col-qty" style="font-size: 9px;">QTY</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stok as $item)
                        <tr>
                            <td class="font-bold text-secondary sticky-1">{{ $item->id }}</td>
                            <td class="text-xs sticky-2">{{ $item->tanggal->format('d/m/y') }}</td>
                            <td class="sticky-3 font-bold text-dark text-uppercase px-3">{{ $item->keterangan }}</td>
                            <td class="text-uppercase text-secondary" style="font-size: 10px;">{{ $item->jenis }}</td>
                            <td><span class="tag-pill">{{ $item->tag ?? '-' }}</span></td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    @if($item->qty_awal > 1)
                                    <form action="{{ route('rincian-hpp.split', $item->id) }}" method="POST">@csrf <button type="submit" class="btn-action-sm btn-split"><i class="fas fa-scissors"></i></button></form>
                                    @endif
                                    <button class="btn-action-sm btn-edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}"><i class="fas fa-pen"></i></button>
                                    <form action="{{ route('rincian-hpp.destroy', $item->id) }}" method="POST">@csrf @method('DELETE') <button type="submit" class="btn-action-sm btn-delete"><i class="fas fa-trash"></i></button></form>
                                </div>
                            </td>
                            <td class="text-end px-3 font-bold bg-soft-yellow" style="border-left: 1px solid #e2e8f0;">{{ number_format($item->harga_awal, 0, ',', '.') }}</td>
                            <td class="text-center font-bold bg-soft-yellow">{{ $item->qty_awal }}</td>
                            @foreach($bulanList as $bulan)
                                @php $det = $item->rincian_bulanan->where('bulan', $bulan)->first(); @endphp
                                <td style="border-left: 1px solid #f1f5f9; padding: 5px !important;"><input type="number" class="input-cell" value="{{ $det ? (int)$det->harga_update : '' }}" onchange="updateCell('{{ $item->id }}', '{{ $bulan }}', 'harga_update', this.value)"></td>
                                <td style="padding: 5px !important;"><input type="number" class="input-cell" value="{{ $det ? $det->qty_update : '' }}" onchange="updateCell('{{ $item->id }}', '{{ $bulan }}', 'qty_update', this.value)"></td>
                            @endforeach
                        </tr>

                        <!-- MODAL EDIT -->
                        <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <form action="{{ route('rincian-hpp.update', $item->id) }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                                    @csrf @method('PUT')
                                    <div class="modal-body p-4 text-start">
                                        <h6 class="font-bold text-dark mb-4 text-center">EDIT BARIS DATA</h6>
                                        <div class="row g-3">
                                            <div class="col-6"><label class="small font-bold">TANGGAL</label><input type="date" name="tanggal" class="form-control" value="{{ $item->tanggal->format('Y-m-d') }}"></div>
                                            <div class="col-6"><label class="small font-bold">TAG ID</label><input type="text" name="tag" class="form-control" value="{{ $item->tag }}"></div>
                                            <div class="col-12"><label class="small font-bold">SUPPLIER</label><input type="text" name="keterangan" class="form-control text-uppercase" value="{{ $item->keterangan }}"></div>
                                            <div class="col-6"><label class="small font-bold">JENIS</label><input type="text" name="jenis" class="form-control text-uppercase" value="{{ $item->jenis }}"></div>
                                            <div class="col-6"><label class="small font-bold">KLASTER</label><input type="text" name="klaster" class="form-control text-uppercase" value="{{ $item->klaster }}"></div>
                                            <div class="col-6"><label class="small font-bold">MODAL</label><input type="number" name="harga_awal" class="form-control" value="{{ (int)$item->harga_awal }}"></div>
                                            <div class="col-6"><label class="small font-bold">QTY</label><input type="number" name="qty_awal" class="form-control" value="{{ $item->qty_awal }}"></div>
                                        </div>
                                        <div class="mt-4 d-flex gap-2"><button type="button" class="btn btn-light w-100 rounded-pill font-bold" data-bs-dismiss="modal">BATAL</button><button type="submit" class="btn btn-primary w-100 rounded-pill border-0 font-bold" style="background:#3b82f6;">SIMPAN</button></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <td colspan="7" class="text-center font-bold py-4 sticky-1 sticky-2 sticky-3" style="background: #f8fafc !important; border-right: 2px solid #e2e8f0;">TOTAL KESELURUHAN</td>
                            <td class="text-center font-bold bg-soft-yellow" style="background: #fffdf2 !important;">{{ $stok->sum('qty_awal') }}</td>
                            @foreach($bulanList as $bulan)
                                @php
                                    $tH = 0; $tQ = 0; foreach($stok as $s) {
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

            <!-- SIDEBAR BAWAH -->
            <div class="row g-4 px-2">
                <div class="col-md-6 text-start">
                    <div class="card metric-card shadow-sm">
                        <div class="metric-header"><span>STOCK KANDANG (MANUAL)</span><button class="btn-plus-white" data-bs-toggle="modal" data-bs-target="#modalAddStock"> + </button></div>
                        <div class="card-body p-0 bg-white">
                            <div class="row g-0">
                                @foreach($summaryStock as $s)
                                    <div class="col-6 d-flex justify-content-between align-items-center p-3 border-bottom border-end">
                                        <div class="d-flex align-items-center">
                                            <form action="{{ route('rincian-hpp.delete-label', $s->id) }}" method="POST" class="m-0">@csrf @method('DELETE') <button class="btn btn-link text-danger p-0 m-0 me-2"><i class="fas fa-trash-alt" style="font-size:11px;"></i></button></form>
                                            <span class="text-xxs font-bold text-secondary text-uppercase">{{ $s->label }}</span>
                                        </div>
                                        <input type="text" class="input-sidebar" value="{{ $s->nilai }}" onchange="saveSummary('stock', '{{ $s->label }}', this.value)">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-start">
                    <div class="card metric-card shadow-sm">
                        <div class="metric-header" style="background:#475569;"><span>KLASTER BANGSALAN (MANUAL)</span><button class="btn-plus-white" data-bs-toggle="modal" data-bs-target="#modalAddKlaster"> + </button></div>
                        <div class="card-body p-0 bg-white">
                            @foreach($summaryKlaster as $k)
                                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                    <div class="d-flex align-items-center text-start">
                                        <form action="{{ route('rincian-hpp.delete-label', $k->id) }}" method="POST" class="m-0">@csrf @method('DELETE') <button class="btn btn-link text-danger p-0 m-0 me-2"><i class="fas fa-trash-alt" style="font-size:11px;"></i></button></form>
                                        <span class="text-xxs font-bold text-secondary text-uppercase text-start">{{ $k->label }}</span>
                                    </div>
                                    <input type="text" class="input-sidebar" style="width: 220px;" value="{{ $k->nilai }}" onchange="saveSummary('klaster', '{{ $k->label }}', this.value)">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- MODAL TAMBAH BARIS -->
        <div class="modal fade" id="addModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><form action="{{ route('rincian-hpp.store') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 24px;">@csrf<div class="modal-body p-4 text-start"><h5 class="fw-bold text-primary mb-4 text-center">Tambah Stok Baru</h5><div class="row g-3"><div class="col-6"><label class="text-xxs font-bold">TANGGAL</label><input type="date" name="tanggal" class="form-control rounded-3" value="{{ date('Y-m-d') }}" required></div><div class="col-6"><label class="text-xxs font-bold">TAG ID</label><input type="text" name="tag" class="form-control rounded-3" placeholder="Misal: BB-01"></div><div class="col-12"><label class="text-xxs font-bold text-uppercase">Supplier</label><input type="text" name="keterangan" class="form-control rounded-3 text-uppercase" required></div><div class="col-6"><label class="text-xxs font-bold text-uppercase">Jenis</label><input type="text" name="jenis" class="form-control rounded-3 text-uppercase" required></div><div class="col-6"><label class="text-xxs font-bold text-uppercase">Klaster</label><input type="text" name="klaster" class="form-control rounded-3 text-uppercase" required></div><div class="col-6"><label class="text-xxs font-bold text-uppercase">Harga Modal</label><input type="number" name="harga_awal" class="form-control rounded-3" required></div><div class="col-6"><label class="text-xxs font-bold text-uppercase">Qty</label><input type="number" name="qty_awal" class="form-control rounded-3" required></div></div><button type="submit" class="btn btn-primary w-100 rounded-pill shadow mt-4 py-2 font-bold border-0 text-white" style="background:#3b82f6;">SIMPAN DATA</button></div></form></div></div>
        
        <!-- MODALS SIDEBAR -->
        <div class="modal fade" id="modalAddStock" tabindex="-1"><div class="modal-dialog modal-sm modal-dialog-centered"><form action="{{ route('rincian-hpp.add-label') }}" method="POST" class="modal-content border-0"><div class="modal-body p-4 text-center">@csrf <input type="hidden" name="tipe" value="stock"><h6 class="font-bold text-xs mb-3 uppercase">Tambah Jenis</h6><input type="text" name="label" class="form-control mb-3 text-uppercase" required autofocus><button type="submit" class="btn btn-dark btn btn-sm w-100 rounded-pill font-bold">TAMBAH</button></div></form></div></div>
        <div class="modal-fade" id="modalAddKlaster" tabindex="-1"><div class="modal-dialog modal-sm modal-dialog-centered"><form action="{{ route('rincian-hpp.add-label') }}" method="POST" class="modal-content border-0"><div class="modal-body p-4 text-center">@csrf <input type="hidden" name="tipe" value="klaster"><h6 class="font-bold text-xs mb-3 uppercase">Tambah Klaster</h6><input type="text" name="label" class="form-control mb-3 text-uppercase" required autofocus><button type="submit" class="btn btn-secondary btn btn-sm w-100 rounded-pill font-bold">TAMBAH</button></div></form></div></div>

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