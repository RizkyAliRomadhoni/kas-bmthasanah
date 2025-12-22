<x-app-layout>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg bg-light">
<div class="container-fluid py-4">

{{-- ================= HEADER & FILTER ================= --}}
<div class="card shadow border-0 mb-4 rounded-4">
    <div class="card-header bg-gradient-primary text-white d-flex flex-wrap justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">📊 NERACA KEUANGAN</h5>

        <form method="GET" class="d-flex gap-2 align-items-center">
            <select name="tahun" class="form-select form-select-sm">
                @foreach($tahunList as $t)
                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>

            <button class="btn btn-light btn-sm fw-bold">
                <i class="fas fa-sync"></i> Tampilkan
            </button>
        </form>
    </div>
</div>

{{-- ================= TABEL NERACA ================= --}}
<div class="card shadow-sm border-0 rounded-4">
<div class="card-body table-responsive">

<table class="table table-bordered table-sm align-middle text-nowrap">
<thead class="table-dark sticky-top">
<tr>
    <th style="min-width:220px">AKUN</th>
    <th class="text-end">NERACA AWAL</th>

    {{-- HEADER BULAN (AMAN) --}}
    @foreach($bulanList as $bulanAngka => $namaBulan)
        <th class="text-end">
            {{ \Carbon\Carbon::createFromDate($tahun, $bulanAngka, 1)->translatedFormat('M y') }}
        </th>
    @endforeach
</tr>
</thead>

<tbody>

{{-- ================= AKTIVA ================= --}}
<tr class="table-success fw-bold">
    <td colspan="{{ count($bulanList) + 2 }}">AKTIVA</td>
</tr>

@foreach($akunAktiva as $akun)
<tr>
    <td>{{ strtoupper($akun) }}</td>
    <td class="text-end">Rp {{ number_format($saldoAwalAktiva[$akun] ?? 0,0,',','.') }}</td>

    @foreach($bulanList as $bulanAngka => $namaBulan)
        <td class="text-end">
            Rp {{ number_format($neracaAktiva[$akun][$bulanAngka] ?? 0,0,',','.') }}
        </td>
    @endforeach
</tr>
@endforeach

<tr class="fw-bold table-light">
    <td>TOTAL ASET</td>
    <td class="text-end">Rp {{ number_format($totalAwalAktiva,0,',','.') }}</td>

    @foreach($bulanList as $bulanAngka => $namaBulan)
        <td class="text-end">
            Rp {{ number_format($totalAktivaPerBulan[$bulanAngka] ?? 0,0,',','.') }}
        </td>
    @endforeach
</tr>

{{-- ================= PASIVA ================= --}}
<tr class="table-danger fw-bold">
    <td colspan="{{ count($bulanList) + 2 }}">PASIVA</td>
</tr>

@foreach($akunPasiva as $akun)
<tr>
    <td>{{ strtoupper($akun) }}</td>
    <td class="text-end">Rp {{ number_format($saldoAwalPasiva[$akun] ?? 0,0,',','.') }}</td>

    @foreach($bulanList as $bulanAngka => $namaBulan)
        <td class="text-end">
            Rp {{ number_format($neracaPasiva[$akun][$bulanAngka] ?? 0,0,',','.') }}
        </td>
    @endforeach
</tr>
@endforeach

<tr class="fw-bold table-light">
    <td>TOTAL PASIVA</td>
    <td class="text-end">Rp {{ number_format($totalAwalPasiva,0,',','.') }}</td>

    @foreach($bulanList as $bulanAngka => $namaBulan)
        <td class="text-end">
            Rp {{ number_format($totalPasivaPerBulan[$bulanAngka] ?? 0,0,',','.') }}
        </td>
    @endforeach
</tr>

</tbody>
</table>

</div>
</div>

{{-- ================= FOOTER ================= --}}
<div class="text-center mt-4 text-muted small">
    <em>© {{ date('Y') }} Neraca Keuangan Peternakan</em>
</div>

</div>
</main>
</x-app-layout>
