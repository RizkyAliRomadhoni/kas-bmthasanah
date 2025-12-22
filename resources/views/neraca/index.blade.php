<x-app-layout>
<main class="container-fluid py-4">

<div class="card shadow-sm border-0 rounded-4">
<div class="card-header bg-dark text-white">
    <h5 class="mb-0">📑 NERACA (AKTIVA & PASIVA)</h5>
</div>

<div class="card-body table-responsive">
<table class="table table-bordered table-sm text-nowrap">
    <thead class="table-light sticky-top">
        <tr>
            <th>Akun</th>
            @foreach($bulanHeader as $ym)
                @php [$y,$m] = explode('-', $ym); @endphp
                <th>{{ $namaBulan[$m] }} {{ $y }}</th>
            @endforeach
        </tr>
    </thead>

    <tbody>
        {{-- ================= AKTIVA ================= --}}
        <tr class="table-success fw-bold">
            <td colspan="{{ count($bulanHeader)+1 }}">AKTIVA</td>
        </tr>

        @foreach($akunAktiva as $a)
        <tr>
            <td>{{ $a }}</td>
            @foreach($bulanHeader as $ym)
                <td class="text-end">
                    Rp {{ number_format($saldo[$a][$ym] ?? 0,0,',','.') }}
                </td>
            @endforeach
        </tr>
        @endforeach

        {{-- TOTAL ASET --}}
        <tr class="fw-bold table-secondary">
            <td>TOTAL ASET</td>
            @foreach($bulanHeader as $ym)
                @php $t=0; foreach($akunAktiva as $a){$t+=$saldo[$a][$ym]??0;} @endphp
                <td class="text-end">Rp {{ number_format($t,0,',','.') }}</td>
            @endforeach
        </tr>

        {{-- ================= PASIVA ================= --}}
        <tr class="table-danger fw-bold">
            <td colspan="{{ count($bulanHeader)+1 }}">PASIVA</td>
        </tr>

        @foreach($akunPasiva as $a)
        <tr>
            <td>{{ $a }}</td>
            @foreach($bulanHeader as $ym)
                <td class="text-end">
                    Rp {{ number_format($saldo[$a][$ym] ?? 0,0,',','.') }}
                </td>
            @endforeach
        </tr>
        @endforeach

        {{-- MODAL --}}
        <tr class="fw-bold table-info">
            <td>LABA RUGI TAHUN BERJALAN</td>
            @foreach($bulanHeader as $ym)
                <td class="text-end">
                    Rp {{ number_format($labaRugi[$ym] ?? 0,0,',','.') }}
                </td>
            @endforeach
        </tr>

        {{-- TOTAL PASIVA --}}
        <tr class="fw-bold table-secondary">
            <td>TOTAL PASIVA</td>
            @foreach($bulanHeader as $ym)
                @php
                    $tp = 0;
                    foreach($akunPasiva as $a){ $tp += $saldo[$a][$ym] ?? 0; }
                    $tp += $labaRugi[$ym] ?? 0;
                @endphp
                <td class="text-end">Rp {{ number_format($tp,0,',','.') }}</td>
            @endforeach
        </tr>
    </tbody>
</table>
</div>
</div>

</main>
</x-app-layout>
