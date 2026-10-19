<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Siswa | Formulir Pendaftaran</title>
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
        <h1 class="page__judul">Formulir Pendaftaran Siswa</h1>
        <p class="page__intro">
            Isi data calon siswa dengan benar agar sekolah dapat menyiapkan pendampingan
            yang sesuai. Tekan "Kirim Pendaftaran" untuk melihat ringkasan. Prototipe ini
            hanya memproses dan menampilkan kembali data Anda, tidak menyimpannya.
        </p>

        <form class="kartu form" id="form-daftar" action="{{ route('formregistrasisiswa.proses') }}" method="POST">
            @csrf

            <div class="field">
                <label class="field__label" for="nama_lengkap">Nama lengkap</label>
                <input class="field__input @error('nama_lengkap') field__input--galat @enderror"
                       type="text" id="nama_lengkap" name="nama_lengkap"
                       value="{{ old('nama_lengkap') }}" maxlength="100"
                       autocomplete="name" required>
                @error('nama_lengkap')
                    <p class="field__galat" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label class="field__label" for="tanggal_lahir">Tanggal lahir</label>
                <input class="field__input @error('tanggal_lahir') field__input--galat @enderror"
                       type="date" id="tanggal_lahir" name="tanggal_lahir"
                       value="{{ old('tanggal_lahir') }}"
                       max="{{ now()->format('Y-m-d') }}" required>
                <p class="field__hint field__hint--live" id="preview-usia" hidden></p>
                @error('tanggal_lahir')
                    <p class="field__galat" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label class="field__label" for="jenis_kelamin">Jenis kelamin</label>
                <select class="field__input @error('jenis_kelamin') field__input--galat @enderror"
                        id="jenis_kelamin" name="jenis_kelamin" required>
                    <option value="" disabled @selected(old('jenis_kelamin') === null) hidden>Pilih jenis kelamin</option>
                    <option value="Laki-laki" @selected(old('jenis_kelamin') === 'Laki-laki')>Laki-laki</option>
                    <option value="Perempuan" @selected(old('jenis_kelamin') === 'Perempuan')>Perempuan</option>
                </select>
                @error('jenis_kelamin')
                    <p class="field__galat" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label class="field__label" for="jenis_disabilitas">Jenis disabilitas</label>
                <select class="field__input @error('jenis_disabilitas') field__input--galat @enderror"
                        id="jenis_disabilitas" name="jenis_disabilitas" required>
                    <option value="" disabled @selected(old('jenis_disabilitas') === null) hidden>Pilih jenis disabilitas</option>
                    <option value="Tunanetra" @selected(old('jenis_disabilitas') === 'Tunanetra')>Tunanetra</option>
                    <option value="Tunarungu" @selected(old('jenis_disabilitas') === 'Tunarungu')>Tunarungu</option>
                    <option value="Tunawicara" @selected(old('jenis_disabilitas') === 'Tunawicara')>Tunawicara</option>
                    <option value="Tunadaksa" @selected(old('jenis_disabilitas') === 'Tunadaksa')>Tunadaksa</option>
                    <option value="Tunagrahita" @selected(old('jenis_disabilitas') === 'Tunagrahita')>Tunagrahita</option>
                    <option value="Autisme" @selected(old('jenis_disabilitas') === 'Autisme')>Autisme</option>
                    <option value="Lainnya" @selected(old('jenis_disabilitas') === 'Lainnya')>Lainnya</option>
                </select>
                @error('jenis_disabilitas')
                    <p class="field__galat" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div class="field" id="field-disabilitas-lainnya" hidden>
                <label class="field__label" for="jenis_disabilitas_lainnya">Sebutkan jenis disabilitas</label>
                <input class="field__input @error('jenis_disabilitas_lainnya') field__input--galat @enderror"
                       type="text" id="jenis_disabilitas_lainnya" name="jenis_disabilitas_lainnya"
                       value="{{ old('jenis_disabilitas_lainnya') }}" maxlength="100"
                       disabled>
                @error('jenis_disabilitas_lainnya')
                    <p class="field__galat" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label class="field__label" for="kelas">Kelas</label>
                <input class="field__input @error('kelas') field__input--galat @enderror"
                       type="text" id="kelas" name="kelas"
                       value="{{ old('kelas') }}" maxlength="50"
                       placeholder="Contoh: 7A" required>
                @error('kelas')
                    <p class="field__galat" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label class="field__label" for="nama_orang_tua">Nama orang tua</label>
                <input class="field__input @error('nama_orang_tua') field__input--galat @enderror"
                       type="text" id="nama_orang_tua" name="nama_orang_tua"
                       value="{{ old('nama_orang_tua') }}" maxlength="100"
                       placeholder="Nama ayah, ibu, atau wali" required>
                @error('nama_orang_tua')
                    <p class="field__galat" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label class="field__label" for="kontak_orang_tua">Kontak orang tua</label>
                <input class="field__input @error('kontak_orang_tua') field__input--galat @enderror"
                       type="tel" id="kontak_orang_tua" name="kontak_orang_tua"
                       value="{{ old('kontak_orang_tua') }}" maxlength="30"
                       inputmode="tel" autocomplete="tel"
                       placeholder="Contoh: 0812-3456-7890" required>
                @error('kontak_orang_tua')
                    <p class="field__galat" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <button class="tombol" type="submit" id="tombol-kirim">Kirim Pendaftaran</button>
        </form>
    </main>

    <footer class="site-footer">
        <p>Prototipe Registrasi Siswa untuk praktikum PABW. Data pendaftaran tidak disimpan secara permanen.</p>
    </footer>

    <script src="{{ asset('js/formregistrasisiswa.js') }}"></script>
</body>
</html>
