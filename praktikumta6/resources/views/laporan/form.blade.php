<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Form Pelaporan Banjir') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('laporan.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="nama_pelapor" :value="__('Nama Pelapor')" />
                        <x-text-input id="nama_pelapor" name="nama_pelapor" type="text" class="mt-1 block w-full"
                                      :value="old('nama_pelapor')" required autofocus />
                        <x-input-error :messages="$errors->get('nama_pelapor')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="lokasi" :value="__('Lokasi Kejadian')" />
                        <x-text-input id="lokasi" name="lokasi" type="text" class="mt-1 block w-full"
                                      :value="old('lokasi')" required />
                        <x-input-error :messages="$errors->get('lokasi')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="tinggi_genangan" :value="__('Tinggi Genangan Air (cm)')" />
                        <x-text-input id="tinggi_genangan" name="tinggi_genangan" type="number" min="1" class="mt-1 block w-full"
                                      :value="old('tinggi_genangan')" required />
                        <x-input-error :messages="$errors->get('tinggi_genangan')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="tanggal_kejadian" :value="__('Tanggal Kejadian')" />
                        <x-text-input id="tanggal_kejadian" name="tanggal_kejadian" type="date" class="mt-1 block w-full"
                                      :value="old('tanggal_kejadian')" required />
                        <x-input-error :messages="$errors->get('tanggal_kejadian')" class="mt-2" />
                    </div>

                    <x-primary-button>
                        {{ __('Kirim Laporan') }}
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
