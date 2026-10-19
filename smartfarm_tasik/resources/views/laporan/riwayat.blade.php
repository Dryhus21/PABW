@extends('layouts.main')

@section('title', 'Riwayat Laporan')

@section('content')
    <h2>Riwayat Laporan SmartFarm</h2>

    @if (session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif

    @forelse($laporan as $item)
        @include('partials.laporan-card', ['laporan' => $item])
    @empty
        <x-alert type="error" message="Belum ada laporan smartfarm." />
    @endforelse
@endsection
