<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body, .main-content { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #1e293b; }
        
        /* Dashboard Container */
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.02), 0 1px 2px rgba(0,0,0,0.06); background: #fff; }
        
        /* === TABLE REFINEMENT === */
        .table-hpp { width: 100%; border-collapse: separate; border-spacing: 0; }
        
        /* Header Utama - Navy Dark */
        .table-hpp thead th { 
            background-color: #1e293b !important; 
            color: #f8fafc !important; 
            font-size: 10px; 
            font-weight: 700; 
            text-transform: uppercase; 
            letter-spacing: 0.05em; 
            padding: 10px 8px;
            border: 0.5px solid #334155 !important;
            vertical-align: middle;
            white-space: nowrap;
        }

        /* Body & Zebra Striping */
        .table-hpp tbody tr:nth-child(even) { background-color: #fafbfc; }
        .table-hpp tbody td { 
            font-size: 12px; 
            padding: 0 !important; 
            border-bottom: 1px solid #f1f5f9; 
            border-right: 1px solid #f1f5f9;
            color: #475569;
            vertical-align: middle;
        }

        /* Sticky Columns - Diperhalus dengan shadow */
        .sticky-col { 
            position: sticky; 
            left: 0; 
            background-color: #fff !important; 
            z-index: 10; 
            border-right: 2px solid #e2e8f0 !important; 
        }
        .table-hpp tbody tr:hover td.sticky-col { background-color: #f1f5f9 !important; }

        /* Input Cell - Seamless dengan baris */
        .input-cell { 
            width: 100%; height: 38px; border: none; background: transparent; 
            text-align: center; font-weight: 600; color: #1e293b; font-size: 12px;
            transition: 0.2s ease;
        }
        .input-cell:focus { 
            outline: none; 
            background-color: #ffffff; 
            box-shadow: inset 0 0 0 2px #3b82f6; 
            z-index: 20; 
            position: relative;
        }

        /* Sidebar Styling */
        .input-sidebar { border: 1px solid #e2e8f0; background: #fff; text-align: right; width: 75px; font-weight: 600; font-size: 12px; padding: 4px 8px; border-radius: 6px; }
        .sidebar-header { background: #1e293b; color: #fff; padding: 12px; border-radius: 12px 12px 0 0; }

        /* Badges & Icons */
        .tag-badge { background: #dbeafe; color: #1e40af; font-weight: 700; padding: 2px 8px; border-radius: 4px; font-size: 10px; }
        .btn-icon { width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; border: none; font-size: 10px; transition: 0.2s; }
        .btn-edit { background: #fef9c3; color: #854d0e; }
        .btn-delete { background: #fee2e2; color: #991b1b; }
        
        /* Tombol + Header Sidebar */
        .btn-plus-white { background: #ffffff; color: #1e293b; width: 24px; height: 24px; border-radius: 4px; border: none; font-weight: 900; font-size: 14px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }

        .bg-stok-awal { background-color: #fffdf5 !important; }
        .footer-total { background-color: #f8fafc !important; font-weight: 700; color: #1e293b; }
    </style>

    <div class="container-fluid py-4">
        
        <!-- HEADER -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 px-2">
            <div class="text-start">
                <h4 class="fw-bold mb-0 text-dark uppercase tracking-tight">Rincian HPP & Stok</h4>
                <p class="text-secondary mb-0" style="font-size: 13px;">Hasanah Farm • Laporan Populasi Terpadu</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('neraca.index') }}" class="btn bg-white border shadow-sm rounded-pill px-4 font-weight-bold" style="font-size: 12px; color: #475569;">
                    <i class="fas fa-arrow-left me-2"></i> NERACA
                </a>
                <form action="{{ route('rincian-hpp.tambah-bulan') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn bg-white border text-primary shadow-sm rounded-pill px-4 font-weight-bold" style="font-size: 12px;">
                        <i class="fas fa-calendar-plus me-2"></i> + BULAN
                    </button>
                </form>
                <button class="btn btn-primary shadow-sm border-0 rounded-pill px-4 font-weight-bold" style="font-size: 12px; background: #3b82f6;" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fas fa-plus me-2"></i> BARIS BARU
                </button>
            </div>
        </div>

        <div class="row px-2">
            <!-- TABEL (KIRI) -->
            <div class="col-lg-9 col-12 mb-4">
                <div class="card card-custom overflow-hidden">
                    <div class="table-responsive">
                        <table class="table-hpp text-center">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width: 40px;">No</th>
                                    <th rowspan="2" style="width: 80px;">Tanggal</th>
                                    <th rowspan="2" class="sticky-col text-start px-3" style="width: 160px;">Supplier / Keterangan</th>
                                    <th rowspan="2" style="width: 100px;">Jenis</th>
                                    <th rowspan="2" style="width: 55px;">TAG</th>
                                    <th rowspan="2" style="width: 80px;">Aksi</th>
                                    <th colspan="2" class="bg-stok-awal" style="border-left: 2px solid #334155;">STOK AWAL</th>
                                    @foreach($bulanList as $bulan)
                                        <th colspan="2" style="border-left: 2px solid #334155;">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('M y') }}</th>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th class="bg-stok-awal" style="font-size: 9px; border-left: 2px solid #334155;">HARGA</th>
                                    <th class="bg-stok-awal" style="font-size: 9px;">QTY</th>
                                    @foreach($bulanList as $bulan)
                                        <th style="font-size: 9px; border-left: 2px solid #334155;">HARGA</th>
                                        <th style="font-size: 9px;">QTY</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stok as $index => $item)
                                <tr>
                                    <td class="font-bold text-secondary">{{ $index + 1 }}</td>
                                    <td style="font-size: 11px;">{{ $item->tanggal->format('d/m/y') }}</td>
                                    <td class="sticky-col text-start px-3 font-bold text-dark text-uppercase">{{ $item->keterangan }}</td>
                                    <td class="text-uppercase text-secondary" style="font-size: 10px;">{{ $item->jenis }}</td>
                                    <td><span class="tag-badge">{{ $item->tag ?? '-' }}</span></td>
                                    <td class="px-2">
                                        <div class="d-flex justify-content-center gap-1">
                                            <button class="btn-icon btn-edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}"><i class="fas fa-pen"></i></button>
                                            <form action="{{ route('rincian-hpp.destroy', $item->id) }}" method="POST">@csrf @method('DELETE')
                                                <button type="submit" class="btn-icon btn-delete" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="text-end px-2 font-bold bg-stok-awal" style="border-left: 1px solid #e2e8f0;">{{ number_format($item->harga_awal, 0, ',', '.') }}</td>
                                    <td class="text-center font-bold bg-stok-awal">{{ $item->qty_awal }}</td>
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
                                            <div class="modal-body p-4">
                                                <h6 class="font-bold text-primary mb-4 text-center">EDIT DATA</h6>
                                                <div class="row g-3">
                                                    <div class="col-6"><label class="text-xxs font-bold">TANGGAL</label><input type="date" name="tanggal" class="form-control" value="{{ $item->tanggal->format('Y-m-d') }}"></div>
                                                    <div class="col-6"><label class="text-xxs font-bold">TAG ID</label><input type="text" name="tag" class="form-control" value="{{ $item->tag }}"></div>
                                                    <div class="col-12"><label class="text-xxs font-bold">SUPPLIER</label><input type="text" name="keterangan" class="form-control text-uppercase" value="{{ $item->keterangan }}"></div>
                                                    <div class="col-6"><label class="text-xxs font-bold">JENIS</label><input type="text" name="jenis" class="form-control text-uppercase" value="{{ $item->jenis }}"></div>
                                                    <div class="col-6"><label class="text-xxs font-bold">KLASTER</label><input type="text" name="klaster" class="form-control text-uppercase" value="{{ $item->klaster }}"></div>
                                                    <div class="col-6"><label class="text-xxs font-bold">MODAL</label><input type="number" name="harga_awal" class="form-control" value="{{ (int)$item->harga_awal }}"></div>
                                                    <div class="col-6"><label class="text-xxs font-bold">QTY</label><input type="number" name="qty_awal" class="form-control" value="{{ $item->qty_awal }}"></div>
                                                </div>
                                                <div class="mt-4 d-flex gap-2">
                                                    <button type="button" class="btn btn-light w-100 rounded-pill font-bold" data-bs-dismiss="modal">BATAL</button>
                                                    <button type="submit" class="btn btn-primary w-100 rounded-pill border-0 font-bold" style="background: #3b82f6;">SIMPAN</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                            <tfoot class="footer-total">
                                <tr>
                                    <td colspan="6" class="text-center py-3 sticky-col" style="background: #f8fafc !important;">TOTAL KESELURUHAN</td>
                                    <td class="text-end px-2">{{ number_format($stok->sum('harga_awal'), 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $stok->sum('qty_awal') }}</td>
                                    @foreach($bulanList as $bulan)
                                        @php
                                            $tH = 0; $tQ = 0; 
                                            foreach($stok as $s) {
                                                $d = $s->rincian_bulanan->where('bulan', $bulan)->first();
                                                if($d) { $tH += $d->harga_update; $tQ += $d->qty_update; }
                                            }
                                        @endphp
                                        <td class="text-center text-primary border-start">{{ number_format($tH, 0, ',', '.') }}</td>
                                        <td class="text-center text-primary">{{ $tQ }}</td>
                                    @endforeach
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR -->
            <div class="col-lg-3 col-12 text-start text-start">
                <div class="card card-custom mb-4 overflow-hidden shadow-sm">
                    <div class="sidebar-header d-flex justify-content-between align-items-center">
                        <span>STOCK KANDANG</span>
                        <button class="btn-plus-white" data-bs-toggle="modal" data-bs-target="#modalAddStock">+</button>
                    </div>
                    <div class="card-body p-0">
                        @foreach($summaryStock as $s)
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                <form action="{{ route('rincian-hpp.delete-label', $s->id) }}" method="POST" class="m-0">@csrf @method('DELETE')
                                    <button class="btn btn-link text-danger p-0 m-0 me-2"><i class="fas fa-trash-can" style="font-size: 11px;"></i></button>
                                </form>
                                <span class="text-xxs font-bold text-uppercase text-secondary flex-grow-1">{{ $s->label }}</span>
                                <input type="text" class="input-sidebar" value="{{ $s->nilai }}" onchange="saveSummary('stock', '{{ $s->label }}', this.value)">
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card card-custom overflow-hidden shadow-sm">
                    <div class="sidebar-header bg-secondary d-flex justify-content-between align-items-center">
                        <span>KLASTER BANGSALAN</span>
                        <button class="btn-plus-white" data-bs-toggle="modal" data-bs-target="#modalAddKlaster">+</button>
                    </div>
                    <div class="card-body p-0">
                        @foreach($summaryKlaster as $k)
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                <form action="{{ route('rincian-hpp.delete-label', $k->id) }}" method="POST" class="m-0">@csrf @method('DELETE')
                                    <button class="btn btn-link text-danger p-0 m-0 me-2"><i class="fas fa-trash-can" style="font-size: 11px;"></i></button>
                                </form>
                                <span class="text-xxs font-bold text-uppercase text-secondary flex-grow-1">{{ $k->label }}</span>
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
            <form action="{{ route('rincian-hpp.store') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 24px;">
                @csrf
                <div class="modal-body p-4">
                    <h6 class="font-bold text-primary mb-4 text-center">TAMBAH STOK BARU</h6>
                    <div class="row g-3">
                        <div class="col-6"><label class="text-xxs font-bold">TANGGAL</label><input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                        <div class="col-6"><label class="text-xxs font-bold">TAG ID</label><input type="text" name="tag" class="form-control" placeholder="Misal: BB-01"></div>
                        <div class="col-12"><label class="text-xxs font-bold">SUPPLIER</label><input type="text" name="keterangan" class="form-control text-uppercase" required></div>
                        <div class="col-6"><label class="text-xxs font-bold">JENIS</label><input type="text" name="jenis" class="form-control text-uppercase" required></div>
                        <div class="col-6"><label class="text-xxs font-bold">KLASTER</label><input type="text" name="klaster" class="form-control text-uppercase" required></div>
                        <div class="col-6"><label class="text-xxs font-bold">HARGA MODAL</label><input type="number" name="harga_awal" class="form-control" required></div>
                        <div class="col-6"><label class="text-xxs font-bold">QTY</label><input type="number" name="qty_awal" class="form-control" required></div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill shadow mt-4 py-2 font-bold border-0" style="background: #3b82f6;">SIMPAN DATA</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modals Sidebar Add -->
    <div class="modal fade" id="modalAddStock" tabindex="-1"><div class="modal-dialog modal-sm modal-dialog-centered"><form action="{{ route('rincian-hpp.add-label') }}" method="POST" class="modal-content border-0 shadow" style="border-radius: 15px;">@csrf <input type="hidden" name="tipe" value="stock"><div class="modal-body p-4 text-center"><h6 class="font-bold text-xs mb-3">TAMBAH JENIS</h6><input type="text" name="label" class="form-control mb-3 text-uppercase" required autofocus><button type="submit" class="btn btn-dark btn-sm w-100 rounded-pill font-bold">TAMBAH</button></div></form></div></div>
    <div class="modal fade" id="modalAddKlaster" tabindex="-1"><div class="modal-dialog modal-sm modal-dialog-centered"><form action="{{ route('rincian-hpp.add-label') }}" method="POST" class="modal-content border-0 shadow" style="border-radius: 15px;">@csrf <input type="hidden" name="tipe" value="klaster"><div class="modal-body p-4 text-center"><h6 class="font-bold text-xs mb-3">TAMBAH KLASTER</h6><input type="text" name="label" class="form-control mb-3 text-uppercase" required autofocus><button type="submit" class="btn btn-secondary btn-sm w-100 rounded-pill font-bold">TAMBAH</button></div></form></div></div>

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