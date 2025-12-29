<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0 text-uppercase">Rincian Kambing: HPP & Kematian</h5>
                <a href="{{ route('neraca.index') }}" class="btn btn-sm btn-outline-secondary mb-0">Kembali ke Neraca</a>
            </div>

            <!-- BAGIAN 1: HARGA KAMBING (HPP) -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-gray-100 py-3">
                    <h6 class="mb-0 text-sm font-weight-bold">INPUT HARGA KAMBING (HPP)</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('rincian-kambing.storeHpp') }}" method="POST" class="row g-2 mb-4">
                        @csrf
                        <div class="col-md-3"><input type="text" name="jenis" class="form-control form-control-sm" placeholder="Jenis Kambing" required></div>
                        <div class="col-md-1"><input type="number" name="qty" class="form-control form-control-sm" placeholder="Qty" required></div>
                        <div class="col-md-2"><input type="number" name="harga_satuan" class="form-control form-control-sm" placeholder="Harga Satuan" required></div>
                        <div class="col-md-2"><input type="number" name="ongkir" class="form-control form-control-sm" placeholder="Ongkir" value="0"></div>
                        <div class="col-md-2"><button type="submit" class="btn btn-sm btn-primary w-100 mb-0">Simpan HPP</button></div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="bg-light text-xxs font-weight-bolder opacity-7 text-uppercase text-center">
                                <tr>
                                    <th>Jenis</th><th>Qty</th><th>Harga Satuan</th><th>Jumlah</th><th>Ongkir</th><th>Total HPP</th><th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs">
                                @foreach($dataHpp as $row)
                                <tr>
                                    <td class="ps-3 fw-bold">{{ $row->jenis }}</td>
                                    <td class="text-center">{{ $row->qty }}</td>
                                    <td class="text-end pe-3">{{ number_format($row->harga_satuan) }}</td>
                                    <td class="text-end pe-3">{{ number_format($row->jumlah) }}</td>
                                    <td class="text-end pe-3">{{ number_format($row->ongkir) }}</td>
                                    <td class="text-end pe-3 fw-bold">{{ number_format($row->total_hpp) }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('rincian-kambing.deleteHpp', $row->id) }}" class="text-danger" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 fw-bold text-xs">
                                <tr>
                                    <td colspan="3" class="text-end pe-3">TOTAL</td>
                                    <td class="text-end pe-3">{{ number_format($dataHpp->sum('jumlah')) }}</td>
                                    <td class="text-end pe-3">{{ number_format($dataHpp->sum('ongkir')) }}</td>
                                    <td class="text-end pe-3 text-primary">Rp {{ number_format($dataHpp->sum('total_hpp')) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- BAGIAN 2: KAMBING MATI -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gray-100 py-3">
                    <h6 class="mb-0 text-sm font-weight-bold">INPUT DATA KAMBING MATI (KERUGIAN)</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('rincian-kambing.storeMati') }}" method="POST" class="row g-2 mb-4">
                        @csrf
                        <div class="col-md-2"><input type="date" name="tanggal" class="form-control form-control-sm" required></div>
                        <div class="col-md-4"><input type="text" name="jenis" class="form-control form-control-sm" placeholder="Keterangan / Jenis Kambing" required></div>
                        <div class="col-md-3"><input type="number" name="harga" class="form-control form-control-sm" placeholder="Harga/Nilai Kerugian" required></div>
                        <div class="col-md-2"><button type="submit" class="btn btn-sm btn-danger w-100 mb-0">Catat Mati</button></div>
                    </form>

                    <div class="table-responsive" style="max-width: 800px;">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="bg-light text-xxs font-weight-bolder opacity-7 text-uppercase text-center">
                                <tr>
                                    <th>Tanggal</th><th>Keterangan Jenis</th><th>Harga (Rugi)</th><th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs">
                                @foreach($dataMati as $row)
                                <tr>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}</td>
                                    <td class="ps-3">{{ $row->jenis }}</td>
                                    <td class="text-end pe-3">{{ number_format($row->harga) }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('rincian-kambing.deleteMati', $row->id) }}" class="text-danger" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 fw-bold text-xs">
                                <tr>
                                    <td colspan="2" class="text-end pe-3">TOTAL KERUGIAN</td>
                                    <td class="text-end pe-3 text-danger">Rp {{ number_format($dataMati->sum('harga')) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </main>
</x-app-layout>

<style>
    .bg-gray-100 { background-color: #f8f9fa !important; }
    .text-xxs { font-size: 0.65rem !important; }
</style>