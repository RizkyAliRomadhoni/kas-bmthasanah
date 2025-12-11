<x-app-layout>

<style>
    .table-compact td, .table-compact th {
        padding: 6px 10px !important;
        font-size: 14px;
        vertical-align: middle;
    }
</style>

<main class="main-content bg-gray-100 min-vh-100 py-6 px-4">

    <div class="container-fluid">

        {{-- HEADER CARD --}}
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-header bg-gradient-primary text-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">💰 Data Kas Utama</h5>

                <a href="{{ route('kas.create') }}" class="btn btn-light text-primary fw-bold shadow-sm">
                    + Tambah Transaksi
                </a>
            </div>
        </div>

        {{-- FILTER --}}
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('kas.index') }}" 
                      class="row gy-3 align-items-end">

                    <div class="col-md-4">
                        <label class="fw-semibold text-gray-700">📅 Pilih Bulan</label>
                        <input type="month" name="bulan" value="{{ request('bulan') }}"
                               class="form-control rounded-3 shadow-sm">
                    </div>

                    <div class="col-md-4">
                        <label class="fw-semibold text-gray-700">💼 Akun</label>
                        <select name="akun" class="form-select rounded-3 shadow-sm">
                            <option value="">Semua Akun</option>
                            @foreach($akunList as $akun)
                                <option value="{{ $akun }}" 
                                    @selected(request('akun') == $akun)>
                                    {{ $akun }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-semibold shadow-sm w-100">
                            <i class="fas fa-eye me-1"></i> Tampilkan
                        </button>

                        <a href="{{ route('kas.exportPdf', request()->query()) }}" 
                           class="btn btn-danger fw-semibold shadow-sm w-100">
                            <i class="fas fa-file-pdf me-1"></i> PDF
                        </a>
                    </div>

                </form>
            </div>
        </div>

        {{-- RINGKASAN --}}
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body">

                <h5 class="fw-bold text-primary mb-4">
                    📊 Ringkasan Transaksi 
                    @if(request('bulan')) 
                        Bulan {{ \Carbon\Carbon::parse(request('bulan'))->translatedFormat('F Y') }} 
                    @endif
                    @if(request('akun')) — Akun: {{ request('akun') }} @endif
                </h5>

                <div class="row text-center g-3">

                    <div class="col-md-4">
                        <div class="p-4 rounded-4 shadow-sm bg-success bg-opacity-10">
                            <h6 class="fw-bold text-success">Pemasukan</h6>
                            <h3 class="fw-bold text-success">Rp{{ number_format($totalMasuk) }}</h3>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="p-4 rounded-4 shadow-sm bg-danger bg-opacity-10">
                            <h6 class="fw-bold text-danger">Pengeluaran</h6>
                            <h3 class="fw-bold text-danger">Rp{{ number_format($totalKeluar) }}</h3>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="p-4 rounded-4 shadow-sm bg-primary bg-opacity-10">
                            <h6 class="fw-bold text-primary">Saldo Akhir</h6>
                            <h3 class="fw-bold text-primary">Rp{{ number_format($saldoRingkasan) }}</h3>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table-sm table-striped align-middle table-compact">
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
                                    $jClass = $isMasuk ? 'text-success fw-bold' : 'text-danger fw-bold';
                                    $sClass = $item->saldo >= 0 ? 'text-success fw-bold' : 'text-danger fw-bold';
                                @endphp

                                <tr>
                                    <td class="text-center">{{ $i + 1 }}</td>

                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>

                                    <td style="white-space: normal; max-width:220px;">
                                        {{ $item->keterangan }}
                                    </td>

                                    <td class="{{ $jClass }}">
                                        {{ ucfirst($item->jenis_transaksi) }}
                                    </td>

                                    <td class="{{ $jClass }}">
                                        Rp{{ number_format($item->jumlah) }}
                                    </td>

                                    <td>{{ $item->akun ?? '-' }}</td>

                                    <td class="{{ $sClass }}">
                                        Rp{{ number_format($item->saldo) }}
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('kas.show', $item->id) }}" 
                                           class="btn btn-info btn-sm">Detail</a>

                                        <a href="{{ route('kas.edit', $item->id) }}" 
                                           class="btn btn-warning btn-sm">Edit</a>

                                        <form action="{{ route('kas.destroy', $item->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Hapus data ini?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        Belum ada data kas
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

        {{-- TOTAL SALDO --}}
        <div class="text-end fw-bold fs-5 mt-3">
            💵 Total Saldo Akhir: 
            <span class="text-primary">Rp{{ number_format($saldo) }}</span>
        </div>

        <div class="mt-3">
            <a href="{{ route('kas.resetSaldo') }}" 
               onclick="return confirm('Hitung ulang saldo dari awal?')"
               class="btn btn-danger">
                Reset Saldo
            </a>
        </div>

    </div>

</main>

</x-app-layout>
