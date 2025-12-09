<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />

        <div class="container-fluid py-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h6 class="mb-0">Update Berat Bulanan Kambing - {{ $kambing->nama }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ url('/kambing/update-berat/' . $kambing->id . '/store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Tanggal Update</label>
                            <input type="date" name="tanggal" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Berat Kambing (kg)</label>
                            <input type="number" step="0.01" name="berat" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('kambing.index') }}" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
