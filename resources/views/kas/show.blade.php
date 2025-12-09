<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg bg-gray-50">

        <x-app.navbar />

        <div class="container py-5">

            <div class="card shadow border-0 rounded-4 mx-auto" style="max-width: 600px;">
                
                {{-- HEADER --}}
                <div class="card-header bg-gradient-primary text-white rounded-top-4">
                    <h5 class="mb-0 text-white fw-bold">📄 Detail Transaksi</h5>
                </div>

                <div class="card-body">

                    @php
                        $warnaJumlah = $kas->jenis_transaksi === 'masuk' ? 'text-success' : 'text-danger';
                        $warnaSaldo  = $kas->saldo >= 0 ? 'text-success' : 'text-danger';
                    @endphp

                    <table class="table table-borderless">
                        <tr>
                            <th class="text-gray-700">Tanggal</th>
                            <td>{{ \Carbon\Carbon::parse($kas->tanggal)->translatedFormat('d F Y') }}</td>
                        </tr>

                        <tr>
                            <th class="text-gray-700">Jenis</th>
                            <td class="fw-bold {{ $warnaJumlah }}">
                                {{ ucfirst($kas->jenis_transaksi) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-gray-700">Jumlah</th>
                            <td class="fw-bold {{ $warnaJumlah }}">
                                Rp{{ number_format($kas->jumlah, 0, ',', '.') }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-gray-700">Akun</th>
                            <td>{{ $kas->akun ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th class="text-gray-700">Saldo Setelah Transaksi</th>
                            <td class="fw-bold {{ $warnaSaldo }}">
                                Rp{{ number_format($kas->saldo, 0, ',', '.') }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-gray-700 align-top">Keterangan</th>
                            <td style="white-space: normal;">
                                {{ $kas->keterangan }}
                            </td>
                        </tr>
                    </table>

                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('kas.index') }}" class="btn btn-secondary">
                            ← Kembali
                        </a>

                        <div>
                            <a href="{{ route('kas.edit', $kas->id) }}" class="btn btn-warning">
                                Edit
                            </a>

                            <form action="{{ route('kas.destroy', $kas->id) }}" 
                                  method="POST" 
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger"
                                        onclick="return confirm('Hapus transaksi ini?')">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </main>
</x-app-layout>
