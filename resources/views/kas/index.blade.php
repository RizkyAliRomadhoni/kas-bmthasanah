<x-app-layout>

{{-- ========================= --}}
{{--  STYLE tambahan --}}
{{-- ========================= --}}
<style>
    .glass-card {
        backdrop-filter: blur(12px);
        background: rgba(255, 255, 255, 0.75);
    }
</style>

{{-- ========================= --}}
{{--  MOBILE HEADER --}}
{{-- ========================= --}}
<div class="lg:hidden w-full bg-slate-900 text-white py-4 px-5 flex items-center justify-between shadow-md">
    <h1 class="text-lg font-bold">💰 Data Kas Utama</h1>

    <button id="mobileMenuBtn" class="text-white text-2xl">
        <i class="fas fa-bars"></i>
    </button>
</div>

{{-- BACKGROUND --}}
<main class="main-content max-height-vh-100 h-100 bg-gray-100">

    {{-- ========================= --}}
    {{--  WRAPPER --}}
    {{-- ========================= --}}
    <div class="container-fluid px-4 py-6">

        {{-- ========================= --}}
        {{--  CARD HEADER --}}
        {{-- ========================= --}}
        <div class="glass-card shadow-xl border border-gray-200 rounded-3xl p-5 mb-6">

            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">

                <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                    <span>💰</span> Data Kas Utama
                </h2>

                <a href="{{ route('kas.create') }}"
                   class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white shadow">
                    + Tambah Transaksi
                </a>

            </div>
        </div>

        {{-- ========================= --}}
        {{--  FILTER --}}
        {{-- ========================= --}}
        <div class="glass-card shadow-lg border border-gray-200 rounded-3xl p-5 mb-6">

            <form action="{{ route('kas.index') }}" method="GET"
                class="grid grid-cols-1 md:grid-cols-4 gap-4">

                {{-- BULAN --}}
                <div>
                    <label class="font-semibold text-slate-700">📅 Pilih Bulan</label>
                    <input type="month" name="bulan" value="{{ request('bulan') }}"
                        class="mt-1 w-full rounded-xl border-gray-300 shadow-sm">
                </div>

                {{-- AKUN --}}
                <div>
                    <label class="font-semibold text-slate-700">💼 Akun</label>
                    <select name="akun"
                        class="mt-1 w-full rounded-xl border-gray-300 shadow-sm">
                        <option value="">Semua Akun</option>
                        @foreach($akunList as $akun)
                            <option value="{{ $akun }}" {{ request('akun') == $akun ? 'selected' : '' }}>
                                {{ $akun }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- TAMPILKAN --}}
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-xl shadow">
                        <i class="fas fa-eye me-1"></i> Tampilkan
                    </button>
                </div>

                {{-- EXPORT PDF --}}
                <div class="flex items-end">
                    <a href="{{ route('kas.exportPdf', request()->query()) }}"
                        class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-xl shadow">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </a>
                </div>

            </form>
        </div>

        {{-- ========================= --}}
        {{--  RINGKASAN --}}
        {{-- ========================= --}}
        <div class="glass-card shadow-lg border border-gray-200 rounded-3xl p-5 mb-6">

            <h4 class="font-bold text-slate-700 mb-4">
                📊 Ringkasan Transaksi
                @if(request('bulan'))
                    Bulan {{ \Carbon\Carbon::parse(request('bulan'))->translatedFormat('F Y') }}
                @endif
                @if(request('akun'))
                    — Akun: {{ request('akun') }}
                @endif
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                {{-- PEMASUKAN --}}
                <div class="p-5 rounded-2xl bg-green-100 shadow text-center">
                    <h6 class="text-green-700 font-bold">Pemasukan</h6>
                    <p class="text-2xl font-extrabold text-green-900">
                        Rp{{ number_format($totalMasuk, 0, ',', '.') }}
                    </p>
                </div>

                {{-- PENGELUARAN --}}
                <div class="p-5 rounded-2xl bg-red-100 shadow text-center">
                    <h6 class="text-red-700 font-bold">Pengeluaran</h6>
                    <p class="text-2xl font-extrabold text-red-900">
                        Rp{{ number_format($totalKeluar, 0, ',', '.') }}
                    </p>
                </div>

                {{-- SALDO --}}
                <div class="p-5 rounded-2xl bg-blue-100 shadow text-center">
                    <h6 class="text-blue-700 font-bold">Saldo Akhir</h6>
                    <p class="text-2xl font-extrabold text-blue-900">
                        Rp{{ number_format($saldoRingkasan, 0, ',', '.') }}
                    </p>
                </div>

            </div>
        </div>

        {{-- ========================= --}}
        {{--  TABLE --}}
        {{-- ========================= --}}
        <div class="glass-card shadow-xl border border-gray-200 rounded-3xl p-5">

            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-sm">

                    <thead class="bg-blue-600 text-white">
                        <tr>
                            <th class="p-3 text-center">No</th>
                            <th class="p-3 text-left">Tanggal</th>
                            <th class="p-3 text-left">Keterangan</th>
                            <th class="p-3">Jenis</th>
                            <th class="p-3">Jumlah</th>
                            <th class="p-3">Akun</th>
                            <th class="p-3">Saldo</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">

                        @forelse($kas as $i => $item)

                            @php
                                $isMasuk = strtolower($item->jenis_transaksi) === 'masuk';
                                $jumlahClass = $isMasuk ? 'text-green-600' : 'text-red-600';
                                $saldoClass = $item->saldo >= 0 ? 'text-green-600' : 'text-red-600';
                            @endphp

                            <tr class="bg-white hover:bg-blue-50">
                                <td class="p-3 text-center">{{ $i + 1 }}</td>

                                <td class="p-3">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                                </td>

                                <td class="p-3 max-w-xs whitespace-normal">
                                    {{ $item->keterangan }}
                                </td>

                                <td class="p-3 font-bold {{ $jumlahClass }}">
                                    {{ ucfirst($item->jenis_transaksi) }}
                                </td>

                                <td class="p-3 font-bold {{ $jumlahClass }}">
                                    Rp{{ number_format($item->jumlah, 0, ',', '.') }}
                                </td>

                                <td class="p-3">{{ $item->akun ?? '-' }}</td>

                                <td class="p-3 font-bold {{ $saldoClass }}">
                                    Rp{{ number_format($item->saldo, 0, ',', '.') }}
                                </td>

                                <td class="p-3 text-center">

                                    <a href="{{ route('kas.show', $item->id) }}"
                                       class="px-3 py-1 bg-sky-600 hover:bg-sky-700 text-white rounded-lg text-xs">
                                        Detail
                                    </a>

                                    <a href="{{ route('kas.edit', $item->id) }}"
                                       class="px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-xs">
                                        Edit
                                    </a>

                                    <form action="{{ route('kas.destroy', $item->id) }}" method="POST"
                                          class="inline">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Hapus data ini?')"
                                                class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs">
                                            Hapus
                                        </button>
                                    </form>

                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="8" class="p-5 text-center text-gray-500">
                                    Belum ada data kas.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- TOTAL SALDO --}}
            <p class="mt-6 text-lg font-bold text-slate-800">
                💵 Total Saldo Akhir: Rp{{ number_format($saldo, 0, ',', '.') }}
            </p>

        </div>

        {{-- RESET SALDO --}}
        <div class="mt-6">
            <a href="{{ route('kas.resetSaldo') }}"
               onclick="return confirm('Hitung ulang saldo dari awal?')"
               class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl shadow">
                Reset Saldo
            </a>
        </div>

    </div>

</main>

{{-- ========================= --}}
{{--  JS MOBILE MENU --}}
{{-- ========================= --}}
<script>
document.getElementById("mobileMenuBtn").addEventListener("click", () => {
    document.getElementById("sidenav-main")?.classList.toggle("hidden");
});
</script>

</x-app-layout>
