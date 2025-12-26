<x-app-layout>
<div class="p-6">

<h2 class="text-xl font-bold mb-4">Kelola Penjualan</h2>

<form method="POST">
@csrf
<input type="date" name="tanggal" required>
<input type="text" name="keterangan" placeholder="Keterangan">
<input type="text" name="tag" placeholder="Tag">
<input type="number" name="harga_jual" placeholder="Harga Jual">
<input type="number" name="hpp" placeholder="HPP">
<button class="bg-blue-500 text-white px-3">Tambah</button>
</form>

<hr class="my-4">

<table class="table-auto w-full border">
<thead>
<tr>
<th>No</th>
<th>Tanggal</th>
<th>Keterangan</th>
<th>Tag</th>
<th>Harga Jual</th>
<th>HPP</th>
@foreach($bulanList as $bulan)
<th>Laba {{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->format('M Y') }}</th>
@endforeach
</tr>
</thead>
<tbody>
@foreach($data as $i => $row)
<tr>
<td>{{ $i+1 }}</td>
<td>{{ $row->tanggal }}</td>
<td>{{ $row->keterangan }}</td>
<td>{{ $row->tag }}</td>
<td>{{ number_format($row->harga_jual) }}</td>
<td>{{ number_format($row->hpp) }}</td>
@foreach($bulanList as $bulan)
<td>
@if(\Carbon\Carbon::parse($row->tanggal)->format('Y-m') == $bulan)
{{ number_format($row->laba) }}
@endif
</td>
@endforeach
</tr>
@endforeach

<tr class="font-bold bg-gray-100">
<td colspan="6">TOTAL</td>
@foreach($bulanList as $bulan)
<td>{{ number_format($totalPerBulan[$bulan]) }}</td>
@endforeach
</tr>

<tr class="font-bold bg-yellow-100">
<td colspan="{{ 6 + count($bulanList) }}">TOTAL KESELURUHAN: {{ number_format($grandTotal) }}</td>
</tr>

</tbody>
</table>

</div>
</x-app-layout>
