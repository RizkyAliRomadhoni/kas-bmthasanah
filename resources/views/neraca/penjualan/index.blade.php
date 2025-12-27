<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4"> <!-- Tambahkan container agar rapi sesuai template -->
            <div class="card p-4"> <!-- Tambahkan Card agar UI konsisten -->
                
                <h2 class="text-xl font-bold mb-4">Kelola Penjualan</h2>

                <!-- PERBAIKAN DI SINI: Tambahkan action yang mengarah ke route store -->
                <form action="{{ route('neraca.penjualan.store') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-2">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label>Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" placeholder="Keterangan">
                    </div>
                    <div class="col-md-1">
                        <label>Tag</label>
                        <input type="text" name="tag" class="form-control" placeholder="Tag">
                    </div>
                    <div class="col-md-2">
                        <label>Harga Jual</label>
                        <input type="number" name="harga_jual" class="form-control" placeholder="Harga Jual">
                    </div>
                    <div class="col-md-2">
                        <label>HPP</label>
                        <input type="number" name="hpp" class="form-control" placeholder="HPP">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Tambah</button>
                    </div>
                </form>

                <hr class="my-4">

                <div class="table-responsive"> <!-- Agar tabel tidak pecah di layar kecil -->
                    <table class="table table-bordered align-items-center mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Keterangan</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tag</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Harga Jual</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">HPP</th>
                                @foreach($bulanList as $bulan)
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Laba {{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->format('M Y') }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $i => $row)
                            <tr>
                                <td class="text-sm px-3">{{ $i+1 }}</td>
                                <td class="text-sm px-3">{{ $row->tanggal }}</td>
                                <td class="text-sm px-3">{{ $row->keterangan }}</td>
                                <td class="text-sm px-3">{{ $row->tag }}</td>
                                <td class="text-sm px-3">{{ number_format($row->harga_jual) }}</td>
                                <td class="text-sm px-3">{{ number_format($row->hpp) }}</td>
                                @foreach($bulanList as $bulan)
                                <td class="text-sm px-3">
                                    @if(\Carbon\Carbon::parse($row->tanggal)->format('Y-m') == $bulan)
                                        {{ number_format($row->laba) }}
                                    @endif
                                </td>
                                @endforeach
                            </tr>
                            @endforeach

                            <tr class="font-weight-bold bg-gray-100">
                                <td colspan="6" class="text-right px-3">TOTAL PER BULAN</td>
                                @foreach($bulanList as $bulan)
                                    <td class="px-3">{{ number_format($totalPerBulan[$bulan] ?? 0) }}</td>
                                @endforeach
                            </tr>

                            <tr class="font-weight-bold bg-warning text-white">
                                <td colspan="{{ 6 + count($bulanList) }}" class="text-center p-2">
                                    TOTAL KESELURUHAN: Rp {{ number_format($grandTotal) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>