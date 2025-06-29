@extends('user.components.main')

@section('title', 'Login Siswa')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
            <div class="col-md-7 col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" width="80" height="80"
                            class="mb-3">
                        <h3 class="card-title mb-1 fw-bold">Login Anggota</h3>
                        <p class="card-subtitle mb-0">Sistem Informasi Perpustakaan</p>
                    </div>
                    <div class="card-body p-4 p-md-5">

                        @include('admin.components.flash_messages')
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                Login Gagal. Periksa kembali NIS dan Password Anda.
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-floating mb-3">
                                <input id="nis" type="text" class="form-control @error('nis') is-invalid @enderror"
                                    name="nis" value="{{ old('nis') }}" required autocomplete="nis" autofocus
                                    placeholder="NIS">
                                <label for="nis">{{ __('NIS') }}</label>
                                @error('nis')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    autocomplete="current-password" placeholder="Password">
                                <label for="password">{{ __('Password') }}</label>
                                @error('password')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        {{ __('Ingat Saya') }}
                                    </label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}">
                                        {{ __('Lupa Password?') }}
                                    </a>
                                @endif
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg fw-bold">
                                    {{ __('Login') }}
                                </button>
                            </div>

                            @if (Route::has('register'))
                                <p class="text-center mt-4 mb-0">Belum punya akun? <a href="{{ route('register') }}">Daftar
                                        di sini</a></p>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        body {
            background-color: #f0f2f5;
        }
    </style>
@endsection
