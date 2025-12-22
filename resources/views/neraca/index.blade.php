<x-app-layout>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg bg-light">
<div class="container-fluid py-4">

{{-- ===========================
HEADER FILTER
=========================== --}}
<div class="card shadow-sm mb-4 border-0">
    <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">📊 NERACA KEUANGAN</h5>
        <form method="GET" class="d-flex gap-2">
            <select name="tahun" class="form-select form-select-sm">
                @foreach($tahunList as $t)
                    <option value="{{ $t }}" {{ $tahun==$t?'selected':'' }}>{{ $t }}</option>
                @endforeach
            </select>
            <button class="btn btn-light btn-sm fw-bold">
                <i class="fas fa-filter me-1"></i>Filter
            </button>
        </form>
    </div>
</div>

{{-- ===========================
NERACA TABLE
=========================== --}}
<div class="card shadow-sm border-0">
<div class="card-body table-responsive" style="overflow-x:auto">

<table class="table table-bordered table-sm align-middle text-nowrap">
<thead class="table-light sticky-top">
<tr class="text-center fw-bold">
    <th style="min-width:220px">AKUN</th>
    <th class="text-end">NERACA AWAL</th>

    @foreach($bulanList as $b)
        <th class="text-end">
            {{ \Carbon\Carbon::createFromDate($tahun, $b, 1)->translatedFormat('M y') }}
        </th>
    @endforeach
</tr>
</thead>

<tbody>

{{-- ===========================
AKTIVA
=========================== --}}
<tr class="table-success fw-bold">
    <td colspan="{{ count($bulanList)+2 }}">AKTIVA</td>
</tr>

@foreach($aktivaAccounts as $akun)
<tr>
    <td>{{ strtoupper($akun) }}</td>
    <td class="text-end">Rp {{ number_format($neracaAwal['aktiva'][$akun] ?? 0,0,',','.') }}</td>

    @foreach($bulanList as $b)
        <td class="text-end">
            Rp {{ number_format($neraca['aktiva'][$akun][$b] ?? 0,0,',','.') }}
        </td>
    @endforeach
</tr>
@endforeach

<tr class="fw-bold table-success">
    <td>TOTAL ASET</td>
    <td class="text-end">Rp {{ number_format($totalAwalAktiva,0,',','.') }}</td>

    @foreach($bulanList as $b)
        <td class="text-end">
            Rp {{ number_format($totalAktiva[$b] ?? 0,0,',','.') }}
        </td>
    @endforeach
</tr>

{{-- ===========================
PASIVA
=========================== --}}
<tr class="table-danger fw-bold">
    <td colspan="{{ count($bulanList)+2 }}">PASIVA</td>
</tr>

@foreach($pasivaAccounts as $akun)
<tr>
    <td>{{ strtoupper($akun) }}</td>
    <td class="text-end">Rp {{ number_format($neracaAwal['pasiva'][$akun] ?? 0,0,',','.') }}</td>

    @foreach($bulanList as $b)
        <td class="text-end">
            Rp {{ number_format($neraca['pasiva'][$akun][$b] ?? 0,0,',','.') }}
        </td>
    @endforeach
</tr>
@endforeach

<tr class="fw-bold table-danger">
    <td>TOTAL PASIVA</td>
    <td class="text-end">Rp {{ number_format($totalAwalPasiva,0,',','.') }}</td>

    @foreach($bulanList as $b)
        <td class="text-end">
            Rp {{ number_format($totalPasiva[$b] ?? 0,0,',','.') }}
        </td>
    @endforeach
</tr>

</tbody>
</table>

</div>
</div>

</div>
</main>

<style>
.table th, .table td {
    vertical-align: middle;
}
.sticky-top {
    position: sticky;
    top: 0;
    z-index: 10;
}
</style>
</x-app-layout>
