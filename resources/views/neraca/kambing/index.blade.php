<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            
            <div class="row">
                <div class="col-lg-9">
                    <h5 class="fw-bold">KELOLA STOK KAMBING</h5>
                    
                    @foreach($data as $kas)
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-header bg-gray-100 d-flex justify-content-between align-items-center py-2">
                            <span class="text-xs font-weight-bold">
                                <i class="fas fa-calendar me-1"></i> {{ $kas->tanggal }} | 
                                <i class="fas fa-file-invoice me-1"></i> {{ $kas->keterangan }}
                            </span>
                            <span class="badge bg-dark">Total Kas: Rp {{ number_format($kas->jumlah) }}</span>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm mb-0">
                                <thead class="text-xxs text-uppercase bg-light">
                                    <tr>
                                        <th class="ps-3">Jenis Kambing</th>
                                        <th class="text-center">Harga Satuan</th>
                                        <th class="text-center">BB (Kg)</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kas->kambingDetails as $detail)
                                    <tr>
                                        <td class="ps-3 text-xs">{{ $detail->jenis }}</td>
                                        <td class="text-center text-xs">Rp {{ number_format($detail->harga_beli) }}</td>
                                        <td class="text-center text-xs">{{ $detail->berat_badan }} Kg</td>
                                        <td class="text-center">
                                            <a href="{{ route('kambing-akun.destroy', $detail->id) }}" class="text-danger"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    
                                    <!-- FORM INPUT BARIS BARU UNTUK KAS INI -->
                                    <tr class="bg-gray-50">
                                        <form action="{{ route('kambing-akun.storeDetail') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="kas_id" value="{{ $kas->id }}">
                                            <td>
                                                <input type="text" name="jenis" class="form-control form-control-sm text-xs" placeholder="Jenis (Merino/Boer)" required>
                                            </td>
                                            <td>
                                                <input type="number" name="harga_beli" class="form-control form-control-sm text-xs" placeholder="Harga Satuan" required>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" name="berat_badan" class="form-control form-control-sm text-xs" placeholder="Berat">
                                            </td>
                                            <td class="text-center">
                                                <button type="submit" class="btn btn-sm btn-primary mb-0"><i class="fas fa-plus"></i></button>
                                            </td>
                                        </form>
                                    </tr>
                                </tbody>
                            </table>
                            
                            @php
                                $totalTerinput = $kas->kambingDetails->sum('harga_beli');
                                $selisih = $kas->jumlah - $totalTerinput;
                            @endphp
                            <div class="p-2 d-flex justify-content-end align-items-center gap-3">
                                <span class="text-xxs">Terinput: <b>Rp {{ number_format($totalTerinput) }}</b></span>
                                <span class="text-xxs @if($selisih != 0) text-danger @else text-success @endif">
                                    Selisih: <b>Rp {{ number_format($selisih) }}</b>
                                    @if($selisih == 0) <i class="fas fa-check-circle"></i> @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- STOCK KANDANG (KANAN) -->
                <div class="col-lg-3">
                    <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                        <div class="card-header bg-dark text-white pb-2">
                            <h6 class="text-white text-sm mb-0">STOK KANDANG SAAT INI</h6>
                        </div>
                        <div class="card-body p-3">
                            @foreach($rekapStok as $jenis => $total)
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-xs">{{ strtoupper($jenis) }}</span>
                                <span class="text-xs font-weight-bold">{{ $total }} Ekor</span>
                            </div>
                            @endforeach
                            <div class="d-flex justify-content-between py-2 fw-bold">
                                <span class="text-xs">TOTAL POPULASI</span>
                                <span class="text-xs">{{ $rekapStok->sum() }} Ekor</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>