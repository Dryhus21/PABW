<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Konfirmasi Laporan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-6 rounded-md border border-green-200 bg-green-50 p-4 text-green-800">
                    Laporan berhasil dikirim!
                </div>

                <h3 class="mb-4 text-lg font-semibold text-gray-900">Data Laporan</h3>

                <dl class="space-y-2 text-gray-700">
                    <div class="flex flex-wrap gap-2">
                        <dt class="font-medium">Nama Pelapor:</dt>
                        <dd>{{ $laporan->nama_pelapor }}</dd>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <dt class="font-medium">Lokasi Kejadian:</dt>
                        <dd>{{ $laporan->lokasi }}</dd>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <dt class="font-medium">Tinggi Genangan Air:</dt>
                        <dd>{{ $laporan->tinggi_genangan }} cm</dd>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <dt class="font-medium">Tanggal Kejadian:</dt>
                        <dd>{{ $laporan->tanggal_kejadian }}</dd>
                    </div>
                </dl>

                <div class="mt-6">
                    <a href="{{ route('laporan.create') }}"
                       class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Kirim Laporan Lain
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
