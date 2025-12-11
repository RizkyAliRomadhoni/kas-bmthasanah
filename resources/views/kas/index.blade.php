<!-- Responsive Kas Index Page (mirip style dashboard) -->
<x-app-layout>
    <style>
        /* Soft UI + Responsive */
        .card-soft { border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .btn-soft { border-radius: 12px; font-weight: 600; }
        .table td, .table th { vertical-align: middle; }
        .table-soft { border-radius: 16px; overflow: hidden; }

        /* Mobile Adjustments */
        @media (max-width: 768px) {
            .mobile-card { padding: 15px !important; }
            .mobile-title { font-size: 18px !important; }
            
            .grid-mobile { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        }
    </style>

    <main class="main-content position-relative max-height-vh-100 h-100 bg-gray-50 p-4">

        <!-- MOBILE MENU (OTOMATIS) -->
        <div class="d-lg-none mb-4">
            <div class="card card-soft p-3">
                <div class="grid-mobile">
                    <a href="{{ route('farm.index') }}" class="btn btn-primary btn-soft">Kelola Farm</a>
                    <a href="{{ route('kas.create') }}" class="btn btn-success btn-soft">Tambah Transaksi</a>
                    <a href="{{ route('kas.laporan') }}" class="btn btn-info btn-soft">Laporan</a>
                </div>
            </div>
        </div>

        <!-- HEADER + BUTTON TAMBAH -->
        <div class="card card-soft p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h4 class="fw-bold text-primary mobile-title">💰 Data Kas Utama</h4>

                <a href="{{ route('kas.create') }}" class="btn btn-light text-primary fw-bold shadow-sm btn-soft mt-2 mt-lg-0">
                    + Tambah Transaksi
                </a>
            </div>
        </div>

        <!-- FILTER -->
        <div class="card card-soft p-4 mb-4 mobile-card">
            <form method="GET" action="{{ route('kas.index') }}" class="row g-3">
                <div class="col-md-4 col-6">
                    <label class="fw-semibold">📅 Bulan</label>
                    <input type="month" name="bulan" class="form-control shadow-sm" value="{{ request('bulan') }}">
                </div>

                <div class="col-md-4 col-6">
                    <label class="fw-semibold">💼 Akun</label>
                    <select name="akun" class="form-select shadow-sm">
                        <option value="">Semua Akun</option>
                        @foreach($akunList as $akun)
                            <option value="{{ $akun }}" {{ request('akun') == $akun ? 'selected' : '' }}>
                                {{ $akun }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 col-12 d-flex align-items-end">
                    <button class="btn btn-primary w-100 btn-soft shadow-sm">Tampilkan</button>
                </div>

                <div class="col-12 mt-2">
                    <a href="{{ route('kas.exportPdf', request()->query()) }}" class="btn btn-danger btn-soft shadow-sm">
                        Export PDF
                    </a>
                </div>
            </form>
        </div>

        <!-- RINGKASAN -->
        <div class="card card-soft p-4 mb-4">
            <h5 class="fw-bold text-primary mb-3">📊 Ringkasan Transaksi</h5>

            <div class="row text-center g-3">
                <div class="col-md-4 col-12">
                    <div class="p-3 bg-success bg-opacity-10 rounded-3 shadow-sm">
                        <h6 class="fw-bold text-success">Pemasukan</h6>
                        <h4 class="fw-bold">Rp{{ number_format($totalMasuk,0,',','.') }}</h4>
                    </div>
                </div>

                <div class="col-md-4 col-12">
                    <div class="p-3 bg-danger bg-opacity-10 rounded-3 shadow-sm">
                        <h6 class="fw-bold text-danger">Pengeluaran</h6>
                        <h4 class="fw-bold">Rp{{ number_format($totalKeluar,0,',','.') }}</h4>
                    </div>
                </div>

                <div class="col-md-4 col-12">
                    <div class="p-3 bg-primary bg-opacity-10 rounded-3 shadow-sm">
                        <h6 class="fw-bold text-primary">Saldo Akhir</h6>
                        <h4 class="fw-bold">Rp{{ number_format($saldoRingkasan,0,',','.') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABEL -->
        <div class="card card-soft p-3 table-soft">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Akun</th>
                            <th>Saldo</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kas as $i => $item)
                            @php
                                $isMasuk = strtolower($item->jenis_transaksi) === 'masuk';
                            @endphp

                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                <td style="white-space: normal; max-width: 200px;">{{ $item->keterangan }}</td>
                                <td class="{{ $isMasuk ? 'text-success' : 'text-danger' }} fw-bold">{{ ucfirst($item->jenis_transaksi) }}</td>
                                <td class="{{ $isMasuk ? 'text-success' : 'text-danger' }} fw-bold">Rp{{ number_format($item->jumlah,0,',','.') }}</td>
                                <td>{{ $item->akun ?? '-' }}</td>
                                <td class="fw-bold {{ $item->saldo >= 0 ? 'text-success' : 'text-danger' }}">Rp{{ number_format($item->saldo,0,',','.') }}</td>

                                <td>
                                    <a href="{{ route('kas.show', $item->id) }}" class="btn btn-info btn-sm btn-soft">Detail</a>
                                    <a href="{{ route('kas.edit', $item->id) }}" class="btn btn-warning btn-sm btn-soft">Edit</a>
                                    <form method="POST" action="{{ route('kas.destroy',$item->id) }}" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Hapus data ini?')" class="btn btn-danger btn-sm btn-soft">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FOOTER SALDO -->
        <div class="mt-4">
            <h5 class="fw-bold">Total Saldo Akhir: Rp{{ number_format($saldo,0,',','.') }}</h5>

            <a href="{{ route('kas.resetSaldo') }}" onclick="return confirm('Hitung ulang saldo dari awal?')" class="btn btn-danger btn-soft mt-2">Reset Saldo</a>
        </div>

    </main>
</x-app-layout>