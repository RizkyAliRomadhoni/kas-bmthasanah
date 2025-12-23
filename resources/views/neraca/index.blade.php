<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">NERACA (Logika Kumulatif)</h5>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-bordered table-sm text-center align-middle">

                        {{-- HEADER --}}
                        <thead class="table-light">
                            <tr>
                                <th rowspan="2" class="text-start">AKUN</th>
                                <th rowspan="2">Saldo Awal</th>
                                <th colspan="{{ count($bulanList) }}">Saldo Akhir per Bulan</th>
                            </tr>
                            <tr>
                                @foreach ($bulanList as $bulan)
                                    <th>
                                        {{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('M Y') }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>

                        {{-- BODY --}}
                        <tbody>

                            {{-- ===================== --}}
                            {{-- 🔹 AKTIVA --}}
                            {{-- ===================== --}}
                            <tr class="table-secondary fw-bold">
                                <td colspan="{{ 2 + count($bulanList) }}" class="text-start">AKTIVA</td>
                            </tr>

                            @foreach ($akunAktiva as $akun)
                                <tr>
                                    <td class="text-start">{{ $akun }}</td>
                                    <td>{{ number_format($saldoAwal[$akun], 0, ',', '.') }}</td>

                                    @foreach ($bulanList as $bulan)
                                        <td>
                                            {{ number_format($saldo[$akun][$bulan] ?? 0, 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach

                            {{-- ===================== --}}
                            {{-- 🔹 PASIVA --}}
                            {{-- ===================== --}}
                            <tr class="table-secondary fw-bold">
                                <td colspan="{{ 2 + count($bulanList) }}" class="text-start">PASIVA</td>
                            </tr>

                            @foreach ($akunPasiva as $akun)
                                <tr>
                                    <td class="text-start">{{ $akun }}</td>
                                    <td>{{ number_format($saldoAwal[$akun], 0, ',', '.') }}</td>

                                    @foreach ($bulanList as $bulan)
                                        <td>
                                            {{ number_format($saldo[$akun][$bulan] ?? 0, 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>
</x-app-layout>
