<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('LaporBanjir') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <h1 class="text-2xl font-bold text-gray-900">
                    Sistem Pelaporan Banjir BPBD Kabupaten Bandung
                </h1>

                <p class="mt-4 max-w-2xl text-gray-600">
                    Warga dapat mengirim laporan banjir tanpa login. Petugas BPBD perlu login untuk melihat dan mengelola daftar laporan.
                </p>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('laporan.create') }}"
                       class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Kirim Laporan Banjir
                    </a>

                    @auth
                        <a href="{{ route('laporan.index') }}"
                           class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Daftar Laporan
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Login Petugas
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
