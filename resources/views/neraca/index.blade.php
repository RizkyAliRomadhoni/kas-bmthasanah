<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">

            <!-- HEADER UTAMA -->
            <div class="row align-items-center mb-3">
                <div class="col-md-6 col-12 text-center text-md-start">
                    <h5 class="fw-bold mb-0 text-uppercase text-primary tracking-tight">Laporan Neraca Keuangan</h5>
                    <p class="text-xs text-secondary mb-0">Hasanah Farm • Periode Aktif</p>
                </div>
                <div class="col-md-6 col-12 d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                    <a href="{{ route('neraca.laba-rugi') }}" class="btn btn-sm btn-primary shadow-sm mb-0 px-4">
                        <i class="fas fa-chart-line me-2"></i> Laporan Laba Rugi
                    </a>
                </div>
            </div>

            <!-- MENU NAVIGASI AKUN (PENGGANTI DROPDOWN) -->
            <div class="card shadow-none border-0 bg-transparent mb-4">
                <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
                    <!-- KELOMPOK ASET -->
                    <a href="{{ route('kambing-akun.index') }}" class="btn-nav shadow-sm">
                        <i class="fas fa-sheep text-dark"></i> Stok Kambing
                    </a>
                    <a href="{{ route('pakan.index') }}" class="btn-nav shadow-sm">
                        <i class="fas fa-utensils text-warning"></i> Pakan
                    </a>
                    <a href="{{ route('kandang.index') }}" class="btn-nav shadow-sm">
                        <i class="fas fa-tools text-info"></i> Kandang
                    </a>
                    <a href="{{ route('perlengkapan.index') }}" class="btn-nav shadow-sm">
                        <i class="fas fa-box text-primary"></i> Perlengkapan
                    </a>
                    <a href="{{ route('upah.index') }}" class="btn-nav shadow-sm">
                        <i class="fas fa-user-tie text-secondary"></i> Upah
                    </a>
                    <a href="{{ route('rincian-kambing.index') }}" class="btn-nav shadow-sm">
                        <i class="fas fa-utensils text-warning"></i> Pakan
                    </a>
                    
                    <!-- KELOMPOK KEUANGAN -->
                    <div class="vr mx-2 d-none d-md-block" style="height: 30px; align-self: center; border-left: 1px solid #ddd;"></div>
                    
                    <a href="{{ route('neraca.penjualan.index') }}" class="btn-nav shadow-sm">
                        <i class="fas fa-shopping-cart text-primary"></i> Penjualan
                    </a>
                    <a href="{{ route('piutang.index') }}" class="btn-nav shadow-sm">
                        <i class="fas fa-file-invoice-dollar text-success"></i> Piutang
                    </a>
                    <a href="{{ route('hutang.index') }}" class="btn-nav shadow-sm">
                        <i class="fas fa-hand-holding-usd text-danger"></i> Hutang
                    </a>
                    <a href="{{ route('operasional.index') }}" class="btn-nav shadow-sm">
                        <i class="fas fa-cogs text-secondary"></i> Operasional
                    </a>
                </div>
            </div>

            <!-- TABEL NERACA UTAMA -->
            <div class="card shadow-sm border-0 border-radius-xl overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="bg-gray-100 text-secondary border-bottom">
                                    <th class="ps-4 text-xxs font-weight-bolder opacity-7 py-3">KATEGORI & AKUN</th>
                                    <th class="text-center text-xxs font-weight-bolder opacity-7">SALDO AWAL</th>
                                    @foreach ($bulanList as $bulan)
                                        <th class="text-center text-xxs font-weight-bolder opacity-7">
                                            {{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('M Y') }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>
                                {{-- SEKSI AKTIVA --}}
                                <tr class="bg-light-primary border-bottom">
                                    <td colspan="{{ 2 + count($bulanList) }}" class="ps-4 py-2">
                                        <span class="text-xs fw-bold text-primary text-uppercase">
                                            <i class="fas fa-plus-circle me-1"></i> Aktiva (Aset)
                                        </span>
                                    </td>
                                </tr>
                                @foreach ($akunAktiva as $akun)
                                    <tr class="border-bottom">
                                        <td class="ps-5 py-2">
                                            <span class="text-sm font-weight-bold text-dark">
                                                {{ $akun == 'Perlengkapan' ? 'Perlengkapan (Complifit)' : $akun }}
                                            </span>
                                        </td>
                                        <td class="text-center text-muted text-xs">
                                            {{ number_format($saldoAwal[$akun] ?? 0, 0, ',', '.') }}
                                        </td>
                                        @foreach ($bulanList as $bulan)
                                            <td class="text-center text-sm font-weight-bold">
                                                {{ number_format($saldo[$akun][$bulan] ?? 0, 0, ',', '.') }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach

                                <tr class="bg-gray-50 fw-bold border-bottom">
                                    <td class="ps-4 text-sm py-2">TOTAL AKTIVA</td>
                                    <td class="text-center text-sm">{{ number_format(array_sum($saldoAwal), 0, ',', '.') }}</td>
                                    @foreach ($bulanList as $bulan)
                                        <td class="text-center text-sm text-primary font-weight-bolder">
                                            @php
                                                $totalAktivaBulan = 0;
                                                foreach($akunAktiva as $a) { $totalAktivaBulan += $saldo[$a][$bulan] ?? 0; }
                                            @endphp
                                            {{ number_format($totalAktivaBulan, 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>

                                {{-- SEKSI PASIVA --}}
                                <tr class="bg-light-danger border-bottom">
                                    <td colspan="{{ 2 + count($bulanList) }}" class="ps-4 py-2">
                                        <span class="text-xs fw-bold text-danger text-uppercase">
                                            <i class="fas fa-minus-circle me-1"></i> Pasiva (Kewajiban & Modal)
                                        </span>
                                    </td>
                                </tr>
                                @foreach ($akunPasiva as $akun)
                                    <tr class="border-bottom">
                                        <td class="ps-5 py-2 text-sm font-weight-bold text-dark">{{ $akun }}</td>
                                        <td class="text-center text-muted text-xs">
                                            {{ number_format($saldoAwal[$akun] ?? 0, 0, ',', '.') }}
                                        </td>
                                        @foreach ($bulanList as $bulan)
                                            <td class="text-center text-sm">
                                                {{ number_format($saldo[$akun][$bulan] ?? 0, 0, ',', '.') }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach

                                <tr class="bg-gray-50 italic border-bottom">
                                    <td class="ps-5 text-sm py-2">Modal Awal Tetap</td>
                                    <td class="text-center text-muted text-xs">200.000.000</td>
                                    @foreach ($bulanList as $bulan)
                                        <td class="text-center text-sm">200.000.000</td>
                                    @endforeach
                                </tr>

                                <tr class="border-bottom">
                                    <td class="ps-5 text-sm py-2">Laba Rugi Tahun Berjalan</td>
                                    <td class="text-center text-muted text-xs">-</td>
                                    @foreach ($bulanList as $bulan)
                                        <td class="text-center text-success fw-bold text-sm">
                                            @php
                                                $totalAktivaBulan = 0;
                                                foreach($akunAktiva as $a) { $totalAktivaBulan += $saldo[$a][$bulan] ?? 0; }
                                                $totalKewajibanBulan = 0;
                                                foreach($akunPasiva as $p) { $totalKewajibanBulan += $saldo[$p][$bulan] ?? 0; }
                                                $labaTahunBerjalan = $totalAktivaBulan - $totalKewajibanBulan - 200000000;
                                            @endphp
                                            {{ number_format($labaTahunBerjalan, 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>

                                <tr class="bg-warning-light fw-bold border-bottom">
                                    <td class="ps-4 text-sm py-2">TOTAL PASIVA</td>
                                    <td class="text-center text-sm">-</td>
                                    @foreach ($bulanList as $bulan)
                                        <td class="text-center text-sm font-weight-bolder">
                                            {{ number_format($totalAktivaBulan, 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>

                                <tr class="bg-white">
                                    <td class="ps-4 text-xxs font-weight-bold py-3 text-secondary">STATUS BALANCE</td>
                                    <td></td>
                                    @foreach ($bulanList as $bulan)
                                        <td class="text-center">
                                            <span class="badge badge-sm bg-gradient-success text-xxs">
                                                <i class="fas fa-check-double me-1"></i> BALANCE
                                            </span>
                                        </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- INFO SALDO KAS -->
            <div class="row mt-4">
                <div class="col-md-4 col-12">
                    <div class="card card-body border-0 shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md" style="width: 45px; height: 45px;">
                                <i class="fas fa-coins text-white" style="font-size: 1.2rem; line-height: 45px;"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-xs mb-0 text-secondary font-weight-bold">Kas Tunai Saat Ini</p>
                                <h5 class="font-weight-bolder mb-0">
                                    Rp {{ number_format(end($sisaSaldo) ?: 0, 0, ',', '.') }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
</x-app-layout>

<style>
    /* CUSTOM NAVIGATION BUTTONS */
    .btn-nav {
        background-color: white;
        color: #344767;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 8px 15px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.2s ease;
    }
    .btn-nav:hover {
        background-color: #f8f9fa;
        color: #5e72e4;
        transform: translateY(-1px);
    }
    .btn-nav i {
        margin-right: 8px;
        font-size: 0.85rem;
    }

    /* TABLE STYLING */
    .bg-light-primary { background-color: #f0f5ff !important; }
    .bg-light-danger { background-color: #fff8f8 !important; }
    .bg-gray-100 { background-color: #f8f9fa !important; }
    .bg-gray-50 { background-color: #fcfcfc !important; }
    .bg-warning-light { background-color: #fffdf5 !important; }
    .italic { font-style: italic; }
    .text-xxs { font-size: 0.65rem !important; }
    .tracking-tight { letter-spacing: -0.025em; }
    .table thead th { border-bottom-width: 1px !important; }
</style>