<div style="border: 1px solid #d1d5db; background-color: #ffffff; padding: 15px; margin-bottom: 15px; border-radius: 6px;">
    <h3 style="margin: 0 0 8px;">{{ $laporan->nama_tanaman }}</h3>
    <p style="margin: 4px 0;">Lokasi: {{ $laporan->lokasi }}</p>
    <p style="margin: 4px 0;">Kelembapan Tanah: {{ $laporan->kelembapan_tanah }}%</p>
    <p style="margin: 4px 0;">Suhu Udara: {{ $laporan->suhu_udara }} &deg;C</p>
    <p style="margin: 4px 0;">Kelembapan Udara: {{ $laporan->kelembapan_udara }}%</p>
    <p style="margin: 4px 0;">Status Lahan: <strong>{{ $laporan->status_lahan }}</strong></p>
</div>
