<x-app-layout>
     <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card shadow border-0">
                    <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">📦 Data Aset</h5>
                        <a href="{{ route('aset.create') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-plus"></i> Tambah Aset
                        </a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover align-items-center">
                                <thead class="bg-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Aset</th>
                                        <th>Kategori</th>
                                        <th>Nilai (Rp)</th>
                                        <th>Tanggal Perolehan</th>
                                        <th>Kondisi</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($asets as $aset)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $aset->nama_aset }}</td>
                                            <td>{{ $aset->kategori }}</td>
                                            <td>Rp {{ number_format($aset->nilai, 0, ',', '.') }}</td>
                                            <td>{{ $aset->tanggal_perolehan }}</td>
                                            <td>{{ $aset->kondisi }}</td>
                                            <td>{{ $aset->keterangan }}</td>
                                            <td>
                                                <a href="{{ route('aset.edit', $aset->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('aset.destroy', $aset->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus aset ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">Belum ada data aset.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    </main>
                </div>
            </div>
        </div>
    </div> 
</x-app-layout>
