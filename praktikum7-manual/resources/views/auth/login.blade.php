
@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Login
                </div>

                <div class="card-body">
                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        {{-- Input Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Email
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                required
                                autofocus>

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Input Password --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Password
                            </label>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                required>

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Ingat Saya --}}
                        <div class="mb-3 form-check">
                            <input
                                type="checkbox"
                                id="remember"
                                name="remember"
                                class="form-check-input"
                                value="1">

                            <label for="remember" class="form-check-label">
                                Ingat saya
                            </label>
                        </div>

                        {{-- Tombol Login --}}
                        <button type="submit" class="btn btn-primary">
                            Login
                        </button>

                        <a href="{{ route('register') }}" class="ms-2">
                            Belum punya akun? Register
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
