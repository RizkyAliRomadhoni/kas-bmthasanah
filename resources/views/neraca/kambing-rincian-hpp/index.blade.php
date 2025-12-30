<x-app-layout>
    <style>
        /* Modern & Minimalist Table Styling */
        .table-hpp th {
            background-color: #f8f9fa;
            color: #344767;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.65rem;
            letter-spacing: 0.05rem;
            vertical-align: middle;
            border: 1px solid #e9ecef !important;
        }

        .table-hpp td {
            font-size: 0.75rem;
            vertical-align: middle;
            border: 1px solid #e9ecef !important;
            color: #4e4e4e;
        }

        /* Sticky Column for Supplier Name */
        .sticky-col {
            position: sticky;
            left: 0;
            background-color: white;
            z-index: 5;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
        }

        /* Input Excel Style */
        .input-cell {
            width: 100%;
            border: none;
            background: transparent;
            text-align: center;
            padding: 4px;
            font-size: 0.75rem;
            transition: all 0.2s;
            font-weight: 500;
        }

        .input-cell:focus {
            outline: none;
            background-color: #eef1ff;
            box-shadow: inset 0 0 0 1px #5e72e4;
            border-radius: 4px;
        }

        /* Summary Cards */
        .card-summary {
            border-radius: 12px;
            border: none;
            transition: transform 0.3s;
        }

        .card-summary:hover {
            transform: translateY(-5px);
        }

        .bg-gradient-soft-blue { background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%); }
        .bg-gradient-soft-dark { background: linear-gradient(135deg, #32325d 0%, #212529 100%); }

        .text-xxs { font-size: 0.65rem; }
    </style>

    <div class="container-fluid py-4">
        <!-- TOP SUMMARY CARDS -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card card-summary shadow-sm">
                    <div class="card-body p-3">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h5 class="fw-bold mb-0">Rincian Stok & HPP</h5>
                                <p class="text-sm text-secondary mb-0">Hasanah Farm • Periode {{ now()->translatedFormat('F Y') }}</p>
                            </div>
                            <div class="col-4 text-end">
                                <button class="btn btn-dark btn-sm rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                                    <i class="fas fa-plus me-2"></i>Tambah Data
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card card-summary bg-gradient-soft-blue shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center text-white">
                            <div>
                                <p class="text-white opacity-8 text-sm mb-0">Total Populasi</p>
                                <h3 class="fw-bold mb-0 text-white">{{ $summaryJenis->sum('total') }} <span class="text-sm fw-normal">Ekor</span></h3>
                            </div>
                            <div class="icon icon-shape bg-white text-primary rounded-circle shadow">
                                <i class="fas fa-sheep"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- MAIN TABLE -->
            <div class="col-lg-9 col-12 mb-4">
                <div class="card shadow-sm border-0 border-radius-xl">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hpp mb-0 align-items-center">
                                <thead class="text-center">
                                    <tr>
                                        <th rowspan="2" class="px-3" style="width: 50px;">No</th>
                                        <th rowspan="2" style="min-width: 100px;">Tanggal</th>
                                        <th rowspan="2" class="sticky-col" style="min-width: 180px;">Keterangan</th>
                                        <th rowspan="2" style="min-width: 130px;">Jenis</th>
                                        <th colspan="2" class="bg-light-warning">Stok Awal</th>
                                        @foreach($bulanList as $bulan)
                                            <th colspan="2" class="bg-light">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('M-y') }}</th>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <th class="text-xxs py-2">Harga</th>
                                        <th class="text-xxs py-2">Qty</th>
                                        @foreach($bulanList as $bulan)
                                            <th class="text-xxs py-2">Harga</th>
                                            <th class="text-xxs py-2">Qty</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stok as $index => $item)
                                    <tr>
                                        <td class="text-center text-secondary">{{ $index + 1 }}</td>
                                        <td class="text-center px-2">
                                            <span class="text-xs">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</span>
                                        </td>
                                        <td class="sticky-col fw-bold px-3">{{ $item->keterangan }}</td>
                                        <td class="px-3 text-uppercase text-xs">{{ $item->jenis }}</td>
                                        
                                        <!-- STOK AWAL -->
                                        <td class="text-end px-3 font-weight-bold" style="background-color: #fff9e6;">
                                            {{ number_format($item->harga_awal, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center" style="background-color: #fff9e6;">
                                            {{ $item->qty_awal }}
                                        </td>
                                        
                                        <!-- BULANAN UPDATE -->
                                        @foreach($bulanList as $bulan)
                                            @php 
                                                $det = $item->rincian_bulanan->where('bulan', $bulan)->first(); 
                                            @endphp
                                            <td class="p-0">
                                                <input type="number" step="any" class="input-cell" 
                                                    value="{{ $det ? (int)$det->harga_update : '' }}"
                                                    onchange="updateCell('{{ $item->id }}', '{{ $bulan }}', 'harga_update', this.value)">
                                            </td>
                                            <td class="p-0">
                                                <input type="number" class="input-cell" 
                                                    value="{{ $det ? $det->qty_update : '' }}"
                                                    onchange="updateCell('{{ $item->id }}', '{{ $bulan }}', 'qty_update', this.value)">
                                            </td>
                                        @endforeach
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="{{ 6 + (count($bulanList) * 2) }}" class="text-center py-5 text-secondary">
                                            Belum ada data stok kambing.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR SUMMARY -->
            <div class="col-lg-3 col-12">
                <!-- Stock Kandang -->
                <div class="card shadow-sm border-0 mb-4 rounded-4 overflow-hidden">
                    <div class="card-header bg-gradient-soft-dark p-3">
                        <h6 class="text-white mb-0 text-sm fw-bold">Stock Kandang</h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach($summaryJenis as $sj)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-2 border-0 border-bottom">
                                <span class="text-xs font-weight-bold text-uppercase">{{ $sj->jenis }}</span>
                                <span class="badge bg-light text-dark rounded-pill">{{ $sj->total }}</span>
                            </li>
                            @endforeach
                            <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-3 bg-light">
                                <span class="text-xs font-weight-bolder">TOTAL STOK</span>
                                <span class="text-sm font-weight-bolder">{{ $summaryJenis->sum('total') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Klaster Bangsalan -->
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-secondary p-3">
                        <h6 class="text-white mb-0 text-sm fw-bold">Klaster Bangsalan</h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach($summaryKlaster as $sk)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-2 border-0 border-bottom">
                                <span class="text-xs font-weight-bold text-uppercase">{{ $sk->klaster }}</span>
                                <span class="text-xs fw-bold">{{ $sk->total }} <span class="text-muted fw-normal">Unit</span></span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH DATA (ELEGANT FORM) -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('rincian-hpp.store') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                @csrf
                <div class="modal-body p-4">
                    <h5 class="fw-bold mb-3">Tambah Data Kambing Baru</h5>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold">Tanggal Masuk</label>
                            <input type="date" name="tanggal" class="form-control rounded-3" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold">Supplier / Keterangan</label>
                            <input type="text" name="keterangan" placeholder="Contoh: RATIMIN" class="form-control rounded-3 text-uppercase" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold">Jenis Kambing</label>
                            <input type="text" name="jenis" placeholder="Contoh: MERINO" class="form-control rounded-3 text-uppercase" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold">Nama Bangsalan</label>
                            <input type="text" name="klaster" placeholder="Contoh: MARTO" class="form-control rounded-3 text-uppercase" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold">Harga Modal (Rp)</label>
                            <input type="number" name="harga_awal" class="form-control rounded-3" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold">Kuantitas (Qty)</label>
                            <input type="number" name="qty_awal" class="form-control rounded-3" required>
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="button" class="btn btn-light w-100 rounded-pill" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill shadow">Simpan Baris</button>
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
                headers: { 
                    "Content-Type": "application/json", 
                    "X-CSRF-TOKEN": "{{ csrf_token() }}" 
                },
                body: JSON.stringify({ id, bulan, kolom, nilai })
            })
            .then(response => response.json())
            .then(data => {
                // Notifikasi kecil di console saja agar tidak mengganggu user
                console.log("Auto-saved: " + data.message);
            })
            .catch(err => {
                console.error("Gagal menyimpan:", err);
                alert("Koneksi bermasalah, data mungkin belum tersimpan.");
            });
        }
    </script>
</x-app-layout>