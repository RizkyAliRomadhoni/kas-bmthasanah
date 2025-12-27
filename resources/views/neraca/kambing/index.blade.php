<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            
            <div class="row">
                <!-- TABEL UTAMA -->
                <div class="col-lg-9">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold">DATA KAMBING HASANAH FARM</h6>
                            <form action="{{ route('kambing-akun.index') }}" method="GET" class="d-flex gap-2">
                                <select name="bulan" class="form-select form-select-sm" style="width: 150px;">
                                    <option value="">Semua Bulan</option>
                                    @foreach($listBulan as $b)
                                        <option value="{{ $b->bulan }}" {{ $bulanTerpilih == $b->bulan ? 'selected' : '' }}>{{ $b->bulan }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary mb-0 text-white">Filter</button>
                            </form>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table table-bordered align-items-center mb-0">
                                    <thead class="bg-gray-100">
                                        <tr class="text-center">
                                            <th class="text-xxs font-weight-bolder opacity-7">TGL</th>
                                            <th class="text-xxs font-weight-bolder opacity-7">KETERANGAN</th>
                                            <th class="text-xxs font-weight-bolder opacity-7 bg-light">JENIS</th>
                                            <th class="text-xxs font-weight-bolder opacity-7 bg-light">QTY</th>
                                            <th class="text-xxs font-weight-bolder opacity-7 bg-light">BB (Kg)</th>
                                            <th class="text-xxs font-weight-bolder opacity-7">HARGA (Kas)</th>
                                            <th class="text-xxs font-weight-bolder opacity-7">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data as $item)
                                        <tr>
                                            <form action="{{ route('kambing-akun.update') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="kas_id" value="{{ $item->id }}">
                                                <td class="text-center text-xs">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m') }}</td>
                                                <td class="text-xs ps-3">{{ strtoupper($item->keterangan) }}</td>
                                                
                                                <!-- INPUT MANUAL -->
                                                <td><input type="text" name="jenis" class="form-control form-control-sm text-xs border-0" value="{{ $item->kambingDetail->jenis ?? '' }}" placeholder="Boer/Merino"></td>
                                                <td><input type="number" name="qty" class="form-control form-control-sm text-xs border-0 text-center" value="{{ $item->kambingDetail->qty ?? 0 }}"></td>
                                                <td><input type="number" step="0.01" name="berat_badan" class="form-control form-control-sm text-xs border-0 text-center" value="{{ $item->kambingDetail->berat_badan ?? 0 }}"></td>
                                                
                                                <td class="text-end pe-3 text-xs font-weight-bold">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                                <td class="text-center">
                                                    <button type="submit" class="btn btn-xs btn-primary mb-0 shadow-none"><i class="fas fa-save"></i></button>
                                                </td>
                                            </form>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SIDEBAR REKAP STOK (BOX KANAN EXCEL) -->
                <div class="col-lg-3">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-gray-100 pb-2">
                            <h6 class="text-sm fw-bold mb-0">STOCK KANDANG</h6>
                        </div>
                        <div class="card-body p-3">
                            <table class="table table-sm mb-0">
                                @php $grandQty = 0; @endphp
                                @foreach($rekapStok as $jenis => $qty)
                                <tr>
                                    <td class="text-xs">{{ strtoupper($jenis) }}</td>
                                    <td class="text-xs text-end font-weight-bold">{{ $qty }}</td>
                                </tr>
                                @php $grandQty += $qty; @endphp
                                @endforeach
                                <tr class="border-top fw-bold">
                                    <td class="text-xs">TOTAL</td>
                                    <td class="text-xs text-end">{{ $grandQty }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
</x-app-layout>

<style>
    .bg-gray-100 { background-color: #f8f9fa !important; }
    .text-xxs { font-size: 0.65rem !important; }
    .btn-xs { padding: 0.2rem 0.4rem; font-size: 0.6rem; }
</style>