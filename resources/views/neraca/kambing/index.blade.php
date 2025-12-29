<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            
            <!-- HEADER & FILTER -->
            <div class="row mb-4">
                <div class="col-lg-6 col-12">
                    <h4 class="font-weight-bolder mb-0 text-uppercase">Manajemen Stok Kambing</h4>
                    <p class="text-sm mb-0">
                        {{ $bulanTerpilih ? 'Menampilkan data bulan ' . \Carbon\Carbon::parse($bulanTerpilih)->translatedFormat('F Y') : 'Menampilkan Semua Data Transaksi' }}
                    </p>
                </div>
                <div class="col-lg-6 col-12 d-flex justify-content-lg-end align-items-center mt-3 mt-lg-0">
                    <form action="{{ route('kambing-akun.index') }}" method="GET" class="d-flex gap-2">
                        <select name="bulan" class="form-select shadow-sm border-0" style="min-width: 200px;" onchange="this.form.submit()">
                            <option value="">-- Tampilkan Semua Bulan --</option>
                            @foreach($listBulan as $b)
                                <option value="{{ $b->bulan }}" {{ $bulanTerpilih == $b->bulan ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::parse($b->bulan)->translatedFormat('F Y') }}
                                </option>
                            @endforeach
                        </select>
                        <a href="{{ route('neraca.index') }}" class="btn btn-outline-primary mb-0 shadow-sm">Neraca</a>
                    </form>
                </div>
            </div>

            <!-- STATS CARDS -->
            <div class="row mb-4">
                <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <p class="text-xs mb-0 text-capitalize font-weight-bold">Total Belanja ({{ $bulanTerpilih ? 'Filter' : 'Semua' }})</p>
                                    <h5 class="font-weight-bolder mb-0">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h5>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                        <i class="fas fa-wallet opacity-10"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <p class="text-xs mb-0 text-capitalize font-weight-bold">Ekor Dibeli ({{ $bulanTerpilih ? 'Filter' : 'Semua' }})</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $totalEkorTerdata }} Ekor</h5>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                        <i class="fas fa-shopping-basket opacity-10"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <p class="text-xs mb-0 text-capitalize font-weight-bold">Populasi Kandang (Aktif)</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $rekapStok->sum('total') }} Ekor</h5>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                        <i class="fas fa-paw opacity-10"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- MAIN CONTENT -->
                <div class="col-lg-8">
                    @forelse($data as $kas)
                    <div class="card shadow-sm border-0 mb-4 overflow-hidden">
                        <div class="card-header bg-gray-100 py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 text-sm font-weight-bold">{{ strtoupper($kas->keterangan) }}</h6>
                                <small class="text-xs text-secondary">{{ \Carbon\Carbon::parse($kas->tanggal)->translatedFormat('d F Y') }}</small>
                            </div>
                            <span class="badge bg-white text-dark shadow-sm border-radius-sm">Rp {{ number_format($kas->jumlah, 0, ',', '.') }}</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-light text-xxs font-weight-bolder opacity-7 text-uppercase">
                                        <tr>
                                            <th class="ps-4">Jenis</th>
                                            <th class="text-center">Harga Satuan</th>
                                            <th class="text-center">BB (Kg)</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-xs">
                                        @foreach($kas->kambingDetails as $detail)
                                        <tr class="border-bottom">
                                            <td class="ps-4 font-weight-bold text-dark">{{ $detail->jenis }}</td>
                                            <td class="text-center font-weight-bold">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                            <td class="text-center">{{ $detail->berat_badan }} kg</td>
                                            <td class="text-center">
                                                <a href="{{ route('kambing-akun.destroy', $detail->id) }}" class="text-danger" onclick="return confirm('Hapus rincian ini?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                        
                                        <!-- FORM INPUT RINCIAN -->
                                        <tr class="bg-gray-50">
                                            <form action="{{ route('kambing-akun.storeDetail') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="kas_id" value="{{ $kas->id }}">
                                                <td class="ps-2">
                                                    <input type="text" name="jenis" class="form-control form-control-sm border-0 bg-transparent" placeholder="Boer / Merino" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="harga_satuan" class="form-control form-control-sm border-0 bg-transparent text-center" placeholder="Rp" required>
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" name="berat_badan" class="form-control form-control-sm border-0 bg-transparent text-center" placeholder="BB">
                                                </td>
                                                <td class="text-center">
                                                    <button type="submit" class="btn btn-sm btn-dark mb-0 px-3 py-1 shadow-none">TAMBAH</button>
                                                </td>
                                            </form>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- FOOTER VALIDASI -->
                        <div class="card-footer py-2 bg-light border-top d-flex justify-content-end align-items-center gap-4">
                            @php
                                $terinput = $kas->kambingDetails->sum('harga_satuan');
                                $selisih = $kas->jumlah - $terinput;
                            @endphp
                            <span class="text-xxs font-weight-bold">TERINPUT: Rp {{ number_format($terinput, 0, ',', '.') }}</span>
                            <span class="text-xxs font-weight-bold {{ $selisih == 0 ? 'text-success' : 'text-danger' }}">
                                SELISIH: Rp {{ number_format($selisih, 0, ',', '.') }}
                                @if($selisih == 0) <i class="fas fa-check-circle ms-1"></i> @endif
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="card shadow-sm border-0 p-5 text-center">
                        <i class="fas fa-search fa-3x text-gray-200 mb-3"></i>
                        <p class="text-secondary">Tidak ada data transaksi kambing ditemukan.</p>
                    </div>
                    @endforelse
                </div>

                <!-- SIDEBAR: REKAP -->
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                        <div class="card-header bg-gradient-dark py-3">
                            <h6 class="text-white mb-0">Rangkuman Populasi</h6>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                @foreach($rekapStok as $row)
                                <li class="list-group-item d-flex justify-content-between align-items-center border-0 py-3">
                                    <span class="text-sm font-weight-bold text-uppercase"><i class="fas fa-tag me-2 text-primary"></i>{{ $row->jenis }}</span>
                                    <span class="badge bg-primary rounded-pill">{{ $row->total }} Ekor</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="card-footer bg-gray-100 py-3 text-center">
                            <h6 class="mb-0 text-sm">TOTAL KESELURUHAN: {{ $rekapStok->sum('total') }} EKOR</h6>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
</x-app-layout>

<style>
    .bg-gray-100 { background-color: #f8f9fa !important; }
    .bg-gray-50 { background-color: #fafafa !important; }
    .text-xxs { font-size: 0.65rem !important; }
    .icon-shape {
        width: 48px;
        height: 48px;
        background-position: center;
        border-radius: 0.75rem;
    }
    .icon-shape i { color: #fff; font-size: 1.25rem; }
</style>