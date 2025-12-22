<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg bg-gray-50">
<div class="container mx-auto px-4 py-6">

<h2 class="text-xl font-bold mb-4">NERACA TAHUN {{ $tahun }}</h2>

<div class="overflow-x-auto">
<table class="min-w-full border text-sm">
<thead class="bg-gray-100 sticky top-0">
<tr>
<th class="border px-2 py-1 text-left">AKUN</th>
<th class="border px-2 py-1 text-right">Saldo Awal</th>
@foreach($bulanList as $b)
<th class="border px-2 py-1 text-right">
{{ \Carbon\Carbon::createFromFormat('Y-m',$b)->translatedFormat('M Y') }}
</th>
@endforeach
</tr>
</thead>

<tbody>

{{-- ===================== AKTIVA ===================== --}}
<tr class="bg-gray-200 font-bold">
<td colspan="{{ 2 + count($bulanList) }}">AKTIVA</td>
</tr>

@foreach($akunAktiva as $akun)
<tr>
<td class="border px-2 py-1">{{ $akun }}</td>
<td class="border px-2 py-1 text-right">
{{ number_format($saldoAwal[$akun] ?? 0,0,',','.') }}
</td>
@foreach($bulanList as $b)
<td class="border px-2 py-1 text-right">
{{ number_format($saldo[$akun][$b] ?? 0,0,',','.') }}
</td>
@endforeach
</tr>
@endforeach

{{-- ===================== PASIVA ===================== --}}
<tr class="bg-gray-200 font-bold">
<td colspan="{{ 2 + count($bulanList) }}">PASIVA</td>
</tr>

@foreach($akunPasiva as $akun)
<tr>
<td class="border px-2 py-1">{{ $akun }}</td>
<td class="border px-2 py-1 text-right">
{{ number_format($saldoAwal[$akun] ?? 0,0,',','.') }}
</td>
@foreach($bulanList as $b)
<td class="border px-2 py-1 text-right">
{{ number_format($saldo[$akun][$b] ?? 0,0,',','.') }}
</td>
@endforeach
</tr>
@endforeach

{{-- ===================== LABA RUGI ===================== --}}
<tr class="bg-blue-100 font-bold">
<td>Laba Rugi Berjalan</td>
<td class="border"></td>
@foreach($bulanList as $b)
<td class="border px-2 py-1 text-right">
{{ number_format($labaRugi[$b] ?? 0,0,',','.') }}
</td>
@endforeach
</tr>

</tbody>
</table>
</div>

</div>
</main>
</x-app-layout>
