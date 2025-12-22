{{-- resources/views/neraca/neraca-tabel.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Neraca Keuangan (Tabel)
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- FILTER TAHUN --}}
            <form method="GET" class="mb-4 flex items-center gap-3">
                <select name="tahun" class="rounded border-gray-300 text-sm">
                    @for ($y = date('Y') - 5; $y <= date('Y') + 1; $y++)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
                <button class="px-4 py-2 bg-blue-600 text-white rounded text-sm">
                    Tampilkan
                </button>
            </form>

            {{-- TABEL --}}
            <div class="bg-white shadow rounded overflow-x-auto">
                <table class="min-w-full border text-sm">
                    {{-- HEADER --}}
                    <thead class="bg-gray-100 sticky top-0 z-10">
                        <tr>
                            <th class="border px-3 py-2 text-left font-semibold">AKUN</th>
                            <th class="border px-3 py-2 text-right font-semibold">NERACA AWAL</th>
                            @foreach ($bulanList as $bulan)
                                <th class="border px-3 py-2 text-right font-semibold">
                                    {{ \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('M y') }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        {{-- ===================== --}}
                        {{-- AKTIVA --}}
                        {{-- ===================== --}}
                        <tr class="bg-gray-50">
                            <td colspan="{{ 2 + count($bulanList) }}" class="border px-3 py-2 font-bold">
                                AKTIVA
                            </td>
                        </tr>

                        @php $totalAktiva = []; @endphp

                        @foreach ($akunAktiva as $akun)
                            <tr>
                                <td class="border px-3 py-1">{{ strtoupper($akun) }}</td>
                                <td class="border px-3 py-1 text-right">Rp 0</td>

                                @foreach ($bulanList as $bulan)
                                    @php
                                        $nilai = $saldo[$akun][$bulan] ?? 0;
                                        $totalAktiva[$bulan] = ($totalAktiva[$bulan] ?? 0) + $nilai;
                                    @endphp
                                    <td class="border px-3 py-1 text-right">
                                        Rp {{ number_format($nilai, 0, ',', '.') }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach

                        {{-- TOTAL ASET --}}
                        <tr class="bg-gray-100 font-bold">
                            <td class="border px-3 py-2">TOTAL ASET</td>
                            <td class="border px-3 py-2 text-right">Rp 0</td>
                            @foreach ($bulanList as $bulan)
                                <td class="border px-3 py-2 text-right">
                                    Rp {{ number_format($totalAktiva[$bulan] ?? 0, 0, ',', '.') }}
                                </td>
                            @endforeach
                        </tr>

                        {{-- ===================== --}}
                        {{-- PASIVA --}}
                        {{-- ===================== --}}
                        <tr class="bg-gray-50">
                            <td colspan="{{ 2 + count($bulanList) }}" class="border px-3 py-2 font-bold">
                                PASIVA
                            </td>
                        </tr>

                        @php $totalPasiva = []; @endphp

                        @foreach ($akunPasiva as $akun)
                            <tr>
                                <td class="border px-3 py-1">{{ strtoupper($akun) }}</td>
                                <td class="border px-3 py-1 text-right">Rp 0</td>

                                @foreach ($bulanList as $bulan)
                                    @php
                                        $nilai = $saldo[$akun][$bulan] ?? 0;
                                        $totalPasiva[$bulan] = ($totalPasiva[$bulan] ?? 0) + $nilai;
                                    @endphp
                                    <td class="border px-3 py-1 text-right">
                                        Rp {{ number_format($nilai, 0, ',', '.') }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach

                        {{-- TOTAL KEWAJIBAN --}}
                        <tr class="bg-gray-100 font-bold">
                            <td class="border px-3 py-2">TOTAL KEWAJIBAN</td>
                            <td class="border px-3 py-2 text-right">Rp 0</td>
                            @foreach ($bulanList as $bulan)
                                <td class="border px-3 py-2 text-right">
                                    Rp {{ number_format($totalPasiva[$bulan] ?? 0, 0, ',', '.') }}
                                </td>
                            @endforeach
                        </tr>

                        {{-- ===================== --}}
                        {{-- MODAL --}}
                        {{-- ===================== --}}
                        <tr class="bg-gray-50">
                            <td colspan="{{ 2 + count($bulanList) }}" class="border px-3 py-2 font-bold">
                                MODAL
                            </td>
                        </tr>

                        <tr>
                            <td class="border px-3 py-1">LABA RUGI TAHUN BERJALAN</td>
                            <td class="border px-3 py-1 text-right">Rp 0</td>
                            @foreach ($bulanList as $bulan)
                                <td class="border px-3 py-1 text-right">
                                    Rp {{ number_format($labaRugi[$bulan] ?? 0, 0, ',', '.') }}
                                </td>
                            @endforeach
                        </tr>

                        {{-- TOTAL PASIVA --}}
                        <tr class="bg-gray-200 font-bold">
                            <td class="border px-3 py-2">TOTAL PASIVA</td>
                            <td class="border px-3 py-2 text-right">Rp 0</td>
                            @foreach ($bulanList as $bulan)
                                @php
                                    $grand = ($totalPasiva[$bulan] ?? 0) + ($labaRugi[$bulan] ?? 0);
                                @endphp
                                <td class="border px-3 py-2 text-right">
                                    Rp {{ number_format($grand, 0, ',', '.') }}
                                </td>
                            @endforeach
                        </tr>

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
