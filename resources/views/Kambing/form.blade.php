<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($kambing) ? __('Edit Data Kambing') : __('Tambah Data Kambing') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md sm:rounded-lg p-6">
                <form action="{{ isset($kambing) ? route('kambing.update', $kambing->id) : route('kambing.store') }}" method="POST">
                    @csrf
                    @if(isset($kambing))
                        @method('PUT')
                    @endif

                    <div class="mb-4">
                        <x-input-label for="nama" :value="__('Nama Kambing')" />
                        <x-text-input id="nama" name="nama" type="text" class="block w-full mt-1"
                            value="{{ old('nama', $kambing->nama ?? '') }}" required autofocus />
                        <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="jenis" :value="__('Jenis Kambing')" />
                        <x-text-input id="jenis" name="jenis" type="text" class="block w-full mt-1"
                            value="{{ old('jenis', $kambing->jenis ?? '') }}" required />
                        <x-input-error :messages="$errors->get('jenis')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="umur" :value="__('Umur (bulan)')" />
                        <x-text-input id="umur" name="umur" type="number" min="0" class="block w-full mt-1"
                            value="{{ old('umur', $kambing->umur ?? '') }}" required />
                        <x-input-error :messages="$errors->get('umur')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="berat" :value="__('Berat (kg)')" />
                        <x-text-input id="berat" name="berat" type="number" step="0.1" class="block w-full mt-1"
                            value="{{ old('berat', $kambing->berat ?? '') }}" required />
                        <x-input-error :messages="$errors->get('berat')" class="mt-2" />
                    </div>

                    <div class="flex justify-end space-x-2 mt-6">
                        <a href="{{ route('kambing.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md text-gray-800 hover:bg-gray-300">
                            {{ __('Kembali') }}
                        </a>
                        <x-primary-button>
                            {{ isset($kambing) ? __('Update') : __('Simpan') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
