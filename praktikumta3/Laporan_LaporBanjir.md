# Laporan Praktikum Modul 3 - Blade Template Laravel

## Aplikasi "LaporBanjir" (BPBD Kabupaten Bandung)

---

## 1. Langkah-Langkah Praktikum

Praktikum dikerjakan menggunakan Laravel tanpa database. Data laporan cukup disimpan dalam bentuk array di dalam Controller. Berikut tahapan yang dilakukan secara berurutan.

### 1.1 Membuat Controller

Controller dibuat dengan perintah berikut pada terminal:

```
php artisan make:controller LaporanController
```

Perintah tersebut menghasilkan file `app/Http/Controllers/LaporanController.php`. Controller ini berisi tiga method, yaitu `index()` untuk menampilkan daftar laporan, `create()` untuk menampilkan form, dan `store()` untuk menerima data dari form dan menampilkannya kembali pada halaman konfirmasi.

```php
class LaporanController extends Controller
{
    public function index()
    {
        $laporan = [
            ['nama' => 'Budi Santoso', 'lokasi' => 'Desa Cangkuang, Kecamatan Rancaekek', 'tinggi' => 25],
            ['nama' => 'Siti Aminah',   'lokasi' => 'Kelurahan Dayeuhkolot',               'tinggi' => 50],
            ['nama' => 'Andi Pratama',  'lokasi' => 'Desa Bojongsoang',                    'tinggi' => 90],
            ['nama' => 'Rina Marlina',  'lokasi' => 'Kecamatan Baleendah',                 'tinggi' => 70],
        ];

        return view('laporan.index', compact('laporan'));
    }

    public function create()
    {
        return view('laporan.form');
    }

    public function store(Request $request)
    {
        $laporan = [
            'nama'   => $request->nama,
            'lokasi' => $request->lokasi,
            'tinggi' => $request->tinggi,
        ];

        return view('laporan.konfirmasi', compact('laporan'));
    }
}
```

`compact('laporan')` setara dengan `['laporan' => $laporan]`, sehingga di dalam view data dapat dipanggil dengan variabel `$laporan`. Pada method `store()`, data form diambil dari objek `$request` berdasarkan atribut `name` pada input.

### 1.2 Menambahkan Route

Route didaftarkan pada `routes/web.php` dengan named route agar dapat dipanggil dari navigasi memakai `route()`.

```php
use App\Http\Controllers\LaporanController;

Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
Route::get('/laporan/create', [LaporanController::class, 'create'])->name('laporan.create');
Route::post('/laporan', [LaporanController::class, 'store'])->name('laporan.store');
```

URL `/laporan` menampilkan daftar laporan, `/laporan/create` menampilkan form, dan permintaan POST ke `/laporan` mengirim data pelaporan.

### 1.3 Membuat Layout Utama

Layout utama dibuat pada `resources/views/layouts/app.blade.php`. File ini memuat bagian yang sama pada semua halaman, yaitu header nama sistem, menu navigasi, footer, dan CSS. Bagian yang berbeda antarhalaman ditandai dengan `@yield`.

```blade
<header>
    <h1>LaporBanjir</h1>
    <p>Sistem Pelaporan Banjir BPBD Kabupaten Bandung</p>
    <nav>
        <a href="{{ route('laporan.create') }}">Form Pelaporan</a>
        <a href="{{ route('laporan.index') }}">Daftar Laporan</a>
    </nav>
</header>

<main>
    @yield('content')
</main>
```

`@yield('title')` dan `@yield('content')` adalah placeholder yang nantinya diisi oleh view turunan. Menu navigasi memakai named route sehingga tautan tidak ditulis manual dan tetap konsisten meskipun URL berubah.

### 1.4 Membuat Komponen Alert

Komponen dipakai untuk menampilkan pesan berulang. File dibuat pada `resources/views/components/alert.blade.php` dan otomatis dikenali sebagai `<x-alert>`.

```blade
<div style="border: 2px solid {{ $type === 'error' ? 'red' : 'green' }}; padding: 15px; margin-bottom: 15px;">
    <strong>{{ ucfirst($type) }}</strong> — {{ $message }}
</div>
```

Nilai `$type` dan `$message` diambil dari atribut saat komponen dipanggil. Operator ternary menentukan warna border, sedangkan `ucfirst()` mengubah huruf pertama tipe menjadi kapital.

### 1.5 Membuat Partial Kartu Laporan

Partial dibuat pada `resources/views/partials/laporan-card.blade.php`. Partial ini menampilkan satu laporan dan menentukan status genangan memakai `@if/@elseif/@else`.

