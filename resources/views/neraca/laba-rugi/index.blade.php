<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            
            <form action="{{ route('neraca.laba-rugi.store-manual') }}" method="POST">
                @csrf
                <div class="card shadow-sm border-0 border-radius-xl">
                    <div class="card-body p-4">
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="fw-bold mb-0 text-uppercase text-primary">Laporan Laba Rugi</h5>
                                <p class="text-xs text-secondary mb-0">Hasanah Farm • Rekapitulasi Pendapatan & Biaya</p>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-sm btn-success shadow-sm mb-0 px-3">
                                    <i class="fas fa-save me-1"></i> Simpan Input Manual
                                </button>
                                <a href="{{ route('neraca.laba-rugi.refresh') }}" class="btn btn-sm btn-warning shadow-sm mb-0 px-3">
                                    <i class="fas fa-sync-alt me-1"></i> Hitung Ulang
                                </a>
                                <a href="{{ route('neraca.index') }}" class="btn btn-sm btn-outline-secondary mb-0 px-3">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                            </div>
                        </div>

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
                                    <!-- PENDAPATAN -->
                                    <tr class="fw-bold bg-light">
                                        <td class="ps-3 py-2 text-xs text-primary" colspan="{{ 1 + count($bulanList) }}">PENDAPATAN</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-5 text-xs">LABA PENJUALAN KAMBING</td>
                                        @foreach($bulanList as $bulan)
                                        <td class="text-end pe-4 text-xs">{{ number_format($labaRugiData[$bulan]['laba_jual_kambing'], 0, ',', '.') }}</td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td class="ps-5 text-xs">LABA PENJUALAN PAKAN</td>
                                        @foreach($bulanList as $bulan)
                                        <td class="text-end pe-4 text-xs">{{ number_format($labaRugiData[$bulan]['laba_jual_pakan'], 0, ',', '.') }}</td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td class="ps-5 text-xs">LABA BASIL / PENYESUAIAN</td>
                                        @foreach($bulanList as $bulan)
                                        <td class="text-end pe-4 text-xs">{{ number_format($labaRugiData[$bulan]['laba_basil'] + $labaRugiData[$bulan]['laba_penyesuaian'], 0, ',', '.') }}</td>
                                        @endforeach
                                    </tr>
                                    <tr class="fw-bold bg-gray-50">
                                        <td class="ps-3 text-xs">TOTAL PENDAPATAN</td>
                                        @foreach($bulanList as $bulan)
                                        <td class="text-end pe-4 text-xs border-top">Rp {{ number_format($labaRugiData[$bulan]['total_pendapatan'], 0, ',', '.') }}</td>
                                        @endforeach
                                    </tr>

                                    <!-- BIAYA -->
                                    <tr class="fw-bold bg-light">
                                        <td class="ps-3 py-2 text-xs text-danger" colspan="{{ 1 + count($bulanList) }}">BIAYA & KERUGIAN</td>
                                    </tr>
                                    
                                    <!-- INPUT MANUAL: BEBAN UPAH -->
                                    <tr>
                                        <td class="ps-5 text-xs font-weight-bold text-dark">BEBAN UPAH (Input)</td>
                                        @foreach($bulanList as $bulan)
                                        <td class="p-0">
                                            <input type="number" name="manual[{{ $bulan }}][beban_upah]" 
                                                class="form-control form-control-flush text-end text-xs px-3 border-0 bg-transparent" 
                                                value="{{ (int)$labaRugiData[$bulan]['beban_upah'] }}">
                                        </td>
                                        @endforeach
                                    </tr>

                                    <!-- INPUT MANUAL: BIAYA LAIN -->
                                    <tr>
                                        <td class="ps-5 text-xs font-weight-bold text-dark">BIAYA LAIN-LAIN (Input)</td>
                                        @foreach($bulanList as $bulan)
                                        <td class="p-0">
                                            <input type="number" name="manual[{{ $bulan }}][biaya_lain]" 
                                                class="form-control form-control-flush text-end text-xs px-3 border-0 bg-transparent" 
                                                value="{{ (int)$labaRugiData[$bulan]['biaya_lain'] }}">
                                        </td>
                                        @endforeach
                                    </tr>

                                    <tr>
                                        <td class="ps-5 text-xs text-danger">KERUGIAN JUAL / MATI</td>
                                        @foreach($bulanList as $bulan)
                                        <td class="text-end pe-4 text-xs text-danger">{{ number_format($labaRugiData[$bulan]['rugi_jual_kambing'] + $labaRugiData[$bulan]['beban_mati'], 0, ',', '.') }}</td>
                                        @endforeach
                                    </tr>

                                    <tr class="fw-bold bg-gray-50 text-danger">
                                        <td class="ps-3 text-xs">TOTAL BIAYA</td>
                                        @foreach($bulanList as $bulan)
                                        <td class="text-end pe-4 text-xs border-top">Rp {{ number_format($labaRugiData[$bulan]['total_biaya'], 0, ',', '.') }}</td>
                                        @endforeach
                                    </tr>

                                    <!-- NET PROFIT -->
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
            </form>
        </div>
    </main>
</x-app-layout>

<style>
    .bg-gray-100 { background-color: #f8f9fa !important; }
    .bg-gray-50 { background-color: #fcfcfc !important; }
    .ps-5 { padding-left: 3rem !important; }
    .form-control-flush:focus { outline: none; box-shadow: none; background-color: #e9ecef !important; }
    /* Menghilangkan panah di input number */
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
</style>