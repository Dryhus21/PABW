<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaporBanjir | Formulir Laporan Banjir</title>
    <link rel="stylesheet" href="{{ asset('css/laporbanjir.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="site-header__dalam">
            <p class="site-header__instansi">BPBD Kabupaten Bandung</p>
            <p class="site-header__brand">LaporBanjir</p>
        </div>
    </header>

    <main class="page">
        <h1 class="page__judul">Formulir Laporan Banjir</h1>
        <p class="page__intro">
            Laporkan kejadian banjir di wilayah Anda agar dapat ditindaklanjuti petugas BPBD.
            Isi data berikut, lalu tekan "Kirim Laporan". Prototipe ini hanya memproses dan
            menampilkan kembali data Anda, tidak menyimpannya.
        </p>

        <form class="kartu form" id="form-lapor" action="{{ route('laporbanjir.proses') }}" method="POST">
            @csrf

            <div class="field">
                <label class="field__label" for="nama">Nama pelapor</label>
                <input class="field__input @error('nama') field__input--galat @enderror"
                       type="text" id="nama" name="nama"
                       value="{{ old('nama') }}" maxlength="100"
                       autocomplete="name" required>
                @error('nama')
                    <p class="field__galat" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label class="field__label" for="kecamatan">Kecamatan lokasi kejadian</label>
                <input class="field__input @error('kecamatan') field__input--galat @enderror"
                       type="text" id="kecamatan" name="kecamatan"
                       value="{{ old('kecamatan') }}" maxlength="100"
                       placeholder="Contoh: Soreang" required>
                @error('kecamatan')
                    <p class="field__galat" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label class="field__label" for="desa">Desa atau kelurahan</label>
                <input class="field__input @error('desa') field__input--galat @enderror"
                       type="text" id="desa" name="desa"
                       value="{{ old('desa') }}" maxlength="100"
                       placeholder="Nama desa atau kelurahan" required>
                @error('desa')
                    <p class="field__galat" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label class="field__label" for="tinggi_genangan">Tinggi genangan air (cm)</label>
                <div class="field__satuan">
                    <input class="field__input field__input--angka @error('tinggi_genangan') field__input--galat @enderror"
                           type="number" id="tinggi_genangan" name="tinggi_genangan"
                           value="{{ old('tinggi_genangan') }}" min="0" max="1000" step="1"
                           inputmode="numeric" required>
                    <span class="field__unit" aria-hidden="true">cm</span>
                </div>
                <p class="field__hint">Perkiraan tinggi air di lokasi kejadian. Contoh: 40.</p>
                <p class="field__hint field__hint--live" id="preview-kategori" hidden></p>
                @error('tinggi_genangan')
                    <p class="field__galat" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <button class="tombol" type="submit" id="tombol-kirim">Kirim Laporan</button>
        </form>
    </main>

    <footer class="site-footer">
        <p>Prototipe LaporBanjir untuk praktikum PABW. Data laporan tidak disimpan secara permanen.</p>
    </footer>

    <script src="{{ asset('js/laporbanjir.js') }}"></script>
</body>
</html>