```blade
<div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 15px;">
    <h3>{{ $laporan['nama'] }}</h3>
    <p>Lokasi: {{ $laporan['lokasi'] }}</p>
    <p>Tinggi Genangan: {{ $laporan['tinggi'] }} cm</p>

    <p>
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
```

Ketentuan status yang diterapkan: kurang dari 30 cm "Waspada", 30–70 cm "Siaga", dan lebih dari 70 cm "Awas". Variabel `$laporan` dikirim dari view pemanggil melalui `@include`.

### 1.6 Membuat Halaman Form Pelaporan

Halaman form dibuat pada `resources/views/laporan/form.blade.php`. Halaman ini mewarisi layout utama dan menyediakan input nama pelapor, lokasi kejadian, dan tinggi genangan air.

```blade
@extends('layouts.app')

@section('title', 'Form Pelaporan')

@section('content')
    <h2>Form Pelaporan Banjir</h2>

    <form action="{{ route('laporan.store') }}" method="POST">
        @csrf

        <p>
            <label>Nama Pelapor</label><br>
            <input type="text" name="nama">
        </p>
        <p>
            <label>Lokasi Kejadian</label><br>
            <input type="text" name="lokasi">
        </p>
        <p>
            <label>Tinggi Genangan Air (cm)</label><br>
            <input type="number" name="tinggi">
        </p>

        <button type="submit">Kirim Laporan</button>
    </form>
@endsection
```

`@extends('layouts.app')` mewarisi layout, `@section('title', ...)` mengisi judul halaman, dan `@section('content')` mengisi konten. Form dikirim dengan method POST ke named route `laporan.store`.

### 1.7 Membuat Halaman Konfirmasi

Halaman konfirmasi dibuat pada `resources/views/laporan/konfirmasi.blade.php`. Halaman ini menampilkan pesan berhasil memakai komponen `<x-alert>` dan menampilkan kembali data yang baru dikirim.

```blade
@extends('layouts.app')

@section('title', 'Konfirmasi Laporan')

@section('content')
    <h2>Konfirmasi Laporan</h2>

    <x-alert type="success" message="Laporan berhasil dikirim!" />

    <h3>Data Laporan</h3>
    <p>Nama Pelapor: {{ $laporan['nama'] }}</p>
    <p>Lokasi Kejadian: {{ $laporan['lokasi'] }}</p>
    <p>Tinggi Genangan Air: {{ $laporan['tinggi'] }} cm</p>
@endsection
```

### 1.8 Membuat Halaman Daftar Laporan

Halaman daftar dibuat pada `resources/views/laporan/index.blade.php`. Data ditampilkan dengan `@forelse`, dan setiap laporan dirender melalui partial dengan `@include`.

```blade
@extends('layouts.app')

@section('title', 'Daftar Laporan')

@section('content')
    <h2>Daftar Laporan Banjir</h2>

    @forelse($laporan as $item)
        @include('partials.laporan-card', ['laporan' => $item])
    @empty
        <x-alert type="error" message="Belum ada laporan banjir." />
    @endforelse
@endsection
```

`@forelse` digunakan agar ketika data kosong dapat ditampilkan pesan alternatif melalui blok `@empty`, sehingga tidak perlu menambahkan pengecekan terpisah.

### 1.9 Menjalankan Aplikasi

Server dijalankan melalui terminal:

```
php artisan serve
```

Setelah server berjalan, aplikasi dapat diakses pada browser untuk menguji ketiga halaman, yaitu form pelaporan, halaman konfirmasi, dan halaman daftar laporan.

---

## 2. Analisis dan Pembahasan

### 2.1 Alasan Pembagian Layout, Komponen, dan Partial

Struktur tampilan dipisah menjadi beberapa bagian agar kode tidak ditulis berulang dan mudah dirawat.

- **Layout utama (`layouts/app.blade.php`)** menampung bagian yang selalu sama di setiap halaman, yaitu header, navigasi, footer, dan CSS. Dengan pola pewarisan `@extends` dan `@yield`, setiap halaman hanya perlu mengisi bagian yang berbeda. Jika suatu saat warna atau menu ingin diubah, cukup mengubah satu file dan seluruh halaman ikut berubah.
- **Komponen (`<x-alert>`)** dipakai untuk elemen antarmuka kecil yang muncul di banyak tempat dan menerima atribut. Pada aplikasi ini komponen alert dipakai di halaman konfirmasi (pesan sukses) dan halaman daftar (pesan ketika data kosong). Dengan atribut `type` dan `message`, satu file komponen dapat menghasilkan tampilan berbeda tanpa menduplikasi kode.
- **Partial (`@include`)** dipakai untuk memecah bagian halaman yang cukup besar, yaitu kartu laporan. Karena setiap laporan memiliki struktur kartu yang sama, pembuatan partial membuat view daftar laporan tetap ringkas. Perbedaan dengan komponen adalah partial tidak memiliki konsep props/atribut, melainkan langsung menerima variabel melalui argumen `@include`.

