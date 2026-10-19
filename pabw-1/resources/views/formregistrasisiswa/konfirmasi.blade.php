<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Siswa | Konfirmasi Pendaftaran</title>
    <link rel="stylesheet" href="{{ asset('css/formregistrasisiswa.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="site-header__dalam">
            <p class="site-header__instansi">Sekolah Inklusif</p>
            <p class="site-header__brand">Registrasi Siswa</p>
        </div>
    </header>

    <main class="page">
        <p class="status status--ok" role="status">Pendaftaran berhasil diproses.</p>
        <h1 class="page__judul">Konfirmasi Pendaftaran</h1>
        <p class="page__intro">
            Terima kasih, {{ $pendaftaran['nama'] }}. Berikut ringkasan data pendaftaran
            yang Anda kirimkan.
        </p>

        <div class="kartu ringkasan">
            <dl class="ringkasan__daftar">
                <div class="ringkasan__baris">
                    <dt>Nomor referensi</dt>
                    <dd>{{ $pendaftaran['referensi'] }}</dd>
                </div>
                <div class="ringkasan__baris">
                    <dt>Nama lengkap</dt>
                    <dd>{{ $pendaftaran['nama'] }}</dd>
                </div>
                <div class="ringkasan__baris">
                    <dt>Tanggal lahir</dt>
                    <dd>{{ $pendaftaran['tanggal_lahir'] }} ({{ $pendaftaran['usia'] }} tahun)</dd>
                </div>
                <div class="ringkasan__baris">
                    <dt>Jenis kelamin</dt>
                    <dd>{{ $pendaftaran['jenis_kelamin'] }}</dd>
                </div>
                <div class="ringkasan__baris">
                    <dt>Kelas</dt>
                    <dd>{{ $pendaftaran['kelas'] }}</dd>
                </div>
                <div class="ringkasan__baris">
                    <dt>Nama orang tua</dt>
                    <dd>{{ $pendaftaran['orang_tua'] }}</dd>
                </div>
                <div class="ringkasan__baris">
                    <dt>Kontak orang tua</dt>
                    <dd>{{ $pendaftaran['kontak'] }}</dd>
                </div>
                <div class="ringkasan__baris">
                    <dt>Waktu pendaftaran</dt>
                    <dd>{{ $pendaftaran['waktu'] }}</dd>
                </div>
            </dl>

            <div class="dukungan">
                <div class="dukungan__kepala">
                    <h2 class="dukungan__judul">Jenis disabilitas</h2>
                </div>
                <p class="badge badge--netral">{{ $pendaftaran['disabilitas'] }}</p>
                <p class="dukungan__ket">
                    Data ini membantu sekolah menyiapkan pendampingan belajar yang sesuai
                    bagi calon siswa.
                </p>
            </div>
        </div>

        <p class="catatan">
            Catatan: prototipe ini tidak menyimpan data. Isi halaman ini hilang setelah
            Anda meninggalkannya.
        </p>

        <a class="tombol tombol--link" href="{{ route('formregistrasisiswa.form') }}">Daftarkan siswa lain</a>
    </main>

    <footer class="site-footer">
        <p>Prototipe Registrasi Siswa untuk praktikum PABW. Data pendaftaran tidak disimpan secara permanen.</p>
    </footer>
</body>
</html>
