<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-bold mb-0">KELOLA AKUN: PERLENGKAPAN</h6>
                <form action="{{ route('perlengkapan.index') }}" method="GET" class="d-flex gap-2">
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
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table table-bordered align-items-center mb-0">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 50px;">NO</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">TANGGAL</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">KETERANGAN</th>
                                    <th class="text-center text-uppercase text-primary text-xxs font-weight-bolder opacity-7" style="width: 100px;">QTY</th>
                                    <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 pe-4">HARGA (Rp)</th>
                                    <th class="text-center text-secondary opacity-7" style="width: 80px;">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $index => $item)
                                <tr>
                                    <form action="{{ route('perlengkapan.update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="kas_id" value="{{ $item->id }}">
                                        
                                        <td class="text-center text-xs">{{ $index + 1 }}</td>
                                        <td class="text-xs ps-3">
                                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                                        </td>
                                        <td class="text-xs ps-3">
                                            {{ strtoupper($item->keterangan) }}
                                        </td>
                                        <td class="text-center">
                                            <input type="number" name="qty" class="form-control form-control-sm text-center mx-auto" 
                                                style="width: 70px; height: 25px; font-size: 11px;" 
                                                value="{{ $item->perlengkapanDetail->qty ?? 0 }}">
                                        </td>
                                        <td class="text-end pe-4 text-xs font-weight-bold">
                                            {{ number_format($item->jumlah, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            <button type="submit" class="btn btn-link text-primary p-0 mb-0 shadow-none">
                                                <i class="fas fa-save"></i>
                                            </button>
                                        </td>
                                    </form>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-xs">Belum ada transaksi perlengkapan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-gray-50 fw-bold">
                                <tr>
                                    <td colspan="4" class="text-end text-xs pe-3 text-uppercase">Total Pengeluaran :</td>
                                    <td class="text-end pe-4 text-xs font-weight-bolder">
                                        {{ number_format($totalHarga, 0, ',', '.') }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('neraca.index') }}" class="btn btn-sm btn-outline-secondary">Kembali ke Neraca</a>
            </div>

        </div>
    </main>
</x-app-layout>