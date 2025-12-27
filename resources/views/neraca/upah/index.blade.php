<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            
            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0 text-uppercase">AKUN: UPAH / GAJI</h5>
                <div class="d-flex gap-2">
                    <form action="{{ route('upah.index') }}" method="GET" class="d-flex gap-2">
                        <select name="bulan" class="form-select form-select-sm" style="width: 180px;">
                            <option value="">Semua Bulan</option>
                            @foreach($listBulan as $b)
                                <option value="{{ $b->bulan }}" {{ $bulanTerpilih == $b->bulan ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::parse($b->bulan)->translatedFormat('F Y') }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary mb-0">Filter</button>
                    </form>
                    <a href="{{ route('neraca.index') }}" class="btn btn-sm btn-outline-secondary mb-0">Kembali</a>
                </div>
            </div>

            <!-- TABEL DATA -->
            <div class="card shadow-sm border-0">
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table table-bordered align-items-center mb-0">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 50px;">NO</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">TANGGAL</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">KETERANGAN UPAH</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">CATATAN (RINCIAN)</th>
                                    <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 pe-4">NOMINAL (Rp)</th>
                                    <th class="text-center text-secondary opacity-7" style="width: 100px;">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $index => $item)
                                <tr>
                                    <!-- FORM SIMPAN SETIAP BARIS -->
                                    <form action="{{ route('upah.update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="kas_id" value="{{ $item->id }}">
                                        
                                        <td class="text-center text-xs">{{ $index + 1 }}</td>
                                        <td class="text-center text-xs">
                                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                                        </td>
                                        <td class="ps-3 text-xs font-weight-bold">
                                            {{ strtoupper($item->keterangan) }}
                                        </td>
                                        <td class="px-2">
                                            <input type="text" name="catatan" 
                                                class="form-control form-control-sm text-xs border-0 bg-transparent" 
                                                placeholder="Contoh: 12 Hari Kerja..."
                                                value="{{ $item->upahDetail->catatan ?? '' }}">
                                        </td>
                                        <td class="text-end pe-4 text-xs font-weight-bold">
                                            {{ number_format($item->jumlah, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            <!-- TOMBOL SIMPAN BIRU -->
                                            <button type="submit" class="btn btn-xs btn-primary mb-0 shadow-none">
                                                <i class="fas fa-save me-1"></i> SIMPAN
                                            </button>
                                        </td>
                                    </form>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-xs text-secondary">Data tidak ditemukan. Pastikan akun di Kas bernama "Upah"</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-gray-50 fw-bold">
                                <tr>
                                    <td colspan="4" class="text-end text-xs pe-3 text-uppercase">Total Pengeluaran Upah :</td>
                                    <td class="text-end pe-4 text-xs font-weight-bolder text-dark">
                                        Rp {{ number_format($totalUpah, 0, ',', '.') }}
                                    </td>
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
    .italic { font-style: italic; }
</style>