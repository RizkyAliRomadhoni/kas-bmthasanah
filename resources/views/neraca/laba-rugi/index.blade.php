<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            
            <div class="card shadow-sm border-0 border-radius-xl">
                <div class="card-body p-4">
                    
                    <!-- HEADER & TOMBOL HITUNG ULANG -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="fw-bold mb-0 text-uppercase text-primary">Laporan Laba Rugi</h5>
                            <p class="text-xs text-secondary mb-0">Hasanah Farm • Rekapitulasi Pendapatan & Biaya</p>
                        </div>
                        <div class="d-flex gap-2">
                            <!-- Tombol Hitung Ulang -->
                            <a href="{{ route('neraca.laba-rugi.refresh') }}" class="btn btn-sm btn-warning shadow-sm mb-0 px-3">
                                <i class="fas fa-sync-alt me-1"></i> Hitung Ulang
                            </a>
                            <a href="{{ route('neraca.index') }}" class="btn btn-sm btn-outline-secondary mb-0 px-3">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <!-- Notifikasi Sukses -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show text-white text-xs py-2 mb-4" role="alert">
                            <span class="alert-icon"><i class="fas fa-check-circle me-2"></i></span>
                            <span class="alert-text">{{ session('success') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="bg-gray-100 text-center">
                                <tr>
                                    <th class="text-start ps-4 py-3" style="min-width: 300px; color: #344767; font-size: 0.75rem;">AKUN</th>
                                    @foreach($bulanList as $bulan)
                                    <th class="text-xs text-uppercase font-weight-bold" style="color: #344767;">
                                        {{ \Carbon\Carbon::parse($bulan)->translatedFormat('M-y') }}
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <!-- SEKSI PENDAPATAN -->
                                <tr class="fw-bold bg-light">
                                    <td class="ps-3 py-2 text-xs text-primary" colspan="{{ 1 + count($bulanList) }}">
                                        <i class="fas fa-arrow-up me-1"></i> PENDAPATAN
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-5 text-xs text-dark font-weight-bold">LABA PENJUALAN KAMBING</td>
                                    @foreach($bulanList as $bulan)
                                    <td class="text-end pe-4 text-xs">{{ number_format($labaRugiData[$bulan]['laba_jual_kambing'], 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="ps-5 text-xs text-dark font-weight-bold">LABA PENJUALAN PAKAN</td>
                                    @foreach($bulanList as $bulan)
                                    <td class="text-end pe-4 text-xs">{{ number_format($labaRugiData[$bulan]['laba_jual_pakan'], 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="ps-5 text-xs text-dark font-weight-bold">LABA PENYESUAIAN HARGA</td>
                                    @foreach($bulanList as $bulan)
                                    <td class="text-end pe-4 text-xs">{{ number_format($labaRugiData[$bulan]['laba_penyesuaian'], 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="ps-5 text-xs text-dark font-weight-bold">LABA BASIL SIMPANAN</td>
                                    @foreach($bulanList as $bulan)
                                    <td class="text-end pe-4 text-xs">{{ number_format($labaRugiData[$bulan]['laba_basil'], 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>
                                <tr class="fw-bold bg-gray-50">
                                    <td class="ps-3 py-2 text-xs">TOTAL PENDAPATAN</td>
                                    @foreach($bulanList as $bulan)
                                    <td class="text-end pe-4 text-xs border-top">Rp {{ number_format($labaRugiData[$bulan]['total_pendapatan'], 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>

                                <!-- SEKSI BIAYA -->
                                <tr class="fw-bold bg-light">
                                    <td class="ps-3 py-2 text-xs text-danger" colspan="{{ 1 + count($bulanList) }}">
                                        <i class="fas fa-arrow-down me-1"></i> BIAYA & KERUGIAN
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-5 text-xs">BEBAN UPAH</td>
                                    @foreach($bulanList as $bulan)
                                    <td class="text-end pe-4 text-xs">-</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="ps-5 text-xs text-danger">BEBAN KERUGIAN PENJUALAN KAMBING</td>
                                    @foreach($bulanList as $bulan)
                                    <td class="text-end pe-4 text-xs text-danger">{{ number_format($labaRugiData[$bulan]['rugi_jual_kambing'], 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="ps-5 text-xs text-danger font-weight-bold">BEBAN KERUGIAN KAMBING MATI</td>
                                    @foreach($bulanList as $bulan)
                                    <td class="text-end pe-4 text-xs text-danger font-weight-bold">{{ number_format($labaRugiData[$bulan]['beban_mati'], 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>
                                <tr class="fw-bold bg-gray-50 text-danger">
                                    <td class="ps-3 py-2 text-xs">TOTAL BIAYA</td>
                                    @foreach($bulanList as $bulan)
                                    <td class="text-end pe-4 text-xs border-top">Rp {{ number_format($labaRugiData[$bulan]['total_biaya'], 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>

                                <!-- LABA RUGI AKHIR -->
                                <tr class="fw-bold bg-dark text-white">
                                    <td class="ps-3 py-3 text-sm text-uppercase">Laba Rugi Bersih</td>
                                    @foreach($bulanList as $bulan)
                                    @php $net = $labaRugiData[$bulan]['net_laba_rugi']; @endphp
                                    <td class="text-end pe-4 py-3 text-sm">
                                        {{ $net < 0 ? '-' : '' }} Rp {{ number_format(abs($net), 0, ',', '.') }}
                                    </td>
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
    .ps-5 { padding-left: 3.5rem !important; }
    .table-bordered td, .table-bordered th { border: 1px solid #e9ecef !important; }
    .font-weight-bold { font-weight: 700 !important; }
</style>