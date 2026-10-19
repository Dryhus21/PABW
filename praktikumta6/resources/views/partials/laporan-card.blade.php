<div class="mb-4 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div class="space-y-1 text-gray-700">
            <h3 class="text-lg font-semibold text-gray-900">{{ $laporan->nama_pelapor }}</h3>
            <p class="text-sm">Lokasi: {{ $laporan->lokasi }}</p>
            <p class="text-sm">Tinggi Genangan: {{ $laporan->tinggi_genangan }} cm</p>
            <p class="text-sm">Tanggal Kejadian: {{ $laporan->tanggal_kejadian }}</p>
        </div>

        <div>
            @if($laporan->tinggi_genangan < 30)
                <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-800">Waspada</span>
            @elseif($laporan->tinggi_genangan <= 70)
                <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-sm font-semibold text-amber-800">Siaga</span>
            @else
                <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-sm font-semibold text-red-800">Awas</span>
            @endif
        </div>
    </div>
</div>
