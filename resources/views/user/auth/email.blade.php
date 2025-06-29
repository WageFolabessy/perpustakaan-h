@extends('user.components.main')

@section('title', 'Reset Password')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
            <div class="col-md-7 col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" width="80" height="80"
                            class="mb-3">
                        <h3 class="card-title mb-1 fw-bold">{{ __('Reset Password') }}</h3>
                        <p class="card-subtitle mb-0">Lupa password? Tidak masalah.</p>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <p class="text-center text-muted mb-4">
                            Masukkan alamat email Anda yang terdaftar. Kami akan mengirimkan link untuk mengatur ulang
                            password Anda.
                        </p>

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @include('admin.components.flash_messages')

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="form-floating mb-3">
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" autofocus
                                    placeholder="Alamat Email">
                                <label for="email">{{ __('Alamat Email') }}</label>
                                @error('email')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg fw-bold">
                                    {{ __('Kirim Link Reset') }}
                                </button>
                            </div>

                            <div class="text-center mt-4">
                                <a href="{{ route('login') }}">Kembali ke Halaman Login</a>
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
