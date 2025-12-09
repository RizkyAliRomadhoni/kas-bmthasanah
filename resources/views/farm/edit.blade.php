<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg bg-light">
        <x-app.navbar />

        <div class="container-fluid py-4 px-4">
            <div class="card shadow-sm p-4 rounded-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Edit Data Animal</h5>
                    <a href="{{ route('farm.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                </div>

                <form action="{{ route('farm.update', $animal->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Kode</label>
                            <input type="text" name="kode" value="{{ old('kode', $animal->kode) }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" value="{{ old('nama', $animal->nama) }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jenis</label>
                            <input type="text" name="jenis" value="{{ old('jenis', $animal->jenis) }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-control">
                                <option value="Jantan" {{ $animal->gender=='Jantan'?'selected':'' }}>Jantan</option>
                                <option value="Betina" {{ $animal->gender=='Betina'?'selected':'' }}>Betina</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Umur (bulan)</label>
                            <input type="number" name="umur" value="{{ old('umur', $animal->umur) }}" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Berat Terakhir (kg)</label>
                            <input type="number" step="0.01" name="berat_terakhir" value="{{ old('berat_terakhir', $animal->berat_terakhir) }}" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="Aktif" {{ $animal->status=='Aktif'?'selected':'' }}>Aktif</option>
                                <option value="Dijual" {{ $animal->status=='Dijual'?'selected':'' }}>Dijual</option>
                                <option value="Sakit" {{ $animal->status=='Sakit'?'selected':'' }}>Sakit</option>
                                <option value="Mati" {{ $animal->status=='Mati'?'selected':'' }}>Mati</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Masuk</label>
                            <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk', $animal->tanggal_masuk) }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kesehatan</label>
                            <input type="text" name="kesehatan" value="{{ old('kesehatan', $animal->kesehatan) }}" class="form-control">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Foto</label>
                            <input type="file" name="foto" class="form-control">

                            @if ($animal->foto)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/'.$animal->foto) }}" class="img-fluid rounded" style="height:120px;width:120px;object-fit:cover;">
                                </div>
                            @endif
                        </div>

                    </div>

                    <div class="mt-4 d-flex justify-content-end">
                        <button class="btn btn-primary px-4">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</x-app-layout>
