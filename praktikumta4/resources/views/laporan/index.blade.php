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
