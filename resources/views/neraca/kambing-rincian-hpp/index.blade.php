<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        /* Base Setup - Clean & Elegant */
        body, .main-content { 
            font-family: 'Inter', sans-serif; 
            background-color: #f6f8fa; 
            color: #2d3748;
        }

        /* Card Elements */
        .card-elegant { border: none; border-radius: 12px; background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.03); overflow: hidden; }
        
        /* Table Styling - Soft & Balanced */
        .table-pro { width: 100%; border-collapse: separate; border-spacing: 0; }
        .table-pro thead th { 
            background-color: #334155 !important; /* Soft Dark Gray */
            color: #ffffff !important; 
            font-size: 11px; 
            font-weight: 600; 
            text-transform: uppercase; 
            letter-spacing: 0.03em; 
            padding: 14px 8px;
            border: 0.5px solid #475569 !important;
            vertical-align: middle;
        }
        .table-pro tbody td { 
            font-size: 13px; 
            padding: 0 !important; 
            border: 0.5px solid #edf2f7 !important; 
            color: #4a5568;
            vertical-align: middle;
        }

        /* Sticky Columns - Fixed & White */
        .sticky-col { position: sticky; left: 0; background-color: #ffffff !important; z-index: 10; border-right: 2px solid #edf2f7 !important; }
        .sticky-label { position: sticky; left: 0; z-index: 10; background-color: #f8fafc !important; }

        /* Input Table Interactions */
        .input-cell { 
            width: 100%; height: 40px; border: none; background: transparent; 
            text-align: center; font-weight: 600; color: #2d3748; transition: 0.2s ease;
        }
        .input-cell:focus { outline: none; background-color: #f0f7ff; box-shadow: inset 0 0 0 2px #3b82f6; }

        /* Buttons & Badges */
        .btn-header { font-weight: 600; font-size: 12px; border-radius: 8px; padding: 10px 18px; border: 1px solid #e2e8f0; background: #fff; transition: 0.2s; }
        .btn-header:hover { background: #f8fafc; border-color: #cbd5e1; transform: translateY(-1px); }
        .btn-primary-elegant { background-color: #3b82f6; color: white; border: none; }
        .btn-primary-elegant:hover { background-color: #2563eb; }

        .btn-action-mini { width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; border: none; font-size: 11px; transition: 0.2s; }
        .btn-edit { background: #fef9c3; color: #854d0e; }
        .btn-delete { background: #fee2e2; color: #991b1b; }

        .tag-pill { background: #f1f5f9; color: #475569; font-weight: 700; padding: 4px 8px; border-radius: 6px; font-size: 10px; }
        .bg-soft-cream { background-color: #fffdf5 !important; }
        
        /* Metric Styling (Sidebar bawah) */
        .metric-card { border-radius: 12px; background: white; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .metric-header { padding: 12px 15px; font-weight: 700; font-size: 11px; text-transform: uppercase; color: #fff; background: #334155; }
        .input-sidebar { border: 1px solid #e2e8f0; background: #f8fafc; text-align: right; width: 85px; font-weight: 700; font-size: 12px; padding: 5px; border-radius: 8px; }
    </style>

    <div class="container-fluid py-4 text-start">
        
        <!-- HEADER -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 px-2">
            <div>
                <h4 class="fw-bold text-dark mb-0 uppercase-tracking">Rincian HPP & Stok Bulanan</h4>
                <p class="text-secondary mb-0" style="font-size: 13px;">Hasanah Farm • Periode Aktif September 2025</p>
            </div>
            <div class="d-flex gap-2 text-start">
                <a href="{{ route('neraca.index') }}" class="btn-header shadow-sm text-decoration-none">
                    <i class="fas fa-arrow-left me-2"></i> Neraca
                </a>
                <form action="{{ route('rincian-hpp.tambah-bulan') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-header shadow-sm text-primary">
                        <i class="fas fa-calendar-plus me-2"></i> + Bulan
                    </button>
                </form>
                <button class="btn btn-header btn-primary-elegant shadow-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fas fa-plus me-2"></i> Baris Baru
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm text-white mb-4 py-3 px-4 mx-2" style="background: #10b981; border-radius: 10px;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="row g-4 px-2">
            <!-- 1. TABEL UTAMA (MEMANJANG) -->
            <div class="col-12">
                <div class="card-elegant">
                    <div class="table-responsive">
                        <table class="table-pro text-center">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width: 45px;">ID</th>
                                    <th rowspan="2" style="width: 90px;">Tanggal</th>
                                    <th rowspan="2" class="sticky-col text-start px-4" style="width: 200px;">Supplier</th>
                                    <th rowspan="2" style="width: 110px;">Jenis</th>
                                    <th rowspan="2" style="width: 65px;">TAG</th>
                                    <th rowspan="2" style="width: 90px;">Aksi</th>
                                    <th colspan="2" class="bg-soft-cream" style="color: #92400e;">STOK AWAL</th>
                                    @foreach($bulanList as $bulan)
                                        <th colspan="2">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('M y') }}</th>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th class="bg-soft-cream" style="font-size: 10px;">HARGA</th>
                                    <th class="bg-soft-cream" style="font-size: 10px;">QTY</th>
                                    @foreach($bulanList as $bulan)
                                        <th style="font-size: 10px; border-left: 1px solid #edf2f7;">HARGA</th>
                                        <th style="font-size: 10px;">QTY</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stok as $index => $item)
                                <tr>
                                    <td class="fw-bold text-secondary">{{ $index + 1 }}</td>
                                    <td class="text-xs">{{ $item->tanggal->format('d/m/y') }}</td>
                                    <td class="sticky-col text-start px-4 fw-bold text-dark text-uppercase">{{ $item->keterangan }}</td>
                                    <td class="text-uppercase text-secondary" style="font-size: 10px;">{{ $item->jenis }}</td>
                                    <td class="text-center"><span class="tag-pill">{{ $item->tag ?? '-' }}</span></td>
                                    <td class="px-2">
                                        <div class="d-flex justify-content-center gap-1">
                                            <button class="btn-action-mini btn-edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}"><i class="fas fa-edit"></i></button>
                                            <form action="{{ route('rincian-hpp.destroy', $item->id) }}" method="POST">@csrf @method('DELETE')
                                                <button type="submit" class="btn-action-mini btn-delete" onclick="return confirm('Hapus?')"><i class="fas fa-trash-alt"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="text-end px-3 font-bold bg-soft-cream" style="border-left: 1px solid #e2e8f0;">{{ number_format($item->harga_awal, 0, ',', '.') }}</td>
                                    <td class="text-center font-bold bg-soft-cream">{{ $item->qty_awal }}</td>
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
                                                <h6 class="font-bold text-dark text-center mb-4 uppercase">Update Baris Data</h6>
                                                <div class="row g-3">
                                                    <div class="col-6"><label class="small font-bold">TANGGAL</label><input type="date" name="tanggal" class="form-control" value="{{ $item->tanggal->format('Y-m-d') }}"></div>
                                                    <div class="col-6"><label class="small font-bold">TAG ID</label><input type="text" name="tag" class="form-control" value="{{ $item->tag }}"></div>
                                                    <div class="col-12"><label class="small font-bold">SUPPLIER</label><input type="text" name="keterangan" class="form-control text-uppercase" value="{{ $item->keterangan }}"></div>
                                                    <div class="col-6"><label class="small font-bold">JENIS</label><input type="text" name="jenis" class="form-control text-uppercase" value="{{ $item->jenis }}"></div>
                                                    <div class="col-6"><label class="small font-bold">KLASTER</label><input type="text" name="klaster" class="form-control text-uppercase" value="{{ $item->klaster }}"></div>
                                                    <div class="col-6"><label class="small font-bold">MODAL</label><input type="number" name="harga_awal" class="form-control" value="{{ (int)$item->harga_awal }}"></div>
                                                    <div class="col-6"><label class="small font-bold">QTY</label><input type="number" name="qty_awal" class="form-control" value="{{ $item->qty_awal }}"></div>
                                                </div>
                                                <div class="mt-4 d-flex gap-2">
                                                    <button type="button" class="btn btn-light w-100 rounded-pill font-bold" data-bs-dismiss="modal">BATAL</button>
                                                    <button type="submit" class="btn btn-primary-pro btn w-100 rounded-pill font-bold py-2 shadow">SIMPAN</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                            <tfoot class="footer-total">
                                <tr>
                                    <td colspan="6" class="text-center font-bold py-4 sticky-label text-dark uppercase">Total Keseluruhan</td>
                                    <td class="text-end px-3 font-bold">{{ number_format($stok->sum('harga_awal'), 0, ',', '.') }}</td>
                                    <td class="text-center font-bold">{{ $stok->sum('qty_awal') }}</td>
                                    @foreach($bulanList as $bulan)
                                        @php
                                            $tH = 0; $tQ = 0; 
                                            foreach($stok as $s) {
                                                $d = $s->rincian_bulanan->where('bulan', $bulan)->first();
                                                if($d) { $tH += $d->harga_update; $tQ += $d->qty_update; }
                                            }
                                        @endphp
                                        <td class="text-center font-bold text-primary border-start" style="font-size: 14px;">{{ number_format($tH, 0, ',', '.') }}</td>
                                        <td class="text-center font-bold text-primary" style="font-size: 14px;">{{ $tQ }}</td>
                                    @endforeach
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 2. SIDEBAR MANUAL (PINDAH KE BAWAH) -->
            <div class="col-12 mt-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card metric-card">
                            <div class="metric-header d-flex justify-content-between align-items-center">
                                <span>STOCK KANDANG</span>
                                <button class="btn btn-white btn-sm px-2 py-1 font-bold" style="background:#fff; color:#334155; border-radius:5px; border:none;" data-bs-toggle="modal" data-bs-target="#modalAddStock">+</button>
                            </div>
                            <div class="card-body p-0">
                                @forelse($summaryStock as $s)
                                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                        <div class="d-flex align-items-center">
                                            <form action="{{ route('rincian-hpp.delete-label', $s->id) }}" method="POST" class="m-0">@csrf @method('DELETE')
                                                <button class="btn btn-link text-danger p-0 m-0 me-2" onclick="return confirm('Hapus?')"><i class="fas fa-times-circle"></i></button>
                                            </form>
                                            <span class="text-xs font-bold text-secondary uppercase tracking-wider">{{ $s->label }}</span>
                                        </div>
                                        <input type="text" class="input-sidebar" value="{{ $s->nilai }}" onchange="saveSummary('stock', '{{ $s->label }}', this.value)">
                                    </div>
                                @empty
                                    <div class="p-4 text-center text-slate-400 text-xs italic">Belum ada data.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card metric-card">
                            <div class="metric-header d-flex justify-content-between align-items-center" style="background:#475569;">
                                <span>KLASTER BANGSALAN</span>
                                <button class="btn btn-white btn-sm px-2 py-1 font-bold" style="background:#fff; color:#334155; border-radius:5px; border:none;" data-bs-toggle="modal" data-bs-target="#modalAddKlaster">+</button>
                            </div>
                            <div class="card-body p-0">
                                @forelse($summaryKlaster as $k)
                                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                        <div class="d-flex align-items-center">
                                            <form action="{{ route('rincian-hpp.delete-label', $k->id) }}" method="POST" class="m-0">@csrf @method('DELETE')
                                                <button class="btn btn-link text-danger p-0 m-0 me-2" onclick="return confirm('Hapus?')"><i class="fas fa-times-circle"></i></button>
                                            </form>
                                            <span class="text-xs font-bold text-secondary uppercase tracking-wider">{{ $k->label }}</span>
                                        </div>
                                        <input type="text" class="input-sidebar" style="width: 180px;" value="{{ $k->nilai }}" onchange="saveSummary('klaster', '{{ $k->label }}', this.value)">
                                    </div>
                                @empty
                                    <div class="p-4 text-center text-slate-400 text-xs italic">Belum ada data.</div>
                                @endforelse
                            </div>
                        </div>
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
                    <h5 class="fw-bold text-primary mb-4 text-center uppercase-tracking">Tambah Stok Baru</h5>
                    <div class="row g-3">
                        <div class="col-6"><label class="text-xxs font-bold">TANGGAL</label><input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                        <div class="col-6"><label class="text-xxs font-bold">TAG ID</label><input type="text" name="tag" class="form-control" placeholder="Misal: BB-01"></div>
                        <div class="col-12"><label class="text-xxs font-bold">SUPPLIER</label><input type="text" name="keterangan" class="form-control text-uppercase" required></div>
                        <div class="col-6"><label class="text-xxs font-bold">JENIS</label><input type="text" name="jenis" class="form-control text-uppercase" required></div>
                        <div class="col-6"><label class="text-xxs font-bold">KLASTER</label><input type="text" name="klaster" class="form-control text-uppercase" required></div>
                        <div class="col-6"><label class="text-xxs font-bold">HARGA MODAL</label><input type="number" name="harga_awal" class="form-control" required></div>
                        <div class="col-6"><label class="text-xxs font-bold">QTY</label><input type="number" name="qty_awal" class="form-control" required></div>
                    </div>
                    <button type="submit" class="btn-primary-emerald btn w-100 rounded-pill shadow mt-4 py-2 font-bold" style="background:var(--primary);">SIMPAN DATA</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modals Sidebar Add Labels -->
    <div class="modal fade" id="modalAddStock" tabindex="-1"><div class="modal-dialog modal-sm modal-dialog-centered"><form action="{{ route('rincian-hpp.add-label') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 15px;">@csrf <input type="hidden" name="tipe" value="stock"><div class="modal-body p-4 text-center"><h6 class="font-bold text-xs mb-3 uppercase">Tambah Jenis</h6><input type="text" name="label" class="form-control mb-3 text-uppercase" required autofocus><button type="submit" class="btn btn-dark btn btn-sm w-100 rounded-pill font-bold">TAMBAH</button></div></form></div></div>
    <div class="modal fade" id="modalAddKlaster" tabindex="-1"><div class="modal-dialog modal-sm modal-dialog-centered"><form action="{{ route('rincian-hpp.add-label') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 15px;">@csrf <input type="hidden" name="tipe" value="klaster"><div class="modal-body p-4 text-center"><h6 class="font-bold text-xs mb-3 uppercase">Tambah Klaster</h6><input type="text" name="label" class="form-control mb-3 text-uppercase" required autofocus><button type="submit" class="btn btn-secondary btn-sm w-100 rounded-pill font-bold">TAMBAH</button></div></form></div></div>

    <!-- AJAX LOGIC -->
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