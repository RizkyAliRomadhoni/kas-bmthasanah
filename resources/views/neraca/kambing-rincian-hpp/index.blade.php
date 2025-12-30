<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <style>
        .table-excel input { width: 100%; border: none; background: transparent; text-align: center; font-size: 12px; }
        .table-excel input:focus { background: #fffde7; outline: 1px solid #ffd600; }
        .table-excel th { font-size: 11px; vertical-align: middle !important; }
        .sticky-col { position: sticky; left: 0; background: white; z-index: 1; }
    </style>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-9">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white d-flex justify-content-between">
                        <h6 class="fw-bold mb-0">RINCIAN HPP & STOK BULANAN</h6>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">+ Tambah Baris</button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-excel mb-0">
                                <thead class="bg-light text-center">
                                    <tr>
                                        <th rowspan="2" class="sticky-col">KETERANGAN</th>
                                        <th rowspan="2">JENIS</th>
                                        <th colspan="2" class="bg-warning text-white">STOK AWAL</th>
                                        @foreach($bulanList as $bulan)
                                            <th colspan="2">{{ \Carbon\Carbon::parse($bulan)->format('M-y') }}</th>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <th class="bg-light">HARGA</th>
                                        <th class="bg-light">QTY</th>
                                        @foreach($bulanList as $bulan)
                                            <th>HARGA</th>
                                            <th>QTY</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stok as $item)
                                    <tr>
                                        <td class="sticky-col fw-bold">{{ $item->keterangan }}</td>
                                        <td>{{ $item->jenis }}</td>
                                        <td class="text-end text-muted">{{ number_format($item->harga_awal) }}</td>
                                        <td class="text-center text-muted">{{ $item->qty_awal }}</td>
                                        
                                        @foreach($bulanList as $bulan)
                                            @php 
                                                $det = $item->rincian_bulanan->where('bulan', $bulan)->first(); 
                                            @endphp
                                            <td class="p-0">
                                                <input type="number" value="{{ $det ? (int)$det->harga_update : '' }}"
                                                    onchange="updateCell('{{ $item->id }}', '{{ $bulan }}', 'harga_update', this.value)">
                                            </td>
                                            <td class="p-0">
                                                <input type="number" value="{{ $det ? $det->qty_update : '' }}"
                                                    onchange="updateCell('{{ $item->id }}', '{{ $bulan }}', 'qty_update', this.value)">
                                            </td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR SUMMARY (KANAN) -->
            <div class="col-md-3">
                <div class="card card-body shadow-sm border-0 mb-3">
                    <h6 class="fw-bold text-xs">STOCK KANDANG</h6>
                    @foreach($summaryJenis as $sj)
                    <div class="d-flex justify-content-between border-bottom py-1 text-xs">
                        <span>{{ strtoupper($sj->jenis) }}</span>
                        <span class="fw-bold">{{ $sj->total }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="card card-body shadow-sm border-0">
                    <h6 class="fw-bold text-xs">KLASTER BANGSALAN</h6>
                    @foreach($summaryKlaster as $sk)
                    <div class="d-flex justify-content-between border-bottom py-1 text-xs">
                        <span>{{ strtoupper($sk->klaster) }}</span>
                        <span class="fw-bold">{{ $sk->total }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('rincian-hpp.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-body">
                    <h6 class="fw-bold mb-3">Tambah Stok Baru</h6>
                    <input type="text" name="keterangan" placeholder="Keterangan (Contoh: RATIMIN)" class="form-control mb-2" required>
                    <input type="text" name="jenis" placeholder="Jenis (Contoh: MERINO)" class="form-control mb-2" required>
                    <input type="text" name="klaster" placeholder="Klaster (Contoh: MARTO)" class="form-control mb-2" required>
                    <div class="row">
                        <div class="col-6"><input type="number" name="harga_awal" placeholder="Harga Modal" class="form-control" required></div>
                        <div class="col-6"><input type="number" name="qty_awal" placeholder="Qty" class="form-control" required></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary btn-sm">Simpan Baris</button></div>
            </form>
        </div>
    </div>

    <script>
        function updateCell(id, bulan, kolom, nilai) {
            fetch("{{ route('rincian-hpp.update') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ id, bulan, kolom, nilai })
            }).then(response => response.json()).then(data => console.log(data.message));
        }
    </script>
    </main>
</x-app-layout>