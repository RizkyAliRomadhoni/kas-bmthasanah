<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            
            <!-- TOMBOL NAVIGASI & HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-bold mb-0">KELOLA AKUN: PAKAN</h5>
                    <p class="text-sm mb-0">Lengkapi detail teknis pakan dari transaksi Kas</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('neraca.index') }}" class="btn btn-sm btn-outline-secondary mb-0">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Neraca
                    </a>
                    <a href="{{ route('kas.create') }}" class="btn btn-sm btn-dark mb-0">
                        <i class="fas fa-plus me-1"></i> Tambah Transaksi Kas
                    </a>
                </div>
            </div>

            <!-- FILTER BOX -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-3">
                    <form action="{{ route('pakan.index') }}" method="GET" class="row align-items-center">
                        <div class="col-md-4">
                            <label class="text-xs font-weight-bold">Filter Berdasarkan Bulan:</label>
                            <select name="bulan" class="form-select form-select-sm">
                                <option value="">-- Tampilkan Semua Bulan --</option>
                                @foreach($listBulan as $b)
                                    <option value="{{ $b->bulan }}" {{ $bulanTerpilih == $b->bulan ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::parse($b->bulan)->translatedFormat('F Y') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mt-4">
                            <button type="submit" class="btn btn-sm btn-primary mb-0 w-100">Terapkan Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TABEL DATA -->
            <div class="card shadow-sm border-0">
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="ps-4 text-uppercase text-secondary text-xxs font-weight-bolder">Tanggal</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Keterangan</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Harga Kas (Rp)</th>
                                    <th class="text-uppercase text-primary text-xxs font-weight-bolder">Qty (Kg)</th>
                                    <th class="text-uppercase text-primary text-xxs font-weight-bolder">Hrg/Kg</th>
                                    <th class="text-uppercase text-primary text-xxs font-weight-bolder">Hrg Jual/Kg</th>
                                    <th class="text-uppercase text-success text-xxs font-weight-bolder">Potensi Jual</th>
                                    <th class="text-center text-secondary text-xxs font-weight-bolder">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $item)
                                <tr>
                                    <!-- FORM MULAI -->
                                    <form action="{{ route('pakan.update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="kas_id" value="{{ $item->id }}">
                                        
                                        <td class="ps-4 text-xs">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                        <td class="text-xs">{{ $item->keterangan }}</td>
                                        <td class="text-xs font-weight-bold">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                        
                                        <td>
                                            <input type="number" step="0.01" name="qty_kg" class="form-control form-control-sm text-xs" style="width: 80px" value="{{ $item->pakanDetail->qty_kg ?? 0 }}">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="harga_kg" class="form-control form-control-sm text-xs" style="width: 100px" value="{{ $item->pakanDetail->harga_kg ?? 0 }}">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="harga_jual_kg" class="form-control form-control-sm text-xs" style="width: 100px" value="{{ $item->pakanDetail->harga_jual_kg ?? 0 }}">
                                        </td>

                                        <td class="text-xs font-weight-bold text-success">
                                            Rp {{ number_format(($item->pakanDetail->qty_kg ?? 0) * ($item->pakanDetail->harga_jual_kg ?? 0), 0, ',', '.') }}
                                        </td>

                                        <td class="text-center">
                                            <!-- TOMBOL SIMPAN YANG TERLIHAT JELAS -->
                                            <button type="submit" class="btn btn-xs btn-primary mb-0">
                                                <i class="fas fa-save me-1"></i> Simpan
                                            </button>
                                        </td>
                                    </form>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-100 fw-bold">
                                <tr>
                                    <td colspan="2" class="ps-4 text-sm">TOTAL {{ $bulanTerpilih ? 'BULAN INI' : 'KESELURUHAN' }}</td>
                                    <td class="text-sm">Rp {{ number_format($totalHargaKas, 0, ',', '.') }}</td>
                                    <td class="text-sm text-primary">{{ number_format($totalQty, 2) }} Kg</td>
                                    <td colspan="2"></td>
                                    <td class="text-sm text-success">Rp {{ number_format($totalPotensiJual, 0, ',', '.') }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </main>
</x-app-layout>

<style>
    .bg-gray-100 { background-color: #f8f9fa !important; }
    .btn-xs { padding: 0.25rem 0.5rem; font-size: 0.7rem; }
</style>