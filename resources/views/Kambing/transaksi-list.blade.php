<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />

        <div class="py-6 px-6">
            <h2 class="text-2xl font-semibold mb-6 text-gray-800">📋 Daftar Transaksi Kambing</h2>
            

            {{-- Tombol kembali --}}
            <div class="mb-4">
                <a href="{{ route('kambing.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                   ← Kembali ke Halaman Kambing
                </a>
            </div>

            {{-- Tabel daftar transaksi --}}
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full table-auto border-collapse border border-gray-200">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-2 border">No</th>
                            <th class="px-4 py-2 border">Tanggal</th>
                            <th class="px-4 py-2 border">Nama Kambing</th>
                            <th class="px-4 py-2 border">Jenis Transaksi</th>
                            <th class="px-4 py-2 border">Keterangan</th>
                            <th class="px-4 py-2 border text-right">Jumlah (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksi as $index => $item)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-2 border text-center">{{ $index + 1 }}</td>
                                <td class="px-4 py-2 border text-center">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                </td>
                                <td class="px-4 py-2 border text-center">
                                    {{ $item->kambing->nama ?? '-' }}
                                </td>
                                <td class="px-4 py-2 border text-center">
                                    @if($item->jenis == 'pemasukan')
                                        <span class="text-green-600 font-semibold">Pemasukan</span>
                                    @else
                                        <span class="text-red-600 font-semibold">Pengeluaran</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 border">{{ $item->keterangan }}</td>
                                <td class="px-4 py-2 border text-right">
                                    Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-gray-500">Belum ada transaksi kambing</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Total saldo per tanggal --}}
            <div class="mt-8">
                <h3 class="text-xl font-semibold text-gray-700 mb-3">📆 Ringkasan Saldo per Hari</h3>
                @php
                    $grouped = $transaksi->groupBy('tanggal');
                @endphp
                <div class="bg-white shadow-md rounded-lg p-4">
                    <table class="min-w-full border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 border text-left">Tanggal</th>
                                <th class="px-4 py-2 border text-right">Total Pemasukan</th>
                                <th class="px-4 py-2 border text-right">Total Pengeluaran</th>
                                <th class="px-4 py-2 border text-right">Saldo Hari Itu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($grouped as $tgl => $items)
                                @php
                                    $masuk = $items->where('jenis', 'pemasukan')->sum('jumlah');
                                    $keluar = $items->where('jenis', 'pengeluaran')->sum('jumlah');
                                    $saldoHari = $masuk - $keluar;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($tgl)->format('d M Y') }}</td>
                                    <td class="px-4 py-2 border text-right text-green-700">
                                        Rp {{ number_format($masuk, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 border text-right text-red-700">
                                        Rp {{ number_format($keluar, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 border text-right font-semibold">
                                        Rp {{ number_format($saldoHari, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-gray-500">Tidak ada data saldo per hari</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>
</x-app-layout>
