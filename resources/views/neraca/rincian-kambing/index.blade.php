<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

<div class="p-6">

<h2 class="font-bold text-lg">Harga Kambing (HPP)</h2>

<table class="table-auto w-full border mb-6">
<tr>
<th>Jenis</th><th>Qty</th><th>Harga</th><th>Jumlah</th><th>Ongkir</th><th>Total HPP</th>
</tr>
@foreach($hpp as $row)
<tr>
<td>{{ $row->jenis }}</td>
<td>{{ $row->qty }}</td>
<td>{{ number_format($row->harga_satuan) }}</td>
<td>{{ number_format($row->jumlah) }}</td>
<td>{{ number_format($row->ongkir) }}</td>
<td>{{ number_format($row->total_hpp) }}</td>
</tr>
@endforeach
<tr class="font-bold bg-gray-100">
<td colspan="5">TOTAL</td>
<td>{{ number_format($totalHpp) }}</td>
</tr>
</table>

<h2 class="font-bold text-lg">Kambing Mati</h2>

<table class="table-auto w-full border">
<tr>
<th>Tanggal</th><th>Jenis</th><th>Harga</th>
</tr>
@foreach($mati as $row)
<tr>
<td>{{ $row->tanggal }}</td>
<td>{{ $row->jenis }}</td>
<td>{{ number_format($row->harga) }}</td>
</tr>
@endforeach
<tr class="font-bold bg-red-100">
<td colspan="2">TOTAL KERUGIAN</td>
<td>{{ number_format($totalMati) }}</td>
</tr>
</table>

</div>
</main>
</x-app-layout>
