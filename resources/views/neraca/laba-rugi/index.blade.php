<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            
            <div class="card shadow-sm border-0 border-radius-xl">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">LAPORAN LABA RUGI</h5>
                        <a href="{{ route('neraca.index') }}" class="btn btn-sm btn-outline-secondary">Kembali</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="bg-gray-100 text-center">
                                <tr>
                                    <th class="text-start ps-4 py-3" style="width: 350px;">AKUN</th>
                                    @foreach($bulanList as $bulan)
                                    <th class="text-xs">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('M-y') }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <!-- PENDAPATAN -->
                                <tr class="fw-bold bg-light">
                                    <td class="ps-3 py-2 text-xs" colspan="{{ 1 + count($bulanList) }}">PENDAPATAN</td>
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
                                    <td class="ps-5 text-xs">LABA PENYESUAIAN HARGA</td>
                                    @foreach($bulanList as $bulan)
                                    <td class="text-end pe-4 text-xs">{{ number_format($labaRugiData[$bulan]['laba_penyesuaian'], 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="ps-5 text-xs">LABA BASIL SIMPANAN</td>
                                    @foreach($bulanList as $bulan)
                                    <td class="text-end pe-4 text-xs">{{ number_format($labaRugiData[$bulan]['laba_basil'], 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>
                                <tr class="fw-bold">
                                    <td class="ps-3 py-2 text-xs">TOTAL PENDAPATAN</td>
                                    @foreach($bulanList as $bulan)
                                    <td class="text-end pe-4 text-xs border-top">Rp {{ number_format($labaRugiData[$bulan]['total_pendapatan'], 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>

                                <!-- BIAYA -->
                                <tr class="fw-bold bg-light">
                                    <td class="ps-3 py-2 text-xs" colspan="{{ 1 + count($bulanList) }}">BIAYA</td>
                                </tr>
                                <tr>
                                    <td class="ps-5 text-xs">BEBAN UPAH</td>
                                    @foreach($bulanList as $bulan)
                                    <td class="text-end pe-4 text-xs">-</td> <!-- Kosong sesuai instruksi -->
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="ps-5 text-xs">BEBAN KERUGIAN PENJUALAN KAMBING</td>
                                    @foreach($bulanList as $bulan)
                                    <td class="text-end pe-4 text-xs">{{ number_format($labaRugiData[$bulan]['rugi_jual_kambing'], 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="ps-5 text-xs text-danger">BEBAN KERUGIAN KAMBING MATI</td>
                                    @foreach($bulanList as $bulan)
                                    <td class="text-end pe-4 text-xs text-danger">{{ number_format($labaRugiData[$bulan]['beban_mati'], 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>
                                <tr class="fw-bold">
                                    <td class="ps-3 py-2 text-xs">TOTAL BIAYA</td>
                                    @foreach($bulanList as $bulan)
                                    <td class="text-end pe-4 text-xs border-top">Rp {{ number_format($labaRugiData[$bulan]['total_biaya'], 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>

                                <!-- LABA RUGI BERSIH -->
                                <tr class="fw-bold bg-gray-100">
                                    <td class="ps-3 py-3 text-sm">LABA RUGI</td>
                                    @foreach($bulanList as $bulan)
                                    @php $net = $labaRugiData[$bulan]['net_laba_rugi']; @endphp
                                    <td class="text-end pe-4 py-3 text-sm {{ $net < 0 ? 'text-danger' : 'text-success' }}">
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
    .ps-5 { padding-left: 3.5rem !important; }
    .table-bordered td, .table-bordered th { border: 1px solid #dee2e6 !important; }
</style>