<div style="border: 1px solid #ccc; background-color: #ffffff; padding: 15px; margin-bottom: 15px;">
    <h3 style="margin: 0 0 5px 0;">{{ $laporan['nama'] }}</h3>
    <p style="margin: 0 0 5px 0;">Lokasi: {{ $laporan['lokasi'] }}</p>
    <p style="margin: 0 0 5px 0;">Tinggi Genangan: {{ $laporan['tinggi'] }} cm</p>

    <p style="margin: 0;">
        Status:
        @if($laporan['tinggi'] < 30)
            <strong style="color: green;">Waspada</strong>
        @elseif($laporan['tinggi'] <= 70)
            <strong style="color: orange;">Siaga</strong>
        @else
            <strong style="color: red;">Awas</strong>
        @endif
    </p>
</div>
