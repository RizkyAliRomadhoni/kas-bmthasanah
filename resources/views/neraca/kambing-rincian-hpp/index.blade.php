<x-app-layout>
     <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body, .main-content { 
            font-family: 'Inter', sans-serif; 
            background-color: #f8fafc; 
            color: #1e293b;
        }

        /* Card Customization */
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.02), 0 1px 2px rgba(0,0,0,0.06); background: #fff; }
        
        /* Table Premium Styling */
        .table-hpp { border-collapse: separate; border-spacing: 0; width: 100%; }
        .table-hpp thead th { 
            background-color: #1e293b; 
            color: #f8fafc; 
            font-size: 11px; 
            font-weight: 600; 
            text-transform: uppercase; 
            letter-spacing: 0.05em; 
            padding: 14px 10px;
            border: none;
            white-space: nowrap;
        }
        .table-hpp tbody td { 
            font-size: 13px; 
            padding: 0 !important; 
            border-bottom: 1px solid #f1f5f9; 
            color: #475569;
            vertical-align: middle;
        }
        
        /* Sticky Column Logic */
        .sticky-col { position: sticky; left: 0; background-color: #fff !important; z-index: 10; border-right: 2px solid #f1f5f9 !important; }
        .sticky-label { position: sticky; left: 0; z-index: 10; background-color: #f8fafc !important; border-right: 2px solid #f1f5f9 !important; }

        /* Input Interaction */
        .input-cell { 
            width: 100%; 
            height: 42px; 
            border: none; 
            background: transparent; 
            text-align: center; 
            font-weight: 500; 
            color: #1e293b; 
            transition: all 0.2s ease;
        }
        .input-cell:focus { outline: none; background-color: #f0f7ff; box-shadow: inset 0 0 0 2px #3b82f6; }

        /* Sidebar Inputs */
        .input-sidebar { 
            border: 1px solid #e2e8f0; 
            background: #fff; 
            text-align: right; 
            width: 80px; 
            font-weight: 600; 
            font-size: 12px; 
            padding: 4px 8px; 
            border-radius: 6px;
        }

        /* Badges & Icons */
        .tag-blue { background: #dbeafe; color: #1e40af; font-weight: 700; padding: 3px 8px; border-radius: 5px; font-size: 11px; }
        .btn-icon-sm { width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; border-radius: 6px; border: none; transition: 0.2s; font-size: 12px; }
        .btn-edit { background: #fef9c3; color: #854d0e; }
        .btn-delete { background: #fee2e2; color: #991b1b; }
        .btn-plus-white { background: #fff; color: #1e293b; width: 24px; height: 24px; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 14px; border: none; font-weight: 800; cursor: pointer; }

        /* Navigation & Header */
        .btn-header { font-weight: 600; font-size: 12px; border-radius: 8px; padding: 10px 20px; text-transform: uppercase; letter-spacing: 0.025em; border: 1px solid #e2e8f0; background: #fff; color: #475569; display: inline-flex; align-items: center; transition: 0.2s; }
        .btn-header:hover { background: #f8fafc; color: #1e293b; transform: translateY(-1px); }
        .btn-primary-custom { background: #3b82f6; color: #fff; border: none; }
        .btn-primary-custom:hover { background: #2563eb; color: #fff; }

        .bg-accent { background-color: #fafbfc !important; }
        .font-bold { font-weight: 700 !important; }
    </style>

    <div class="container-fluid py-4">
        
        <!-- HEADER -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold mb-1 tracking-tight text-dark">Rincian HPP & Stok Bulanan</h4>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary rounded-pill" style="font-size: 10px;">PRO MODE</span>
                    <p class="text-secondary mb-0" style="font-size: 13px;">Hasanah Farm • Periode Mulai September 2025</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('neraca.index') }}" class="btn-header shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i> Neraca
                </a>
                <form action="{{ route('rincian-hpp.tambah-bulan') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-header shadow-sm text-primary">
                        <i class="fas fa-calendar-plus me-2"></i> Bulan
                    </button>
                </form>
                <button class="btn-header btn-primary-custom shadow-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fas fa-plus me-2"></i> Baris Baru
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm text-white mb-4" style="background: #10b981; border-radius: 10px; font-size: 14px;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <!-- TABEL UTAMA -->
            <div class="col-lg-9 col-12 mb-4">
                <div class="card card-custom overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hpp mb-0 text-center">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="px-2" style="width: 45px;">ID</th>
                                    <th rowspan="2" style="min-width: 90px;">Tanggal</th>
                                    <th rowspan="2" class="sticky-col text-start px-3">Supplier / Keterangan</th>
                                    <th rowspan="2" style="min-width: 100px;">Kategori</th>
                                    <th rowspan="2" style="width: 60px;">TAG</th>
                                    <th rowspan="2" style="width: 80px;">Aksi</th>
                                    <th colspan="2" class="bg-accent" style="border-left: 1px solid #334155;">STOK AWAL</th>
                                    @foreach($bulanList as $bulan)
                                        <th colspan="2" style="border-left: 1px solid #334155;">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('M y') }}</th>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th class="bg-accent" style="font-size: 10px; border-left: 1px solid #334155;">HARGA</th>
                                    <th class="bg-accent" style="font-size: 10px;">QTY</th>
                                    @foreach($bulanList as $bulan)
                                        <th style="font-size: 10px; border-left: 1px solid #334155;">HARGA</th>
                                        <th style="font-size: 10px;">QTY</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stok as $index => $item)
                                <tr>
                                    <td class="font-bold text-secondary">{{ $index + 1 }}</td>
                                    <td class="text-xs">{{ $item->tanggal->format('d/m/y') }}</td>
                                    <td class="sticky-col text-start px-3 font-bold text-dark text-uppercase">{{ $item->keterangan }}</td>
                                    <td class="text-uppercase text-secondary" style="font-size: 10px;">{{ $item->jenis }}</td>
                                    <td><span class="tag-blue">{{ $item->tag ?? '-' }}</span></td>
                                    
                                    <td class="px-2">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button class="btn-icon-sm btn-edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            <form action="{{ route('rincian-hpp.destroy', $item->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-icon-sm btn-delete" onclick="return confirm('Hapus baris data ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>

                                    <td class="text-end px-3 font-bold bg-accent" style="border-left: 1px solid #f1f5f9;">{{ number_format($item->harga_awal, 0, ',', '.') }}</td>
                                    <td class="text-center font-bold bg-accent">{{ $item->qty_awal }}</td>
                                    
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

                                <!-- MODAL EDIT -->
                                <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form action="{{ route('rincian-hpp.update-induk', $item->id) }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                                            @csrf @method('PUT')
                                            <div class="modal-body p-4 text-start">
                                                <h6 class="font-bold text-primary mb-4 text-center text-uppercase">Edit Baris Data</h6>
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
                                                    <button type="button" class="btn btn-light w-100 rounded-pill text-xs font-bold" data-bs-dismiss="modal">BATAL</button>
                                                    <button type="submit" class="btn btn-primary w-100 rounded-pill shadow text-xs font-bold">UPDATE</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @empty
                                <tr><td colspan="100" class="text-center py-5 text-secondary">Belum ada baris data.</td></tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="bg-accent">
                                    <td colspan="6" class="text-center text-uppercase font-bold sticky-label py-3 text-dark">Total Keseluruhan</td>
                                    <td class="text-end px-3 font-bold text-dark">{{ number_format($stok->sum('harga_awal'), 0, ',', '.') }}</td>
                                    <td class="text-center font-bold text-dark">{{ $stok->sum('qty_awal') }}</td>
                                    @foreach($bulanList as $bulan)
                                        @php
                                            $tH = 0; $tQ = 0;
                                            foreach($stok as $s) {
                                                $d = $s->rincian_bulanan->where('bulan', $bulan)->first();
                                                if($d) { $tH += $d->harga_update; $tQ += $d->qty_update; }
                                            }
                                        @endphp
                                        <td class="text-center font-bold text-primary border-start">{{ number_format($tH, 0, ',', '.') }}</td>
                                        <td class="text-center font-bold text-primary">{{ $tQ }}</td>
                                    @endforeach
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR SUMMARY -->
            <div class="col-lg-3 col-12">
                <div class="card card-custom mb-4 overflow-hidden">
                    <div class="card-header bg-dark p-3 d-flex justify-content-between align-items-center">
                        <h6 class="text-white mb-0 text-xs font-bold">STOCK KANDANG</h6>
                        <button class="btn-plus-white" data-bs-toggle="modal" data-bs-target="#modalAddStock">+</button>
                    </div>
                    <div class="card-body p-0">
                        @forelse($summaryStock as $s)
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                <form action="{{ route('rincian-hpp.delete-label', $s->id) }}" method="POST" class="m-0">@csrf @method('DELETE')
                                    <button class="btn btn-link text-danger p-0 m-0 me-2" onclick="return confirm('Hapus?')"><i class="fas fa-trash-can" style="font-size: 11px;"></i></button>
                                </form>
                                <span class="text-xxs font-bold text-uppercase text-secondary flex-grow-1">{{ $s->label }}</span>
                                <input type="text" class="input-sidebar" value="{{ $s->nilai }}" onchange="saveSummary('stock', '{{ $s->label }}', this.value)">
                            </div>
                        @empty
                            <div class="p-4 text-center text-xxs text-secondary">Klik (+) untuk tambah jenis</div>
                        @endforelse
                    </div>
                </div>

                <div class="card card-custom mb-4 overflow-hidden">
                    <div class="card-header bg-secondary p-3 d-flex justify-content-between align-items-center">
                        <h6 class="text-white mb-0 text-xs font-bold">KLASTER BANGSALAN</h6>
                        <button class="btn-plus-white" data-bs-toggle="modal" data-bs-target="#modalAddKlaster">+</button>
                    </div>
                    <div class="card-body p-0">
                        @forelse($summaryKlaster as $k)
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                <form action="{{ route('rincian-hpp.delete-label', $k->id) }}" method="POST" class="m-0">@csrf @method('DELETE')
                                    <button class="btn btn-link text-danger p-0 m-0 me-2" onclick="return confirm('Hapus?')"><i class="fas fa-trash-can" style="font-size: 11px;"></i></button>
                                </form>
                                <span class="text-xxs font-bold text-uppercase text-secondary flex-grow-1">{{ $k->label }}</span>
                                <input type="text" class="input-sidebar" style="width: 100px;" value="{{ $k->nilai }}" onchange="saveSummary('klaster', '{{ $k->label }}', this.value)">
                            </div>
                        @empty
                            <div class="p-4 text-center text-xxs text-secondary">Klik (+) untuk tambah klaster</div>
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
                    <h5 class="font-bold text-primary mb-3 text-center text-uppercase">Tambah Stok Baru</h5>
                    <div class="row g-3">
                        <div class="col-6"><label class="text-xxs font-bold">TANGGAL</label><input type="date" name="tanggal" class="form-control rounded-3" value="{{ date('Y-m-d') }}" required></div>
                        <div class="col-6"><label class="text-xxs font-bold">TAG ID</label><input type="text" name="tag" class="form-control rounded-3" placeholder="Misal: BB-01"></div>
                        <div class="col-12"><label class="text-xxs font-bold">SUPPLIER</label><input type="text" name="keterangan" class="form-control rounded-3 text-uppercase" required></div>
                        <div class="col-6"><label class="text-xxs font-bold text-uppercase">Jenis</label><input type="text" name="jenis" class="form-control rounded-3 text-uppercase" placeholder="Merino" required></div>
                        <div class="col-6"><label class="text-xxs font-bold text-uppercase">Klaster</label><input type="text" name="klaster" class="form-control rounded-3 text-uppercase" placeholder="Marto" required></div>
                        <div class="col-6"><label class="text-xxs font-bold text-uppercase">Harga Modal</label><input type="number" name="harga_awal" class="form-control rounded-3" required></div>
                        <div class="col-6"><label class="text-xxs font-bold text-uppercase">Qty Awal</label><input type="number" name="qty_awal" class="form-control rounded-3" required></div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill shadow mt-4 font-bold">SIMPAN DATA</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Sidebar -->
    <div class="modal fade" id="modalAddStock" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <form action="{{ route('rincian-hpp.add-label') }}" method="POST" class="modal-content border-0" style="border-radius: 15px;">@csrf <input type="hidden" name="tipe" value="stock">
                <div class="modal-body p-4"><h6 class="font-bold text-xs mb-3 text-center text-uppercase">Tambah Jenis Stok</h6><input type="text" name="label" class="form-control mb-3 text-uppercase" required autofocus><button type="submit" class="btn btn-dark btn-sm w-100 rounded-pill font-bold">TAMBAH</button></div></form></div></div>
    <div class="modal fade" id="modalAddKlaster" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <form action="{{ route('rincian-hpp.add-label') }}" method="POST" class="modal-content border-0" style="border-radius: 15px;">@csrf <input type="hidden" name="tipe" value="klaster">
                <div class="modal-body p-4"><h6 class="font-bold text-xs mb-3 text-center text-uppercase">Tambah Klaster</h6><input type="text" name="label" class="form-control mb-3 text-uppercase" required autofocus><button type="submit" class="btn btn-secondary btn-sm w-100 rounded-pill font-bold">TAMBAH</button></div></form></div></div>

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