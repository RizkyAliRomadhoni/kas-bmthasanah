<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">

            <!-- HEADER UTAMA -->
            <div class="row align-items-center mb-3">
                <div class="col-md-6 col-12">
                    <h5 class="fw-bold mb-0 text-uppercase text-primary tracking-tight">Laporan Neraca Keuangan</h5>
                    <p class="text-xs text-secondary mb-0">Hasanah Farm • Rekapitulasi Posisi Keuangan</p>
                </div>
                <div class="col-md-6 col-12 d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                    <a href="{{ route('neraca.laba-rugi') }}" class="btn btn-sm btn-primary shadow-sm mb-0 px-4">
                        <i class="fas fa-chart-line me-2"></i> Laporan Laba Rugi
                    </a>
                </div>
            </div>

            <!-- NAVIGASI TOMBOL KELOLA AKUN -->
            <div class="card shadow-none border-0 bg-transparent mb-4">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('kambing-akun.index') }}" class="btn-nav"><i class="fas fa-sheep text-dark"></i> Stok Kambing</a>
                    <a href="{{ route('pakan.index') }}" class="btn-nav"><i class="fas fa-utensils text-warning"></i> Pakan</a>
                    <a href="{{ route('kandang.index') }}" class="btn-nav"><i class="fas fa-tools text-info"></i> Kandang</a>
                    <a href="{{ route('perlengkapan.index') }}" class="btn-nav"><i class="fas fa-box text-primary"></i> Perlengkapan</a>
                    <a href="{{ route('upah.index') }}" class="btn-nav"><i class="fas fa-user-tie text-secondary"></i> Upah</a>
                    <a href="{{ route('operasional.index') }}" class="btn-nav"><i class="fas fa-cogs text-secondary"></i> Operasional</a>
                    <div class="vr mx-1 d-none d-md-block" style="height: 30px; align-self: center; opacity: 0.2;"></div>
                    <a href="{{ route('neraca.penjualan.index') }}" class="btn-nav"><i class="fas fa-shopping-cart text-primary"></i> Penjualan</a>
                    <a href="{{ route('neraca.rincian-kambing.index') }}" class="btn-nav"><i class="fas fa-horse-head text-success"></i> Rincian (HPP & Mati)</a>
                    <a href="{{ route('piutang.index') }}" class="btn-nav"><i class="fas fa-file-invoice-dollar text-warning"></i> Piutang</a>
                    <a href="{{ route('hutang.index') }}" class="btn-nav"><i class="fas fa-hand-holding-usd text-danger"></i> Hutang</a>
                </div>
            </div>

            <!-- TABEL NERACA -->
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
                                <tr class="bg-light-primary">
                                    <td colspan="{{ 2 + count($bulanList) }}" class="ps-4 py-2">
                                        <span class="text-xs fw-bold text-primary text-uppercase">Aktiva (Aset)</span>
                                    </td>
                                </tr>
                                @foreach ($akunAktiva as $akun)
                                    <tr>
                                        <td class="ps-5 py-2 text-sm font-weight-bold text-dark">{{ $akun }}</td>
                                        <td class="text-center text-muted text-xs">0</td>
                                        @foreach ($bulanList as $bulan)
                                            <td class="text-center text-sm font-weight-bold">{{ number_format($saldo[$akun][$bulan] ?? 0, 0, ',', '.') }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach

                                <tr class="bg-gray-50 fw-bold border-top">
                                    <td class="ps-4 text-sm py-2">TOTAL AKTIVA</td>
                                    <td class="text-center text-sm">0</td>
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
                                <tr class="bg-light-danger">
                                    <td colspan="{{ 2 + count($bulanList) }}" class="ps-4 py-2">
                                        <span class="text-xs fw-bold text-danger text-uppercase">Pasiva</span>
                                    </td>
                                </tr>
                                @foreach ($akunPasiva as $akun)
                                    <tr>
                                        <td class="ps-5 py-2 text-sm font-weight-bold text-dark">{{ $akun }}</td>
                                        <td class="text-center text-muted text-xs">0</td>
                                        @foreach ($bulanList as $bulan)
                                            <td class="text-center text-sm">{{ number_format($saldo[$akun][$bulan] ?? 0, 0, ',', '.') }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach

                                <tr class="bg-gray-50 italic">
                                    <td class="ps-5 text-sm py-2">Modal Awal Tetap</td>
                                    <td class="text-center text-muted text-xs">200.000.000</td>
                                    @foreach ($bulanList as $bulan)
                                        <td class="text-center text-sm">200.000.000</td>
                                    @endforeach
                                </tr>

                                {{-- BARIS LABA RUGI AKUMULASI --}}
                                <tr>
                                    <td class="ps-5 text-sm py-2 font-weight-bold">Laba Rugi Tahun Berjalan</td>
                                    <td class="text-center text-muted text-xs">-</td>
                                    @foreach ($bulanList as $bulan)
                                        @php $lr_kumulatif = $labaRugiKumulatif[$bulan] ?? 0; @endphp
                                        <td class="text-center fw-bold text-sm {{ $lr_kumulatif < 0 ? 'text-danger' : 'text-success' }}">
                                            {{ $lr_kumulatif < 0 ? '-' : '' }} {{ number_format(abs($lr_kumulatif), 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>

                                <tr class="bg-warning-light fw-bold border-top">
                                    <td class="ps-4 text-sm py-2 text-uppercase">TOTAL PASIVA</td>
                                    <td class="text-center text-sm">-</td>
                                    @foreach ($bulanList as $bulan)
                                        <td class="text-center text-sm font-weight-bolder">
                                            @php
                                                $totalPasivaHutang = 0;
                                                foreach($akunPasiva as $p) { $totalPasivaHutang += $saldo[$p][$bulan] ?? 0; }
                                                $totalPasivaBulan = $totalPasivaHutang + 200000000 + ($labaRugiKumulatif[$bulan] ?? 0);
                                            @endphp
                                            {{ number_format($totalPasivaBulan, 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>

                                <tr class="bg-white border-top">
                                    <td class="ps-4 text-xxs font-weight-bold py-3 text-secondary">STATUS BALANCE</td>
                                    <td></td>
                                    @foreach ($bulanList as $bulan)
                                        @php
                                            $ta = 0; foreach($akunAktiva as $a) { $ta += $saldo[$a][$bulan] ?? 0; }
                                            $tp = 0; foreach($akunPasiva as $p) { $tp += $saldo[$p][$bulan] ?? 0; }
                                            $tp += 200000000 + ($labaRugiKumulatif[$bulan] ?? 0);
                                            $isBalance = abs($ta - $tp) < 100;
                                        @endphp
                                        <td class="text-center">
                                            @if($isBalance)
                                                <span class="badge badge-sm bg-gradient-success text-xxs">BALANCE</span>
                                            @else
                                                <span class="badge badge-sm bg-gradient-danger text-xxs">SELISIH Rp {{ number_format(abs($ta - $tp), 0, ',', '.') }}</span>
                                            @endif
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
    .btn-nav {
        background-color: white; color: #344767; font-size: 0.7rem; font-weight: 700;
        padding: 7px 12px; border-radius: 6px; border: 1px solid #e9ecef;
        text-decoration: none !important; display: inline-flex; align-items: center;
        transition: all 0.2s ease; text-transform: uppercase;
    }
    .btn-nav:hover { background-color: #f8f9fa; border-color: #5e72e4; color: #5e72e4 !important; }
    .bg-light-primary { background-color: #f0f5ff !important; }
    .bg-light-danger { background-color: #fff8f8 !important; }
    .bg-gray-100 { background-color: #f8f9fa !important; }
    .bg-gray-50 { background-color: #fcfcfc !important; }
    .bg-warning-light { background-color: #fffdf5 !important; }
    .italic { font-style: italic; }
    .text-xxs { font-size: 0.65rem !important; }
</style>