<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-gray-900">
                    Anda berhasil login sebagai <strong>{{ Auth::user()->name }}</strong>.
                </p>

                <p class="mt-2 text-sm text-gray-600">
                    Gunakan menu di atas untuk membuka daftar laporan atau profil petugas.
                </p>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('laporan.index') }}"
                       class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Buka Daftar Laporan
                    </a>

                    <a href="{{ route('profile.edit') }}"
                       class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Lihat Profil
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
