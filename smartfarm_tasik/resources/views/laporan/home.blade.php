@extends('layouts.main')

@section('title', 'Daftar Laporan')

@section('content')
    <h2>Daftar Laporan SmartFarm</h2>

    @forelse($laporan as $item)
        @include('partials.laporan-card', ['laporan' => $item])
    @empty
        <x-alert type="error" message="Belum ada laporan smartfarm." />
    @endforelse
@endsection


