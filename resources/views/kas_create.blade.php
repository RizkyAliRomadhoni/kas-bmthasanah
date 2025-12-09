<x-app-layout>
    <style>
        main {
            padding-left: 260px;
        }
        @media (max-width: 991px) {
            main {
                padding-left: 0;
            }
        }
    </style>

    <div class="container-fluid py-4 px-4">
        <div class="card shadow-sm border-0 col-lg-6 mx-auto">
            <div class="card-header bg-white border-0">
                <h5 class="fw-bold mb-0">Tambah Transaksi Kas</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('kas.store') }}">
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
                        <select name="jenis_transaksi" class="form-select" required>
                            <option value="Masuk">Kas Masuk</option>
                            <option value="Keluar">Kas Keluar</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah (Rp)</label>
                        <input type="number" name="jumlah" class="form-control" required>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('kas.index') }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
