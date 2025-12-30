<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">

            <!-- HEADER -->
            <div class="row align-items-center mb-3">
                <div class="col-md-6 col-12">
                    <h5 class="fw-bold mb-0 text-uppercase text-primary tracking-tight">Laporan Neraca Keuangan</h5>
                    <p class="text-xs text-secondary mb-0">Hasanah Farm • Periode Aktif s/d {{ now()->translatedFormat('F Y') }}</p>
                </div>
                <div class="col-md-6 col-12 d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                    <a href="{{ route('neraca.laba-rugi') }}" class="btn btn-sm btn-primary shadow-sm mb-0 px-4">
                        <i class="fas fa-chart-line me-2"></i> Laporan Laba Rugi
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
                                {{-- AKTIVA --}}
                                <tr class="bg-light-primary"><td colspan="{{ 2 + count($bulanList) }}" class="ps-4 py-2"><span class="text-xs fw-bold text-primary text-uppercase">Aktiva (Aset)</span></td></tr>
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
                                            @php $ta = 0; foreach($akunAktiva as $a) { $ta += $saldo[$a][$bulan] ?? 0; } @endphp
                                            {{ number_format($ta, 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>

                                {{-- PASIVA --}}
                                <tr class="bg-light-danger"><td colspan="{{ 2 + count($bulanList) }}" class="ps-4 py-2"><span class="text-xs fw-bold text-danger text-uppercase">Pasiva</span></td></tr>
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

                                {{-- LABA RUGI TAHUN BERJALAN (AKUMULASI) --}}
                                <tr>
                                    <td class="ps-5 text-sm py-2 font-weight-bold">Laba Rugi Tahun Berjalan</td>
                                    <td class="text-center text-muted text-xs">-</td>
                                    @foreach ($bulanList as $bulan)
                                        @php $lr_kum = $labaRugiKumulatif[$bulan] ?? 0; @endphp
                                        <td class="text-center fw-bold text-sm {{ $lr_kum < 0 ? 'text-danger' : 'text-success' }}">
                                            {{ $lr_kum < 0 ? '-' : '' }} {{ number_format(abs($lr_kum), 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>

                                <tr class="bg-warning-light fw-bold border-top">
                                    <td class="ps-4 text-sm py-2 text-uppercase">TOTAL PASIVA</td>
                                    <td class="text-center text-sm">-</td>
                                    @foreach ($bulanList as $bulan)
                                        <td class="text-center text-sm font-weight-bolder">
                                            @php
                                                $tp_hut = 0; foreach($akunPasiva as $p) { $tp_hut += $saldo[$p][$bulan] ?? 0; }
                                                $tp_total = $tp_hut + 200000000 + ($labaRugiKumulatif[$bulan] ?? 0);
                                            @endphp
                                            {{ number_format($tp_total, 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>

                                <tr class="bg-white border-top">
                                    <td class="ps-4 text-xxs font-weight-bold py-3 text-secondary">STATUS BALANCE</td>
                                    <td></td>
                                    @foreach ($bulanList as $bulan)
                                        @php
                                            $ta_bal = 0; foreach($akunAktiva as $a) { $ta_bal += $saldo[$a][$bulan] ?? 0; }
                                            $tp_bal = 0; foreach($akunPasiva as $p) { $tp_bal += $saldo[$p][$bulan] ?? 0; }
                                            $tp_bal += 200000000 + ($labaRugiKumulatif[$bulan] ?? 0);
                                            $diff = abs($ta_bal - $tp_bal);
                                        @endphp
                                        <td class="text-center">
                                            @if($diff < 500)
                                                <span class="badge badge-sm bg-gradient-success text-xxs">BALANCE</span>
                                            @else
                                                <span class="badge badge-sm bg-gradient-danger text-xxs">SELISIH Rp {{ number_format($diff, 0, ',', '.') }}</span>
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
    .bg-light-primary { background-color: #f0f5ff !important; }
    .bg-light-danger { background-color: #fff8f8 !important; }
    .bg-gray-100 { background-color: #f8f9fa !important; }
    .bg-gray-50 { background-color: #fcfcfc !important; }
    .bg-warning-light { background-color: #fffdf5 !important; }
    .italic { font-style: italic; }
    .text-xxs { font-size: 0.65rem !important; }
</style>