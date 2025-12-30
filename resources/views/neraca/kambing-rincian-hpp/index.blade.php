<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <style>
        /* Modern & Minimalist Style */
        .table-hpp th { 
            background-color: #f8f9fa; 
            color: #344767; 
            font-weight: 700; 
            font-size: 0.65rem; 
            text-transform: uppercase; 
            border: 1px solid #e9ecef !important; 
            vertical-align: middle; 
        }
        .table-hpp td { font-size: 0.75rem; border: 1px solid #e9ecef !important; vertical-align: middle; }
        
        /* Kolom Keterangan Lengket (Sticky) */
        .sticky-col { 
            position: sticky; 
            left: 0; 
            background-color: white !important; 
            z-index: 5; 
            box-shadow: 2px 0 5px rgba(0,0,0,0.05); 
        }

        /* Input Table Style (Tanpa Border) */
        .input-cell { 
            width: 100%; 
            border: none; 
            background: transparent; 
            text-align: center; 
            font-size: 0.75rem; 
            font-weight: 600; 
            padding: 8px; 
            transition: 0.2s; 
        }
        .input-cell:focus { 
            outline: none; 
            background: #eef1ff; 
            border-radius: 4px; 
            box-shadow: inset 0 0 0 1px #5e72e4; 
        }
        
        .card-summary { border-radius: 15px; border: none; }
        .bg-gradient-blue { background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%); }
        .bg-light-warning { background-color: #fff9e6 !important; }

        /* Tombol Navigasi Sub-Menu */
        .btn-nav {
            background-color: white; color: #344767; font-size: 0.7rem; font-weight: 700;
            padding: 7px 12px; border-radius: 6px; border: 1px solid #e9ecef;
            text-decoration: none !important; display: inline-flex; align-items: center;
            transition: all 0.2s ease; text-transform: uppercase;
        }
        .btn-nav:hover { 
            background-color: #f8f9fa; border-color: #5e72e4; color: #5e72e4 !important; 
            transform: translateY(-1px); box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11);
        }
        .btn-nav i { margin-right: 6px; font-size: 0.8rem; }
    </style>

    <div class="container-fluid py-4">

        <!-- HEADER UTAMA -->
        <div class="row align-items-center mb-3">
            <div class="col-md-6 col-12">
                <h5 class="fw-bold mb-0 text-uppercase text-primary tracking-tight">Rincian HPP & Stok Bulanan</h5>
                <p class="text-xs text-secondary mb-0">Hasanah Farm • Sinkronisasi Data Excel (Mulai Juli 2025)</p>
            </div>
            <div class="col-md-6 col-12 d-flex justify-content-md-end justify-content-center mt-3 mt-md-0 gap-2">
                <!-- TOMBOL TAMBAH BULAN -->
                <form action="{{ route('rincian-hpp.tambah-bulan') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-primary shadow-sm mb-0 px-4 rounded-pill">
                        <i class="fas fa-calendar-plus me-2"></i> Tambah Bulan
                    </button>
                </form>
                <button class="btn btn-sm btn-primary shadow-sm mb-0 px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fas fa-plus me-2"></i> Tambah Baris
                </button>
            </div>
        </div>

        <!-- NAVIGASI TOMBOL KELOLA AKUN (KOMPONEN UTUH) -->
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
            <!-- TABEL RINCIAN (KIRI) -->
            <div class="col-lg-9 col-12 mb-4">
                <div class="card shadow-sm border-0 border-radius-xl overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hpp mb-0 align-items-center">
                            <thead class="text-center">
                                <tr>
                                    <th rowspan="2" class="px-3" style="width: 50px;">No</th>
                                    <th rowspan="2" style="min-width: 90px;">Tanggal</th>
                                    <th rowspan="2" class="sticky-col" style="min-width: 180px;">Keterangan</th>
                                    <th rowspan="2" style="min-width: 120px;">Jenis</th>
                                    <th colspan="2" class="bg-light-warning">Stok Awal</th>
                                    @foreach($bulanList as $bulan)
                                        <th colspan="2" class="bg-light">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('M-y') }}</th>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th class="py-2 bg-light-warning">Harga</th>
                                    <th class="py-2 bg-light-warning">Qty</th>
                                    @foreach($bulanList as $bulan)
                                        <th class="py-2">Harga</th>
                                        <th class="py-2">Qty</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stok as $index => $item)
                                <tr>
                                    <td class="text-center text-secondary font-weight-bold">{{ $index + 1 }}</td>
                                    <td class="text-center text-xs">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/y') }}
                                    </td>
                                    <td class="sticky-col fw-bold px-3 text-uppercase text-dark">{{ $item->keterangan }}</td>
                                    <td class="px-2 text-center text-uppercase" style="font-size: 10px;">{{ $item->jenis }}</td>
                                    
                                    <!-- HARGA & QTY AWAL (STATIS) -->
                                    <td class="text-end px-3 font-weight-bold text-dark" style="background-color: #fffdf5;">
                                        {{ number_format($item->harga_awal, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center font-weight-bold text-dark" style="background-color: #fffdf5;">
                                        {{ $item->qty_awal }}
                                    </td>
                                    
                                    <!-- INPUT DINAMIS PER BULAN -->
                                    @foreach($bulanList as $bulan)
                                        @php 
                                            $det = $item->rincian_bulanan->where('bulan', $bulan)->first(); 
                                        @endphp
                                        <td class="p-0">
                                            <input type="number" class="input-cell" 
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
                                <tr><td colspan="100" class="text-center py-5 text-secondary">Belum ada data stok. Klik "Tambah Baris" untuk memulai.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR RINGKASAN (KANAN) -->
            <div class="col-lg-3 col-12">
                <!-- Stock Kandang -->
                <div class="card shadow-sm border-0 mb-4 rounded-4 overflow-hidden">
                    <div class="card-header bg-dark p-3">
                        <h6 class="text-white mb-0 text-xs fw-bold text-uppercase">Stock Kandang</h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach($summaryJenis as $sj)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-2 border-0 border-bottom">
                                <span class="text-xxs font-weight-bold text-uppercase">{{ $sj->jenis }}</span>
                                <span class="badge bg-light text-dark rounded-pill">{{ $sj->total }}</span>
                            </li>
                            @endforeach
                            <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-3 bg-light">
                                <span class="text-xs font-weight-bolder">TOTAL POPULASI</span>
                                <span class="text-sm font-weight-bolder text-primary">{{ $summaryJenis->sum('total') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Klaster Bangsalan -->
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-secondary p-3">
                        <h6 class="text-white mb-0 text-xs fw-bold text-uppercase">Klaster Bangsalan</h6>
                    </div>
                    <div class="card-body p-0">
                        @foreach($summaryKlaster as $sk)
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                            <span class="text-xxs font-weight-bold text-uppercase">{{ $sk->klaster }}</span>
                            <span class="text-xs fw-bold">{{ $sk->total }} <small class="text-muted">Ekor</small></span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH BARIS (MODERN DESIGN) -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('rincian-hpp.store') }}" method="POST" class="modal-content shadow-lg border-0" style="border-radius: 20px;">
                @csrf
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <h5 class="fw-bold text-primary mb-1">Tambah Stok Baru</h5>
                        <p class="text-xs text-secondary">Masukkan detail pembelian kambing/domba</p>
                    </div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="text-xxs fw-bold text-uppercase">Tanggal Masuk</label>
                            <input type="date" name="tanggal" class="form-control form-control-sm rounded-3" value="2025-07-01" required>
                        </div>
                        <div class="col-12">
                            <label class="text-xxs fw-bold text-uppercase">Keterangan (Supplier)</label>
                            <input type="text" name="keterangan" class="form-control form-control-sm rounded-3" placeholder="Contoh: HARIYANTO" required>
                        </div>
                        <div class="col-6">
                            <label class="text-xxs fw-bold text-uppercase">Jenis Kambing</label>
                            <input type="text" name="jenis" class="form-control form-control-sm rounded-3" placeholder="Contoh: MERINO" required>
                        </div>
                        <div class="col-6">
                            <label class="text-xxs fw-bold text-uppercase">Klaster (Kandang)</label>
                            <input type="text" name="klaster" class="form-control form-control-sm rounded-3" placeholder="Contoh: MARTO" required>
                        </div>
                        <div class="col-6">
                            <label class="text-xxs fw-bold text-uppercase">Harga Modal (Rp)</label>
                            <input type="number" name="harga_awal" class="form-control form-control-sm rounded-3" required>
                        </div>
                        <div class="col-6">
                            <label class="text-xxs fw-bold text-uppercase">Kuantitas (Qty)</label>
                            <input type="number" name="qty_awal" class="form-control form-control-sm rounded-3" required>
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="button" class="btn btn-light w-100 rounded-pill text-uppercase fw-bold text-xs" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill shadow text-uppercase fw-bold text-xs">Simpan Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPT AJAX AUTO-SAVE -->
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
                console.log('Data tersimpan otomatis');
            })
            .catch(err => {
                console.error('Gagal menyimpan data:', err);
            });
        }
    </script>
    </main>
</x-app-layout>