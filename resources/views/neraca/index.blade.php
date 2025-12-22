<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg bg-light">
        <div class="container-fluid py-4">

            {{-- HEADER --}}
            <div class="card shadow border-0 mb-4 rounded-4">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">📊 NERACA KEUANGAN ({{ $tahun }})</h5>
                </div>
            </div>

            {{-- TABEL NERACA --}}
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body table-responsive" style="overflow-x:auto;">
                    <table class="table table-bordered table-hover align-middle text-nowrap">
                        <thead class="table-dark sticky-top">
                            <tr>
                                <th style="min-width:220px;">AKUN</th>
                                @foreach ($bulanList as $bulan)
                                    <th class="text-end">
                                        {{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->format('M Y') }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            {{-- ================= AKTIVA ================= --}}
                            <tr class="table-secondary fw-bold">
                                <td colspan="{{ count($bulanList) + 1 }}">AKTIVA</td>
                            </tr>

                            @foreach ($akunAktiva as $akun)
                                <tr>
                                    <td>{{ $akun }}</td>
                                    @foreach ($bulanList as $bulan)
                                        <td class="text-end">
                                            Rp {{ number_format($saldo[$akun][$bulan] ?? 0, 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach

                            <tr class="table-success fw-bold">
                                <td>TOTAL AKTIVA</td>
                                @foreach ($bulanList as $bulan)
                                    <td class="text-end">
                                        Rp {{ number_format($totalAktiva[$bulan] ?? 0, 0, ',', '.') }}
                                    </td>
                                @endforeach
                            </tr>

                            {{-- ================= PASIVA ================= --}}
                            <tr class="table-secondary fw-bold">
                                <td colspan="{{ count($bulanList) + 1 }}">PASIVA</td>
                            </tr>

                            @foreach ($akunPasiva as $akun)
                                <tr>
                                    <td>{{ $akun }}</td>
                                    @foreach ($bulanList as $bulan)
                                        <td class="text-end">
                                            Rp {{ number_format($saldo[$akun][$bulan] ?? 0, 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach

                            <tr class="table-danger fw-bold">
                                <td>TOTAL PASIVA</td>
                                @foreach ($bulanList as $bulan)
                                    <td class="text-end">
                                        Rp {{ number_format($totalPasiva[$bulan] ?? 0, 0, ',', '.') }}
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>
</x-app-layout>
