<x-app-layout>
    <x-slot name="header">
        <x-app.navbar />
        <div class="container-fluid py-4">
            <h2 class="font-semibold text-xl text-dark mb-0">
                {{ __('Penjualan Kambing') }}
            </h2>
        </div>
    </x-slot>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-lg-8 col-md-10 mx-auto">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-gradient-primary text-white">
                            <h6 class="mb-0">Form Penjualan Kambing</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('kambing.jual.proses') }}" method="POST" class="mt-3">
                                @csrf

                                {{-- Pilih Kambing --}}
                                <div class="mb-3">
                                    <label for="kambing_id" class="form-label fw-semibold">Pilih Kambing</label>
                                    <select name="kambing_id" id="kambing_id" required class="form-select border border-gray-300 rounded-2">
                                        <option value="">-- Pilih Kode Kambing --</option>
                                        @foreach($kambing as $k)
                                            <option value="{{ $k->id }}">{{ $k->kode }} - {{ $k->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Harga Jual --}}
                                <div class="mb-3">
                                    <label for="harga_jual" class="form-label fw-semibold">Harga Jual</label>
                                    <input type="number" name="harga_jual" id="harga_jual" required 
                                           class="form-control border border-gray-300 rounded-2" 
                                           placeholder="Masukkan harga jual">
                                </div>

                                {{-- Tanggal Jual --}}
                               <div class="mb-3">
    <label for="tanggal" class="form-label fw-semibold">Tanggal Transaksi</label>
    <input type="date" name="tanggal" id="tanggal" required 
           class="form-control border border-gray-300 rounded-2">
</div>


                                {{-- Keterangan --}}
                                <div class="mb-4">
                                    <label for="keterangan" class="form-label fw-semibold">Keterangan</label>
                                    <textarea name="keterangan" id="keterangan" rows="3" 
                                              class="form-control border border-gray-300 rounded-2" 
                                              placeholder="Contoh: Dijual ke Pak Budi"></textarea>
                                </div>

                                {{-- Tombol Aksi --}}
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('kambing.index') }}" 
                                       class="btn btn-secondary me-2 px-4">
                                        Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-check-circle me-1"></i> Konfirmasi Penjualan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
