<x-app-layout>
    <main class="main-content p-4">

        <h4 class="mb-4">Kelola Akun Kas</h4>

        {{-- ALERT --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- FORM TAMBAH AKUN --}}
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{ route('kas.tambah-akun') }}">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-10">
                            <input type="text" name="akun" class="form-control" placeholder="Nama akun baru" required>
                        </div>
                        <div class="col-md-2 d-grid">
                            <button class="btn btn-primary">Tambah</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- LIST AKUN --}}
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-compact">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Nama Akun</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($akunList as $i => $akun)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $akun }}</td>
                                <td>
                                    <form method="POST" action="{{ route('kas.hapus-akun', $akun) }}"
                                          onsubmit="return confirm('Hapus akun ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    Belum ada akun
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</x-app-layout>
