<x-app-layout>
    <main class="p-4 bg-gray-50 min-h-screen">

        {{-- HEADER --}}
        <div class="mb-4">
            <h2 class="text-xl font-bold">NERACA KEUANGAN {{ $tahun }}</h2>
            <p class="text-sm text-gray-500">Format Neraca (Aktiva = Pasiva)</p>
        </div>

        {{-- TABLE WRAPPER --}}
        <div class="overflow-x-auto bg-white shadow rounded-lg">

            <table class="min-w-full border text-sm">
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

                    {{-- ================= AKTIVA ================= --}}
                    <tr class="bg-green-50 font-bold">
                        <td class="border px-3 py-2" colspan="{{ count($bulanList)+1 }}">
                            AKTIVA
                        </td>
                    </tr>

                    @foreach($akunAktiva as $akun)
                        <tr>
                            <td class="border px-3 py-2">{{ strtoupper($akun) }}</td>

                            @foreach($bulanList as $bulan)
                                <td class="border px-3 py-2 text-right">
                                    Rp {{ number_format($saldo[$akun][$bulan] ?? 0, 0, ',', '.') }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach

                    {{-- TOTAL AKTIVA --}}
                    <tr class="bg-green-100 font-bold">
                        <td class="border px-3 py-2">TOTAL AKTIVA</td>

                        @foreach($bulanList as $bulan)
                            @php
                                $totalAktiva = 0;
                                foreach($akunAktiva as $a){
                                    $totalAktiva += $saldo[$a][$bulan] ?? 0;
                                }
                            @endphp
                            <td class="border px-3 py-2 text-right">
                                Rp {{ number_format($totalAktiva,0,',','.') }}
                            </td>
                        @endforeach
                    </tr>

                    {{-- ================= PASIVA ================= --}}
                    <tr class="bg-red-50 font-bold">
                        <td class="border px-3 py-2" colspan="{{ count($bulanList)+1 }}">
                            PASIVA
                        </td>
                    </tr>

                    @foreach($akunPasiva as $akun)
                        <tr>
                            <td class="border px-3 py-2">{{ strtoupper($akun) }}</td>

                            @foreach($bulanList as $bulan)
                                <td class="border px-3 py-2 text-right">
                                    Rp {{ number_format($saldo[$akun][$bulan] ?? 0, 0, ',', '.') }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach

                    {{-- LABA RUGI --}}
                    <tr class="bg-blue-50 font-bold">
                        <td class="border px-3 py-2">LABA RUGI BULAN BERJALAN</td>

                        @foreach($bulanList as $bulan)
                            <td class="border px-3 py-2 text-right">
                                Rp {{ number_format($labaRugi[$bulan] ?? 0,0,',','.') }}
                            </td>
                        @endforeach
                    </tr>

                    {{-- TOTAL PASIVA --}}
                    <tr class="bg-red-100 font-bold">
                        <td class="border px-3 py-2">TOTAL PASIVA</td>

                        @foreach($bulanList as $bulan)
                            @php
                                $totalPasiva = 0;
                                foreach($akunPasiva as $a){
                                    $totalPasiva += $saldo[$a][$bulan] ?? 0;
                                }
                                $totalPasiva += $labaRugi[$bulan] ?? 0;
                            @endphp
                            <td class="border px-3 py-2 text-right">
                                Rp {{ number_format($totalPasiva,0,',','.') }}
                            </td>
                        @endforeach
                    </tr>

                </tbody>
            </table>

        </div>

        {{-- CATATAN --}}
        <div class="mt-4 text-sm text-gray-500">
            <p>* Neraca dihitung otomatis dari tabel <b>kas</b></p>
            <p>* Aktiva HARUS sama dengan Pasiva</p>
        </div>

    </main>
</x-app-layout>
