<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Neraca (Tabel) – Tahun {{ $tahun }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="bg-white shadow rounded p-4 overflow-x-auto">

            <table class="min-w-full border border-gray-300 text-sm">
                <thead class="bg-gray-100 sticky top-0 z-10">
                    <tr>
                        <th class="border px-3 py-2 text-left">AKUN</th>
                        @foreach ($bulanList as $b)
                            @php
                                [$y, $m] = explode('-', $b);
                                $bulanNama = [
                                    1=>'Jan','Feb','Mar','Apr','Mei','Jun',
                                    'Jul','Agu','Sep','Okt','Nov','Des'
                                ];
                            @endphp
                            <th class="border px-3 py-2 text-right whitespace-nowrap">
                                {{ $bulanNama[(int)$m] }} {{ $y }}
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    {{-- ================= AKTIVA ================= --}}
                    <tr class="bg-gray-200 font-semibold">
                        <td class="border px-3 py-2" colspan="{{ count($bulanList)+1 }}">
                            AKTIVA
                        </td>
                    </tr>

                    @foreach ($akunAktiva as $akun)
                        <tr>
                            <td class="border px-3 py-2">{{ strtoupper($akun) }}</td>
                            @foreach ($bulanList as $b)
                                <td class="border px-3 py-2 text-right">
                                    Rp {{ number_format($saldo[$akun][$b] ?? 0, 0, ',', '.') }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach

                    {{-- TOTAL ASET --}}
                    <tr class="bg-gray-100 font-bold">
                        <td class="border px-3 py-2">TOTAL ASET</td>
                        @foreach ($bulanList as $b)
                            <td class="border px-3 py-2 text-right">
                                Rp {{
                                    number_format(
                                        collect($akunAktiva)->sum(fn($a) => $saldo[$a][$b] ?? 0),
                                        0, ',', '.'
                                    )
                                }}
                            </td>
                        @endforeach
                    </tr>

                    {{-- ================= PASIVA ================= --}}
                    <tr class="bg-gray-200 font-semibold">
                        <td class="border px-3 py-2" colspan="{{ count($bulanList)+1 }}">
                            PASIVA
                        </td>
                    </tr>

                    @foreach ($akunPasiva as $akun)
                        <tr>
                            <td class="border px-3 py-2">{{ strtoupper($akun) }}</td>
                            @foreach ($bulanList as $b)
                                <td class="border px-3 py-2 text-right">
                                    Rp {{ number_format($saldo[$akun][$b] ?? 0, 0, ',', '.') }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach

                    {{-- LABA RUGI --}}
                    <tr class="font-semibold">
                        <td class="border px-3 py-2">LABA RUGI TAHUN BERJALAN</td>
                        @foreach ($bulanList as $b)
                            <td class="border px-3 py-2 text-right">
                                Rp {{ number_format($labaRugi[$b] ?? 0, 0, ',', '.') }}
                            </td>
                        @endforeach
                    </tr>

                    {{-- TOTAL PASIVA --}}
                    <tr class="bg-gray-100 font-bold">
                        <td class="border px-3 py-2">TOTAL PASIVA</td>
                        @foreach ($bulanList as $b)
                            <td class="border px-3 py-2 text-right">
                                Rp {{
                                    number_format(
                                        collect($akunPasiva)->sum(fn($a) => $saldo[$a][$b] ?? 0)
                                        + ($labaRugi[$b] ?? 0),
                                        0, ',', '.'
                                    )
                                }}
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</x-app-layout>
