@extends('layouts.main')

@section('title', 'Form Pelaporan')

@section('content')
    <h2>Form Pelaporan SmartFarm</h2>

    @if ($errors->any())
        <x-alert type="error" :message="$errors->first()" />
    @endif

    <form action="{{ route('monitoring.store') }}" method="POST">
        @csrf

        <p>
            <label>Nama Tanaman</label><br>
            <input type="text" name="nama_tanaman" value="{{ old('nama_tanaman') }}">
        </p>

        <p>
            <label>Lokasi Lahan</label><br>
            <input type="text" name="lokasi" value="{{ old('lokasi') }}">
        </p>

        <p>
            <label>Kelembapan Tanah (%)</label><br>
            <input type="number" name="kelembapan_tanah" value="{{ old('kelembapan_tanah') }}">
        </p>

        <p>
            <label>Suhu Udara (&deg;C)</label><br>
            <input type="number" step="any" name="suhu_udara" value="{{ old('suhu_udara') }}">
        </p>

        <p>
            <label>Kelembapan Udara (%)</label><br>
            <input type="number" step="any" name="kelembapan_udara" value="{{ old('kelembapan_udara') }}">
        </p>

        <button type="submit">Kirim Laporan</button>
    </form>
@endsection
