<x-app-layout>
    
    <!-- Google Fonts: Inter & Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        /* Modern UI Theme */
        :root {
            --primary-blue: #3b82f6;
            --dark-slate: #1e293b;
            --emerald-farm: #10b981;
            --bg-body: #f8fafc;
            --border-color: #e2e8f0;
        }

        body, .main-content { 
            font-family: 'Inter', sans-serif; 
            background-color: var(--bg-body); 
            color: #334155; 
        }

        /* Card & Section Styling */
        .card-pro { border: none; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.04); background: #fff; }
        .header-section { background: white; border-radius: 1rem; padding: 1.5rem; margin-bottom: 2rem; border: 1px solid var(--border-color); }
        
        /* Table Professional Styling */
        .table-wrapper { position: relative; max-height: 650px; overflow: auto; border-radius: 1rem; border: 1px solid var(--border-color); background: white; }
        .table-neraca { width: 100%; border-collapse: separate; border-spacing: 0; }
        .table-neraca thead th { 
            position: sticky; top: 0; z-index: 40; 
            background: var(--dark-slate) !important; color: #fff !important; 
            font-size: 11px; padding: 14px; text-transform: uppercase; letter-spacing: 0.05em; border: 1px solid #334155;
        }
        .sticky-column { 
            position: sticky; left: 0; z-index: 30; 
            background: #ffffff !important; border-right: 2px solid #f1f5f9 !important; 
            font-weight: 700; color: var(--dark-slate);
        }
        .table-neraca thead th:first-child { z-index: 50; left: 0; }
        .table-neraca tbody td { padding: 0 !important; font-size: 13px; border-bottom: 1px solid #f1f5f9; }

        /* Input Interaction */
        .input-cell { 
            width: 100%; height: 45px; border: none; background: transparent; 
            text-align: center; font-weight: 600; color: var(--dark-slate); transition: 0.2s ease;
        }
        .input-cell:focus { outline: none; background-color: #f0f7ff; box-shadow: inset 0 0 0 2px var(--primary-blue); }

        /* Sidebar Styling */
        .sidebar-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 15px; border-bottom: 1px solid #f1f5f9; }
        .input-sidebar { border: 1px solid var(--border-color); background: #fff; text-align: right; width: 85px; font-weight: 700; font-size: 12px; padding: 4px 8px; border-radius: 8px; color: var(--dark-slate); }
        .input-sidebar:focus { border-color: var(--primary-blue); outline: none; }

        /* Button Pro */
        .btn-pro { font-weight: 700; font-size: 12px; text-transform: uppercase; border-radius: 10px; padding: 10px 20px; transition: 0.3s; display: inline-flex; align-items: center; gap: 8px; border: 1px solid var(--border-color); text-decoration: none !important; }
        .btn-pro:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .btn-primary-pro { background: var(--primary-blue); color: white; border: none; }
        .btn-primary-pro:hover { background: #2563eb; color: white !important; }
        
        .btn-action-sm { width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: none; transition: 0.2s; font-size: 12px; }
        .btn-edit { background: #fef9c3; color: #854d0e; }
        .btn-delete { background: #fee2e2; color: #991b1b; }

        /* Status & Accents */
        .badge-tag { background: #dbeafe; color: #1e40af; font-weight: 700; padding: 4px 10px; border-radius: 6px; font-size: 11px; }
        .bg-light-warning { background-color: #fffdf5 !important; }
        .footer-total { background-color: #f8fafc !important; font-weight: 800; color: var(--dark-slate); }
    </style>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">

            <!-- 1. HEADER UTAMA -->
            <div class="header-section shadow-sm d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="text-start">
                    <h4 class="fw-bold text-dark mb-0 uppercase tracking-tighter" style="font-family: 'Plus Jakarta Sans';">Rincian HPP & Stok Bulanan</h4>
                    <p class="text-secondary mb-0" style="font-size: 14px;">Hasanah Farm • Dashboard Manajemen Aset Terpadu</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('neraca.index') }}" class="btn-pro bg-white shadow-sm">
                        <i class="fas fa-arrow-left"></i> Neraca
                    </a>
                    <form action="{{ route('rincian-hpp.tambah-bulan') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-pro bg-white text-primary shadow-sm" onclick="return confirm('Tambah kolom bulan baru?')">
                            <i class="fas fa-calendar-plus"></i> + Bulan
                        </button>
                    </form>
                    <button class="btn-pro btn-primary-pro shadow-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="fas fa-plus"></i> Baris Baru
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm text-white mb-4 py-3 px-4 mx-2" style="background: var(--emerald-farm); border-radius: 12px;">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif

            <div class="row g-4">
                <!-- 2. TABEL UTAMA (KIRI - COL 9) -->
                <div class="col-lg-9 col-12">
                    <div class="card card-pro overflow-hidden">
                        <div class="table-wrapper">
                            <table class="table-neraca text-center">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="width: 45px;">ID</th>
                                        <th rowspan="2" style="width: 100px;">Tanggal</th>
                                        <th rowspan="2" class="sticky-column text-start">Supplier / Keterangan</th>
                                        <th rowspan="2" style="width: 110px;">Jenis</th>
                                        <th rowspan="2" style="width: 60px;">TAG</th>
                                        <th rowspan="2" style="width: 90px;">Aksi</th>
                                        <th colspan="2" class="bg-light-warning text-dark border-start">STOK AWAL</th>
                                        @foreach($bulanList as $bulan)
                                            <th colspan="2" class="border-start">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('M y') }}</th>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <th class="bg-light-warning" style="font-size: 10px;">HARGA</th>
                                        <th class="bg-light-warning" style="font-size: 10px;">QTY</th>
                                        @foreach($bulanList as $bulan)
                                            <th style="font-size: 10px; border-left: 1px solid #334155;">HARGA</th>
                                            <th style="font-size: 10px;">QTY</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stok as $index => $item)
                                    <tr>
                                        <td class="font-bold text-secondary text-center">{{ $index + 1 }}</td>
                                        <td class="text-xs text-center">{{ $item->tanggal->format('d/m/y') }}</td>
                                        <td class="sticky-column text-start px-3 font-bold text-dark text-uppercase">{{ $item->keterangan }}</td>
                                        <td class="text-uppercase text-secondary text-center" style="font-size: 10px;">{{ $item->jenis }}</td>
                                        <td class="text-center"><span class="badge-tag">{{ $item->tag ?? '-' }}</span></td>
                                        
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <button class="btn-action-sm btn-edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}"><i class="fas fa-pen"></i></button>
                                                <form action="{{ route('rincian-hpp.destroy', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn-action-sm btn-delete" onclick="return confirm('Hapus baris data ini?')"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>

                                        <td class="text-end px-3 font-bold bg-light-warning border-start">{{ number_format($item->harga_awal, 0, ',', '.') }}</td>
                                        <td class="text-center font-bold bg-light-warning">{{ $item->qty_awal }}</td>
                                        
                                        @foreach($bulanList as $bulan)
                                            @php $det = $item->rincian_bulanan->where('bulan', $bulan)->first(); @endphp
                                            <td style="border-left: 1px solid #f1f5f9;">
                                                <input type="number" class="input-cell" value="{{ $det ? (int)$det->harga_update : '' }}" onchange="updateCell('{{ $item->id }}', '{{ $bulan }}', 'harga_update', this.value)">
                                            </td>
                                            <td>
                                                <input type="number" class="input-cell" value="{{ $det ? $det->qty_update : '' }}" onchange="updateCell('{{ $item->id }}', '{{ $bulan }}', 'qty_update', this.value)">
                                            </td>
                                        @endforeach
                                    </tr>

                                    <!-- MODAL EDIT INDUK -->
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
                                                    <div class="mt-4 d-flex gap-2">
                                                        <button type="button" class="btn btn-light w-100 rounded-pill font-bold" data-bs-dismiss="modal">BATAL</button>
                                                        <button type="submit" class="btn btn-primary-pro btn w-100 rounded-pill font-bold py-2 shadow">SIMPAN</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    @empty
                                    <tr><td colspan="100" class="text-center py-5">Belum ada baris data.</td></tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="footer-total">
                                    <tr>
                                        <td colspan="6" class="text-center font-bold py-3 sticky-label text-dark uppercase">Total Keseluruhan</td>
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

                <!-- 3. SIDEBAR (KANAN - COL 3) -->
                <div class="col-lg-3 col-12">
                    <!-- CARD STOCK KANDANG -->
                    <div class="card card-pro mb-4 overflow-hidden">
                        <div class="sidebar-header d-flex justify-content-between align-items-center">
                            <span class="font-bold">STOCK KANDANG</span>
                            <button class="btn btn-white btn-sm m-0 px-2 py-1 font-weight-bold" data-bs-toggle="modal" data-bs-target="#modalAddStock" style="color: #1e293b; border: none; border-radius: 6px; font-size: 16px;">+</button>
                        </div>
                        <div class="card-body p-0 bg-white">
                            @forelse($summaryStock as $s)
                                <div class="sidebar-item">
                                    <form action="{{ route('rincian-hpp.delete-label', $s->id) }}" method="POST" class="m-0">@csrf @method('DELETE')
                                        <button class="btn btn-link text-danger p-0 m-0 me-2" onclick="return confirm('Hapus?')"><i class="fas fa-trash-alt" style="font-size: 11px;"></i></button>
                                    </form>
                                    <span class="text-xxs font-bold text-uppercase text-secondary flex-grow-1">{{ $s->label }}</span>
                                    <input type="text" class="input-sidebar" value="{{ $s->nilai }}" onchange="saveSummary('stock', '{{ $s->label }}', this.value)">
                                </div>
                            @empty
                                <div class="p-4 text-center text-xxs text-secondary italic">Klik (+) untuk tambah jenis</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- CARD KLASTER BANGSALAN -->
                    <div class="card card-pro overflow-hidden shadow-sm">
                        <div class="sidebar-header bg-secondary d-flex justify-content-between align-items-center">
                            <span class="font-bold uppercase">Klaster Bangsalan</span>
                            <button class="btn btn-white btn-sm m-0 px-2 py-1 font-weight-bold" data-bs-toggle="modal" data-bs-target="#modalAddKlaster" style="color: #1e293b; border: none; border-radius: 6px; font-size: 16px;">+</button>
                        </div>
                        <div class="card-body p-0 bg-white">
                            @forelse($summaryKlaster as $k)
                                <div class="sidebar-item">
                                    <form action="{{ route('rincian-hpp.delete-label', $k->id) }}" method="POST" class="m-0">@csrf @method('DELETE')
                                        <button class="btn btn-link text-danger p-0 m-0 me-2" onclick="return confirm('Hapus?')"><i class="fas fa-trash-alt" style="font-size: 11px;"></i></button>
                                    </form>
                                    <span class="text-xxs font-bold text-uppercase text-secondary flex-grow-1">{{ $k->label }}</span>
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

        <!-- MODAL TAMBAH BARIS BARU -->
        <div class="modal fade" id="addModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('rincian-hpp.store') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 24px;">
                    @csrf
                    <div class="modal-body p-4 text-start">
                        <h5 class="fw-bold text-primary mb-4 text-center uppercase">Tambah Stok Baru</h5>
                        <div class="row g-3">
                            <div class="col-6"><label class="text-xxs font-bold">TANGGAL</label><input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                            <div class="col-6"><label class="text-xxs font-bold">TAG ID</label><input type="text" name="tag" class="form-control" placeholder="Misal: BB-01"></div>
                            <div class="col-12"><label class="text-xxs font-bold">SUPPLIER</label><input type="text" name="keterangan" class="form-control text-uppercase" required></div>
                            <div class="col-6"><label class="text-xxs font-bold">JENIS</label><input type="text" name="jenis" class="form-control text-uppercase" placeholder="Merino" required></div>
                            <div class="col-6"><label class="text-xxs font-bold">KLASTER</label><input type="text" name="klaster" class="form-control text-uppercase" placeholder="Marto" required></div>
                            <div class="col-6"><label class="text-xxs font-bold">HARGA AWAL</label><input type="number" name="harga_awal" class="form-control" required></div>
                            <div class="col-6"><label class="text-xxs font-bold">QTY</label><input type="number" name="qty_awal" class="form-control" required></div>
                        </div>
                        <button type="submit" class="btn-primary-emerald btn w-100 rounded-pill shadow mt-4 py-2 font-bold text-white border-0" style="background: var(--primary-blue);">SIMPAN DATA</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL TAMBAH KATEGORI SIDEBAR -->
        <div class="modal fade" id="modalAddStock" tabindex="-1">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <form action="{{ route('rincian-hpp.add-label') }}" method="POST" class="modal-content border-0 shadow" style="border-radius: 15px;">
                    @csrf <input type="hidden" name="tipe" value="stock">
                    <div class="modal-body p-4 text-center">
                        <h6 class="font-bold text-xs mb-3 uppercase">Tambah Jenis Stok</h6>
                        <input type="text" name="label" class="form-control mb-3 text-uppercase" required autofocus>
                        <button type="submit" class="btn btn-dark btn-sm w-100 rounded-pill font-bold border-0">TAMBAH</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="modalAddKlaster" tabindex="-1">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <form action="{{ route('rincian-hpp.add-label') }}" method="POST" class="modal-content border-0 shadow" style="border-radius: 15px;">
                    @csrf <input type="hidden" name="tipe" value="klaster">
                    <div class="modal-body p-4 text-center">
                        <h6 class="font-bold text-xs mb-3 uppercase">Tambah Klaster</h6>
                        <input type="text" name="label" class="form-control mb-3 text-uppercase" required autofocus>
                        <button type="submit" class="btn btn-secondary btn-sm w-100 rounded-pill font-bold border-0">TAMBAH</button>
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
</x-app-layout>