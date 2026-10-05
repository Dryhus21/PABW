@extends('layouts.app')

@section('title', 'Konfirmasi Laporan')

@section('content')
    <h2>Konfirmasi Laporan</h2>

    <x-alert type="success" message="Laporan berhasil dikirim!" />

    <h3>Data Laporan</h3>
    <p>Nama Pelapor: {{ $laporan->nama_pelapor }}</p>
    <p>Lokasi Kejadian: {{ $laporan->lokasi }}</p>
    <p>Tinggi Genangan Air: {{ $laporan->tinggi_genangan }} cm</p>
    <p>Tanggal Kejadian: {{ $laporan->tanggal_kejadian }}</p>
@endsection
