<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100">
        <x-app.navbar />
        <div class="container-fluid py-4 px-4">

            <div class="card mb-3 p-3 shadow-sm">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $animal->nama }} <small class="text-muted">({{ $animal->kode }})</small></h4>
                        <small class="text-muted">{{ $animal->jenis }} • {{ $animal->gender }} • Umur {{ $animal->umur }} bln</small>
                    </div>
                    <div>
                        <a href="{{ route('farm.edit',$animal->id) }}" class="btn btn-warning">Edit</a>
                        <a href="{{ route('farm.index') }}" class="btn btn-light">Kembali</a>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card p-3 shadow-sm">
                        <div class="text-center mb-3">
                            @if($animal->foto)
                                <img src="{{ asset('storage/'.$animal->foto) }}" class="img-fluid rounded mb-2" style="max-height:220px;">
                            @else
                                <div class="bg-secondary rounded p-5 text-white">No Photo</div>
                            @endif
                        </div>
                        <div class="mb-2"><strong>Status:</strong> <span class="badge bg-{{ $animal->status=='Aktif' ? 'success' : 'danger' }}">{{ $animal->status }}</span></div>
                        <div class="mb-2"><strong>Kesehatan:</strong> {{ $animal->kesehatan ?? '-' }}</div>
                        <div class="mb-2"><strong>Berat terakhir:</strong> {{ number_format($animal->berat_terakhir,2,',','.') }} kg</div>
                        <div class="mb-2"><strong>Tanggal masuk:</strong> {{ optional($animal->tanggal_masuk)->format('d M Y') }}</div>
                    </div>

                    <div class="card p-3 mt-3 shadow-sm">
                        <h6 class="mb-2">Update Berat</h6>
                        <form action="{{ route('farm.updateWeight',$animal->id) }}" method="POST">
                            @csrf
                            <div class="mb-2">
                                <input type="number" step="0.01" name="berat" class="form-control" placeholder="Berat (kg)" required>
                            </div>
                            <div class="mb-2">
                                <input type="date" name="tanggal_update" class="form-control">
                            </div>
                            <div class="mb-2">
                                <input type="text" name="catatan" class="form-control" placeholder="Catatan (opsional)">
                            </div>
                            <button class="btn btn-success w-100">Simpan Berat</button>
                        </form>
                    </div>

                    <div class="card p-3 mt-3 shadow-sm">
                        <h6 class="mb-2">Tambah Pakan</h6>
                        <form action="{{ route('farm.addFeed',$animal->id) }}" method="POST">
                            @csrf
                            <div class="mb-2">
                                <input type="text" name="jenis_pakan" class="form-control" placeholder="Jenis Pakan" required>
                            </div>
                            <div class="mb-2">
                                <input type="number" name="jumlah" class="form-control" placeholder="Jumlah (gram/hari)" required>
                            </div>
                            <div class="mb-2">
                                <input type="text" name="catatan" class="form-control" placeholder="Catatan (opsional)">
                            </div>
                            <button class="btn btn-primary w-100">Tambah Pakan</button>
                        </form>
                    </div>

                </div>

                <div class="col-md-8">
                    <div class="card p-3 shadow-sm mb-3">
                        <h6>Riwayat Berat</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Berat (kg)</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($animal->beratHistory as $h)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($h->tanggal_update)->format('d M Y') }}</td>
                                        <td>{{ number_format($h->berat,2,',','.') }}</td>
                                        <td>{{ $h->catatan }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="3" class="text-center text-muted">Belum ada history</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card p-3 shadow-sm">
                        <h6>Riwayat Pakan</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Jenis</th>
                                        <th>Jumlah (g)</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($animal->pakan as $p)
                                    <tr>
                                        <td>{{ $p->created_at->format('d M Y H:i') }}</td>
                                        <td>{{ $p->jenis_pakan }}</td>
                                        <td>{{ number_format($p->jumlah,0,',','.') }}</td>
                                        <td>{{ $p->catatan }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center text-muted">Belum ada pencatatan pakan</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </main>
</x-app-layout>
