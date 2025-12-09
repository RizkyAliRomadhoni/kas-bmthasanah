<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-gradient-warning text-white">
                    <h6 class="mb-0">Edit Data Kas</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('kas.update', $kas->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" 
       value="{{ \Carbon\Carbon::parse($kas->tanggal)->format('Y-m-d') }}" 
       class="form-control">

                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <input type="text" name="keterangan" value="{{ $kas->keterangan }}" class="form-control">
                        </div>
                        <div class="mb-3">
                        <label class="form-label">Jenis Transaksi</label>
                     <select name="jenis_transaksi" class="form-control">
                      <option value="Masuk" {{ $kas->jenis_transaksi == 'Masuk' ? 'selected' : '' }}>Pemasukan</option>
                      <option value="Keluar" {{ $kas->jenis_transaksi == 'Keluar' ? 'selected' : '' }}>Pengeluaran</option>
                         </select>
                            </div>

                        <div class="mb-3">
                            <label class="form-label">Akun</label>
                            <select name="akun" class="form-control">
                                @foreach($akunList as $akun)
                                    <option value="{{ $akun }}" {{ $kas->akun == $akun ? 'selected' : '' }}>{{ $akun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah</label>
                            <input type="number" name="jumlah" value="{{ $kas->jumlah }}" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="{{ route('kas.index') }}" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
