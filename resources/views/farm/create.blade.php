<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100">
        <x-app.navbar />
        <div class="container-fluid py-4 px-4">
            <div class="card p-3 shadow-sm">
                <h5>Tambah Animal</h5>
                <form action="{{ route('farm.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label>Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Jenis</label>
                            <input type="text" name="jenis" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">--</option>
                                <option value="Jantan">Jantan</option>
                                <option value="Betina">Betina</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Umur (bulan)</label>
                            <input type="number" name="umur" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Berat terakhir (kg)</label>
                            <input type="number" step="0.01" name="berat_terakhir" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Tanggal masuk</label>
                            <input type="date" name="tanggal_masuk" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Status</label>
                            <select name="status" class="form-select">
                                <option value="Aktif">Aktif</option>
                                <option value="Dijual">Dijual</option>
                                <option value="Mati">Mati</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label>Kesehatan</label>
                            <input type="text" name="kesehatan" class="form-control">
                        </div>
                        <div class="col-12">
                            <label>Foto</label>
                            <input type="file" name="foto" class="form-control">
                        </div>
                        <div class="col-12 text-end">
                            <a href="{{ route('farm.index') }}" class="btn btn-light">Batal</a>
                            <button class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
</x-app-layout>
