<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            
            <!-- HEADER & FILTER -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0 p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0">Kelola Akun: Pakan</h6>
                            
                            <!-- Form Filter Per Bulan -->
                            <form action="{{ route('pakan.index') }}" method="GET" class="d-flex gap-2">
                                <select name="bulan" class="form-select form-select-sm" style="width: 200px;">
                                    <option value="">-- Semua Bulan --</option>
                                    @foreach($listBulan as $b)
                                        <option value="{{ $b->bulan }}" {{ $bulanTerpilih == $b->bulan ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::parse($b->bulan)->translatedFormat('F Y') }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary mb-0">Filter</button>
                                @if($bulanTerpilih)
                                    <a href="{{ route('pakan.index') }}" class="btn btn-sm btn-secondary mb-0">Reset</a>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABEL DATA -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Keterangan</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Harga Kas (Rp)</th>
                                            <th class="text-uppercase text-primary text-xxs font-weight-bolder opacity-7">Qty (Kg)</th>
                                            <th class="text-uppercase text-primary text-xxs font-weight-bolder opacity-7">Harga/Kg</th>
                                            <th class="text-uppercase text-primary text-xxs font-weight-bolder opacity-7">Hrg Jual/Kg</th>
                                            <th class="text-uppercase text-primary text-xxs font-weight-bolder opacity-7">Total Nilai Jual</th>
                                            <th class="text-center text-secondary opacity-7">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data as $item)
                                        <tr>
                                            <form action="{{ route('pakan.update') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="kas_id" value="{{ $item->id }}">
                                                
                                                <td class="ps-4 text-xs">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                                <td class="text-xs">{{ $item->keterangan }}</td>
                                                <td class="text-xs font-weight-bold">
                                                    {{ number_format($item->jumlah, 0, ',', '.') }}
                                                </td>
                                                
                                                <!-- Input Manual Qty -->
                                                <td>
                                                    <input type="number" step="0.01" name="qty_kg" 
                                                        class="form-control form-control-sm text-xs w-80" 
                                                        value="{{ $item->pakanDetail->qty_kg ?? 0 }}">
                                                </td>
                                                
                                                <!-- Input Manual Harga/Kg -->
                                                <td>
                                                    <input type="number" step="0.01" name="harga_kg" 
                                                        class="form-control form-control-sm text-xs w-100" 
                                                        value="{{ $item->pakanDetail->harga_kg ?? 0 }}">
                                                </td>

                                                <!-- Input Manual Harga Jual/Kg -->
                                                <td>
                                                    <input type="number" step="0.01" name="harga_jual_kg" 
                                                        class="form-control form-control-sm text-xs w-100" 
                                                        value="{{ $item->pakanDetail->harga_jual_kg ?? 0 }}">
                                                </td>

                                                <!-- Kalkulasi Otomatis Baris: Qty * Harga Jual -->
                                                <td class="text-xs font-weight-bold text-success">
                                                    {{ number_format(($item->pakanDetail->qty_kg ?? 0) * ($item->pakanDetail->harga_jual_kg ?? 0), 0, ',', '.') }}
                                                </td>

                                                <td class="text-center">
                                                    <button type="submit" class="btn btn-link text-primary p-0 mb-0">
                                                        <i class="fas fa-save"></i>
                                                    </button>
                                                </td>
                                            </form>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-xs">Data tidak ditemukan untuk bulan ini.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    
                                    <!-- FOOTER TOTAL -->
                                    <tfoot class="bg-gray-50 fw-bold">
                                        <tr>
                                            <td colspan="2" class="text-end text-xs text-uppercase ps-4">TOTAL :</td>
                                            <td class="text-xs">Rp {{ number_format($totalHargaKas, 0, ',', '.') }}</td>
                                            <td class="text-xs text-primary">{{ number_format($totalQty, 2, ',', '.') }} Kg</td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-xs text-success">Rp {{ number_format($totalPotensiJual, 0, ',', '.') }}</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </main>
</x-app-layout>

<style>
    .w-80 { width: 80px !important; }
    .w-100 { width: 100px !important; }
    .bg-gray-100 { background-color: #f8f9fa; }
    .bg-gray-50 { background-color: #fafafa; }
</style>