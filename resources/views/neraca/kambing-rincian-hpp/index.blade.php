<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <style>
        /* Desain Tabel Excel Modern */
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
        
        /* Kolom Keterangan yang Tidak Bergerak saat Scroll */
        .sticky-col { position: sticky; left: 0; background: white; z-index: 5; box-shadow: 2px 0 5px rgba(0,0,0,0.05); }
        
        /* Input yang Terlihat Seperti Sel Excel */
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
        .input-cell:focus { outline: none; background: #eef1ff; border-radius: 4px; box-shadow: inset 0 0 0 1px #5e72e4; }
        
        .card-summary { border-radius: 15px; border: none; }
        .bg-gradient-blue { background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%); }
    </style>

    <div class="container-fluid py-4">
        <!-- BARIS HEADER & RINGKASAN -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card card-summary shadow-sm">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-0 text-primary">Rincian HPP & Stok Bulanan</h5>
                            <p class="text-xs text-secondary mb-0">Kelola populasi dan nilai aset farm secara dinamis.</p>
                        </div>
                        <div class="d-flex gap-2">
                            <form action="{{ route('rincian-hpp.tambah-bulan') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm" onclick="return confirm('Tambah kolom bulan berikutnya?')">
                                    <i class="fas fa-calendar-plus me-1"></i> Tambah Bulan
                                </button>
                            </form>
                            <button class="btn btn-dark btn-sm rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                                <i class="fas fa-plus me-1"></i> Baris Baru
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-summary bg-gradient-blue shadow-sm text-white">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-sm mb-0 opacity-8">Total Populasi</p>
                            <h3 class="fw-bold mb-0 text-white">{{ $summaryJenis->sum('total') }} <span class="text-xs fw-normal">Ekor</span></h3>
                        </div>
                        <i class="fas fa-sheep fa-2x opacity-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- AREA TABEL UTAMA -->
            <div class="col-lg-9 mb-4">
                <div class="card shadow-sm border-0 border-radius-xl overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hpp mb-0">
                            <thead class="text-center">
                                <tr>
                                    <th rowspan="2" class="px-3">No</th>
                                    <th rowspan="2">Tanggal</th>
                                    <th rowspan="2" class="sticky-col">Keterangan</th>
                                    <th rowspan="2">Jenis</th>
                                    <th colspan="2" style="background:#fff9e6">Stok Awal</th>
                                    @foreach($bulanList as $bulan)
                                        <th colspan="2">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('M-y') }}</th>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th class="py-2" style="background:#fffde7">Harga</th>
                                    <th class="py-2" style="background:#fffde7">Qty</th>
                                    @foreach($bulanList as $bulan)
                                        <th class="py-2">Harga</th>
                                        <th class="py-2">Qty</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stok as $index => $item)
                                <tr>
                                    <td class="text-center text-secondary">{{ $index + 1 }}</td>
                                    <td class="text-center text-xs">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/y') }}</td>
                                    <td class="sticky-col fw-bold px-3 text-uppercase text-dark">{{ $item->keterangan }}</td>
                                    <td class="px-2 text-center text-uppercase text-xxs">{{ $item->jenis }}</td>
                                    <td class="text-end px-3 font-weight-bold" style="background:#fffde7">{{ number_format($item->harga_awal,0,',','.') }}</td>
                                    <td class="text-center font-weight-bold" style="background:#fffde7">{{ $item->qty_awal }}</td>
                                    
                                    @foreach($bulanList as $bulan)
                                        @php $det = $item->rincian_bulanan->where('bulan', $bulan)->first(); @endphp
                                        <td class="p-0">
                                            <input type="number" class="input-cell" value="{{ $det ? (int)$det->harga_update : '' }}"
                                                onchange="updateCell('{{ $item->id }}', '{{ $bulan }}', 'harga_update', this.value)">
                                        </td>
                                        <td class="p-0">
                                            <input type="number" class="input-cell" value="{{ $det ? $det->qty_update : '' }}"
                                                onchange="updateCell('{{ $item->id }}', '{{ $bulan }}', 'qty_update', this.value)">
                                        </td>
                                    @endforeach
                                </tr>
                                @empty
                                <tr><td colspan="10" class="text-center py-4">Belum ada data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR RINGKASAN -->
            <div class="col-lg-3">
                <div class="card shadow-sm border-0 rounded-4 mb-3 overflow-hidden">
                    <div class="card-header bg-dark p-3"><h6 class="text-white mb-0 text-xs fw-bold text-uppercase">Stock Kandang</h6></div>
                    <div class="card-body p-0">
                        @foreach($summaryJenis as $sj)
                        <div class="d-flex justify-content-between p-2 border-bottom text-xxs px-3">
                            <span class="fw-bold">{{ $sj->jenis }}</span>
                            <span class="badge bg-light text-dark">{{ $sj->total }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-secondary p-3"><h6 class="text-white mb-0 text-xs fw-bold text-uppercase">Klaster Bangsalan</h6></div>
                    <div class="card-body p-0">
                        @foreach($summaryKlaster as $sk)
                        <div class="d-flex justify-content-between p-2 border-bottom text-xxs px-3">
                            <span class="fw-bold">{{ $sk->klaster }}</span>
                            <span class="badge bg-light text-dark">{{ $sk->total }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH BARIS -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('rincian-hpp.store') }}" method="POST" class="modal-content shadow-lg border-0" style="border-radius: 20px;">
                @csrf
                <div class="modal-body p-4">
                    <h5 class="fw-bold mb-3 text-primary">Tambah Baris Baru</h5>
                    <div class="row g-3">
                        <div class="col-12"><label class="text-xxs fw-bold">Tanggal Masuk</label><input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                        <div class="col-12"><label class="text-xxs fw-bold">Keterangan (Supplier)</label><input type="text" name="keterangan" class="form-control" placeholder="Contoh: HARIYANTO" required></div>
                        <div class="col-6"><label class="text-xxs fw-bold">Jenis</label><input type="text" name="jenis" class="form-control" placeholder="KAMBING DERE" required></div>
                        <div class="col-6"><label class="text-xxs fw-bold">Klaster (Kandang)</label><input type="text" name="klaster" class="form-control" placeholder="MARTO" required></div>
                        <div class="col-6"><label class="text-xxs fw-bold">Harga Modal (Rp)</label><input type="number" name="harga_awal" class="form-control" required></div>
                        <div class="col-6"><label class="text-xxs fw-bold">Qty Awal</label><input type="number" name="qty_awal" class="form-control" required></div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="button" class="btn btn-light w-100 rounded-pill" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill shadow">Simpan Baris</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- AJAX SCRIPTS -->
    <script>
        function updateCell(id, bulan, kolom, nilai) {
            // Mengirim data ke server menggunakan Fetch API
            fetch("{{ route('rincian-hpp.update') }}", {
                method: "POST",
                headers: { 
                    "Content-Type": "application/json", 
                    "X-CSRF-TOKEN": "{{ csrf_token() }}" 
                },
                body: JSON.stringify({ id, bulan, kolom, nilai })
            })
            .then(res => res.json())
            .then(data => console.log('Tersimpan otomatis'))
            .catch(err => alert('Gagal menyimpan, cek koneksi internet!'));
        }
    </script>
    </main>
</x-app-layout>