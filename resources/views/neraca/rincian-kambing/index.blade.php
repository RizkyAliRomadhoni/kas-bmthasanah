<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            
            <!-- SECTION 1: HARGA KAMBING (HPP) -->
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Input Harga Kambing (HPP)</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('neraca.rincian-kambing.hpp.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Jenis Kambing</label>
                                            <input type="text" name="jenis" class="form-control" placeholder="Contoh: Boer" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Qty (Ekor)</label>
                                            <input type="number" name="qty" class="form-control" value="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Harga Satuan</label>
                                            <input type="number" name="harga_satuan" class="form-control" value="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Ongkir</label>
                                            <input type="number" name="ongkir" class="form-control" value="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100">Simpan HPP</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Tabel Harga Kambing (HPP)</h6>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jenis</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Qty</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Harga Satuan</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jumlah</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Ongkir</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total HPP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($hpp as $row)
                                        <tr>
                                            <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $row->jenis }}</p></td>
                                            <td class="text-center"><span class="text-secondary text-xs font-weight-bold">{{ $row->qty }}</span></td>
                                            <td><p class="text-xs font-weight-bold mb-0">{{ number_format($row->harga_satuan) }}</p></td>
                                            <td><p class="text-xs font-weight-bold mb-0">{{ number_format($row->jumlah) }}</p></td>
                                            <td><p class="text-xs font-weight-bold mb-0">{{ number_format($row->ongkir) }}</p></td>
                                            <td><p class="text-xs font-weight-bold mb-0">{{ number_format($row->total_hpp) }}</p></td>
                                        </tr>
                                        @endforeach
                                        <tr class="bg-gray-100">
                                            <td colspan="5" class="text-end font-weight-bold text-sm">TOTAL KESELURUHAN HPP:</td>
                                            <td class="font-weight-bold text-sm">Rp {{ number_format($totalHpp) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="horizontal dark my-4">

            <!-- SECTION 2: KAMBING MATI -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Input Kambing Mati</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('neraca.rincian-kambing.mati.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="date" name="tanggal" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Keterangan (Jenis/ID)</label>
                                    <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Mati Kembung" required>
                                </div>
                                <div class="form-group">
                                    <label>Harga/Kerugian</label>
                                    <input type="number" name="harga" class="form-control" placeholder="Rp" required>
                                </div>
                                <button type="submit" class="btn btn-danger w-100">Catat Kematian</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Daftar Kambing Mati</h6>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Keterangan</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kerugian (Harga)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($mati as $row)
                                        <tr>
                                            <td class="ps-4 text-xs">{{ $row->tanggal }}</td>
                                            <td class="text-xs">{{ $row->keterangan }}</td>
                                            <td class="text-xs font-weight-bold">{{ number_format($row->harga) }}</td>
                                        </tr>
                                        @endforeach
                                        <tr class="bg-red-100">
                                            <td colspan="2" class="text-end font-weight-bold text-sm">TOTAL KERUGIAN:</td>
                                            <td class="font-weight-bold text-sm text-danger">Rp {{ number_format($totalMati) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
</x-app-layout>