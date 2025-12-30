<x-app-layout>
<div class="container-fluid py-4">
    <form action="{{ route('neraca.laba-rugi.store-manual') }}" method="POST">
        @csrf
        <div class="card shadow-sm border-radius-xl">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold text-primary text-uppercase">Laporan Laba Rugi (Full Editable)</h5>
                    <button type="submit" class="btn btn-success shadow-sm">SIMPAN PERUBAHAN MANUAL</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="bg-gray-100 text-center">
                            <tr>
                                <th class="text-start ps-4" style="width: 250px;">AKUN</th>
                                @foreach($bulanList as $bulan)
                                    <th class="text-xs">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('M-y') }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <!-- SEKSI PENDAPATAN -->
                            <tr class="bg-light fw-bold text-primary text-xs">
                                <td colspan="{{ count($bulanList)+1 }}">PENDAPATAN</td>
                            </tr>
                            <tr>
                                <td class="ps-5 text-xs">Laba Penjualan Kambing</td>
                                @foreach($bulanList as $bulan)
                                    <td class="p-0"><input type="number" name="manual[{{ $bulan }}][laba_kambing]" value="{{ (int)$labaRugiData[$bulan]['laba_kambing'] }}" class="input-table"></td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="ps-5 text-xs">Laba Penjualan Pakan</td>
                                @foreach($bulanList as $bulan)
                                    <td class="p-0"><input type="number" name="manual[{{ $bulan }}][laba_pakan]" value="{{ (int)$labaRugiData[$bulan]['laba_pakan'] }}" class="input-table"></td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="ps-5 text-xs">Laba Basil & Penyesuaian</td>
                                @foreach($bulanList as $bulan)
                                    <td class="p-0"><input type="number" name="manual[{{ $bulan }}][laba_lain]" value="{{ (int)$labaRugiData[$bulan]['laba_lain'] }}" class="input-table"></td>
                                @endforeach
                            </tr>
                            <tr class="bg-gray-50 fw-bold">
                                <td class="ps-3 text-xs">TOTAL PENDAPATAN</td>
                                @foreach($bulanList as $bulan)
                                    <td class="text-end pe-4 text-xs">Rp {{ number_format($labaRugiData[$bulan]['total_pnd'], 0, ',', '.') }}</td>
                                @endforeach
                            </tr>

                            <!-- SEKSI BIAYA -->
                            <tr class="bg-light fw-bold text-danger text-xs">
                                <td colspan="{{ count($bulanList)+1 }}">BIAYA & KERUGIAN</td>
                            </tr>
                            <tr>
                                <td class="ps-5 text-xs">Beban Upah</td>
                                @foreach($bulanList as $bulan)
                                    <td class="p-0"><input type="number" name="manual[{{ $bulan }}][beban_upah]" value="{{ (int)$labaRugiData[$bulan]['beban_upah'] }}" class="input-table"></td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="ps-5 text-xs">Biaya Lain-lain</td>
                                @foreach($bulanList as $bulan)
                                    <td class="p-0"><input type="number" name="manual[{{ $bulan }}][biaya_lain]" value="{{ (int)$labaRugiData[$bulan]['biaya_lain'] }}" class="input-table"></td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="ps-5 text-xs">Beban Kambing Mati & Rugi Jual</td>
                                @foreach($bulanList as $bulan)
                                    <td class="p-0"><input type="number" name="manual[{{ $bulan }}][beban_mati]" value="{{ (int)$labaRugiData[$bulan]['beban_mati'] }}" class="input-table text-danger"></td>
                                @endforeach
                            </tr>
                            <tr class="bg-gray-50 fw-bold text-danger">
                                <td class="ps-3 text-xs">TOTAL BIAYA</td>
                                @foreach($bulanList as $bulan)
                                    <td class="text-end pe-4 text-xs">Rp {{ number_format($labaRugiData[$bulan]['total_biaya'], 0, ',', '.') }}</td>
                                @endforeach
                            </tr>

                            <!-- SUMMARY -->
                            <tr class="bg-dark text-white fw-bold">
                                <td class="ps-3 py-3 text-sm">LABA RUGI BERSIH</td>
                                @foreach($bulanList as $bulan)
                                    <td class="text-end pe-4 text-sm">Rp {{ number_format($labaRugiData[$bulan]['net'], 0, ',', '.') }}</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .input-table {
        width: 100%;
        height: 40px;
        border: none;
        background: transparent;
        text-align: right;
        padding-right: 15px;
        font-size: 0.75rem;
        font-weight: bold;
    }
    .input-table:focus {
        background: #fff;
        outline: 1px solid #4CAF50;
    }
    .ps-5 { padding-left: 3rem !important; }
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
</style>
</x-app-layout>