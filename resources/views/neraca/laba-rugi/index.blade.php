<x-app-layout>
<div class="container-fluid py-4">
    
    <!-- CARD 1: FORM INPUT MANUAL -->
    <div class="card shadow-sm border-0 border-radius-xl mb-4">
        <div class="card-body p-3">
            <h6 class="text-primary fw-bold mb-3"><i class="fas fa-edit me-2"></i> Form Input / Koreksi Data</h6>
            <form action="{{ route('neraca.laba-rugi.store-manual') }}" method="POST" class="row g-2 align-items-end">
                @csrf
                <div class="col-md-3">
                    <label class="text-xs fw-bold">1. Pilih Bulan</label>
                    <select name="bulan" class="form-control form-control-sm" required>
                        @foreach($bulanList as $bulan)
                            <option value="{{ $bulan }}">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('F Y') }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="text-xs fw-bold">2. Pilih Akun/Baris</label>
                    <select name="kategori" class="form-control form-control-sm" required>
                        <optgroup label="PENDAPATAN">
                            <option value="laba_kambing">Laba Penjualan Kambing</option>
                            <option value="laba_pakan">Laba Penjualan Pakan</option>
                            <option value="laba_lain">Laba Basil & Penyesuaian</option>
                        </optgroup>
                        <optgroup label="BIAYA">
                            <option value="beban_upah">Beban Upah</option>
                            <option value="biaya_lain">Biaya Lain-lain</option>
                            <option value="beban_mati">Beban Kambing Mati & Rugi Jual</option>
                        </optgroup>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="text-xs fw-bold">3. Masukkan Nilai Baru (Rp)</label>
                    <input type="number" name="nilai" class="form-control form-control-sm" placeholder="Contoh: 1500000" required>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-sm btn-primary w-100 mb-0 shadow-sm">
                        Update Baris & Kolom
                    </button>
                </div>
            </form>
            <p class="text-xxs text-secondary mt-2 mb-0 italic">* Mengisi form ini akan menimpa (override) hitungan otomatis dari sistem pada baris dan bulan yang dipilih.</p>
        </div>
    </div>

    <!-- CARD 2: TABEL LAPORAN (BERSIH) -->
    <div class="card shadow-sm border-0 border-radius-xl">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-bold mb-0 text-uppercase text-primary">Laporan Laba Rugi</h5>
                    <p class="text-xs text-secondary mb-0">Hasanah Farm • Rekapitulasi Keuangan</p>
                </div>
                <a href="{{ route('neraca.index') }}" class="btn btn-sm btn-outline-secondary mb-0">Kembali</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success text-white text-xs py-2 mb-4 border-radius-lg">
                    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="bg-gray-100 text-center">
                        <tr>
                            <th class="text-start ps-4 py-3 text-xs font-weight-bold" style="width: 250px;">AKUN</th>
                            @foreach($bulanList as $bulan)
                                <th class="text-xs font-weight-bold">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('M-y') }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <!-- PENDAPATAN -->
                        <tr class="bg-light fw-bold text-xs text-primary">
                            <td colspan="{{ count($bulanList) + 1 }}" class="ps-3">PENDAPATAN</td>
                        </tr>
                        <tr>
                            <td class="ps-5 text-xs">Laba Penjualan Kambing</td>
                            @foreach($bulanList as $bulan)
                                <td class="text-end pe-4 text-xs">{{ number_format($labaRugiData[$bulan]['laba_kambing'], 0, ',', '.') }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="ps-5 text-xs">Laba Penjualan Pakan</td>
                            @foreach($bulanList as $bulan)
                                <td class="text-end pe-4 text-xs">{{ number_format($labaRugiData[$bulan]['laba_pakan'], 0, ',', '.') }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="ps-5 text-xs">Laba Basil & Penyesuaian</td>
                            @foreach($bulanList as $bulan)
                                <td class="text-end pe-4 text-xs">{{ number_format($labaRugiData[$bulan]['laba_lain'], 0, ',', '.') }}</td>
                            @endforeach
                        </tr>
                        <tr class="bg-gray-50 fw-bold">
                            <td class="ps-3 text-xs">TOTAL PENDAPATAN</td>
                            @foreach($bulanList as $bulan)
                                <td class="text-end pe-4 text-xs">Rp {{ number_format($labaRugiData[$bulan]['total_pnd'], 0, ',', '.') }}</td>
                            @endforeach
                        </tr>

                        <!-- BIAYA -->
                        <tr class="bg-light fw-bold text-xs text-danger">
                            <td colspan="{{ count($bulanList) + 1 }}" class="ps-3">BIAYA & KERUGIAN</td>
                        </tr>
                        <tr>
                            <td class="ps-5 text-xs">Beban Upah</td>
                            @foreach($bulanList as $bulan)
                                <td class="text-end pe-4 text-xs text-dark font-weight-bold">{{ number_format($labaRugiData[$bulan]['beban_upah'], 0, ',', '.') }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="ps-5 text-xs">Biaya Lain-lain</td>
                            @foreach($bulanList as $bulan)
                                <td class="text-end pe-4 text-xs text-dark font-weight-bold">{{ number_format($labaRugiData[$bulan]['biaya_lain'], 0, ',', '.') }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="ps-5 text-xs text-danger">Beban Kambing Mati & Rugi Jual</td>
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

                        <!-- HASIL AKHIR -->
                        <tr class="bg-dark text-white fw-bold">
                            <td class="ps-3 py-3 text-sm">LABA RUGI BERSIH</td>
                            @foreach($bulanList as $bulan)
                                <td class="text-end pe-4 py-3 text-sm">Rp {{ number_format($labaRugiData[$bulan]['net'], 0, ',', '.') }}</td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .ps-5 { padding-left: 3rem !important; }
    .bg-gray-100 { background-color: #f8f9fa !important; }
    .bg-gray-50 { background-color: #fcfcfc !important; }
    .italic { font-style: italic; }
</style>
</x-app-layout>