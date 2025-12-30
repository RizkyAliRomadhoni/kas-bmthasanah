<x-app-layout>
    <main class="main-content">
        <div class="container-fluid py-4">
            <form action="{{ route('neraca.laba-rugi.save') }}" method="POST">
                @csrf
                <div class="card shadow-sm border-0 border-radius-xl">
                    <div class="card-body p-4">
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="fw-bold mb-0 text-uppercase text-primary">Laporan Laba Rugi</h5>
                                <p class="text-xs text-secondary mb-0">Hasanah Farm • Rekapitulasi Otomatis & Input Manual</p>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-sm btn-success px-4 mb-0 shadow-sm">
                                    <i class="fas fa-save me-1"></i> SIMPAN INPUT MANUAL
                                </button>
                                <a href="{{ route('neraca.index') }}" class="btn btn-sm btn-outline-secondary mb-0">Kembali</a>
                            </div>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success text-white text-xs py-2 mb-4">{{ session('success') }}</div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="bg-gray-100 text-center">
                                    <tr>
                                        <th class="text-start ps-4 py-3 text-xs font-weight-bold" style="min-width: 250px;">AKUN</th>
                                        @foreach($bulanList as $bulan)
                                        <th class="text-xs font-weight-bold">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('M-y') }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- SEKSI PENDAPATAN (SEMUA OTOMATIS) -->
                                    <tr class="fw-bold bg-light">
                                        <td class="ps-3 py-2 text-xs text-primary" colspan="{{ 1 + count($bulanList) }}">PENDAPATAN (Otomatis dari Sistem)</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-5 text-xs">Laba Penjualan Kambing</td>
                                        @foreach($bulanList as $bulan)
                                        <td class="text-end pe-4 text-xs">{{ number_format($labaRugiData[$bulan]['laba_jual_kambing'], 0, ',', '.') }}</td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td class="ps-5 text-xs">Laba Penjualan Pakan</td>
                                        @foreach($bulanList as $bulan)
                                        <td class="text-end pe-4 text-xs">{{ number_format($labaRugiData[$bulan]['laba_jual_pakan'], 0, ',', '.') }}</td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td class="ps-5 text-xs">Laba Basil & Penyesuaian</td>
                                        @foreach($bulanList as $bulan)
                                        <td class="text-end pe-4 text-xs">{{ number_format($labaRugiData[$bulan]['laba_basil'] + $labaRugiData[$bulan]['laba_penyesuaian'], 0, ',', '.') }}</td>
                                        @endforeach
                                    </tr>
                                    <tr class="fw-bold bg-gray-50">
                                        <td class="ps-3 text-xs">TOTAL PENDAPATAN</td>
                                        @foreach($bulanList as $bulan)
                                        <td class="text-end pe-4 text-xs">Rp {{ number_format($labaRugiData[$bulan]['total_pendapatan'], 0, ',', '.') }}</td>
                                        @endforeach
                                    </tr>

                                    <!-- SEKSI BIAYA (CAMPURAN OTOMATIS & MANUAL) -->
                                    <tr class="fw-bold bg-light">
                                        <td class="ps-3 py-2 text-xs text-danger" colspan="{{ 1 + count($bulanList) }}">BIAYA & KERUGIAN</td>
                                    </tr>

                                    <!-- BARIS MANUAL: BEBAN UPAH -->
                                    <tr class="bg-yellow-50">
                                        <td class="ps-5 text-xs font-weight-bold"><i class="fas fa-edit text-warning me-1"></i> BEBAN UPAH (Manual)</td>
                                        @foreach($bulanList as $bulan)
                                        <td class="p-0">
                                            <input type="number" name="manual[{{ $bulan }}][beban_upah]" 
                                                class="form-control-table" 
                                                value="{{ (int)$labaRugiData[$bulan]['beban_upah'] }}">
                                        </td>
                                        @endforeach
                                    </tr>

                                    <!-- BARIS MANUAL: BIAYA LAIN-LAIN -->
                                    <tr class="bg-yellow-50">
                                        <td class="ps-5 text-xs font-weight-bold"><i class="fas fa-edit text-warning me-1"></i> BIAYA LAIN-LAIN (Manual)</td>
                                        @foreach($bulanList as $bulan)
                                        <td class="p-0">
                                            <input type="number" name="manual[{{ $bulan }}][biaya_lain]" 
                                                class="form-control-table" 
                                                value="{{ (int)$labaRugiData[$bulan]['biaya_lain'] }}">
                                        </td>
                                        @endforeach
                                    </tr>

                                    <!-- BARIS OTOMATIS: KERUGIAN -->
                                    <tr>
                                        <td class="ps-5 text-xs text-danger">Beban Kambing Mati & Rugi Jual</td>
                                        @foreach($bulanList as $bulan)
                                        <td class="text-end pe-4 text-xs text-danger">{{ number_format($labaRugiData[$bulan]['rugi_jual_kambing'] + $labaRugiData[$bulan]['beban_mati'], 0, ',', '.') }}</td>
                                        @endforeach
                                    </tr>

                                    <tr class="fw-bold bg-gray-50 text-danger">
                                        <td class="ps-3 text-xs">TOTAL BIAYA</td>
                                        @foreach($bulanList as $bulan)
                                        <td class="text-end pe-4 text-xs">Rp {{ number_format($labaRugiData[$bulan]['total_biaya'], 0, ',', '.') }}</td>
                                        @endforeach
                                    </tr>

                                    <!-- HASIL AKHIR -->
                                    <tr class="fw-bold bg-dark text-white">
                                        <td class="ps-3 py-3 text-sm">LABA RUGI BERSIH</td>
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
    .bg-yellow-50 { background-color: #fffdf0; }
    .form-control-table {
        width: 100%;
        border: none;
        background: transparent;
        text-align: right;
        padding: 8px 24px;
        font-size: 0.75rem;
        font-weight: bold;
        color: #344767;
    }
    .form-control-table:focus {
        background-color: #fff;
        outline: 2px solid #5e72e4;
        border-radius: 4px;
    }
    .ps-5 { padding-left: 3rem !important; }
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
</style>