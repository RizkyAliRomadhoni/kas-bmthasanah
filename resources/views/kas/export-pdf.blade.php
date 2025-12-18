<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
        }
        th {
            background: #eee;
        }
        h3 {
            text-align: center;
        }
    </style>
</head>
<body>

<h3>LAPORAN KAS</h3>

<p>
    <strong>Bulan:</strong>
    {{ $bulan ? \Carbon\Carbon::parse($bulan)->format('F Y') : 'Semua' }} <br>
    <strong>Akun:</strong> {{ $akun ?? 'Semua' }}
</p>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <th>Jenis</th>
            <th>Jumlah</th>
            <th>Akun</th>
            <th>Saldo</th>
        </tr>
    </thead>
    <tbody>
        @foreach($kas as $i => $item)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
            <td>{{ $item->keterangan }}</td>
            <td>{{ $item->jenis_transaksi }}</td>
            <td>Rp{{ number_format($item->jumlah, 0, ',', '.') }}</td>
            <td>{{ $item->akun }}</td>
            <td>Rp{{ number_format($item->saldo, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<p>
    <strong>Pemasukan:</strong> Rp{{ number_format($totalMasuk, 0, ',', '.') }} <br>
    <strong>Pengeluaran:</strong> Rp{{ number_format($totalKeluar, 0, ',', '.') }} <br>
    <strong>Saldo Akhir:</strong> Rp{{ number_format($saldoRingkasan, 0, ',', '.') }}
</p>

</body>
</html>
