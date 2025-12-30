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

            <!-- TABEL NERACA -->
            <div class="card shadow-sm border-0 border-radius-xl overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
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
                                        <td class="text-center text-muted text-xs">{{ number_format($saldoAwal[$akun] ?? 0, 0, ',', '.') }}</td>
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
                                        <span class="text-xs fw-bold text-danger text-uppercase">Pasiva (Kewajiban & Modal)</span>
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

                                {{-- BARIS LABA RUGI - MENGAMBIL DATA DARI CONTROLLER LABA RUGI --}}
                                <tr>
                                    <td class="ps-5 text-sm py-2 font-weight-bold">Laba Rugi Tahun Berjalan</td>
                                    <td class="text-center text-muted text-xs">-</td>
                                    @foreach ($bulanList as $bulan)
                                        @php $lr = $labaRugiPerBulan[$bulan] ?? 0; @endphp
                                        <td class="text-center fw-bold text-sm {{ $lr < 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($lr, 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>

                                <tr class="bg-warning-light fw-bold border-top">
                                    <td class="ps-4 text-sm py-2 text-uppercase">TOTAL PASIVA</td>
                                    <td class="text-center text-sm">-</td>
                                    @foreach ($bulanList as $bulan)
                                        <td class="text-center text-sm font-weight-bolder">
                                            @php
                                                $totalKewajibanModal = 0;
                                                foreach($akunPasiva as $p) { $totalKewajibanModal += $saldo[$p][$bulan] ?? 0; }
                                                // Rumus: Total Hutang/Pasiva + Modal Tetap (200jt) + Laba Rugi Berjalan
                                                $totalPasivaBulan = $totalKewajibanModal + 200000000 + ($labaRugiPerBulan[$bulan] ?? 0);
                                            @endphp
                                            {{ number_format($totalPasivaBulan, 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>

                                <tr class="bg-white">
                                    <td class="ps-4 text-xxs font-weight-bold py-3 text-secondary">STATUS BALANCE</td>
                                    <td></td>
                                    @foreach ($bulanList as $bulan)
                                        @php
                                            // Hitung Total Aktiva
                                            $totalAktiva = 0; foreach($akunAktiva as $a) { $totalAktiva += $saldo[$a][$bulan] ?? 0; }
                                            // Hitung Total Pasiva
                                            $totalPasiva = 0; foreach($akunPasiva as $p) { $totalPasiva += $saldo[$p][$bulan] ?? 0; }
                                            $totalPasiva += 200000000 + ($labaRugiPerBulan[$bulan] ?? 0);
                                            
                                            $isBalance = abs($totalAktiva - $totalPasiva) < 100; // Toleransi selisih pembulatan
                                        @endphp
                                        <td class="text-center">
                                            @if($isBalance)
                                                <span class="badge badge-sm bg-gradient-success text-xxs">BALANCE</span>
                                            @else
                                                <span class="badge badge-sm bg-gradient-danger text-xxs">SELISIH Rp {{ number_format(abs($totalAktiva - $totalPasiva), 0, ',', '.') }}</span>
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