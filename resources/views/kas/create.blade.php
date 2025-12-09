<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Tambah Data Kas</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('kas.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <input type="text" name="keterangan" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Transaksi</label>
                            <select name="jenis_transaksi" class="form-control" required>
                                <option value="Masuk">Pemasukan</option>
                                <option value="Keluar">Pengeluaran</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Akun</label>
                            <select name="akun" class="form-control">
                                @foreach($akunList as $akun)
                                    <option value="{{ $akun }}">{{ $akun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah</label>
                            <input type="number" name="jumlah" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('kas.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
                <hr class="my-4">

{{-- Tombol Kelola Akun --}}
<div class="text-end mb-3">
    <button type="button" class="btn btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#kelolaAkunCollapse">
        ⚙️ Kelola Akun
    </button>
</div>

{{-- Form Kelola Akun --}}
<div class="collapse" id="kelolaAkunCollapse">
    <div class="card card-body border-0 shadow-sm rounded-4">

        <h6 class="fw-bold text-primary mb-3">Kelola Akun</h6>

        {{-- Tambah Akun Baru --}}
        <form action="{{ route('kas.kelolaAkun') }}" method="POST" class="d-flex mb-3">
            @csrf
            <input type="text" name="tambah_akun" class="form-control me-2" placeholder="Tambah akun baru..." required>
            <button type="submit" class="btn btn-success">Tambah</button>
        </form>

        {{-- Hapus Akun --}}
        <form action="{{ route('kas.kelolaAkun') }}" method="POST" class="d-flex mb-3">
            @csrf
            <select name="hapus_akun" class="form-select me-2" required>
                <option value="">Pilih akun yang ingin dihapus...</option>
                @foreach(json_decode(File::get(storage_path('app/akun.json')), true) as $akun)
                    <option value="{{ $akun }}">{{ $akun }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-danger">Hapus</button>
        </form>

        {{-- Ganti Nama Akun --}}
        <form action="{{ route('kas.kelolaAkun') }}" method="POST" class="d-flex mb-2">
            @csrf
            <select name="akun_lama" class="form-select me-2" required>
                <option value="">Pilih akun yang ingin diubah...</option>
                @foreach(json_decode(File::get(storage_path('app/akun.json')), true) as $akun)
                    <option value="{{ $akun }}">{{ $akun }}</option>
                @endforeach
            </select>
            <input type="text" name="akun_baru" class="form-control me-2" placeholder="Nama baru..." required>
            <button type="submit" class="btn btn-warning text-white">Ganti Nama</button>
        </form>

    </div>
</div>

            </div>
        </div>
    </main>
</x-app-layout>
