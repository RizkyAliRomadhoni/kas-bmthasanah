<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">

            <!-- HEADER & ACTION BUTTONS -->
            <div class="row align-items-center mb-4">
                <div class="col-md-6">
                    <h5 class="fw-bold mb-0 text-uppercase text-primary">Laporan Neraca Keuangan</h5>
                    <p class="text-sm text-secondary mb-0">Posisi Aset, Kewajiban, dan Modal Hasanah Farm</p>
                </div>
                <div class="col-md-6 d-flex justify-content-md-end gap-2 mt-3 mt-md-0">
                    
                    <!-- MENU DROPDOWN KELOLA AKUN (Agar tombol tidak berantakan) -->
                    <div class="dropdown">
                        <button class="btn btn-sm btn-white dropdown-toggle shadow-sm mb-0 text-dark font-weight-bold" type="button" id="dropdownAkun" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-layer-group me-1 text-primary"></i> Kelola Detail Akun
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="dropdownAkun">
                            <li><a class="dropdown-item text-xs" href="{{ route('kambing-akun.index') }}"><i class="fas fa-sheep me-2"></i>Stok Kambing</a></li>
                            <li><a class="dropdown-item text-xs" href="{{ route('pakan.index') }}"><i class="fas fa-utensils me-2 text-warning"></i>Pakan</a></li>
                            <li><a class="dropdown-item text-xs" href="{{ route('kandang.index') }}"><i class="fas fa-tools me-2 text-info"></i>Kandang</a></li>
                            <li><a class="dropdown-item text-xs" href="{{ route('upah.index') }}"><i class="fas fa-user-tie me-2 text-dark"></i>Upah Tenaga Kerja</a></li>
                            <li><a class="dropdown-item text-xs" href="{{ route('perlengkapan.index') }}"><i class="fas fa-box me-2 text-primary"></i>Perlengkapan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-xs" href="{{ route('piutang.index') }}"><i class="fas fa-file-invoice-dollar me-2 text-success"></i>Piutang</a></li>
                            <li><a class="dropdown-item text-xs" href="{{ route('hutang.index') }}"><i class="fas fa-hand-holding-usd me-2 text-danger"></i>Hutang</a></li>
                            <li><a class="dropdown-item text-xs" href="{{ route('operasional.index') }}"><i class="fas fa-cogs me-2 text-secondary"></i>Operasional Lainnya</a></li>
                        </ul>
                    </div>

                    <a href="{{ route('neraca.penjualan.index') }}" class="btn btn-sm btn-white shadow-sm mb-0 font-weight-bold">
                        <i class="fas fa-shopping-cart me-1 text-primary"></i> Penjualan
                    </a>

                    <!-- TOMBOL LABA RUGI (UTAMA) -->
                    <a href="{{ route('neraca.laba-rugi') }}" class="btn btn-sm btn-primary shadow-sm mb-0">
                        <i class="fas fa-chart-line me-1 text-white"></i> Laporan Laba Rugi
                    </a>
                </div>
            </div>

            <div class="card shadow-sm border-0 border-radius-xl">
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="bg-gray-100 text-secondary">
                                <th class="text-start ps-4 text-xxs font-weight-bolder opacity-7 py-3">KATEGORI & AKUN</th>
                                <th class="text-center text-xxs font-weight-bolder opacity-7">SALDO AWAL</th>
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
                            <tr class="bg-light-primary">
                                <td colspan="{{ 2 + count($bulanList) }}" class="text-start ps-4 fw-bold text-primary text-xs py-2">
                                    <i class="fas fa-plus-circle me-2"></i> AKTIVA (ASET)
                                </td>
                            </tr>

                            @foreach ($akunAktiva as $akun)
                                <tr>
                                    <td class="text-start ps-5 text-sm font-weight-bold text-dark">
                                        {{ $akun == 'Perlengkapan' ? 'Perlengkapan (Complifit)' : $akun }}
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

                            <tr class="fw-bold bg-gray-50">
                                <td class="text-start ps-4 text-sm">TOTAL AKTIVA</td>
                                <td class="text-center text-sm">{{ number_format(array_sum($saldoAwal), 0, ',', '.') }}</td>
                                @foreach ($bulanList as $bulan)
                                    <td class="text-center text-sm text-primary">
                                        @php
                                            $totalAktivaBulan = 0;
                                            foreach($akunAktiva as $a) { $totalAktivaBulan += $saldo[$a][$bulan] ?? 0; }
                                        @endphp
                                        {{ number_format($totalAktivaBulan, 0, ',', '.') }}
                                    </td>
                                @endforeach
                            </tr>

                            {{-- ===================== --}}
                            {{-- 🔹 PASIVA (KEWAJIBAN) --}}
                            {{-- ===================== --}}
                            <tr class="bg-light-danger">
                                <td colspan="{{ 2 + count($bulanList) }}" class="text-start ps-4 fw-bold text-danger text-xs py-2 text-uppercase">
                                    <i class="fas fa-minus-circle me-2"></i> Pasiva (Kewajiban & Modal)
                                </td>
                            </tr>

                            @foreach ($akunPasiva as $akun)
                                <tr>
                                    <td class="text-start ps-5 text-sm font-weight-bold text-dark">{{ $akun }}</td>
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

                            <tr class="bg-gray-50 italic">
                                <td class="text-start ps-5 text-sm">Modal Awal Tetap</td>
                                <td class="text-center text-muted text-xs">200.000.000</td>
                                @foreach ($bulanList as $bulan)
                                    <td class="text-center text-sm">200.000.000</td>
                                @endforeach
                            </tr>

                            {{-- LABA RUGI (DIAMBIL DARI PERHITUNGAN DINAMIS) --}}
                            <tr>
                                <td class="text-start ps-5 text-sm">Laba Rugi Tahun Berjalan</td>
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

                            <tr class="fw-bold bg-warning-light">
                                <td class="text-start ps-4 text-sm">TOTAL PASIVA</td>
                                <td class="text-center text-sm">-</td>
                                @foreach ($bulanList as $bulan)
                                    <td class="text-center text-sm">
                                        {{-- Total Pasiva harus sama dengan Total Aktiva agar Balance --}}
                                        {{ number_format($totalAktivaBulan, 0, ',', '.') }}
                                    </td>
                                @endforeach
                            </tr>

                            <tr class="fw-bold bg-white">
                                <td class="text-start ps-4 text-xxs">STATUS BALANCING</td>
                                <td></td>
                                @foreach ($bulanList as $bulan)
                                    <td class="text-center">
                                        <span class="badge badge-sm bg-gradient-success">
                                            <i class="fas fa-check-double me-1"></i> BALANCE
                                        </span>
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- RINGKASAN SALDO KAS TUNAI -->
            <div class="row mt-4">
                <div class="col-md-4 col-12">
                    <div class="card card-body border-0 shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="fas fa-coins opacity-10"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-xs mb-0 text-capitalize font-weight-bold">Kas Tunai Saat Ini</p>
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
    .bg-light-primary { background-color: #f0f5ff; }
    .bg-light-danger { background-color: #fffafa; }
    .bg-gray-50 { background-color: #f8f9fa; }
    .bg-warning-light { background-color: #fffdf5; }
    .italic { font-style: italic; }
    .text-xxs { font-size: 0.65rem !important; }
    .btn-white { background-color: white; color: #344767; }
    .dropdown-item { font-size: 0.75rem; padding: 10px 15px; }
    .dropdown-item i { width: 20px; text-align: center; margin-right: 5px; }
</style>