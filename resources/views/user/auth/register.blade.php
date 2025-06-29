@extends('user.components.main')

@section('title', 'Registrasi Akun Baru')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 90vh;">
            <div class="col-md-9 col-lg-7 col-xl-6">
                <div class="card shadow-lg border-0 rounded-lg my-5">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" width="80" height="80"
                            class="mb-3">
                        <h3 class="card-title mb-1 fw-bold">Registrasi Akun Baru</h3>
                        <p class="card-subtitle mb-0">Sistem Informasi Perpustakaan</p>
                    </div>

                    <div class="card-body p-4 p-md-5">

                        @include('admin.components.flash_messages')
                        @include('admin.components.validation_errors')

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="form-floating mb-3">
                                <input id="nis" type="text" class="form-control @error('nis') is-invalid @enderror"
                                    name="nis" value="{{ old('nis') }}" required autocomplete="nis" autofocus
                                    placeholder="NIS">
                                <label for="nis">{{ __('NIS') }}</label>
                                @error('nis')
                                    <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input id="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                    value="{{ old('name') }}" required autocomplete="name" placeholder="Nama Lengkap">
                                <label for="name">{{ __('Nama Lengkap') }}</label>
                                @error('name')
                                    <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" placeholder="Alamat Email">
                                <label for="email">{{ __('Alamat Email') }}</label>
                                @error('email')
                                    <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                @enderror
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-md">
                                    <div class="form-floating">
                                        <input id="class" type="text"
                                            class="form-control @error('class') is-invalid @enderror" name="class"
                                            value="{{ old('class') }}" placeholder="Kelas">
                                        <label for="class">{{ __('Kelas') }}</label>
                                        @error('class')
                                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-floating">
                                        <input id="major" type="text"
                                            class="form-control @error('major') is-invalid @enderror" name="major"
                                            value="{{ old('major') }}" placeholder="Jurusan">
                                        <label for="major">{{ __('Jurusan') }}</label>
                                        @error('major')
                                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Mengelompokkan Password dan Konfirmasi Password dalam satu baris --}}
                            <div class="row g-2 mb-4">
                                <div class="col-md">
                                    <div class="form-floating">
                                        <input id="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            required autocomplete="new-password" placeholder="Password">
                                        <label for="password">{{ __('Password') }}</label>
                                        @error('password')
                                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-floating">
                                        <input id="password-confirm" type="password" class="form-control"
                                            name="password_confirmation" required autocomplete="new-password"
                                            placeholder="Konfirmasi Password">
                                        <label for="password-confirm">{{ __('Konfirmasi Password') }}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg fw-bold">
                                    {{ __('Buat Akun') }}
                                </button>
                            </div>

                            <div class="text-center mt-4">
                                <p class="mb-0">Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
                            </div>

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
