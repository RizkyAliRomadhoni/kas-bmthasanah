<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Transaksi Kas') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('kas.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium">Tanggal</label>
                        <input type="date" name="tanggal" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Keterangan</label>
                        <input type="text" name="keterangan" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Jenis Transaksi</label>
                        <select name="jenis_transaksi" class="w-full border rounded p-2" required>
                            <option value="">-- Pilih --</option>
                            <option value="Masuk">Masuk</option>
                            <option value="Keluar">Keluar</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Jumlah (Rp)</label>
                        <input type="number" name="jumlah" class="w-full border rounded p-2" required>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
