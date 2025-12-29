<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            
            <div class="row">
                <div class="col-lg-9">
                    <h5 class="fw-bold mb-4 text-uppercase">Input Rincian Kambing per Transaksi</h5>

                    @forelse($data as $kas)
                    <div class="card shadow-sm border-0 mb-4">
                        <!-- HEADER TRANSAKSI KAS -->
                        <div class="card-header bg-gray-100 d-flex justify-content-between align-items-center py-2">
                            <div>
                                <span class="badge bg-primary me-2">{{ \Carbon\Carbon::parse($kas->tanggal)->format('d/m/Y') }}</span>
                                <span class="text-sm font-weight-bold text-dark">{{ strtoupper($kas->keterangan) }}</span>
                            </div>
                            <span class="text-sm font-weight-bold">Total Kas: Rp {{ number_format($kas->jumlah, 0, ',', '.') }}</span>
                        </div>

                        <!-- TABEL RINCIAN (MUNCUL DI BAWAH KAS) -->
                        <div class="card-body p-0">
                            <table class="table table-sm align-items-center mb-0">
                                <thead class="bg-light text-xxs font-weight-bolder opacity-7">
                                    <tr>
                                        <th class="ps-4">JENIS KAMBING</th>
                                        <th class="text-center">HARGA SATUAN (Rp)</th>
                                        <th class="text-center">BB (Kg)</th>
                                        <th class="text-center">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kas->kambingDetails as $detail)
                                    <tr>
                                        <td class="ps-4 text-xs font-weight-bold">{{ $detail->jenis }}</td>
                                        <td class="text-center text-xs">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                        <td class="text-center text-xs">{{ $detail->berat_badan }} Kg</td>
                                        <td class="text-center">
                                            <a href="{{ route('kambing-akun.destroy', $detail->id) }}" class="btn btn-link text-danger p-0 m-0" onclick="return confirm('Hapus rincian ini?')">
                                                <i class="fas fa-trash"></i>
                                            </td>
                                        </td>
                                    </tr>
                                    @endforeach

                                    <!-- FORM INPUT RINCIAN BARU -->
                                    <tr class="bg-gray-50">
                                        <form action="{{ route('kambing-akun.storeDetail') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="kas_id" value="{{ $kas->id }}">
                                            <td class="ps-2">
                                                <input type="text" name="jenis" class="form-control form-control-sm text-xs border-0" placeholder="Contoh: Boer / Merino" required>
                                            </td>
                                            <td>
                                                <input type="number" name="harga_satuan" class="form-control form-control-sm text-xs border-0 text-center" placeholder="Harga" required>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" name="berat_badan" class="form-control form-control-sm text-xs border-0 text-center" placeholder="BB">
                                            </td>
                                            <td class="text-center">
                                                <button type="submit" class="btn btn-sm btn-dark mb-0 py-1 shadow-none">
                                                    <i class="fas fa-plus me-1"></i> TAMBAH
                                                </button>
                                            </td>
                                        </form>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- FOOTER KARDUS (INFO VALIDASI HARGA) -->
                        <div class="card-footer py-2 bg-gray-100 d-flex justify-content-end align-items-center gap-4">
                            @php
                                $totalTerinput = $kas->kambingDetails->sum('harga_satuan');
                                $selisih = $kas->jumlah - $totalTerinput;
                            @endphp
                            <span class="text-xxs font-weight-bold">TERINPUT: Rp {{ number_format($totalTerinput, 0, ',', '.') }}</span>
                            <span class="text-xxs font-weight-bold @if($selisih != 0) text-danger @else text-success @endif">
                                SELISIH: Rp {{ number_format($selisih, 0, ',', '.') }}
                                @if($selisih == 0) <i class="fas fa-check-circle ms-1"></i> @endif
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="card card-body text-center text-xs py-5">
                        Belum ada transaksi kambing di buku Kas.
                    </div>
                    @endforelse
                </div>

                <!-- STOCK KANDANG (BOX KANAN) -->
                <div class="col-lg-3">
                    <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                        <div class="card-header bg-primary text-white pb-0">
                            <h6 class="text-white">STOCK KANDANG</h6>
                        </div>
                        <div class="card-body p-3">
                            <table class="table table-sm mb-0">
                                @foreach($rekapStok as $jenis => $total)
                                <tr>
                                    <td class="text-xs">{{ strtoupper($jenis) }}</td>
                                    <td class="text-xs text-end font-weight-bold">{{ $total }}</td>
                                </tr>
                                @endforeach
                                <tr class="border-top fw-bold">
                                    <td class="text-xs">TOTAL POPULASI</td>
                                    <td class="text-xs text-end">{{ $rekapStok->sum() }}</td>
                                </tr>
                            </table>
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
</style>