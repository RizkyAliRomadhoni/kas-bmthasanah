<x-app-layout>
  <main class="main-content ...">
    <x-app.navbar/>
    <div class="container-fluid py-4">
      <div class="card">
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between">
          <h6>Kelola Kas - {{ $kambing->nama }}</h6>
          <a href="{{ route('kambing.index') }}" class="btn btn-light btn-sm">Kembali</a>
        </div>
        <div class="card-body">
          {{-- Form transaksi --}}
          <form action="{{ route('kambing.storeKas', $kambing->id) }}" method="POST" class="row g-3">
            @csrf
            <div class="col-md-3">
              <label class="form-label">Kategori</label>
              <select name="kategori" class="form-select" required>
                <option value="pemasukan">Pemasukan</option>
                <option value="pengeluaran">Pengeluaran</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Jumlah (Rp)</label>
              <input type="number" name="jumlah" class="form-control" required>
            </div>
            <div class="col-md-3">
              <label class="form-label">Tanggal</label>
              <input type="date" name="tanggal" class="form-control">
            </div>
            <div class="col-md-3">
              <label class="form-label">Keterangan</label>
              <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Beli pakan">
            </div>
            <div class="col-12 text-end mt-2">
              <button class="btn btn-primary">Simpan Transaksi</button>
            </div>
          </form>

          {{-- Tabel riwayat kas untuk kambing --}}
          <hr>
          <h6>Riwayat Kas</h6>
          <table class="table table-bordered">
            <thead><tr><th>Tanggal</th><th>Keterangan</th><th>Kategori</th><th>Jumlah</th><th>Saldo</th></tr></thead>
            <tbody>
              @foreach($kas as $r)
                <tr>
                  <td>{{ $r->created_at->format('Y-m-d') }}</td>
                  <td>{{ $r->keterangan }}</td>
                  <td>{{ ucfirst($r->kategori) }}</td>
                  <td>Rp {{ number_format($r->jumlah,0,',','.') }}</td>
                  <td>Rp {{ number_format($r->saldo,0,',','.') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>

        </div>
      </div>
    </div>
  </main>
</x-app-layout>
