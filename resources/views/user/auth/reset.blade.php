@extends('user.components.main')

@section('title', 'Atur Ulang Password')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
            <div class="col-md-7 col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" width="80" height="80"
                            class="mb-3">
                        <h3 class="card-title mb-1 fw-bold">{{ __('Atur Ulang Password') }}</h3>
                        <p class="card-subtitle mb-0">Buat password baru Anda.</p>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="form-floating mb-3">
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus
                                    placeholder="Alamat Email">
                                <label for="email">{{ __('Alamat Email') }}</label>
                                @error('email')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="new-password" placeholder="Password Baru">
                                <label for="password">{{ __('Password Baru') }}</label>
                                @error('password')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>

                            <div class="form-floating mb-4">
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password"
                                    placeholder="Konfirmasi Password Baru">
                                <label for="password-confirm">{{ __('Konfirmasi Password Baru') }}</label>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg fw-bold">
                                    {{ __('Simpan Password Baru') }}
                                </button>
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