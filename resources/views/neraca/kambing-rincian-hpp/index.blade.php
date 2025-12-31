<x-app-layout>
    <style>
        /* Desain Umum & Tipografi */
        .main-content { background-color: #f4f7fe; min-height: 100vh; }
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }

        /* Tabel Excel-Web Modern */
        .table-hpp thead th { 
            background-color: #f8f9fa; 
            color: #344767; 
            font-weight: 700; 
            font-size: 0.65rem; 
            text-transform: uppercase; 
            border: 1px solid #e9ecef !important; 
            vertical-align: middle; 
            letter-spacing: 0.5px;
        }
        .table-hpp tbody td { 
            font-size: 0.72rem; 
            border: 1px solid #e9ecef !important; 
            vertical-align: middle; 
            color: #4e4e4e;
            padding: 0 !important; 
        }
        
        /* Footer Tabel untuk Total */
        .table-hpp tfoot td {
            font-size: 0.75rem;
            font-weight: 800;
            border: 1px solid #e9ecef !important;
            vertical-align: middle;
            color: #344767;
            padding: 10px 5px !important;
        }
        
        /* Sticky Column agar nama supplier tidak hilang saat geser kanan */
        .sticky-col { 
            position: sticky; 
            left: 0; 
            background-color: #ffffff !important; 
            z-index: 10; 
        }

        .sticky-label {
            position: sticky;
            left: 0;
            z-index: 10;
            background-color: #f8f9fa !important;
        }

        /* Input Sel Tabel (Live Edit) */
        .input-cell { 
            width: 100%; 
            height: 38px;
            border: none; 
            background: transparent; 
            text-align: center; 
            font-size: 0.75rem; 
            font-weight: 600; 
            color: #344767;
            transition: all 0.2s ease;
        }
        .input-cell:focus { 
            outline: none; 
            background: #eef1ff; 
            box-shadow: inset 0 0 0 1px #5e72e4; 
            border-radius: 4px;
        }

        /* Sidebar Manual Input */
        .input-sidebar { 
            border: none; 
            background: #f4f7fe; 
            text-align: right; 
            width: 90px; 
            font-weight: bold; 
            font-size: 11px; 
            padding: 4px 8px; 
            border-radius: 6px; 
        }
        .input-sidebar:focus { outline: 1px solid #5e72e4; background: white; }

        .bg-light-warning { background-color: #fff9e6 !important; }
        .bg-total { background-color: #f8f9fa !important; font-weight: 800; color: #344767; }
        .text-xxs { font-size: 0.65rem !important; }
    </style>

    <div class="container-fluid py-4">

        <!-- HEADER UTAMA -->
        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                <h4 class="fw-bold mb-0 text-primary text-uppercase">Rincian HPP & Stok Bulanan</h4>
                <p class="text-sm text-secondary mb-0">Hasanah Farm • Periode Aktif Mulai September 2025</p>
            </div>
            <div class="col-md-6 text-md-end text-center mt-3 mt-md-0">
                <div class="d-flex justify-content-md-end gap-2 flex-wrap justify-content-center">
                    <!-- TOMBOL KEMBALI KE NERACA -->
                    <a href="{{ route('neraca.index') }}" class="btn btn-outline-secondary shadow-sm rounded-pill px-4 btn-sm fw-bold">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Neraca
                    </a>
                    
                    <form action="{{ route('rincian-hpp.tambah-bulan') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary shadow-sm rounded-pill px-4 btn-sm fw-bold">
                            <i class="fas fa-calendar-plus me-2"></i>Tambah Bulan
                        </button>
                    </form>
                    
                    <button class="btn btn-primary shadow-sm rounded-pill px-4 btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="fas fa-plus me-2"></i>Tambah Baris
                    </button>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success text-white text-sm rounded-4 mb-4 border-0 shadow-sm">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <!-- TABEL UTAMA -->
            <div class="col-lg-9 col-12 mb-4">
                <div class="card shadow-sm border-0 border-radius-xl overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hpp mb-0 align-items-center">
                            <thead class="text-center">
                                <tr>
                                    <th rowspan="2" class="px-2" style="width: 40px;">No</th>
                                    <th rowspan="2" style="min-width: 90px;">Tanggal</th>
                                    <th rowspan="2" class="sticky-col">Keterangan</th>
                                    <th rowspan="2" style="min-width: 110px;">Jenis</th>
                                    <th rowspan="2" class="bg-primary text-white" style="width: 60px;">TAG</th>
                                    <th rowspan="2" style="min-width: 80px;">AKSI</th>
                                    <th colspan="2" class="bg-light-warning text-dark">Stok Awal</th>
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
                                    <td class="text-center text-xs">
                                        {{ $item->tanggal ? $item->tanggal->format('d/m/y') : '-' }}
                                    </td>
                                    <td class="sticky-col fw-bold px-3 text-uppercase text-dark">
                                        {{ $item->keterangan }}
                                    </td>
                                    <td class="px-2 text-center text-uppercase" style="font-size: 10px;">{{ $item->jenis }}</td>
                                    <td class="text-center fw-bold text-primary">{{ $item->tag ?? '-' }}</td>
                                    
                                    <!-- TOMBOL AKSI -->
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button class="btn btn-link text-warning p-0 mb-0" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">
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

                                    <td class="text-end px-2 font-weight-bold text-dark" style="background-color: #fffdf5;">
                                        {{ number_format($item->harga_awal, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center font-weight-bold text-dark" style="background-color: #fffdf5;">
                                        {{ $item->qty_awal }}
                                    </td>
                                    
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

                                <!-- MODAL EDIT BARIS -->
                                <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form action="{{ route('rincian-hpp.update-induk', $item->id) }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                                            @csrf @method('PUT')
                                            <div class="modal-body p-4 text-start">
                                                <h5 class="fw-bold text-primary mb-3 text-center">Edit Data Baris</h5>
                                                <div class="row g-3">
                                                    <div class="col-6">
                                                        <label class="text-xxs fw-bold text-secondary">TANGGAL</label>
                                                        <input type="date" name="tanggal" class="form-control rounded-3" value="{{ $item->tanggal ? $item->tanggal->format('Y-m-d') : '' }}">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="text-xxs fw-bold text-secondary">TAG ID</label>
                                                        <input type="text" name="tag" class="form-control rounded-3" value="{{ $item->tag }}">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="text-xxs fw-bold text-secondary">SUPPLIER / KETERANGAN</label>
                                                        <input type="text" name="keterangan" class="form-control rounded-3 text-uppercase" value="{{ $item->keterangan }}">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="text-xxs fw-bold text-secondary">JENIS</label>
                                                        <input type="text" name="jenis" class="form-control rounded-3 text-uppercase" value="{{ $item->jenis }}">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="text-xxs fw-bold text-secondary">KLASTER</label>
                                                        <input type="text" name="klaster" class="form-control rounded-3 text-uppercase" value="{{ $item->klaster }}">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="text-xxs fw-bold text-secondary">HARGA AWAL</label>
                                                        <input type="number" name="harga_awal" class="form-control rounded-3" value="{{ (int)$item->harga_awal }}">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="text-xxs fw-bold text-secondary">QTY AWAL</label>
                                                        <input type="number" name="qty_awal" class="form-control rounded-3" value="{{ $item->qty_awal }}">
                                                    </div>
                                                </div>
                                                <div class="mt-4 d-flex gap-2">
                                                    <button type="button" class="btn btn-light w-100 rounded-pill fw-bold text-xs" data-bs-dismiss="modal">BATAL</button>
                                                    <button type="submit" class="btn btn-primary w-100 rounded-pill shadow fw-bold text-xs text-uppercase">Update Data</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @empty
                                <tr><td colspan="100" class="text-center py-5 text-secondary">Belum ada data stok. Klik "Tambah Baris".</td></tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-light">
                                <tr class="bg-gray-100">
                                    <td colspan="6" class="text-center text-uppercase fw-bold sticky-label py-3">TOTAL KESELURUHAN</td>
                                    <td class="text-end px-2 bg-light-warning fw-bolder text-dark">{{ number_format($stok->sum('harga_awal'), 0, ',', '.') }}</td>
                                    <td class="text-center bg-light-warning fw-bolder text-dark">{{ $stok->sum('qty_awal') }}</td>
                                    @foreach($bulanList as $bulan)
                                        @php
                                            $tH = 0; $tQ = 0;
                                            foreach($stok as $s) {
                                                $d = $s->rincian_bulanan->where('bulan', $bulan)->first();
                                                if($d) { $tH += $d->harga_update; $tQ += $d->qty_update; }
                                            }
                                        @endphp
                                        <td class="text-center border-start fw-bolder text-primary">{{ number_format($tH, 0, ',', '.') }}</td>
                                        <td class="text-center fw-bolder text-primary">{{ $tQ }}</td>
                                    @endforeach
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR SUMMARY -->
            <div class="col-lg-3 col-12">
                <div class="card shadow-sm border-0 mb-4 rounded-4 overflow-hidden">
                    <div class="card-header bg-dark p-3"><h6 class="text-white mb-0 text-xs fw-bold text-uppercase">Stock Kandang (Manual)</h6></div>
                    <div class="card-body p-2">
                        @foreach(['KAMBING', 'DOMBO', 'MERINO', 'CROSS'] as $label)
                            @php $val = $summaryStock->where('label', $label)->first(); @endphp
                            <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-1 px-2">
                                <span class="text-xxs font-weight-bold text-uppercase text-secondary">{{ $label }}</span>
                                <input type="text" class="input-sidebar" value="{{ $val->nilai ?? '' }}" onchange="saveSummary('stock', '{{ $label }}', this.value)">
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-secondary p-3"><h6 class="text-white mb-0 text-xs fw-bold text-uppercase">Klaster Bangsalan (Manual)</h6></div>
                    <div class="card-body p-2">
                        @foreach(['MARTO', 'SUTIK', 'BOINI', 'P WID', 'P JI'] as $label)
                            @php $val = $summaryKlaster->where('label', $label)->first(); @endphp
                            <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-1 px-2">
                                <span class="text-xxs font-weight-bold text-uppercase text-secondary">{{ $label }}</span>
                                <input type="text" class="input-sidebar" value="{{ $val->nilai ?? '' }}" onchange="saveSummary('klaster', '{{ $label }}', this.value)">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH BARIS -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('rincian-hpp.store') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                @csrf
                <div class="modal-body p-4 text-start">
                    <div class="text-center mb-4">
                        <h5 class="fw-bold text-primary">Tambah Stok Baru</h5>
                        <p class="text-xs text-secondary">Masukkan data pembelian awal kambing</p>
                    </div>
                    <div class="row g-3">
                        <div class="col-12"><label class="text-xxs fw-bold text-uppercase text-secondary">Tanggal Masuk</label><input type="date" name="tanggal" class="form-control rounded-3" value="2025-09-01" required></div>
                        <div class="col-12"><label class="text-xxs fw-bold text-uppercase text-secondary">Tag ID</label><input type="text" name="tag" class="form-control rounded-3 text-uppercase" placeholder="Misal: BB-01"></div>
                        <div class="col-12"><label class="text-xxs fw-bold text-uppercase text-secondary">Supplier / Keterangan</label><input type="text" name="keterangan" class="form-control rounded-3 text-uppercase" placeholder="Contoh: RATIMIN" required></div>
                        <div class="col-6"><label class="text-xxs fw-bold text-uppercase text-secondary">Jenis</label><input type="text" name="jenis" class="form-control rounded-3 text-uppercase" placeholder="Contoh: MERINO" required></div>
                        <div class="col-6"><label class="text-xxs fw-bold text-uppercase text-secondary">Klaster (Kandang)</label><input type="text" name="klaster" class="form-control rounded-3 text-uppercase" placeholder="Contoh: MARTO" required></div>
                        <div class="col-6"><label class="text-xxs fw-bold text-secondary text-uppercase">Harga Modal (Rp)</label><input type="number" name="harga_awal" class="form-control rounded-3" required></div>
                        <div class="col-6"><label class="text-xxs fw-bold text-secondary text-uppercase">Qty Awal</label><input type="number" name="qty_awal" class="form-control rounded-3" required></div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="button" class="btn btn-light w-100 rounded-pill fw-bold text-xs" data-bs-dismiss="modal">BATAL</button>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill shadow fw-bold text-xs text-uppercase">Simpan Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- AJAX LOGIC -->
    <script>
        function updateCell(id, bulan, kolom, nilai) {
            fetch("{{ route('rincian-hpp.update') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ id, bulan, kolom, nilai })
            }).then(res => console.log('Update Success'));
        }

        function saveSummary(tipe, label, nilai) {
            fetch("{{ route('rincian-hpp.update-summary') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ tipe, label, nilai })
            }).then(res => console.log('Summary Saved'));
        }
    </script>
</x-app-layout>