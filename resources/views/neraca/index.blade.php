<x-app-layout>
    <main class="main-content position-relative h-100 bg-light">
        <div class="container-fluid py-4">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">NERACA ({{ $tahun }})</h5>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-bordered table-sm text-nowrap">
                        <thead class="table-secondary sticky-top">
                            <tr>
                                <th>AKUN</th>
                                @foreach($bulanList as $ym)
                                    <th class="text-end">{{ $ym }}</th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            {{-- ================= AKTIVA ================= --}}
                            <tr class="table-light fw-bold">
                                <td colspan="{{ count($bulanList)+1 }}">AKTIVA</td>
                            </tr>

                            @foreach($akunAktiva as $akun)
                                <tr>
                                    <td>{{ $akun }}</td>
                                    @foreach($bulanList as $ym)
                                        <td class="text-end">
                                            {{ number_format($saldo[$akun][$ym] ?? 0, 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach

                            <tr class="fw-bold table-success">
                                <td>TOTAL AKTIVA</td>
                                @foreach($bulanList as $ym)
                                    <td class="text-end">
                                        {{ number_format(
                                            collect($akunAktiva)->sum(fn($a) => $saldo[$a][$ym] ?? 0),
                                            0, ',', '.'
                                        ) }}
                                    </td>
                                @endforeach
                            </tr>

                            {{-- ================= PASIVA ================= --}}
                            <tr class="table-light fw-bold">
                                <td colspan="{{ count($bulanList)+1 }}">PASIVA</td>
                            </tr>

                            @foreach($akunPasiva as $akun)
                                <tr>
                                    <td>{{ $akun }}</td>
                                    @foreach($bulanList as $ym)
                                        <td class="text-end">
                                            {{ number_format($saldo[$akun][$ym] ?? 0, 0, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach

                            <tr class="fw-bold table-warning">
                                <td>LABA RUGI TAHUN BERJALAN</td>
                                @foreach($bulanList as $ym)
                                    <td class="text-end">
                                        {{ number_format($labaRugi[$ym] ?? 0, 0, ',', '.') }}
                                    </td>
                                @endforeach
                            </tr>

                            <tr class="fw-bold table-danger">
                                <td>TOTAL PASIVA</td>
                                @foreach($bulanList as $ym)
                                    <td class="text-end">
                                        {{ number_format(
                                            collect($akunPasiva)->sum(fn($a) => $saldo[$a][$ym] ?? 0)
                                            + ($labaRugi[$ym] ?? 0),
                                            0, ',', '.'
                                        ) }}
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
