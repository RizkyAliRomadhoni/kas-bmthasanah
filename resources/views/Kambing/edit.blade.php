<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />

        <div class="container-fluid py-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Edit Data Kambing</h6>
                    <a href="{{ route('kambing.index') }}" class="btn btn-light text-primary fw-bold btn-sm">Kembali</a>
                </div>
                <div class="card-body">
                    @include('kambing._form', ['action' => route('kambing.update', $kambing->id), 'method' => 'PUT', 'kambing' => $kambing])
                </div>
            </div>

            {{-- FORM UPDATE BERAT BULANAN --}}
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-gradient-info text-white">
                    <h6 class="mb-0">Perbarui Berat Bulanan</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('kambing.updateBerat', $kambing->id) }}" method="POST" class="row g-3">
                        @csrf
                        <div class="col-md-3">
                            <label class="form-label">Bulan</label>
                            <input type="number" name="bulan" class="form-control" min="1" max="12" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tahun</label>
                            <input type="number" name="tahun" class="form-control" min="2020" value="{{ date('Y') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Berat (kg)</label>
                            <input type="number" name="berat" step="0.01" class="form-control" required>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Simpan Berat</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
