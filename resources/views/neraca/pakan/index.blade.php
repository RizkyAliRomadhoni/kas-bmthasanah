<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header pb-0 d-flex justify-content-between">
                            <h6 class="fw-bold">Kelola Akun: Pakan (Integrasi Kas)</h6>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0 table-hover">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Keterangan</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Harga (Total)</th>
                                            <th class="text-uppercase text-primary text-xxs font-weight-bolder opacity-7">Qty/Kg</th>
                                            <th class="text-uppercase text-primary text-xxs font-weight-bolder opacity-7">Harga/Kg</th>
                                            <th class="text-uppercase text-primary text-xxs font-weight-bolder opacity-7">Harga Jual/Kg</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data as $index => $item)
                                        <tr>
                                            <form action="{{ route('pakan.update') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="kas_id" value="{{ $item->id }}">
                                                
                                                <td class="ps-4 text-xs font-weight-bold">{{ $index + 1 }}</td>
                                                <td class="text-xs">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                                <td class="text-xs">{{ $item->keterangan }}</td>
                                                <td class="text-xs font-weight-bold">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                                
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

                                                <td class="align-middle">
                                                    <button type="submit" class="btn btn-link text-primary font-weight-bold text-xs mb-0">
                                                        <i class="fas fa-save me-1"></i> Simpan
                                                    </button>
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
            </div>
        </div>
    </main>
</x-app-layout>

<style>
    .w-80 { width: 80px !important; }
    .w-100 { width: 100px !important; }
    .bg-gray-100 { background-color: #f8f9fa; }
</style>