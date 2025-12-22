<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Neraca Keuangan
        </h2>
    </x-slot>

    <div class="py-6 px-4">

        {{-- INFO RINGKAS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 rounded shadow">
                <div class="text-gray-500 text-sm">Total Pemasukan</div>
                <div class="text-xl font-bold text-green-600">
                    Rp {{ number_format($pemasukan, 0, ',', '.') }}
                </div>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <div class="text-gray-500 text-sm">Total Pengeluaran</div>
                <div class="text-xl font-bold text-red-600">
                    Rp {{ number_format($pengeluaran, 0, ',', '.') }}
                </div>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <div class="text-gray-500 text-sm">Saldo Akhir</div>
                <div class="text-xl font-bold">
                    Rp {{ number_format($saldoAkhir, 0, ',', '.') }}
                </div>
            </div>
        </div>

        {{-- NERACA TABEL --}}
        <div class="bg-white rounded shadow p-4">
            <div class="overflow-x-auto">
                <table class="table-auto w-full border text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-2 py-1 text-left">AKUN</th>
                            @foreach ($bulanList as $b)
                                <th class="border px-2 py-1 text-right">
                                    {{ \Carbon\Carbon::createFromFormat('Y-m', $b)->format('M Y') }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        {{-- AKTIVA --}}
                        <tr class="bg-gray-200 font-bold">
                            <td colspan="{{ count($bulanList)+1 }}" class="border px-2 py-1">
                                AKTIVA
                            </td>
                        </tr>

                        @foreach ($akunAktiva as $akun)
                            <tr>
                                <td class="border px-2 py-1">{{ $akun }}</td>
                                @foreach ($bulanList as $b)
                                    <td class="border px-2 py-1 text-right">
                                        {{ number_format($saldo[$akun][$b] ?? 0, 0, ',', '.') }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach

                        {{-- TOTAL AKTIVA --}}
                        <tr class="font-bold bg-gray-50">
                            <td class="border px-2 py-1">TOTAL AKTIVA</td>
                            @foreach ($bulanList as $b)
                                <td class="border px-2 py-1 text-right">
                                    {{
                                        number_format(
                                            collect($akunAktiva)->sum(fn($a) => $saldo[$a][$b] ?? 0),
                                            0, ',', '.'
                                        )
                                    }}
                                </td>
                            @endforeach
                        </tr>

                        {{-- PASIVA --}}
                        <tr class="bg-gray-200 font-bold">
                            <td colspan="{{ count($bulanList)+1 }}" class="border px-2 py-1">
                                PASIVA
                            </td>
                        </tr>

                        @foreach ($akunPasiva as $akun)
                            <tr>
                                <td class="border px-2 py-1">{{ $akun }}</td>
                                @foreach ($bulanList as $b)
                                    <td class="border px-2 py-1 text-right">
                                        {{ number_format($saldo[$akun][$b] ?? 0, 0, ',', '.') }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach

                        {{-- LABA RUGI --}}
                        <tr>
                            <td class="border px-2 py-1">Laba / Rugi</td>
                            @foreach ($bulanList as $b)
                                <td class="border px-2 py-1 text-right">
                                    {{ number_format($labaRugi[$b] ?? 0, 0, ',', '.') }}
                                </td>
                            @endforeach
                        </tr>

                        {{-- TOTAL PASIVA --}}
                        <tr class="font-bold bg-gray-50">
                            <td class="border px-2 py-1">TOTAL PASIVA</td>
                            @foreach ($bulanList as $b)
                                <td class="border px-2 py-1 text-right">
                                    {{
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
    </div>
    </main>
</x-app-layout>
