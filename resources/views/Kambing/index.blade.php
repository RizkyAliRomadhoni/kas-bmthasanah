<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        

        <div class="container-fluid py-5 px-4">

            {{-- HEADER --}}
            <div class="card border-0 shadow-lg rounded-4 mb-5" style="background: linear-gradient(135deg,#4e73df,#224abe);">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center text-white py-5 px-4">
                    <div>
                        <h2 class="fw-bold text-white mb-1">🐐 Pengelolaan Kambing</h2>
                        <p class="mb-0 text-white-50">Kelola data ternak dan keuangan dengan tampilan modern.</p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <a href="{{ route('kambing.create') }}" class="btn btn-light text-primary fw-bold me-2 shadow-sm px-4 py-2">
                            + Tambah Data
                        </a>
                        <a href="{{ route('kambing.jual') }}" class="btn btn-warning fw-bold shadow-sm px-4 py-2">
                            💰 Jual Kambing
                        </a>
                    </div>
                </div>
            </div>

            {{-- RINGKASAN KEUANGAN --}}
            <div class="row g-4 mb-5">
                <div class="col-lg-4 col-md-6">
                    <div class="card shadow-lg border-0 rounded-4 bg-gradient-success text-white">
                        <div class="card-body p-4">
                            <h6 class="text-uppercase text-white-50 mb-2">Total Pemasukan</h6>
                            <h3 class="fw-bold text-white mb-1">Rp {{ number_format($totalMasuk ?? 0, 0, ',', '.') }}</h3>
                            <small class="text-white-50">Dari penjualan kambing</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card shadow-lg border-0 rounded-4 bg-gradient-danger text-white">
                        <div class="card-body p-4">
                            <h6 class="text-uppercase text-white-50 mb-2">Total Pengeluaran</h6>
                            <h3 class="fw-bold text-white mb-1">Rp {{ number_format($totalKeluar ?? 0, 0, ',', '.') }}</h3>
                            <small class="text-white-50">Biaya pakan & perawatan</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card shadow-lg border-0 rounded-4 bg-gradient-info text-white">
                        <div class="card-body p-4">
                            <h6 class="text-uppercase text-white-50 mb-2">Saldo Kas Kambing</h6>
                            <h3 class="fw-bold text-white mb-1">Rp {{ number_format($saldo ?? 0, 0, ',', '.') }}</h3>
                            <small class="text-white-50">Sisa saldo saat ini</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TABEL DATA KAMBING --}}
            <div class="card border-0 shadow-lg rounded-4 mb-5">
                <div class="card-header bg-gradient-primary text-white rounded-top-4 d-flex justify-content-between align-items-center py-3 px-4">
                    <h5 class="fw-bold text-white mb-0">📄 Daftar Kambing di Peternakan</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle text-center mb-0">
                            <thead class="bg-light text-secondary">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Jumlah</th>
                                    <th>Berat Awal (kg)</th>
                                    <th>Konsumsi Pakan</th>
                                    <th>Harga Beli</th>
                                    <th>Harga Jual</th>
                                    <th>Berat Bulan Ini</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kambing as $item)
                                    <tr class="hover-row">
                                        <td class="fw-bold text-primary">{{ $item->kode }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->jumlah }}</td>
                                        <td>{{ $item->berat_awal }}</td>
                                        <td>{{ $item->konsumsi_pakan ?? '-' }}</td>
                                        <td class="text-success fw-semibold">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                        <td class="text-warning fw-semibold">Rp {{ number_format($item->harga_jual ?? 0, 0, ',', '.') }}</td>
                                        <td>
                                            @php
                                                $beratTerbaru = optional($item->beratBulanan)->sortByDesc('created_at')->first();
                                            @endphp
                                            {{ $beratTerbaru?->berat ?? '-' }} kg
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('kambing.edit', $item->id) }}" class="btn btn-outline-primary btn-sm px-3 shadow-sm">✏️ Edit</a>
                                                <a href="{{ route('kambing.showKas', $item->id) }}" class="btn btn-outline-success btn-sm px-3 shadow-sm">💼 Kas</a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">Belum ada data kambing</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- TOMBOL TRANSAKSI --}}
            <div class="text-end mb-4">
                <a href="{{ route('kambing.transaksiList') }}" class="btn btn-gradient-primary shadow-lg px-5 py-3 fw-bold text-white rounded-pill">
                    💳 Lihat Semua Transaksi Kambing
                </a>
            </div>

            {{-- FOOTER --}}
            <footer class="text-center mt-5 text-muted small">
                <em>© {{ date('Y') }} Sistem Pengelolaan Peternakan Kambing</em>
            </footer>

        </div>
    </main>

    <style>
        .bg-gradient-success {
            background: linear-gradient(135deg, #1cc88a, #198754);
        }
        .bg-gradient-danger {
            background: linear-gradient(135deg, #e74a3b, #be2617);
        }
        .bg-gradient-info {
            background: linear-gradient(135deg, #36b9cc, #0dcaf0);
        }
        .btn-gradient-primary {
            background: linear-gradient(135deg, #4e73df, #1a52d0);
            border: none;
            transition: all 0.3s ease;
        }
        .btn-gradient-primary:hover {
            background: linear-gradient(135deg, #224abe, #102d8b);
            transform: scale(1.03);
        }
        .hover-row:hover {
            background-color: #f8f9fc;
            transition: background-color 0.3s ease;
        }
        .rounded-4 {
            border-radius: 1.5rem !important;
        }
    </style>
</x-app-layout>
