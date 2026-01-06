<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Google Font: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body, .main-content { 
            font-family: 'Inter', sans-serif; 
            background-color: #f8fafc; 
            color: #1e293b;
        }

        /* Modern Typography */
        .uppercase-tracking { text-transform: uppercase; letter-spacing: 0.05em; }

        /* Card Styling */
        .card-elegant { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); background: #fff; }
        
        /* Table Design - Premium & Clean */
        .table-hpp { width: 100%; border-collapse: separate; border-spacing: 0; }
        .table-hpp thead th { 
            background-color: #1e293b !important; 
            color: #ffffff !important; 
            font-size: 11px; 
            font-weight: 700; 
            padding: 15px 10px;
            border: 1px solid #334155 !important;
            vertical-align: middle;
            text-transform: uppercase;
        }
        .table-hpp tbody td { 
            font-size: 13px; 
            padding: 0 !important; 
            border: 1px solid #f1f5f9 !important; 
            color: #475569;
            vertical-align: middle;
        }
        
        /* Sticky Column Logic */
        .sticky-col { position: sticky; left: 0; background-color: #ffffff !important; z-index: 10; border-right: 2px solid #f1f5f9 !important; }
        .sticky-label { position: sticky; left: 0; z-index: 10; background-color: #f8fafc !important; border-right: 2px solid #f1f5f9 !important; }

        /* Input Table Interactions */
        .input-cell { 
            width: 100%; height: 45px; border: none; background: transparent; 
            text-align: center; font-weight: 600; color: #1e293b; transition: 0.2s ease;
        }
        .input-cell:focus { outline: none; background-color: #f0fdf4; box-shadow: inset 0 0 0 2px #10b981; }

        /* Action UI */
        .btn-action-sm { width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: none; transition: 0.2s; font-size: 11px; }
        .btn-edit { background: #fef9c3; color: #854d0e; }
        .btn-delete { background: #fee2e2; color: #991b1b; }
        .btn-edit:hover { background: #fde68a; }
        .btn-delete:hover { background: #fecaca; }

        /* Sidebar Styling */
        .sidebar-card { border-radius: 20px; overflow: hidden; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        .sidebar-header { background: #1e293b; color: #fff; padding: 15px; font-weight: 700; font-size: 12px; }
        .input-sidebar { border: 1px solid #e2e8f0; background: #fff; text-align: right; width: 85px; font-weight: 700; font-size: 12px; padding: 5px 10px; border-radius: 8px; }
        .btn-plus-white { background: #ffffff !important; color: #1e293b !important; width: 26px; height: 26px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 900; border: none; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }

        /* Header UI */
        .btn-header-pro { background: #fff; color: #475569; font-weight: 700; font-size: 12px; padding: 10px 20px; border-radius: 12px; border: 1px solid #e2e8f0; transition: 0.2s; text-decoration: none !important; }
        .btn-header-pro:hover { background: #f8fafc; color: #10b981; border-color: #10b981; transform: translateY(-2px); }
        .btn-primary-emerald { background: #10b981; color: #fff; border: none; }
        .btn-primary-emerald:hover { background: #059669; color: #fff; }

        .tag-pill { background: #f0fdf4; color: #166534; font-weight: 800; padding: 4px 10px; border-radius: 6px; font-size: 11px; }
        .bg-soft-yellow { background-color: #fffdf2 !important; }
    </style>

    <div class="container-fluid py-4 text-start">
        
        <!-- HEADER -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3 px-2">
            <div>
                <h3 class="fw-bolder text-dark mb-1 uppercase-tracking" style="font-size: 26px;">Rincian HPP & Stok</h3>
                <p class="text-secondary mb-0" style="font-size: 14px;">Hasanah Farm • Sistem Pengelolaan Aset Real-time</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('neraca.index') }}" class="btn-header-pro shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i> Neraca
                </a>
                <form action="{{ route('rincian-hpp.tambah-bulan') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-header-pro shadow-sm text-success">
                        <i class="fas fa-calendar-plus me-2"></i> + Bulan
                    </button>
                </form>
                <button class="btn-header-pro btn-primary-emerald shadow-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fas fa-plus me-2"></i> Baris Baru
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm text-white mb-4 py-3 px-4 mx-2" style="background: #10b981; border-radius: 12px;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="row px-2">
            <!-- TABEL (KIRI) -->
            <div class="col-lg-9 col-12 mb-4">
                <div class="card card-elegant overflow-hidden">
                    <div class="table-responsive">
                        <table class="table-hpp text-center">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width: 45px;">ID</th>
                                    <th rowspan="2" style="width: 100px;">Tanggal</th>
                                    <th rowspan="2" class="sticky-col text-start px-4" style="width: 200px;">Supplier / Keterangan</th>
                                    <th rowspan="2" style="min-width: 110px;">Jenis</th>
                                    <th rowspan="2" style="width: 65px;">TAG</th>
                                    <th rowspan="2" style="width: 90px;">Aksi</th>
                                    <th colspan="2" class="bg-soft-yellow" style="border-left: 2px solid #334155;">STOK AWAL</th>
                                    @foreach($bulanList as $bulan)
                                        <th colspan="2" style="border-left: 2px solid #334155;">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('M y') }}</th>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th class="bg-soft-yellow" style="font-size: 10px; border-left: 2px solid #334155;">HARGA</th>
                                    <th class="bg-soft-yellow" style="font-size: 10px;">QTY</th>
                                    @foreach($bulanList as $bulan)
                                        <th style="font-size: 10px; border-left: 2px solid #334155;">HARGA</th>
                                        <th style="font-size: 10px;">QTY</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stok as $index => $item)
                                <tr>
                                    <td class="fw-bold text-secondary">{{ $index + 1 }}</td>
                                    <td class="text-xs">{{ $item->tanggal->format('d/m/y') }}</td>
                                    <td class="sticky-col text-start px-4 fw-bold text-dark uppercase-tracking" style="font-size: 12px;">{{ $item->keterangan }}</td>
                                    <td class="text-uppercase text-secondary" style="font-size: 10px;">{{ $item->jenis }}</td>
                                    <td><span class="tag-pill">{{ $item->tag ?? '-' }}</span></td>
                                    <td class="px-2">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button class="btn-action-sm btn-edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            <form action="{{ route('rincian-hpp.destroy', $item->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-action-sm btn-delete" onclick="return confirm('Hapus baris data ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="text-end px-3 fw-bold bg-soft-yellow" style="border-left: 1px solid #f1f5f9;">{{ number_format($item->harga_awal, 0, ',', '.') }}</td>
                                    <td class="text-center fw-bold bg-soft-yellow">{{ $item->qty_awal }}</td>
                                    @foreach($bulanList as $bulan)
                                        @php $det = $item->rincian_bulanan->where('bulan', $bulan)->first(); @endphp
                                        <td style="border-left: 1px solid #f1f5f9;"><input type="number" class="input-cell" value="{{ $det ? (int)$det->harga_update : '' }}" onchange="updateCell('{{ $item->id }}', '{{ $bulan }}', 'harga_update', this.value)"></td>
                                        <td><input type="number" class="input-cell" value="{{ $det ? $det->qty_update : '' }}" onchange="updateCell('{{ $item->id }}', '{{ $bulan }}', 'qty_update', this.value)"></td>
                                    @endforeach
                                </tr>

                                <!-- MODAL EDIT PER BARIS -->
                                <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form action="{{ route('rincian-hpp.update', $item->id) }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 24px;">
                                            @csrf @method('PUT')
                                            <div class="modal-body p-4 text-start">
                                                <h5 class="fw-bold text-primary mb-4 text-center">EDIT BARIS DATA</h5>
                                                <div class="row g-3">
                                                    <div class="col-6"><label class="text-xxs fw-bold">TANGGAL</label><input type="date" name="tanggal" class="form-control" value="{{ $item->tanggal->format('Y-m-d') }}"></div>
                                                    <div class="col-6"><label class="text-xxs fw-bold">TAG ID</label><input type="text" name="tag" class="form-control" value="{{ $item->tag }}"></div>
                                                    <div class="col-12"><label class="text-xxs fw-bold">SUPPLIER</label><input type="text" name="keterangan" class="form-control text-uppercase" value="{{ $item->keterangan }}"></div>
                                                    <div class="col-6"><label class="text-xxs fw-bold">JENIS</label><input type="text" name="jenis" class="form-control text-uppercase" value="{{ $item->jenis }}"></div>
                                                    <div class="col-6"><label class="text-xxs fw-bold">KLASTER</label><input type="text" name="klaster" class="form-control text-uppercase" value="{{ $item->klaster }}"></div>
                                                    <div class="col-6"><label class="text-xxs fw-bold">MODAL</label><input type="number" name="harga_awal" class="form-control" value="{{ (int)$item->harga_awal }}"></div>
                                                    <div class="col-6"><label class="text-xxs fw-bold">QTY</label><input type="number" name="qty_awal" class="form-control" value="{{ $item->qty_awal }}"></div>
                                                </div>
                                                <div class="mt-4 d-flex gap-2">
                                                    <button type="button" class="btn btn-light w-100 rounded-pill fw-bold" data-bs-dismiss="modal">BATAL</button>
                                                    <button type="submit" class="btn-primary-emerald btn w-100 rounded-pill font-bold shadow py-2">SIMPAN</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-soft-yellow">
                                <tr>
                                    <td colspan="6" class="text-center fw-bold py-4 sticky-label text-dark">TOTAL KESELURUHAN</td>
                                    <td class="text-end px-3 fw-bold">{{ number_format($stok->sum('harga_awal'), 0, ',', '.') }}</td>
                                    <td class="text-center fw-bold">{{ $stok->sum('qty_awal') }}</td>
                                    @foreach($bulanList as $bulan)
                                        @php
                                            $tH = 0; $tQ = 0; 
                                            foreach($stok as $s) {
                                                $d = $s->rincian_bulanan->where('bulan', $bulan)->first();
                                                if($d) { $tH += $d->harga_update; $tQ += $d->qty_update; }
                                            }
                                        @endphp
                                        <td class="text-center fw-bold text-success border-start" style="font-size: 14px;">{{ number_format($tH, 0, ',', '.') }}</td>
                                        <td class="text-center fw-bold text-success" style="font-size: 14px;">{{ $tQ }}</td>
                                    @endforeach
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR (KANAN) -->
            <div class="col-lg-3 col-12">
                <div class="card sidebar-card mb-4 bg-white shadow-sm">
                    <div class="sidebar-header d-flex justify-content-between align-items-center">
                        <span class="uppercase-tracking">Stock Kandang</span>
                        <button class="btn-plus-white" data-bs-toggle="modal" data-bs-target="#modalAddStock">+</button>
                    </div>
                    <div class="card-body p-0">
                        @forelse($summaryStock as $s)
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                <form action="{{ route('rincian-hpp.delete-label', $s->id) }}" method="POST" class="m-0">@csrf @method('DELETE')
                                    <button class="btn btn-link text-danger p-0 m-0 me-2"><i class="fas fa-trash-alt" style="font-size: 11px;"></i></button>
                                </form>
                                <span class="text-xxs fw-bold text-uppercase text-secondary flex-grow-1">{{ $s->label }}</span>
                                <input type="text" class="input-sidebar" value="{{ $s->nilai }}" onchange="saveSummary('stock', '{{ $s->label }}', this.value)">
                            </div>
                        @empty
                            <div class="p-4 text-center text-xxs text-secondary italic">Klik (+) untuk tambah jenis</div>
                        @endforelse
                    </div>
                </div>

                <div class="card sidebar-card bg-white shadow-sm">
                    <div class="sidebar-header bg-secondary d-flex justify-content-between align-items-center">
                        <span class="uppercase-tracking">Klaster Bangsalan</span>
                        <button class="btn-plus-white" data-bs-toggle="modal" data-bs-target="#modalAddKlaster">+</button>
                    </div>
                    <div class="card-body p-0">
                        @forelse($summaryKlaster as $k)
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                <form action="{{ route('rincian-hpp.delete-label', $k->id) }}" method="POST" class="m-0">@csrf @method('DELETE')
                                    <button class="btn btn-link text-danger p-0 m-0 me-2"><i class="fas fa-trash-alt" style="font-size: 11px;"></i></button>
                                </form>
                                <span class="text-xxs fw-bold text-uppercase text-secondary flex-grow-1">{{ $k->label }}</span>
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

    <!-- MODAL ADD BARIS UTAMA -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('rincian-hpp.store') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 24px;">
                @csrf
                <div class="modal-body p-4 text-start">
                    <h5 class="fw-bold text-primary mb-4 text-center">TAMBAH STOK BARU</h5>
                    <div class="row g-3">
                        <div class="col-6"><label class="text-xxs fw-bold">TANGGAL</label><input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                        <div class="col-6"><label class="text-xxs fw-bold">TAG ID</label><input type="text" name="tag" class="form-control" placeholder="Misal: BB-01"></div>
                        <div class="col-12"><label class="text-xxs fw-bold">SUPPLIER</label><input type="text" name="keterangan" class="form-control text-uppercase" required></div>
                        <div class="col-6"><label class="text-xxs fw-bold">JENIS</label><input type="text" name="jenis" class="form-control text-uppercase" required></div>
                        <div class="col-6"><label class="text-xxs fw-bold">KLASTER</label><input type="text" name="klaster" class="form-control text-uppercase" required></div>
                        <div class="col-6"><label class="text-xxs fw-bold">HARGA AWAL</label><input type="number" name="harga_awal" class="form-control" required></div>
                        <div class="col-6"><label class="text-xxs fw-bold">QTY</label><input type="number" name="qty_awal" class="form-control" required></div>
                    </div>
                    <button type="submit" class="btn-primary-emerald btn w-100 rounded-pill shadow mt-4 py-2 font-bold">SIMPAN DATA</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modals Sidebar Add Labels -->
    <div class="modal fade" id="modalAddStock" tabindex="-1"><div class="modal-dialog modal-sm modal-dialog-centered"><form action="{{ route('rincian-hpp.add-label') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 15px;">@csrf <input type="hidden" name="tipe" value="stock"><div class="modal-body p-4 text-center"><h6 class="font-bold text-xs mb-3 uppercase">Tambah Jenis</h6><input type="text" name="label" class="form-control mb-3 text-uppercase" required autofocus><button type="submit" class="btn-dark btn btn-sm w-100 rounded-pill font-bold">TAMBAH</button></div></form></div></div>
    <div class="modal fade" id="modalAddKlaster" tabindex="-1"><div class="modal-dialog modal-sm modal-dialog-centered"><form action="{{ route('rincian-hpp.add-label') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 15px;">@csrf <input type="hidden" name="tipe" value="klaster"><div class="modal-body p-4 text-center"><h6 class="font-bold text-xs mb-3 uppercase">Tambah Klaster</h6><input type="text" name="label" class="form-control mb-3 text-uppercase" required autofocus><button type="submit" class="btn btn-secondary btn btn-sm w-100 rounded-pill font-bold">TAMBAH</button></div></form></div></div>

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