<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            
            <!-- ===========================================================
                 CARD 1: FORM INPUT MANUAL (UNTUK KOREKSI/TAMBAH DATA)
                 =========================================================== -->
            <div class="card shadow-sm border-0 border-radius-xl mb-4">
                <div class="card-body p-3">
                    <h6 class="text-primary fw-bold mb-3 text-sm">
                        <i class="fas fa-edit me-2"></i> Form Input / Koreksi Data Manual
                    </h6>
                    <form action="{{ route('neraca.laba-rugi.store-manual') }}" method="POST" class="row g-2 align-items-end">
                        @csrf
                        <div class="col-md-3">
                            <label class="text-xxs fw-bold text-uppercase">1. Pilih Bulan</label>
                            <select name="bulan" class="form-control form-control-sm" required>
                                @foreach($bulanList as $bulan)
                                    <option value="{{ $bulan }}">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('F Y') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="text-xxs fw-bold text-uppercase">2. Pilih Baris Akun</label>
                            <select name="kategori" class="form-control form-control-sm" required>
                                <optgroup label="PENDAPATAN">
                                    <option value="laba_kambing">Laba Penjualan Kambing (Net)</option>
                                    <option value="laba_pakan">Laba Penjualan Pakan</option>
                                    <option value="laba_penyesuaian">Penyesuaian Harga</option>
                                    <option value="laba_basil">Laba Basil</option>
                                </optgroup>
                                <optgroup label="BIAYA & PENGELUARAN">
                                    <option value="beban_upah">Beban Upah</option>
                                    <option value="biaya_lain">Biaya Lain-lain</option>
                                    <option value="beban_mati">Beban Kambing Mati</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="text-xxs fw-bold text-uppercase">3. Nilai Rp (Gunakan "-" jika Rugi)</label>
                            <input type="number" name="nilai" class="form-control form-control-sm" placeholder="Contoh: -1000000" required>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-sm btn-primary w-100 mb-0 shadow-sm">
                                <i class="fas fa-save me-1"></i> Update Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ===========================================================
                 CARD 2: TABEL LAPORAN LABA RUGI
                 =========================================================== -->
            <div class="card shadow-sm border-0 border-radius-xl">
                <div class="card-body p-4">
                    
                    <!-- Header Laporan -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="fw-bold mb-0 text-uppercase text-primary">Laporan Laba Rugi</h5>
                            <p class="text-xs text-secondary mb-0">Hasanah Farm • Rekapitulasi Keuangan Bulanan</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('neraca.index') }}" class="btn btn-sm btn-outline-secondary mb-0 px-3">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <!-- Notifikasi Sukses -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show text-white text-xs py-2 mb-4" role="alert">
                            <span class="alert-text"><i class="fas fa-check-circle me-2"></i> {{ session('success') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="bg-gray-100 text-center">
                                <tr>
                                    <th class="text-start ps-4 py-3 text-xs font-weight-bold" style="min-width: 250px; color: #344767;">KETERANGAN AKUN</th>
                                    @foreach($bulanList as $bulan)
                                    <th class="text-xs font-weight-bold text-uppercase" style="color: #344767;">
                                        {{ \Carbon\Carbon::parse($bulan)->translatedFormat('M-y') }}
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                
                                <!-- SEKSI PENDAPATAN -->
                                <tr class="bg-light fw-bold">
                                    <td class="ps-3 py-2 text-xs text-primary" colspan="{{ 1 + count($bulanList) }}">
                                        <i class="fas fa-arrow-up me-1 text-xxs"></i> PENDAPATAN
                                    </td>
                                </tr>

                                <!-- Laba Penjualan Kambing (NET) -->
                                <tr>
                                    <td class="ps-5 text-xs">Laba Penjualan Kambing (Net)</td>
                                    @foreach($bulanList as $bulan)
                                        @php $val = (float)$labaRugiData[$bulan]['laba_kambing']; @endphp
                                        <td class="text-end pe-4 text-xs font-weight-bold {{ $val < 0 ? 'text-danger' : 'text-dark' }}">
                                            {{ $val < 0 ? '-' : '' }} {{ number_format(abs($val), 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>

                                <!-- Laba Penjualan Pakan -->
                                <tr>
                                    <td class="ps-5 text-xs">Laba Penjualan Pakan</td>
                                    @foreach($bulanList as $bulan)
                                        <td class="text-end pe-4 text-xs text-dark">
                                            {{ number_format($labaRugiData[$bulan]['laba_pakan'], 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>

                                <!-- Penyesuaian Harga -->
                                <tr>
                                    <td class="ps-5 text-xs">Penyesuaian Harga</td>
                                    @foreach($bulanList as $bulan)
                                        <td class="text-end pe-4 text-xs text-dark">
                                            {{ number_format($labaRugiData[$bulan]['laba_penyesuaian'], 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>

                                <!-- Laba Basil -->
                                <tr>
                                    <td class="ps-5 text-xs">Laba Basil</td>
                                    @foreach($bulanList as $bulan)
                                        <td class="text-end pe-4 text-xs text-dark">
                                            {{ number_format($labaRugiData[$bulan]['laba_basil'], 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>

                                <!-- Total Pendapatan -->
                                <tr class="bg-gray-50 fw-bold">
                                    <td class="ps-3 text-xs">TOTAL PENDAPATAN</td>
                                    @foreach($bulanList as $bulan)
                                        @php $tp = (float)$labaRugiData[$bulan]['total_pnd']; @endphp
                                        <td class="text-end pe-4 text-xs {{ $tp < 0 ? 'text-danger' : 'text-dark' }}">
                                            {{ $tp < 0 ? '-' : '' }} Rp {{ number_format(abs($tp), 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>

                                <!-- SEKSI BIAYA & KERUGIAN -->
                                <tr class="bg-light fw-bold text-danger">
                                    <td class="ps-3 py-2 text-xs" colspan="{{ 1 + count($bulanList) }}">
                                        <i class="fas fa-arrow-down me-1 text-xxs"></i> BIAYA & KERUGIAN
                                    </td>
                                </tr>

                                <!-- Beban Upah -->
                                <tr>
                                    <td class="ps-5 text-xs">Beban Upah (Manual)</td>
                                    @foreach($bulanList as $bulan)
                                        <td class="text-end pe-4 text-xs text-dark">
                                            {{ number_format($labaRugiData[$bulan]['beban_upah'], 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>

                                <!-- Biaya Lain-lain -->
                                <tr>
                                    <td class="ps-5 text-xs">Biaya Lain-lain (Manual)</td>
                                    @foreach($bulanList as $bulan)
                                        <td class="text-end pe-4 text-xs text-dark">
                                            {{ number_format($labaRugiData[$bulan]['biaya_lain'], 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>

                                <!-- Beban Kambing Mati -->
                                <tr>
                                    <td class="ps-5 text-xs text-danger">Beban Kambing Mati</td>
                                    @foreach($bulanList as $bulan)
                                        <td class="text-end pe-4 text-xs text-danger font-weight-bold">
                                            {{ number_format($labaRugiData[$bulan]['beban_mati'], 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>

                                <!-- Total Biaya -->
                                <tr class="bg-gray-50 fw-bold text-danger border-top">
                                    <td class="ps-3 text-xs">TOTAL BIAYA</td>
                                    @foreach($bulanList as $bulan)
                                        <td class="text-end pe-4 text-xs">
                                            Rp {{ number_format($labaRugiData[$bulan]['total_biaya'], 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>

                                <!-- LABA RUGI BERSIH (FINAL) -->
                                <tr class="bg-dark text-white fw-bold">
                                    <td class="ps-3 py-3 text-sm text-uppercase">Laba Rugi Bersih</td>
                                    @foreach($bulanList as $bulan)
                                        @php $net = (float)$labaRugiData[$bulan]['net']; @endphp
                                        <td class="text-end pe-4 py-3 text-sm {{ $net < 0 ? 'text-warning' : '' }}">
                                            {{ $net < 0 ? '-' : '' }} Rp {{ number_format(abs($net), 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <!-- Keterangan Tambahan -->
                    <div class="mt-4">
                        <p class="text-xxs text-secondary italic mb-0">
                            * <strong>Laba Penjualan Kambing (Net)</strong> dihitung dari (Total Harga Jual - Total Modal HPP). Jika rugi, nilai akan otomatis minus (-).<br>
                            * Baris dengan keterangan <strong>(Manual)</strong> datanya ditarik dari input form di atas tabel.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <!-- CSS CUSTOM UNTUK TAMPILAN -->
    <style>
        .ps-5 { padding-left: 3.5rem !important; }
        .bg-gray-100 { background-color: #f8f9fa !important; }
        .bg-gray-50 { background-color: #fcfcfc !important; }
        .table-bordered td, .table-bordered th { border: 1px solid #e9ecef !important; }
        .italic { font-style: italic; }
        .text-warning { color: #ffbc00 !important; } /* Kuning cerah untuk minus di background gelap */
    </style>
</x-app-layout>