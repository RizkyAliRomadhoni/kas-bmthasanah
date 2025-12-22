<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Neraca Keuangan Tahun {{ $tahun }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ================= FILTER TAHUN ================= --}}
            <div class="mb-4 flex justify-end">
                <form method="GET" class="flex gap-2">
                    <select name="tahun"
                        class="rounded-md border-gray-300 text-sm focus:ring focus:ring-indigo-200">
                        @for($y = date('Y') - 5; $y <= date('Y') + 1; $y++)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                    <button
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
                        Tampilkan
                    </button>
                </form>
            </div>

            {{-- ================= NERACA TABLE ================= --}}
            <div class="bg-white shadow-sm rounded-lg overflow-x-auto">
                <table class="min-w-full border border-gray-200 text-sm">
                    <thead class="bg-gray-800 text-white text-center">
                        <tr>
                            <th class="px-4 py-2 border">Akun</th>
                            @foreach($bulanList as $b)
                                <th class="px-4 py-2 border">
                                    {{ \Carbon\Carbon::parse($b.'-01')->translatedFormat('M Y') }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>

                        {{-- ================= AKTIVA ================= --}}
                        <tr class="bg-blue-100 font-bold">
                            <td colspan="{{ count($bulanList) + 1 }}"
                                class="px-4 py-2 border">
                                AKTIVA
                            </td>
                        </tr>

                        @foreach($akunAktiva as $akun)
                            <tr>
                                <td class="px-4 py-2 border">{{ $akun }}</td>
                                @foreach($bulanList as $b)
                                    <td class="px-4 py-2 border text-right">
                                        {{ number_format($saldo[$akun][$b] ?? 0, 0, ',', '.') }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach

                        {{-- ================= PASIVA ================= --}}
                        <tr class="bg-yellow-100 font-bold">
                            <td colspan="{{ count($bulanList) + 1 }}"
                                class="px-4 py-2 border">
                                PASIVA
                            </td>
                        </tr>

                        @foreach($akunPasiva as $akun)
                            <tr>
                                <td class="px-4 py-2 border">{{ $akun }}</td>
                                @foreach($bulanList as $b)
                                    <td class="px-4 py-2 border text-right">
                                        {{ number_format($saldo[$akun][$b] ?? 0, 0, ',', '.') }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach

                        {{-- ================= LABA RUGI ================= --}}
                        <tr class="bg-green-100 font-bold">
                            <td class="px-4 py-2 border">LABA / RUGI</td>
                            @foreach($bulanList as $b)
                                <td class="px-4 py-2 border text-right">
                                    {{ number_format($labaRugi[$b] ?? 0, 0, ',', '.') }}
                                </td>
                            @endforeach
                        </tr>

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
