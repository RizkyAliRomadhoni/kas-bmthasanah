<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg bg-light">
        <x-app.navbar />
        <div class="container-fluid py-4 px-4">

            {{-- Top cards --}}
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card shadow-sm rounded-3 p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Total Animals</small>
                                <h4 class="mb-0">{{ $totalAnimals ?? 0 }}</h4>
                            </div>
                            <div class="text-end">
                                <a href="{{ route('farm.index') }}" class="btn btn-sm btn-outline-primary">Manage</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm rounded-3 p-3 h-100">
                        <small class="text-muted">Avg Weight (kg)</small>
                        <h4 class="mb-0">{{ number_format($avgWeight ?? 0,2,',','.') }}</h4>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm rounded-3 p-3 h-100">
                        <small class="text-muted">Feed Today (g)</small>
                        <h4 class="mb-0">{{ number_format($totalFeedToday ?? 0,0,',','.') }}</h4>
                    </div>
                </div>

                <div class="col-md-3 text-end">
                    <a href="{{ route('farm.create') }}" class="btn btn-primary btn-lg">+ Tambah Animal</a>
                </div>
            </div>

            {{-- Chart & recent --}}
            <div class="row mb-4 g-3">
                <div class="col-lg-8">
                    <div class="card p-3 shadow-sm">
                        <h6 class="mb-3">Trend Berat & Konsumsi (6 bulan)</h6>
                        <canvas id="farmChart" height="130"></canvas>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card p-3 shadow-sm mb-3">
                        <h6 class="mb-2">3 Update Berat Terakhir</h6>
                        <ul class="list-group">
                            @forelse($lastWeights as $w)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold">{{ $w->animal->nama ?? '—' }}</div>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($w->tanggal_update)->format('d M Y') }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-success"> {{ $w->berat }} kg</span>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted">Tidak ada data</li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="card p-3 shadow-sm">
                        <h6 class="mb-2">3 Pencatatan Pakan Terakhir</h6>
                        <ul class="list-group">
                            @forelse($lastFeeds as $f)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold">{{ $f->animal->nama ?? '—' }}</div>
                                        <small class="text-muted">{{ $f->jenis_pakan }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-warning"> {{ number_format($f->jumlah,0,',','.') }} g</span>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted">Tidak ada data</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="card p-3 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Daftar Animals</h6>
                    <form class="d-flex" method="GET" action="{{ route('farm.index') }}">
                        <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Cari nama / kode / jenis" value="{{ request('search') }}">
                        <button class="btn btn-sm btn-light">Cari</button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Jenis</th>
                                <th>Umur (bln)</th>
                                <th>Berat (kg)</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($animals as $i => $a)
                            <tr>
                                <td>{{ $animals->firstItem() + $i }}</td>
                                <td style="width:60px">
                                    @if($a->foto)
                                        <img src="{{ asset('storage/'.$a->foto) }}" alt="" class="img-fluid rounded" style="height:48px;width:48px;object-fit:cover;">
                                    @else
                                        <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" style="height:48px;width:48px">
                                            <small>—</small>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $a->nama }}</div>
                                    <small class="text-muted">{{ $a->kode }}</small>
                                </td>
                                <td>{{ $a->jenis }}</td>
                                <td>{{ $a->umur }}</td>
                                <td>
                                    <span class="{{ $a->berat_terakhir >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                        {{ number_format($a->berat_terakhir,2,',','.') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $a->status == 'Aktif' ? 'success' : ($a->status=='Dijual' ? 'warning' : 'danger') }}">
                                        {{ $a->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('farm.show',$a->id) }}" class="btn btn-sm btn-info">Detail</a>
                                    <a href="{{ route('farm.edit',$a->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('farm.destroy',$a->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-2">
                        {{ $animals->withQueryString()->links() }}
                    </div>
                </div>
            </div>

        </div>
    </main>

    {{-- Chart.js include --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('farmChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($months),
                    datasets: [
                        {
                            label: 'Total Berat (kg)',
                            data: @json($pWeight),
                            backgroundColor: 'rgba(34,197,94,0.6)',
                            borderColor: 'rgba(34,197,94,1)',
                            yAxisID: 'y'
                        },
                        {
                            label: 'Total Pakan (g)',
                            data: @json($pFeed),
                            backgroundColor: 'rgba(59,130,246,0.6)',
                            borderColor: 'rgba(59,130,246,1)',
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    interaction: {mode: 'index', intersect: false},
                    scales: {
                        y: { beginAtZero: true, position: 'left' },
                        y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false } }
                    }
                }
            });
        }
    </script>
</x-app-layout>
