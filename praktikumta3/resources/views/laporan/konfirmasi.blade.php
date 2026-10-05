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
