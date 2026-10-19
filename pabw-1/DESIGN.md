# DESIGN.md, LaporBanjir

Arah desain untuk tugas praktikum Modul 2 PABW: aplikasi pelaporan banjir BPBD Kabupaten Bandung (prototipe tanpa database).

- **Produk**: LaporBanjir, formulir pelaporan banjir untuk warga.
- **Audiens**: warga Kabupaten Bandung yang melapor kejadian banjir, mayoritas dari ponsel, kondisi bisa tergesa.
- **Identitas**: layanan publik resmi daerah, bukan produk komersial.
- **Kepribadian**: tenang, lugas, terpercaya. Tidak menakut-nakuti, tidak memancing.
- **Mood**: resmi khas layanan sipil, terang, mudah dibaca sekali lihat.
- **Dial**: ENERGY 1 / RHYTHM 1 / MOTION 1 (layanan publik; kejelasan di atas segalanya).

## Palet (2 warna inti + 1 aksen + warna status fungsional)

- Biru tinta dalam `#123B5C`: warna inti. Alasan: mengacu air dan seragam resmi instansi, sekaligus pembeda identitas dari biru default AI.
- Netral kertas `#F5F7F8` + teks tinta `#1C2B33`: alasan: kertas terang khas layanan publik, kontras maksimal.
- Aksen amber gelap `#92400E`: hanya pada tombol kirim, indikator kategori, dan cincin fokus. Alasan: satu aksen pada momen kunci (mengirim laporan, membaca tinggi air).
- Status fungsional: hijau `#1B6E4A` (sukses), merah `#B3261E` (galat validasi). Alasan: menandai keadaan nyata, bukan dekorasi.

## Tipografi

- Stack sistem (Segoe UI, Roboto, Helvetica, Arial). Alasan: demo berjalan offline via XAMPP tanpa CDN, dan keterbacaan tinggi khas layanan publik.
- Hierarki lewat ukuran dan tebal, tanpa monospace, tanpa huruf kapital ber-traking lebar.

## Motif identitas

- Skala genangan: mistar horizontal 0, 30, 70, 100+ cm dengan penunjuk posisi air. Alasan: elemen yang spesifik produk banjir, dipakai di halaman konfirmasi untuk membantu warga membaca tinggi genangan.

## Gerak

- Hanya hover dan focus. Alasan: MOTION 1, layanan darurat tidak butuh hiburan gerak.

## Prinsip konten

- Semua teks jujur: halaman konfirmasi menyatakan terbuka bahwa prototipe tidak menyimpan data.
- Tanpa data palsu: tidak ada statistik, testimoni, atau jumlah laporan fiktif.
- Nama instansi dan logo: cukup nama teks "LaporBanjir, BPBD Kabupaten Bandung" tanpa aset logo buatan.
