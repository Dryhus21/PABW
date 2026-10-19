(function () {
    var form = document.getElementById('form-lapor');
    var tombol = document.getElementById('tombol-kirim');

    if (form && tombol) {
        form.addEventListener('submit', function () {
            tombol.disabled = true;
            tombol.setAttribute('aria-busy', 'true');
            tombol.textContent = 'Mengirim laporan...';
        });
    }

    var inputTinggi = document.getElementById('tinggi_genangan');
    var preview = document.getElementById('preview-kategori');

    if (inputTinggi && preview) {
        var perbarui = function () {
            var nilai = parseFloat(inputTinggi.value);

            if (isNaN(nilai) || nilai < 0) {
                preview.hidden = true;
                return;
            }

            var kategori;
            if (nilai >= 70) {
                kategori = 'Banjir Tinggi';
            } else if (nilai >= 30) {
                kategori = 'Genangan Sedang';
            } else {
                kategori = 'Genangan Rendah';
            }

            preview.textContent = 'Perkiraan kategori: ' + kategori;
            preview.hidden = false;
        };

        inputTinggi.addEventListener('input', perbarui);
        perbarui();
    }
})();
