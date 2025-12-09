<x-app-layout>
        <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Data Kambing
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('kambing.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="kode" class="block text-gray-700 text-sm font-bold mb-2">Kode Kambing</label>
                        <input type="text" name="kode" id="kode" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="nama" class="block text-gray-700 text-sm font-bold mb-2">Nama Kambing</label>
                        <input type="text" name="nama" id="nama" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="jumlah" class="block text-gray-700 text-sm font-bold mb-2">Jumlah</label>
                        <input type="number" name="jumlah" id="jumlah" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="berat_awal" class="block text-gray-700 text-sm font-bold mb-2">Berat Awal (kg)</label>
                        <input type="number" step="0.01" name="berat_awal" id="berat_awal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="konsumsi_pakan" class="block text-gray-700 text-sm font-bold mb-2">Konsumsi Pakan</label>
                        <input type="text" name="konsumsi_pakan" id="konsumsi_pakan" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="harga_beli" class="block text-gray-700 text-sm font-bold mb-2">Harga Beli (Rp)</label>
                        <input type="number" name="harga_beli" id="harga_beli" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="harga_jual" class="block text-gray-700 text-sm font-bold mb-2">Harga Jual (Rp)</label>
                        <input type="number" name="harga_jual" id="harga_jual" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-4">
                        <label for="jenis_transaksi" class="block text-gray-700 text-sm font-bold mb-2">Jenis Transaksi</label>
                        <select name="jenis_transaksi" id="jenis_transaksi" class="block w-full mt-1 rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            <option value="Pemasukan">Pemasukan</option>
                            <option value="Pengeluaran">Pengeluaran</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="deskripsi" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </main>
</x-app-layout>
