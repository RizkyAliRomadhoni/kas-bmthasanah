<!-- Responsive version of kas/index.blade.php -->
<x-app-layout>
    <style>
        /* TABLE COMPACT MODE */
        .table-compact td, .table-compact th {
            padding: 6px 10px !important;
            font-size: 14px;
            vertical-align: middle;
        }

        /* WRAP KETERANGAN */
        .text-wrap {
            white-space: normal !important;
            max-width: 200px;
        }

        /* WARNA OTOMATIS */
        .text-plus { color: #0d8f3d !important; font-weight: bold; }
        .text-minus { color: #c70000 !important; font-weight: bold; }

        /* BUTTON DETAIL */
        .btn-detail {
            background-color: #4b49ac;
            color: white;
        }
        .btn-detail:hover {
            background-color: #373590;
            color: white;
        }

        /* RESPONSIVE FIXES */
        @media (max-width: 768px) {
            .filter-row { flex-direction: column !important; align-items: flex-start !important; }
            .filter-row label { margin-top: 10px; }
            .btn { width: 100%; margin-top: 10px; }
            .table-responsive { font-size: 12px; }
        }
    </style>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg bg-gray-50">
        <div class="container-fluid py-4 px-3 px-md-5">

            <!-- Card Header -->
            <div class="card shadow border-0 rounded-4 mb-4">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center flex-wrap">
                    <h5 class="mb-0 text-white fw-bold">💰 Data Kas Utama</h5>

                    <a href="{{ route('kas.create') }}" class="btn btn-light text-primary fw-bold shadow-sm mt-2 mt-md-0">
                        + Tambah Transaksi
                    </a>
                </div>
            </div>

            <!-- Filter -->
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('kas.index') }}" class="d-flex flex-wrap gap-3 align-items-center filter-row">

                        <div>
                            <label class="fw-semibold text-gray-700">📅 Pilih Bulan:</label>
                            <input type="month" name="bulan" id="bulan" value="{{ request('bulan') }}" class="form-control border-gray-300 rounded-3 shadow-sm w-auto">
                        </div>

                        <div>
                            <label class="fw-semibold text-gray-700">💼 Akun:</label>
                            <select name="akun" id="akun" class="form-select border-gray-300 rounded-3 shadow-sm w-auto">
                                <option value="">Semua Akun</option>
                                @foreach($akunList as $akun)
                                    <option value="{{ $akun }}" {{ request('akun') == $akun ? 'selected' : '' }}>{{ $akun }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary fw-semibold shadow-sm">
                            <i class="fas fa-eye me-1"></i> Tampilkan
                        </button>

                        <a href="{{ route('kas.exportPdf', request()->query()) }}" class="btn btn-danger fw-semibold shadow-sm">
                            <i class="fas fa-file-pdf me-1"></i> Export PDF
                        </a>
                    </form>
                </div>
            </div>

            <!-- Ringkasan -->
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold text-primary mb-3">📊 Ringkasan Transaksi
                        @if(request('bulan')) Bulan {{ \Carbon\Carbon::parse(request('bulan'))->translatedFormat('F Y') }} @endif
                        @if(request('akun')) — Akun: {{ request('akun') }} @endif
                    </h5>

                    <div class="row text-center g-3">
                        <div class="col-md-4 col-12">
                            <div class="p-3 bg-success bg-opacity-10 rounded-3 shadow-sm">
                                <h6 class="fw-bold text-success">Pemasukan</h6>
                                <h4 class="fw-bold">Rp{{ number_format($totalMasuk, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="p-3 bg-danger bg-opacity-10 rounded-3 shadow-sm">
                                <h6 class="fw-bold text-danger">Pengeluaran</h6>
                                <h4 class="fw-bold">Rp{{ number_format($totalKeluar, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="p-3 bg-primary bg-opacity-10 rounded-3 shadow-sm">
                                <h6 class="fw-bold text-primary">Saldo Akhir</h6>
                                <h4 class="fw-bold">Rp{{ number_format($saldoRingkasan, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Kas -->
            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover align-middle shadow-sm rounded-3" style="font-size: 14px;">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="text-center">No</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Akun</th>
                            <th>Saldo</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kas as $i => $item)
                        @php
                            $isMasuk = strtolower($item->jenis_transaksi) === 'masuk';
                            $jumlahClass = $isMasuk ? 'text-success fw-bold' : 'text-danger fw-bold';
                            $saldoClass  = $item->saldo >= 0 ? 'text-success fw-bold' : 'text-danger fw-bold';
                        @endphp
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                            <td style="white-space: normal; max-width: 200px;">{{ $item->keterangan }}</td>
                            <td class="{{ $jumlahClass }}">{{ ucfirst($item->jenis_transaksi) }}</td>
                            <td class="{{ $jumlahClass }}">Rp{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                            <td>{{ $item->akun ?? '-' }}</td>
                            <td class="{{ $saldoClass }}">Rp{{ number_format($item->saldo, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <a href="{{ route('kas.show', $item->id) }}" class="btn btn-sm btn-info px-2 py-1">Detail</a>
                                <a href="{{ route('kas.edit', $item->id) }}" class="btn btn-sm btn-warning px-2 py-1">Edit</a>
                                <form action="{{ route('kas.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger px-2 py-1" onclick="return confirm('Hapus data ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-3">Belum ada data kas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <h6 class="fw-bold">💵 Total Saldo Akhir: Rp{{ number_format($saldo, 0, ',', '.') }}</h6>
            </div>

            <a href="{{ route('kas.resetSaldo') }}" class="btn btn-danger mt-3" onclick="return confirm('Hitung ulang saldo dari awal?')">Reset Saldo</a>
        </div>
    </main>
</x-app-layout>