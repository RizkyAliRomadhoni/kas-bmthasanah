<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            
            <!-- FORM INPUT SIMPLE -->
            <div class="card shadow-sm mb-4 border-0 border-radius-xl">
                <div class="card-body p-3">
                    <h6 class="text-sm fw-bold mb-3 text-primary"><i class="fas fa-plus-circle me-1"></i> Input Biaya Manual</h6>
                    <form action="{{ route('neraca.laba-rugi.store-manual') }}" method="POST" class="row g-2 align-items-end">
                        @csrf
                        <div class="col-md-3">
                            <label class="text-xs fw-bold">Bulan</label>
                            <select name="bulan" class="form-control form-control-sm" required>
                                @foreach($bulanList as $bulan)
                                    <option value="{{ $bulan }}">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('F Y') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="text-xs fw-bold">Kategori</label>
                            <select name="kategori" class="form-control form-control-sm" required>
                                <option value="beban_upah">Beban Upah</option>
                                <option value="biaya_lain">Biaya Lain-lain</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="text-xs fw-bold">Jumlah (Rp)</label>
                            <input type="number" name="nilai" class="form-control form-control-sm" placeholder="Contoh: 500000" required>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-sm btn-primary w-100 mb-0">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TABEL LAPORAN -->
            <div class="card shadow-sm border-0 border-radius-xl">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="fw-bold mb-0 text-uppercase text-primary">Laporan Laba Rugi</h5>
                            <p class="text-xs text-secondary mb-0">Hasanah Farm • Rekapitulasi Keuangan</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('neraca.laba-rugi.refresh') }}" class="btn btn-sm btn-warning mb-0 px-3">
                                <i class="fas fa-sync-alt me-1"></i> Refresh
                            </a>
                            <a href="{{ route('neraca.index') }}" class="btn btn-sm btn-outline-secondary mb-0 px-3">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success text-white text-xs py-2 mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="bg-gray-100">
                                <tr class="text-center">
                                    <th class="text-start ps-4 py-3 text-xs font-weight-bold" style="min-width: 250px;">AKUN</th>
                                    @foreach($bulanList as $bulan)
                                        <th class="text-xs font-weight-bold">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('M-y') }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <!-- PENDAPATAN -->
                                <tr class="bg-light fw-bold">
                                    <td class="ps-3 text-xs text-primary" colspan="{{ 1 + count($bulanList) }}">PENDAPATAN</td>
                                </tr>
                                <tr>
                                    <td class="ps-5 text-xs text-dark">Laba Penjualan Kambing</td>
                                    @foreach($bulanList as $bulan)
                                        <td class="text-end pe-4 text-xs">{{ number_format($labaRugiData[$bulan]['laba_kambing'], 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="ps-5 text-xs text-dark">Laba Penjualan Pakan</td>
                                    @foreach($bulanList as $bulan)
                                        <td class="text-end pe-4 text-xs">{{ number_format($labaRugiData[$bulan]['laba_pakan'], 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="ps-5 text-xs text-dark">Basil & Penyesuaian</td>
                                    @foreach($bulanList as $bulan)
                                        <td class="text-end pe-4 text-xs">{{ number_format($labaRugiData[$bulan]['laba_lain'], 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>
                                <tr class="bg-gray-50 fw-bold">
                                    <td class="ps-3 text-xs">TOTAL PENDAPATAN</td>
                                    @foreach($bulanList as $bulan)
                                        <td class="text-end pe-4 text-xs">Rp {{ number_format($labaRugiData[$bulan]['total_pendapatan'], 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>

                                <!-- BIAYA -->
                                <tr class="bg-light fw-bold">
                                    <td class="ps-3 text-xs text-danger" colspan="{{ 1 + count($bulanList) }}">BIAYA & KERUGIAN</td>
                                </tr>
                                <tr>
                                    <td class="ps-5 text-xs font-weight-bold text-primary">Beban Upah (Manual)</td>
                                    @foreach($bulanList as $bulan)
                                        <td class="text-end pe-4 text-xs">{{ number_format($labaRugiData[$bulan]['beban_upah'], 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="ps-5 text-xs font-weight-bold text-primary">Biaya Lain-lain (Manual)</td>
                                    @foreach($bulanList as $bulan)
                                        <td class="text-end pe-4 text-xs">{{ number_format($labaRugiData[$bulan]['biaya_lain'], 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="ps-5 text-xs text-danger">Kerugian Penjualan & Mati</td>
                                    @foreach($bulanList as $bulan)
                                        <td class="text-end pe-4 text-xs text-danger">{{ number_format($labaRugiData[$bulan]['rugi_jual'] + $labaRugiData[$bulan]['beban_mati'], 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>
                                <tr class="bg-gray-50 fw-bold text-danger">
                                    <td class="ps-3 text-xs">TOTAL BIAYA</td>
                                    @foreach($bulanList as $bulan)
                                        <td class="text-end pe-4 text-xs">Rp {{ number_format($labaRugiData[$bulan]['total_biaya'], 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>

                                <!-- HASIL AKHIR -->
                                <tr class="bg-dark text-white fw-bold">
                                    <td class="ps-3 py-3 text-sm">LABA RUGI BERSIH</td>
                                    @foreach($bulanList as $bulan)
                                        <td class="text-end pe-4 py-3 text-sm">Rp {{ number_format($labaRugiData[$bulan]['net_laba_rugi'], 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>

<style>
    .bg-gray-100 { background-color: #f8f9fa !important; }
    .bg-gray-50 { background-color: #fcfcfc !important; }
    .ps-5 { padding-left: 3rem !important; }
    .table-bordered td, .table-bordered th { border: 1px solid #e9ecef !important; }
</style>