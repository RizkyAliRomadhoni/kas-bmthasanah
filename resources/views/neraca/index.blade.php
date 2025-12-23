<x-app-layout>
     <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg bg-gray-50">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Neraca
        </h2>
    </x-slot>

    <div class="py-6 px-4 overflow-x-auto">
        <table class="min-w-full border border-gray-300 text-sm">
            <thead class="bg-gray-100 sticky top-0 z-10">
                <tr>
                    <th class="border px-3 py-2 text-left">AKUN</th>
                    @foreach($bulanList as $bulan)
                        <th class="border px-3 py-2 text-right">
                            {{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('M Y') }}
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                {{-- ================== AKTIVA ================== --}}
                <tr class="bg-blue-50 font-bold">
                    <td colspan="{{ count($bulanList) + 1 }}" class="border px-3 py-2">
                        AKTIVA
                    </td>
                </tr>

                @foreach($akunAktiva as $akun)
                    <tr>
                        <td class="border px-3 py-2">{{ $akun }}</td>
                        @foreach($bulanList as $bulan)
                            <td class="border px-3 py-2 text-right">
                                {{ number_format($saldo[$akun][$bulan] ?? 0, 0, ',', '.') }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach

                <tr class="bg-blue-100 font-bold">
                    <td class="border px-3 py-2">TOTAL AKTIVA</td>
                    @foreach($bulanList as $bulan)
                        <td class="border px-3 py-2 text-right">
                            {{ number_format($totalAktiva[$bulan], 0, ',', '.') }}
                        </td>
                    @endforeach
                </tr>

                {{-- ================== PASIVA ================== --}}
                <tr class="bg-red-50 font-bold">
                    <td colspan="{{ count($bulanList) + 1 }}" class="border px-3 py-2">
                        PASIVA
                    </td>
                </tr>

                @foreach($akunPasiva as $akun)
                    <tr>
                        <td class="border px-3 py-2">{{ $akun }}</td>
                        @foreach($bulanList as $bulan)
                            <td class="border px-3 py-2 text-right">
                                {{ number_format($saldo[$akun][$bulan] ?? 0, 0, ',', '.') }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach

                <tr class="bg-red-100 font-bold">
                    <td class="border px-3 py-2">TOTAL PASIVA</td>
                    @foreach($bulanList as $bulan)
                        <td class="border px-3 py-2 text-right">
                            {{ number_format($totalPasiva[$bulan], 0, ',', '.') }}
                        </td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
    </main>
</x-app-layout>
