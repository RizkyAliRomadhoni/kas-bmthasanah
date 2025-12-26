<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">NERACA</h5>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-bordered table-sm text-center align-middle">

                        <thead class="table-light">
                            <tr>
                                <th rowspan="2" class="text-start">AKUN</th>
                                <th rowspan="2">Saldo Awal</th>
                                <th colspan="{{ count($bulanList) }}">Saldo Akhir per Bulan</th>
                            </tr>
                            <tr>
                                @foreach ($bulanList as $bulan)
                                    <th>{{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('M Y') }}</th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>

                            {{-- ===================== --}}
                            {{-- 🔥 SISA SALDO (INFO) --}}
                            {{-- ===================== --}}
                            <tr class="table-success fw-bold">
                                <td class="text-start">Sisa Saldo</td>
                                <td>0</td>
                                @foreach ($bulanList as $bulan)
                                    <td>{{ number_format($sisaSaldo[$bulan] ?? 0, 0, ',', '.') }}</td>
                                @endforeach
                            </tr>

                            {{-- ===================== --}}
                            {{-- 🔹 AKTIVA --}}
                            {{-- ===================== --}}
                            <tr class="table-secondary fw-bold">
                                <td colspan="{{ 2 + count($bulanList) }}" class="text-start">AKTIVA</td>
                            </tr>

                            @php
                                $totalAktivaAwal = 0;
                                $totalAktiva = [];
                            @endphp

                            @foreach ($akunAktiva as $akun)
                                <tr>
                                    <td class="text-start">{{ $akun }}</td>
                                    <td>
                                        {{ number_format($saldoAwal[$akun] ?? 0, 0, ',', '.') }}
                                        @php $totalAktivaAwal += $saldoAwal[$akun] ?? 0; @endphp
                                    </td>

                                    @foreach ($bulanList as $bulan)
                                        @php
                                            $nilai = $saldo[$akun][$bulan] ?? 0;
                                            $totalAktiva[$bulan] = ($totalAktiva[$bulan] ?? 0) + $nilai;
                                        @endphp
                                        <td>{{ number_format($nilai, 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>
                            @endforeach

                            {{-- TOTAL ASET --}}
                            <tr class="fw-bold table-light">
                                <td class="text-start">TOTAL ASET</td>
                                <td>{{ number_format($totalAktivaAwal, 0, ',', '.') }}</td>
                                @foreach ($bulanList as $bulan)
                                    <td>{{ number_format($totalAktiva[$bulan] ?? 0, 0, ',', '.') }}</td>
                                @endforeach
                            </tr>

                            {{-- ===================== --}}
                            {{-- 🔹 PASIVA --}}
                            {{-- ===================== --}}
                            <tr class="table-secondary fw-bold">
                                <td colspan="{{ 2 + count($bulanList) }}" class="text-start">PASIVA</td>
                            </tr>

                            @php
                                $totalKewajibanAwal = 0;
                                $totalKewajiban = [];
                            @endphp

                            @foreach ($akunPasiva as $akun)
                                <tr>
                                    <td class="text-start">{{ $akun }}</td>
                                    <td>
                                        {{ number_format($saldoAwal[$akun] ?? 0, 0, ',', '.') }}
                                        @php $totalKewajibanAwal += $saldoAwal[$akun] ?? 0; @endphp
                                    </td>

                                    @foreach ($bulanList as $bulan)
                                        @php
                                            $nilai = $saldo[$akun][$bulan] ?? 0;
                                            $totalKewajiban[$bulan] = ($totalKewajiban[$bulan] ?? 0) + $nilai;
                                        @endphp
                                        <td>{{ number_format($nilai, 0, ',', '.') }}</td>
                                    @endforeach
                                </tr>
                            @endforeach

                            {{-- TOTAL KEWAJIBAN --}}
                            <tr class="fw-bold table-light">
                                <td class="text-start">TOTAL KEWAJIBAN</td>
                                <td>{{ number_format($totalKewajibanAwal, 0, ',', '.') }}</td>
                                @foreach ($bulanList as $bulan)
                                    <td>{{ number_format($totalKewajiban[$bulan] ?? 0, 0, ',', '.') }}</td>
                                @endforeach
                            </tr>

                            {{-- ===================== --}}
                            {{-- 🔹 MODAL --}}
                            {{-- ===================== --}}
                            @php
                                $modalAwal = 200000000;
                                $laba = [];
                                $totalModal = [];
                                $totalPasiva = [];
                            @endphp

                            <tr class="table-secondary fw-bold">
                                <td colspan="{{ 2 + count($bulanList) }}" class="text-start">MODAL</td>
                            </tr>

                            {{-- LABA RUGI --}}
                            <tr>
                                <td class="text-start">Laba Rugi Tahun Berjalan</td>
                                <td>-</td>
                                @foreach ($bulanList as $bulan)
                                    @php
                                        $laba[$bulan] =
                                            ($totalAktiva[$bulan] ?? 0)
                                            - ($totalKewajiban[$bulan] ?? 0)
                                            - $modalAwal;
                                        $totalModal[$bulan] = $laba[$bulan];
                                        $totalPasiva[$bulan] = ($totalKewajiban[$bulan] ?? 0) + $totalModal[$bulan];
                                    @endphp
                                    <td>{{ number_format($laba[$bulan], 0, ',', '.') }}</td>
                                @endforeach
                            </tr>

                            {{-- TOTAL MODAL --}}
                            <tr class="fw-bold table-light">
                                <td class="text-start">TOTAL MODAL</td>
                                <td>-</td>
                                @foreach ($bulanList as $bulan)
                                    <td>{{ number_format($totalModal[$bulan] ?? 0, 0, ',', '.') }}</td>
                                @endforeach
                            </tr>

                            {{-- TOTAL PASIVA --}}
                            <tr class="fw-bold table-warning">
                                <td class="text-start">TOTAL PASIVA</td>
                                <td>{{ number_format($totalAktivaAwal, 0, ',', '.') }}</td>
                                @foreach ($bulanList as $bulan)
                                    <td>{{ number_format($totalPasiva[$bulan] ?? 0, 0, ',', '.') }}</td>
                                @endforeach
                            </tr>

                            {{-- VALIDASI --}}
                            <tr class="fw-bold table-danger">
                                <td class="text-start">AKTIVA - PASIVA</td>
                                <td>0</td>
                                @foreach ($bulanList as $bulan)
                                    <td>{{ number_format(($totalAktiva[$bulan] ?? 0) - ($totalPasiva[$bulan] ?? 0), 0, ',', '.') }}</td>
                                @endforeach
                            </tr>

                            {{-- KAS INFO --}}
                            <tr class="fw-bold table-info">
                                <td class="text-start">Kas (Informasi Saldo)</td>
                                <td>0</td>
                                @foreach ($bulanList as $bulan)
                                    <td>{{ number_format($sisaSaldo[$bulan] ?? 0, 0, ',', '.') }}</td>
                                @endforeach
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>
</x-app-layout>
