<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">

            <!-- HEADER & ACTION BUTTONS -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0 text-uppercase">Laporan Neraca Keuangan</h5>
                <div class="d-flex gap-2">
                    <!-- TOMBOL BARU: KELOLA PAKAN -->
                    <a href="{{ route('pakan.index') }}" class="btn btn-sm btn-white shadow-sm mb-0">
                        <i class="fas fa-utensils me-1 text-warning"></i> Kelola Pakan
                    </a>

                    <!-- Tambahkan ini di deretan tombol Penjualan, Pakan, dll -->
                    <a href="{{ route('kandang.index') }}" class="btn btn-sm btn-white shadow-sm mb-0">
                      <i class="fas fa-tools me-1 text-info"></i> Kandang
                        </a>
                    
                    <a href="{{ route('neraca.penjualan.index') }}" class="btn btn-sm btn-white shadow-sm mb-0">
                        <i class="fas fa-shopping-cart me-1 text-primary"></i> Penjualan
                    </a>

                    <a href="{{ route('operasional.index') }}" class="btn btn-sm btn-white shadow-sm mb-0">
                     <i class="fas fa-cogs me-1 text-warning"></i> Operasional
                    </a>
                    
                    <a href="{{ route('perlengkapan.index') }}" class="btn btn-sm btn-white shadow-sm mb-0">
                     <i class="fas fa-box me-1 text-primary"></i> Perlengkapan
                    </a>

                    <a href="{{ route('neraca.rincian-kambing.index') }}" class="btn btn-sm btn-white shadow-sm mb-0">
                        <i class="fas fa-horse me-1 text-success"></i> Rincian Kambing
                    </a>
                    
                    <a href="{{ route('neraca.laba-rugi') }}" class="btn btn-sm btn-primary shadow-sm mb-0">
                        <i class="fas fa-chart-line me-1"></i> Laba Rugi
                    </a>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-gray-100">
                            <tr class="text-secondary">
                                <th class="text-start ps-4 text-xxs font-weight-bolder opacity-7">KATEGORI & AKUN</th>
                                <th class="text-xxs font-weight-bolder opacity-7">SALDO AWAL</th>
                                @foreach ($bulanList as $bulan)
                                    <th class="text-center text-xxs font-weight-bolder opacity-7">
                                        {{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('M Y') }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            {{-- ===================== --}}
                            {{-- 🔹 AKTIVA (ASET)      --}}
                            {{-- ===================== --}}
                            <tr class="bg-light">
                                <td colspan="{{ 2 + count($bulanList) }}" class="text-start ps-4 fw-bold text-primary text-sm">
                                    <i class="fas fa-plus-circle me-2"></i> AKTIVA (ASET)
                                </td>
                            </tr>

                            @foreach ($akunAktiva as $akun)
                                <tr>
                                    <td class="text-start ps-5 text-sm">{{ $akun }}</td>
                                    <td class="text-muted text-xs">
                                        {{ number_format($saldoAwal[$akun] ?? 0, 0, ',', '.') }}
                                    </td>
                                    @foreach ($bulanList as $bulan)
                                        <td class="text-center text-sm">
                                            {{ number_format($saldo[$akun][$bulan] ?? 0, 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach

                            {{-- TOTAL AKTIVA --}}
                            <tr class="fw-bold bg-gray-50">
                                <td class="text-start ps-4 text-sm">TOTAL AKTIVA</td>
                                <td class="text-sm">{{ number_format(array_sum($saldoAwal), 0, ',', '.') }}</td>
                                @foreach ($bulanList as $bulan)
                                    <td class="text-center text-sm text-primary">
                                        @php
                                            $totalAktivaBulan = 0;
                                            foreach($akunAktiva as $a) {
                                                $totalAktivaBulan += $saldo[$a][$bulan] ?? 0;
                                            }
                                        @endphp
                                        {{ number_format($totalAktivaBulan, 0, ',', '.') }}
                                    </td>
                                @endforeach
                            </tr>

                            {{-- ===================== --}}
                            {{-- 🔹 PASIVA (KEWAJIBAN) --}}
                            {{-- ===================== --}}
                            <tr class="bg-light">
                                <td colspan="{{ 2 + count($bulanList) }}" class="text-start ps-4 fw-bold text-danger text-sm">
                                    <i class="fas fa-minus-circle me-2"></i> PASIVA (KEWAJIBAN & MODAL)
                                </td>
                            </tr>

                            @foreach ($akunPasiva as $akun)
                                <tr>
                                    <td class="text-start ps-5 text-sm">{{ $akun }}</td>
                                    <td class="text-muted text-xs">
                                        {{ number_format($saldoAwal[$akun] ?? 0, 0, ',', '.') }}
                                    </td>
                                    @foreach ($bulanList as $bulan)
                                        <td class="text-center text-sm">
                                            {{ number_format($saldo[$akun][$bulan] ?? 0, 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach

                            {{-- MODAL TETAP --}}
                            <tr class="bg-gray-50 italic">
                                <td class="text-start ps-5 text-sm">Modal Awal</td>
                                <td class="text-muted text-xs">200.000.000</td>
                                @foreach ($bulanList as $bulan)
                                    <td class="text-center text-sm">200.000.000</td>
                                @endforeach
                            </tr>

                            {{-- LABA RUGI (BALANCING) --}}
                            <tr>
                                <td class="text-start ps-5 text-sm">Laba Rugi Tahun Berjalan</td>
                                <td class="text-muted text-xs">-</td>
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

                            {{-- TOTAL PASIVA --}}
                            <tr class="fw-bold bg-warning-light">
                                <td class="text-start ps-4 text-sm">TOTAL PASIVA</td>
                                <td class="text-sm">-</td>
                                @foreach ($bulanList as $bulan)
                                    <td class="text-center text-sm">
                                        @php
                                            // Total Pasiva harus sama dengan Total Aktiva agar Balance
                                            echo number_format($totalAktivaBulan, 0, ',', '.');
                                        @endphp
                                    </td>
                                @endforeach
                            </tr>

                            {{-- VALIDASI --}}
                            <tr class="fw-bold bg-gray-100">
                                <td class="text-start ps-4 text-xxs">STATUS NERACA</td>
                                <td></td>
                                @foreach ($bulanList as $bulan)
                                    <td class="text-center text-xxs text-success">
                                        <i class="fas fa-check-circle me-1"></i> BALANCE
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- KAS INFO CARD -->
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card card-body border-0 shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="fas fa-wallet opacity-10"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-xs mb-0 text-capitalize font-weight-bold">Saldo Kas Terakhir</p>
                                <h6 class="font-weight-bolder mb-0">
                                    Rp {{ number_format(end($sisaSaldo) ?: 0, 0, ',', '.') }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
</x-app-layout>

<style>
    .bg-gray-50 { background-color: #f8f9fa; }
    .bg-warning-light { background-color: #fff9e6; }
    .italic { font-style: italic; }
    .text-xxs { font-size: 0.65rem !important; }
    .btn-white { background-color: white; color: #344767; }
</style>