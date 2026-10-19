(function () {
    var form = document.getElementById('form-daftar');
    var tombol = document.getElementById('tombol-kirim');

    if (form && tombol) {
        form.addEventListener('submit', function () {
            tombol.disabled = true;
            tombol.setAttribute('aria-busy', 'true');
            tombol.textContent = 'Mengirim pendaftaran...';
        });
    }

    var pilihanDisabilitas = document.getElementById('jenis_disabilitas');
    var fieldLainnya = document.getElementById('field-disabilitas-lainnya');
    var inputLainnya = document.getElementById('jenis_disabilitas_lainnya');

    if (pilihanDisabilitas && fieldLainnya && inputLainnya) {
        var sinkron = function () {
            var tampil = pilihanDisabilitas.value === 'Lainnya';
            fieldLainnya.hidden = !tampil;
            inputLainnya.disabled = !tampil;
            inputLainnya.required = tampil;
            if (tampil) {
                inputLainnya.focus();
            } else {
                inputLainnya.value = '';
            }
        };

        pilihanDisabilitas.addEventListener('change', sinkron);
        sinkron();
    }

    var inputLahir = document.getElementById('tanggal_lahir');
    var previewUsia = document.getElementById('preview-usia');

    if (inputLahir && previewUsia) {
        var perbaruiUsia = function () {
            var nilai = inputLahir.value;

            if (!nilai) {
                previewUsia.hidden = true;
                return;
            }

            var lahir = new Date(nilai);
            if (isNaN(lahir.getTime())) {
                previewUsia.hidden = true;
                return;
            }

            var hariIni = new Date();
            var usia = hariIni.getFullYear() - lahir.getFullYear();
            var bulan = hariIni.getMonth() - lahir.getMonth();
            if (bulan < 0 || (bulan === 0 && hariIni.getDate() < lahir.getDate())) {
                usia--;
            }

            if (usia < 0) {
                previewUsia.hidden = true;
                return;
            }

            previewUsia.textContent = 'Usia saat ini: ' + usia + ' tahun';
            previewUsia.hidden = false;
        };

        inputLahir.addEventListener('input', perbaruiUsia);
        inputLahir.addEventListener('change', perbaruiUsia);
        perbaruiUsia();
    }
})();
