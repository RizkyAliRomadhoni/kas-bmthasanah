<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            
            <!-- HEADER & FILTER -->
            <div class="row mb-4">
                <div class="col-lg-6 col-12">
                    <h4 class="font-weight-bolder mb-0 text-uppercase">Manajemen Stok Kambing</h4>
                    <p class="text-sm mb-0">Rincian inventaris berdasarkan transaksi buku kas.</p>
                </div>
                <div class="col-lg-6 col-12 d-flex justify-content-lg-end align-items-center mt-3 mt-lg-0">
                    <form action="{{ route('kambing-akun.index') }}" method="GET" class="d-flex gap-2">
                        <select name="bulan" class="form-select shadow-sm border-0" onchange="this.form.submit()">
                            @foreach($listBulan as $b)
                                <option value="{{ $b->bulan }}" {{ $bulanTerpilih == $b->bulan ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::parse($b->bulan)->translatedFormat('F Y') }}
                                </option>
                            @endforeach
                        </select>
                        <a href="{{ route('neraca.index') }}" class="btn btn-outline-primary mb-0">Neraca</a>
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Belanja (Bulan Ini)</p>
                                    <h5 class="font-weight-bolder mb-0">Rp {{ number_format($totalPengeluaranBulanIni, 0, ',', '.') }}</h5>
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Pembelian (Bulan Ini)</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $totalEkorBulanIni }} Ekor</h5>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                        <i class="fas fa-truck-loading opacity-10"></i>
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Populasi Aktif</p>
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
                <!-- MAIN CONTENT: TRANSAKSI -->
                <div class="col-lg-8">
                    @forelse($data as $kas)
                    <div class="card shadow-sm border-0 mb-4 overflow-hidden">
                        <div class="card-header bg-gradient-light py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 text-sm font-weight-bold"><i class="fas fa-receipt me-2"></i>{{ strtoupper($kas->keterangan) }}</h6>
                                <small class="text-xs">{{ \Carbon\Carbon::parse($kas->tanggal)->translatedFormat('d F Y') }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-white text-dark shadow-sm">Nilai Kas: Rp {{ number_format($kas->jumlah, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-gray-50 text-xxs font-weight-bolder opacity-7">
                                        <tr>
                                            <th class="ps-4">JENIS</th>
                                            <th class="text-center">HARGA SATUAN</th>
                                            <th class="text-center">BB</th>
                                            <th class="text-center">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-xs">
                                        @foreach($kas->kambingDetails as $detail)
                                        <tr>
                                            <td class="ps-4 font-weight-bold text-dark">{{ $detail->jenis }}</td>
                                            <td class="text-center font-weight-bold">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                            <td class="text-center">{{ $detail->berat_badan }} Kg</td>
                                            <td class="text-center">
                                                <a href="{{ route('kambing-akun.destroy', $detail->id) }}" class="text-danger" onclick="return confirm('Hapus?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                        
                                        <!-- FORM INPUT -->
                                        <tr class="bg-gray-50">
                                            <form action="{{ route('kambing-akun.storeDetail') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="kas_id" value="{{ $kas->id }}">
                                                <td class="ps-2">
                                                    <input type="text" name="jenis" class="form-control form-control-sm border-0 bg-transparent" placeholder="Jenis (Boer/Merino)" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="harga_satuan" class="form-control form-control-sm border-0 bg-transparent text-center" placeholder="Harga Satuan" required>
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" name="berat_badan" class="form-control form-control-sm border-0 bg-transparent text-center" placeholder="BB">
                                                </td>
                                                <td class="text-center">
                                                    <button type="submit" class="btn btn-sm btn-dark mb-0 py-1 shadow-none">TAMBAH</button>
                                                </td>
                                            </form>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- FOOTER VALIDASI -->
                        <div class="card-footer py-2 bg-light border-top d-flex justify-content-end align-items-center gap-3">
                            @php
                                $terinput = $kas->kambingDetails->sum('harga_satuan');
                                $selisih = $kas->jumlah - $terinput;
                            @endphp
                            <span class="text-xxs font-weight-bold text-uppercase">Terinput: <span class="text-dark">Rp {{ number_format($terinput, 0, ',', '.') }}</span></span>
                            <span class="text-xxs font-weight-bold text-uppercase">Selisih: 
                                <span class="{{ $selisih == 0 ? 'text-success' : 'text-danger' }}">
                                    Rp {{ number_format($selisih, 0, ',', '.') }}
                                    @if($selisih == 0) <i class="fas fa-check-circle ms-1"></i> @endif
                                </span>
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="card shadow-sm border-0 p-5 text-center">
                        <i class="fas fa-folder-open fa-3x text-gray-200 mb-3"></i>
                        <p class="text-secondary">Tidak ada transaksi kambing di bulan ini.</p>
                    </div>
                    @endforelse
                </div>

                <!-- SIDEBAR: REKAP STOK KANDANG -->
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                        <div class="card-header bg-gradient-dark py-3">
                            <h6 class="text-white mb-0">Komposisi Stok Kandang</h6>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                @foreach($rekapStok as $row)
                                <li class="list-group-item d-flex justify-content-between align-items-center border-0 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon icon-xs bg-gray-100 border-radius-sm text-center me-2">
                                            <i class="fas fa-tag text-xs text-primary"></i>
                                        </div>
                                        <span class="text-sm font-weight-bold">{{ strtoupper($row->jenis) }}</span>
                                    </div>
                                    <span class="badge bg-primary rounded-pill">{{ $row->total }} Ekor</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="card-footer bg-gray-100 py-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-sm font-weight-bold">Total Populasi</span>
                                <span class="text-sm font-weight-bold">{{ $rekapStok->sum('total') }} Ekor</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
</x-app-layout>

<style>
    .bg-gray-50 { background-color: #f8f9fa !important; }
    .text-xxs { font-size: 0.65rem !important; }
    .icon-shape {
        width: 48px;
        height: 48px;
        background-position: center;
        border-radius: 0.75rem;
    }
    .icon-shape i { color: #fff; font-size: 1.25rem; }
</style>