<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <div class="container mt-4">
        <h3 class="mb-3">➕ Tambah Data Aset</h3>

        <form action="{{ route('aset.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama Aset</label>
                <input type="text" name="nama_aset" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <input type="text" name="kategori" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Nilai (Rp)</label>
                <input type="number" name="nilai" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('aset.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
    </main>
</x-app-layout>
