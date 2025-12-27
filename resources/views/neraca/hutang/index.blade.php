<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            
            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0 text-uppercase">AKUN: HUTANG (BMT / LAINNYA)</h5>
                <div class="d-flex gap-2">
                    <form action="{{ route('hutang.index') }}" method="GET" class="d-flex gap-2">
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
                                <tr class="text-center">
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 50px;">NO</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">TANGGAL</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">KETERANGAN</th>
                                    <th class="text-uppercase text-success text-xxs font-weight-bolder opacity-7">DEBET (+)</th>
                                    <th class="text-uppercase text-danger text-xxs font-weight-bolder opacity-7">KREDIT (-)</th>
                                    <th class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7">SALDO HUTANG</th>
                                    <th class="text-secondary opacity-7" style="width: 80px;">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dataRaw as $index => $item)
                                <tr>
                                    <td class="text-center text-xs">{{ $index + 1 }}</td>
                                    <td class="text-center text-xs">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                                    </td>
                                    <td class="ps-3 text-xs">
                                        <form action="{{ route('hutang.update') }}" method="POST" class="d-flex align-items-center m-0">
                                            @csrf
                                            <input type="hidden" name="kas_id" value="{{ $item->id }}">
                                            <div class="w-100">
                                                <div class="fw-bold">{{ strtoupper($item->keterangan) }}</div>
                                                <input type="text" name="catatan" class="form-control form-control-sm border-0 p-0 text-xxs italic" 
                                                       placeholder="Klik untuk tambah catatan..." 
                                                       value="{{ $item->hutangDetail->catatan ?? '' }}">
                                            </div>
                                    </td>
                                    <td class="text-end pe-3 text-xs text-success">
                                        {{ $item->jenis_transaksi == 'Masuk' ? number_format($item->jumlah, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="text-end pe-3 text-xs text-danger">
                                        {{ $item->jenis_transaksi == 'Keluar' ? number_format($item->jumlah, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="text-end pe-3 text-xs font-weight-bold bg-gray-50">
                                        {{ number_format($item->saldo_berjalan, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                            <button type="submit" class="btn btn-link text-primary p-0 mb-0 shadow-none">
                                                <i class="fas fa-save"></i> SIMPAN
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-xs">Data tidak ditemukan. Gunakan akun "Hutang" di Kas.</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-gray-100 fw-bold">
                                <tr>
                                    <td colspan="5" class="text-end text-xs pe-3 text-uppercase">Sisa Hutang Saat Ini :</td>
                                    <td class="text-end pe-3 text-xs font-weight-bolder text-dark">
                                        Rp {{ number_format($runningSaldo, 0, ',', '.') }}
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
    .bg-gray-50 { background-color: #fafafa !important; }
    .italic { font-style: italic; }
    .text-xxs { font-size: 0.65rem !important; }
</style>