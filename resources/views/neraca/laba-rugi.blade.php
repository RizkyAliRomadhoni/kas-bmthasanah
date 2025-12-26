<x-app-layout>
     <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
<div class="container-fluid py-4">
<div class="card shadow-sm">
<div class="card-header fw-bold">LAPORAN LABA RUGI</div>

<div class="card-body table-responsive">
<table class="table table-bordered">

<thead class="table-light text-center">
<tr>
<th rowspan="2">Akun</th>
@foreach ($bulanList as $bulan)
<th>{{ \Carbon\Carbon::createFromFormat('Y-m',$bulan)->translatedFormat('M Y') }}</th>
@endforeach
</tr>
</thead>

<tbody>
<tr class="fw-bold"><td colspan="{{ count($bulanList)+1 }}">PENDAPATAN</td></tr>

<tr><td>Penjualan</td>
@foreach($bulanList as $b)<td>{{ number_format($data[$b]['penjualan'],0,',','.') }}</td>@endforeach
</tr>

<tr><td>Pakan (Penjualan)</td>
@foreach($bulanList as $b)<td>{{ number_format($data[$b]['pakan'],0,',','.') }}</td>@endforeach
</tr>

<tr><td>Laba Penyesuaian Harga</td>
@foreach($bulanList as $b)<td>{{ number_format($data[$b]['penyesuaian_harga'],0,',','.') }}</td>@endforeach
</tr>

<tr><td>Basil</td>
@foreach($bulanList as $b)<td>{{ number_format($data[$b]['basil'],0,',','.') }}</td>@endforeach
</tr>

<tr class="fw-bold table-secondary">
<td>Total Pendapatan</td>
@foreach($bulanList as $b)<td>{{ number_format($data[$b]['total_pendapatan'],0,',','.') }}</td>@endforeach
</tr>

<tr class="fw-bold"><td colspan="{{ count($bulanList)+1 }}">BIAYA</td></tr>

<tr><td>Beban Upah</td>
@foreach($bulanList as $b)<td>{{ number_format($data[$b]['upah'],0,',','.') }}</td>@endforeach
</tr>

<tr><td>Kerugian Penjualan Kambing</td>
@foreach($bulanList as $b)<td>{{ number_format($data[$b]['kerugian_penjualan'],0,',','.') }}</td>@endforeach
</tr>

<tr><td>Kerugian Kambing Mati</td>
@foreach($bulanList as $b)<td>{{ number_format($data[$b]['kerugian_mati'],0,',','.') }}</td>@endforeach
</tr>

<tr class="fw-bold table-secondary">
<td>Total Biaya</td>
@foreach($bulanList as $b)<td>{{ number_format($data[$b]['total_biaya'],0,',','.') }}</td>@endforeach
</tr>

<tr class="fw-bold table-warning">
<td>LABA / RUGI</td>
@foreach($bulanList as $b)
<td class="{{ $data[$b]['laba_rugi'] < 0 ? 'text-danger' : 'text-success' }}">
{{ number_format($data[$b]['laba_rugi'],0,',','.') }}
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
