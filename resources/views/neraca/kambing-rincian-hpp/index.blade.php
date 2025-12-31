<x-app-layout>
     <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
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
            font-size: 0.75rem; 
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
        }
        
        /* Sticky Column agar nama supplier & label Total tidak hilang saat geser kanan */
        .sticky-col { 
            position: sticky; 
            left: 0; 
            background-color: #ffffff !important; 
            z-index: 10; 
            min-width: 180px;
            border-right: 2px solid #e9ecef !important;
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
        }

        /* Navigasi Sub-Menu */
        .btn-nav {
            background-color: white; color: #344767; font-size: 0.7rem; font-weight: 700;
            padding: 8px 16px; border-radius: 8px; border: 1px solid #e9ecef;
            text-decoration: none !important; display: inline-flex; align-items: center;
            transition: all 0.2s ease; text-transform: uppercase;
        }
        .btn-nav:hover { 
            background-color: #f8f9fa; border-color: #5e72e4; color: #5e72e4 !important; 
            transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }
        .btn-nav i { margin-right: 8px; font-size: 0.85rem; }

        .bg-light-warning { background-color: #fff9e6 !important; }
        .bg-total { background-color: #f8f9fa !important; }
        .text-xxs { font-size: 0.65rem !important; }
    </style>

    <div class="container-fluid py-4">

        <!-- HEADER UTAMA -->
        <div class="row align-items-center mb-4">
            <div class="col-md-7">
                <h4 class="fw-bold mb-0 text-primary">Rincian HPP & Stok Bulanan</h4>
                <p class="text-sm text-secondary mb-0">Hasanah Farm • Periode Aktif Mulai September 2025</p>
            </div>
            <div class="col-md-5 text-md-end text-center mt-3 mt-md-0">
                <div class="d-flex justify-content-md-end gap-2">
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

        <!-- NAVIGASI MENU LENGKAP -->
        <div class="card shadow-none border-0 bg-transparent mb-4">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('kambing-akun.index') }}" class="btn-nav"><i class="fas fa-sheep text-dark"></i> Stok Kambing</a>
                <a href="{{ route('rincian-hpp.index') }}" class="btn-nav"><i class="fas fa-file-invoice-dollar text-success"></i> Rincian HPP & Stok</a>
                <a href="{{ route('pakan.index') }}" class="btn-nav"><i class="fas fa-utensils text-warning"></i> Pakan</a>
                <a href="{{ route('kandang.index') }}" class="btn-nav"><i class="fas fa-tools text-info"></i> Kandang</a>
                <a href="{{ route('perlengkapan.index') }}" class="btn-nav"><i class="fas fa-box text-primary"></i> Perlengkapan</a>
                <a href="{{ route('upah.index') }}" class="btn-nav"><i class="fas fa-user-tie text-secondary"></i> Upah</a>
                <a href="{{ route('operasional.index') }}" class="btn-nav"><i class="fas fa-cogs text-secondary"></i> Operasional</a>
                <div class="vr mx-1 d-none d-md-block" style="height: 30px; align-self: center; opacity: 0.2;"></div>
                <a href="{{ route('neraca.penjualan.index') }}" class="btn-nav"><i class="fas fa-shopping-cart text-primary"></i> Penjualan</a>
                <a href="{{ route('neraca.rincian-kambing.index') }}" class="btn-nav"><i class="fas fa-horse-head text-success"></i> Rincian (HPP & Mati)</a>
                <a href="{{ route('piutang.index') }}" class="btn-nav"><i class="fas fa-file-invoice-dollar text-warning"></i> Piutang</a>
                <a href="{{ route('hutang.index') }}" class="btn-nav"><i class="fas fa-hand-holding-usd text-danger"></i> Hutang</a>
            </div>
        </div>

        <div class="row">
            <!-- TABEL RINCIAN -->
            <div class="col-lg-9 col-12 mb-4">
                <div class="card shadow-sm border-0 border-radius-xl overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hpp mb-0 align-items-center">
                            <thead class="text-center">
                                <tr>
                                    <th rowspan="2" class="px-3" style="width: 40px;">No</th>
                                    <th rowspan="2" style="min-width: 90px;">Tanggal</th>
                                    <th rowspan="2" class="sticky-col">Keterangan (Supplier)</th>
                                    <th rowspan="2" style="min-width: 120px;">Jenis</th>
                                    <th colspan="2" class="bg-light-warning text-dark">Stok Awal</th>
                                    @foreach($bulanList as $bulan)
                                        <th colspan="2" class="bg-light text-dark">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('M-y') }}</th>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th class="py-2 bg-light-warning">Harga</th>
                                    <th class="py-2 bg-light-warning">Qty</th>
                                    @foreach($bulanList as $bulan)
                                        <th class="py-2 bg-light text-xxs">Harga</th>
                                        <th class="py-2 bg-light text-xxs">Qty</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stok as $index => $item)
                                <tr>
                                    <td class="text-center text-secondary font-weight-bold px-2">{{ $index + 1 }}</td>
                                    <td class="text-center text-xs px-2">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/y') }}
                                    </td>
                                    <td class="sticky-col fw-bold px-3 text-uppercase text-dark">
                                        {{ $item->keterangan }}
                                    </td>
                                    <td class="px-2 text-center text-uppercase" style="font-size: 10px; color: #8392ab;">
                                        {{ $item->jenis }}
                                    </td>
                                    
                                    <td class="text-end px-3 font-weight-bold text-dark" style="background-color: #fffdf5;">
                                        {{ number_format($item->harga_awal, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center font-weight-bold text-dark" style="background-color: #fffdf5;">
                                        {{ $item->qty_awal }}
                                    </td>
                                    
                                    @foreach($bulanList as $bulan)
                                        @php 
                                            $detail = $item->rincian_bulanan->where('bulan', $bulan)->first(); 
                                        @endphp
                                        <td>
                                            <input type="number" class="input-cell" 
                                                value="{{ $detail ? (int)$detail->harga_update : '' }}"
                                                onchange="updateCell('{{ $item->id }}', '{{ $bulan }}', 'harga_update', this.value)">
                                        </td>
                                        <td>
                                            <input type="number" class="input-cell" 
                                                value="{{ $detail ? $detail->qty_update : '' }}"
                                                onchange="updateCell('{{ $item->id }}', '{{ $bulan }}', 'qty_update', this.value)">
                                        </td>
                                    @endforeach
                                </tr>
                                @empty
                                <tr><td colspan="100" class="text-center py-5 text-secondary">Belum ada data.</td></tr>
                                @endforelse
                            </tbody>

                            <!-- TFOOT: BARIS TOTAL KESELURUHAN -->
                            <tfoot class="bg-total fw-bold text-dark">
                                <tr>
                                    <td colspan="4" class="text-center text-uppercase sticky-label py-3" style="background-color: #f8f9fa !important;">
                                        TOTAL KESELURUHAN
                                    </td>
                                    <td class="text-end px-3" style="background-color: #fff9e6;">
                                        {{ number_format($stok->sum('harga_awal'), 0, ',', '.') }}
                                    </td>
                                    <td class="text-center" style="background-color: #fff9e6;">
                                        {{ $stok->sum('qty_awal') }}
                                    </td>

                                    @foreach($bulanList as $bulan)
                                        @php
                                            $totalHargaBulan = 0;
                                            $totalQtyBulan = 0;
                                            foreach($stok as $item) {
                                                $det = $item->rincian_bulanan->where('bulan', $bulan)->first();
                                                if($det) {
                                                    $totalHargaBulan += $det->harga_update;
                                                    $totalQtyBulan += $det->qty_update;
                                                }
                                            }
                                        @endphp
                                        <td class="text-center py-3">
                                            {{ number_format($totalHargaBulan, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center py-3">
                                            {{ $totalQtyBulan }}
                                        </td>
                                    @endforeach
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR RINGKASAN -->
            <div class="col-lg-3 col-12">
                <div class="card shadow-sm border-0 mb-4 rounded-4 overflow-hidden">
                    <div class="card-header bg-dark p-3">
                        <h6 class="text-white mb-0 text-xs fw-bold text-uppercase">Stock Kandang</h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach($summaryJenis as $sj)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-2 border-0 border-bottom text-xxs font-weight-bold text-uppercase">
                                <span>{{ $sj->jenis }}</span>
                                <span class="badge bg-light text-dark rounded-pill">{{ $sj->total }}</span>
                            </li>
                            @endforeach
                            <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-3 bg-light">
                                <span class="text-xs font-weight-bolder text-dark text-uppercase">TOTAL POPULASI</span>
                                <span class="text-sm font-weight-bolder text-primary">{{ $summaryJenis->sum('total') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-secondary p-3">
                        <h6 class="text-white mb-0 text-xs fw-bold text-uppercase">Klaster Bangsalan</h6>
                    </div>
                    <div class="card-body p-0">
                        @foreach($summaryKlaster as $sk)
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                            <span class="text-xxs font-weight-bold text-uppercase text-secondary">{{ $sk->klaster }}</span>
                            <span class="text-xs fw-bold text-dark">{{ $sk->total }} <small class="text-muted">Ekor</small></span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH DATA -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('rincian-hpp.store') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                @csrf
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <h5 class="fw-bold text-primary">Tambah Stok Baru</h5>
                        <p class="text-xs text-secondary">Masukkan data pembelian awal kambing</p>
                    </div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="text-xxs fw-bold text-uppercase text-secondary">Tanggal Masuk</label>
                            <input type="date" name="tanggal" class="form-control rounded-3" value="2025-09-01" required>
                        </div>
                        <div class="col-12">
                            <label class="text-xxs fw-bold text-uppercase text-secondary">Supplier / Keterangan</label>
                            <input type="text" name="keterangan" class="form-control rounded-3 text-uppercase" placeholder="Contoh: RATIMIN" required>
                        </div>
                        <div class="col-6">
                            <label class="text-xxs fw-bold text-uppercase text-secondary">Jenis</label>
                            <input type="text" name="jenis" class="form-control rounded-3 text-uppercase" placeholder="Contoh: MERINO" required>
                        </div>
                        <div class="col-6">
                            <label class="text-xxs fw-bold text-uppercase text-secondary">Klaster (Kandang)</label>
                            <input type="text" name="klaster" class="form-control rounded-3 text-uppercase" placeholder="Contoh: MARTO" required>
                        </div>
                        <div class="col-6">
                            <label class="text-xxs fw-bold text-uppercase text-secondary">Harga Modal (Rp)</label>
                            <input type="number" name="harga_awal" class="form-control rounded-3" required>
                        </div>
                        <div class="col-6">
                            <label class="text-xxs fw-bold text-uppercase text-secondary">Qty Awal</label>
                            <input type="number" name="qty_awal" class="form-control rounded-3" required>
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="button" class="btn btn-light w-100 rounded-pill fw-bold text-xs" data-bs-dismiss="modal">BATAL</button>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill shadow fw-bold text-xs">SIMPAN DATA</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPT AJAX LIVE-SAVE -->
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
            .then(res => res.json())
            .then(data => {
                console.log('Update Successful');
            })
            .catch(err => {
                console.error('Error saving data:', err);
            });
        }
    </script>
    </main>
</x-app-layout>