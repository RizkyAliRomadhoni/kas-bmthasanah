<x-app-layout>
<main class="main-content">
    <div class="container-fluid py-4">
        
        <!-- CARD INPUT MANUAL -->
        <div class="card shadow-sm border-0 border-radius-xl mb-4">
            <div class="card-body p-3">
                <h6 class="text-primary fw-bold mb-3 text-sm">Input Manual / Koreksi Data</h6>
                <form action="{{ route('neraca.laba-rugi.store-manual') }}" method="POST" class="row g-2 align-items-end">
                    @csrf
                    <div class="col-md-3">
                        <label class="text-xxs fw-bold">Pilih Bulan</label>
                        <select name="bulan" class="form-control form-control-sm">
                            @foreach($bulanList as $bulan)
                                <option value="{{ $bulan }}">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('F Y') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="text-xxs fw-bold">Akun/Baris</label>
                        <select name="kategori" class="form-control form-control-sm">
                            <option value="laba_kambing">Laba Penjualan Kambing (Net)</option>
                            <option value="laba_pakan">Laba Penjualan Pakan</option>
                            <option value="laba_penyesuaian">Penyesuaian Harga</option>
                            <option value="laba_basil">Laba Basil</option>
                            <option value="beban_upah">Beban Upah</option>
                            <option value="biaya_lain">Biaya Lain-lain</option>
                            <option value="beban_mati">Beban Kambing Mati</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="text-xxs fw-bold">Nilai (Gunakan "-" jika minus)</label>
                        <input type="number" name="nilai" class="form-control form-control-sm" required placeholder="Contoh: -1000000">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-sm btn-primary w-100 mb-0 shadow-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- TABEL LAPORAN -->
        <div class="card shadow-sm border-0 border-radius-xl">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between mb-4">
                    <h5 class="fw-bold text-primary text-uppercase mb-0">Laporan Laba Rugi</h5>
                    <a href="{{ route('neraca.index') }}" class="btn btn-sm btn-outline-secondary mb-0">Kembali</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="bg-gray-100 text-center">
                            <tr>
                                <th class="text-start ps-4 py-3 text-xs font-weight-bold">AKUN</th>
                                @foreach($bulanList as $bulan)
                                    <th class="text-xs font-weight-bold">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('M-y') }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <!-- PENDAPATAN -->
                            <tr class="bg-light fw-bold text-xs text-primary"><td colspan="{{ count($bulanList) + 1 }}">PENDAPATAN</td></tr>
                            
                            <tr>
                                <td class="ps-5 text-xs">Laba Penjualan Kambing (Net)</td>
                                @foreach($bulanList as $bulan)
                                    @php $val = $labaRugiData[$bulan]['laba_kambing']; @endphp
                                    <td class="text-end pe-4 text-xs {{ $val < 0 ? 'text-danger font-weight-bold' : '' }}">
                                        {{ $val < 0 ? '-' : '' }} {{ number_format(abs($val), 0, ',', '.') }}
                                    </td>
                                @endforeach
                            </tr>

                            <tr>
                                <td class="ps-5 text-xs">Laba Penjualan Pakan</td>
                                @foreach($bulanList as $bulan)
                                    <td class="text-end pe-4 text-xs">{{ number_format($labaRugiData[$bulan]['laba_pakan'], 0, ',', '.') }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="ps-5 text-xs">Penyesuaian Harga</td>
                                @foreach($bulanList as $bulan)
                                    <td class="text-end pe-4 text-xs">{{ number_format($labaRugiData[$bulan]['laba_penyesuaian'], 0, ',', '.') }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="ps-5 text-xs">Laba Basil</td>
                                @foreach($bulanList as $bulan)
                                    <td class="text-end pe-4 text-xs">{{ number_format($labaRugiData[$bulan]['laba_basil'], 0, ',', '.') }}</td>
                                @endforeach
                            </tr>
                            
                            <tr class="bg-gray-50 fw-bold">
                                <td class="ps-3 text-xs">TOTAL PENDAPATAN</td>
                                @foreach($bulanList as $bulan)
                                    @php $tp = $labaRugiData[$bulan]['total_pnd']; @endphp
                                    <td class="text-end pe-4 text-xs {{ $tp < 0 ? 'text-danger' : '' }}">
                                        {{ $tp < 0 ? '-' : '' }} Rp {{ number_format(abs($tp), 0, ',', '.') }}
                                    </td>
                                @endforeach
                            </tr>

                            <!-- BIAYA -->
                            <tr class="bg-light fw-bold text-xs text-danger"><td colspan="{{ count($bulanList) + 1 }}">BIAYA & KERUGIAN</td></tr>
                            <tr>
                                <td class="ps-5 text-xs font-weight-bold text-dark">Beban Upah (Manual)</td>
                                @foreach($bulanList as $bulan)
                                    <td class="text-end pe-4 text-xs">{{ number_format($labaRugiData[$bulan]['beban_upah'], 0, ',', '.') }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="ps-5 text-xs font-weight-bold text-dark">Biaya Lain-lain (Manual)</td>
                                @foreach($bulanList as $bulan)
                                    <td class="text-end pe-4 text-xs">{{ number_format($labaRugiData[$bulan]['biaya_lain'], 0, ',', '.') }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="ps-5 text-xs text-danger">Beban Kambing Mati</td>
                                @foreach($bulanList as $bulan)
                                    <td class="text-end pe-4 text-xs text-danger">{{ number_format($labaRugiData[$bulan]['beban_mati'], 0, ',', '.') }}</td>
                                @endforeach
                            </tr>
                            
                            <tr class="bg-gray-50 fw-bold text-danger">
                                <td class="ps-3 text-xs">TOTAL BIAYA</td>
                                @foreach($bulanList as $bulan)
                                    <td class="text-end pe-4 text-xs">Rp {{ number_format($labaRugiData[$bulan]['total_biaya'], 0, ',', '.') }}</td>
                                @endforeach
                            </tr>

                            <!-- FINAL -->
                            <tr class="bg-dark text-white fw-bold">
                                <td class="ps-3 py-3 text-sm">LABA RUGI BERSIH</td>
                                @foreach($bulanList as $bulan)
                                    @php $net = $labaRugiData[$bulan]['net']; @endphp
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