### 2.2 Perbedaan Tampilan Sebelum dan Sesudah Menggunakan Blade

Sebelum menggunakan Blade, setiap halaman ditulis sebagai file HTML/PHP utuh. Bagian header, navigasi, dan footer harus disalin ulang di setiap file, sehingga perubahan pada satu bagian harus dilakukan pada semua file. Pada aplikasi ini, halaman juga langsung memuat HTML statis tanpa logika.

Setelah menggunakan Blade, terjadi beberapa perubahan:

- Header, navigasi, footer, dan CSS hanya ditulis satu kali pada layout dan diwariskan dengan `@extends`, `@section`, dan `@yield`.
- Penulisan data menjadi lebih ringkas dengan sintaks `{{ $variabel }}`, yang otomatis melakukan escaping sehingga lebih aman dibandingkan menulis `<?php echo ... ?>` manual.
- Logika tampilan seperti percabangan status dan perulangan daftar laporan ditulis langsung di view memakai directive `@if/@elseif/@else` dan `@forelse`, sehingga view lebih mudah dibaca.
- Tautan navigasi memakai named route `route('laporan.index')`, sehingga URL tidak ditulis manual dan terhindar dari kesalahan penulisan alamat.

### 2.3 Kendala dan Penyelesaian

1. **Error 419 Page Expired saat mengirim form.** Pengiriman data melalui method POST ditolak karena Laravel mensyaratkan token CSRF pada setiap permintaan POST. Masalah diselesaikan dengan menambahkan directive `@csrf` di dalam tag `<form>`, sehingga token ikut terkirim dan permintaan diterima.
2. **Data tidak terbaca di view.** Variabel dari Controller tidak dapat dipakai sebelum dikirim melalui `compact()`. Penyelesaiannya adalah mengirim data dengan `return view('laporan.index', compact('laporan'))` dan memastikan nama variabel pada view sama dengan nama di Controller.
3. **Directive tidak berjalan atau error sintaks.** Directive Blade wajib ditutup dengan benar, misalnya `@if` dengan `@endif`, `@foreach`/`@forelse` dengan `@endforeach`/`@endforelse`. Kesalahan ini diperbaiki dengan memeriksa pasangan setiap directive.
4. **Status genangan salah untuk nilai batas.** Nilai 30 dan 70 cm termasuk kategori "Siaga". Penyelesaiannya adalah menggunakan `@if($tinggi < 30)`, lalu `@elseif($tinggi <= 70)`, dan `@else`, sehingga batas bawah 30 cm dan batas atas 70 cm masuk ke kategori yang tepat.

---

## 3. Kesimpulan

1. Blade Template memudahkan penyusunan tampilan Laravel karena HTML dapat digabungkan dengan directive khusus, seperti `@extends`, `@section`, `@yield`, `@if`, `@forelse`, dan `@include`, sehingga kode tampilan lebih ringkas dan mudah dibaca.
2. Pemisahan layout, komponen, dan partial menghasilkan kode yang tidak berulang. Layout utama menangani bagian yang sama di semua halaman, komponen `<x-alert>` menangani elemen antarmuka dengan atribut, dan partial menangani potongan halaman yang besar. Perubahan satu bagian cukup dilakukan di satu file.
3. Aplikasi "LaporBanjir" berhasil dibangun sebagai prototipe tanpa database dengan tiga halaman, yaitu form pelaporan, halaman konfirmasi yang menampilkan komponen alert, dan halaman daftar laporan yang menampilkan status genangan Waspada, Siaga, dan Awas sesuai tinggi air.
4. Penggunaan named route pada navigasi dan form membuat alur aplikasi (form → konfirmasi → daftar laporan) berjalan dengan baik dan setiap halaman dapat diakses melalui route yang terdefinisi.
5. Kendala teknis seperti error 419 akibat token CSRF dan kesalahan penutupan directive dapat diatasi dengan memahami mekanisme dasar Laravel, sehingga aplikasi dapat berjalan sesuai rancangan.
