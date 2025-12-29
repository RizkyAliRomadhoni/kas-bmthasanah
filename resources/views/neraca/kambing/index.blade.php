<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-lg-9">
                    <h5 class="fw-bold mb-4 text-uppercase">Input Rincian Kambing per Transaksi</h5>

                    @forelse($data as $kas)
                    <div class="card shadow-sm border-0 mb-4">
                        <!-- HEADER KAS -->
                        <div class="card-header bg-gray-100 d-flex justify-content-between align-items-center py-2">
                            <div>
                                <span class="badge bg-secondary me-2">{{ \Carbon\Carbon::parse($kas->tanggal)->format('d/m/Y') }}</span>
                                <span class="text-sm font-weight-bold">{{ strtoupper($kas->keterangan) }}</span>
                            </div>
                            <span class="text-sm font-weight-bold text-dark">Total Kas: Rp {{ number_format($kas->jumlah, 0, ',', '.') }}</span>
                        </div>

                        <!-- TABEL RINCIAN -->
                        <div class="card-body p-0">
                            <table class="table align-items-center mb-0">
                                <thead class="bg-light text-xxs font-weight-bolder opacity-7 text-uppercase">
                                    <tr>
                                        <th class="ps-4">Jenis Kambing</th>
                                        <th class="text-center">Harga Satuan (Rp)</th>
                                        <th class="text-center">BB (Kg)</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kas->kambingDetails as $detail)
                                    <tr>
                                        <td class="ps-4">
                                            <p class="text-xs font-weight-bold mb-0">{{ $detail->jenis }}</p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-xs mb-0">{{ number_format($detail->berat_badan, 2) }} Kg</p>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('kambing-akun.destroy', $detail->id) }}" class="btn btn-link text-danger p-0 m-0" onclick="return confirm('Hapus rincian ini?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach

                                    <!-- FORM INPUT (MUNCUL PALING BAWAH DI TABEL) -->
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

                        <!-- FOOTER VALIDASI (LOGIKA SELISIH) -->
                        <div class="card-footer py-2 bg-gray-50 d-flex justify-content-end align-items-center gap-4 border-top">
                            @php
                                // Menghitung total rincian yang sudah diinput untuk Kas ini
                                $totalTerinput = $kas->kambingDetails->sum('harga_satuan');
                                $selisih = $kas->jumlah - $totalTerinput;
                            @endphp
                            
                            <span class="text-xxs font-weight-bold text-uppercase">Terinput: 
                                <span class="text-dark">Rp {{ number_format($totalTerinput, 0, ',', '.') }}</span>
                            </span>

                            <span class="text-xxs font-weight-bold text-uppercase">Selisih: 
                                <span class="{{ $selisih == 0 ? 'text-success' : 'text-danger' }}">
                                    Rp {{ number_format($selisih, 0, ',', '.') }}
                                    @if($selisih == 0)
                                        <i class="fas fa-check-circle ms-1"></i>
                                    @endif
                                </span>
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="card card-body text-center text-xs py-5">
                        Data transaksi kambing tidak ditemukan.
                    </div>
                    @endforelse
                </div>

                <!-- BOX STOCK (KANAN) -->
                <div class="col-lg-3">
                    <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                        <div class="card-header bg-primary text-white pb-0">
                            <h6 class="text-white text-sm">STOCK KANDANG</h6>
                        </div>
                        <div class="card-body p-3">
                            <table class="table table-sm mb-0">
                                @forelse($rekapStok as $jenis => $total)
                                <tr>
                                    <td class="text-xs text-uppercase">{{ $jenis }}</td>
                                    <td class="text-xs text-end font-weight-bold">{{ $total }} Ekor</td>
                                </tr>
                                @empty
                                <tr><td colspan="2" class="text-center text-xxs">Kosong</td></tr>
                                @endforelse
                                <tr class="border-top fw-bold">
                                    <td class="text-xs">TOTAL POPULASI</td>
                                    <td class="text-xs text-end">{{ $rekapStok->sum() }} Ekor</td>
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