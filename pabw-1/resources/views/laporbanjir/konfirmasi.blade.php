<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaporBanjir | Konfirmasi Laporan</title>
    <link rel="stylesheet" href="{{ asset('css/laporbanjir.css') }}">
</head>
@php
    $tinggiTampil = rtrim(rtrim(number_format($laporan['tinggi'], 1, ',', '.'), '0'), ',');
    $persen = max(0, min(100, $laporan['tinggi']));
    $posisi = $persen < 12 ? 'kiri' : ($persen > 88 ? 'kanan' : 'tengah');
    $kelasKategori = str_contains($laporan['kategori'], 'Tinggi')
        ? 'badge--tinggi'
        : (str_contains($laporan['kategori'], 'Sedang') ? 'badge--sedang' : 'badge--rendah');
@endphp
<body>
    <header class="site-header">
        <div class="site-header__dalam">
            <p class="site-header__instansi">BPBD Kabupaten Bandung</p>
            <p class="site-header__brand">LaporBanjir</p>
        </div>
    </header>

    <main class="page">
        <p class="status status--ok" role="status">Laporan berhasil diproses.</p>
        <h1 class="page__judul">Konfirmasi Laporan</h1>
        <p class="page__intro">
            Terima kasih, {{ $laporan['nama'] }}. Berikut ringkasan laporan yang Anda kirimkan.
        </p>

        <div class="kartu ringkasan">
            <dl class="ringkasan__daftar">
                <div class="ringkasan__baris">
                    <dt>Nomor referensi</dt>
                    <dd>{{ $laporan['referensi'] }}</dd>
                </div>
                <div class="ringkasan__baris">
                    <dt>Nama pelapor</dt>
                    <dd>{{ $laporan['nama'] }}</dd>
                </div>
                <div class="ringkasan__baris">
                    <dt>Lokasi kejadian</dt>
                    <dd>Desa {{ $laporan['desa'] }}, Kecamatan {{ $laporan['kecamatan'] }}</dd>
                </div>
                <div class="ringkasan__baris">
                    <dt>Waktu lapor</dt>
                    <dd>{{ $laporan['waktu'] }}</dd>
                </div>
            </dl>

            <div class="genangan">
                <div class="genangan__kepala">
                    <h2 class="genangan__judul">Tinggi genangan</h2>
                    <p class="genangan__nilai">{{ $tinggiTampil }} cm</p>
                </div>

                <div class="gauge" role="img"
                     aria-label="Skala tinggi genangan {{ $tinggiTampil }} sentimeter, kategori {{ $laporan['kategori'] }}">
                    <div class="gauge__batang">
                        <span class="gauge__zona gauge__zona--rendah"></span>
                        <span class="gauge__zona gauge__zona--sedang"></span>
                        <span class="gauge__zona gauge__zona--tinggi"></span>
                        <span class="gauge__penunjuk gauge__penunjuk--{{ $posisi }}" style="left: {{ $persen }}%;">
                            <span class="gauge__label">{{ $tinggiTampil }} cm</span>
                        </span>
                    </div>
                    <div class="gauge__skala" aria-hidden="true">
                        <span>0</span>
                        <span>30</span>
                        <span>70</span>
                        <span>100+</span>
                    </div>
                </div>

                <p class="badge {{ $kelasKategori }}">{{ $laporan['kategori'] }}</p>
                <p class="genangan__ket">{{ $laporan['deskripsiKategori'] }}</p>
                <p class="genangan__sumber">Kategori dihitung otomatis dari tinggi genangan yang Anda laporkan.</p>
            </div>
        </div>

        <p class="catatan">
            Catatan: prototipe ini tidak menyimpan laporan. Data di halaman ini hilang
            setelah Anda meninggalkannya.
        </p>

        <a class="tombol tombol--link" href="{{ route('laporbanjir.form') }}">Buat laporan lain</a>
    </main>

    <footer class="site-footer">
        <p>Prototipe LaporBanjir untuk praktikum PABW. Data laporan tidak disimpan secara permanen.</p>
    </footer>
</body>
</html>
