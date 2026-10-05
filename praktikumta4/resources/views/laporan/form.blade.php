@extends('layouts.app')

@section('title', 'Form Pelaporan')

@section('content')
    <h2>Form Pelaporan Banjir</h2>

    <form action="{{ route('laporan.store') }}" method="POST">
        @csrf

        <p>
            <label>Nama Pelapor</label><br>
            <input type="text" name="nama_pelapor">
        </p>

        <p>
            <label>Lokasi Kejadian</label><br>
            <input type="text" name="lokasi">
        </p>

        <p>
            <label>Tinggi Genangan Air (cm)</label><br>
            <input type="number" name="tinggi_genangan">
        </p>

        <p>
            <label>Tanggal Kejadian</label><br>
            <input type="date" name="tanggal_kejadian">
        </p>

        <button type="submit">Kirim Laporan</button>
    </form>
@endsection
