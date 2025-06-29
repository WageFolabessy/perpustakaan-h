<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - {{ config('app.name', 'SIMPerpus') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    <link href="{{ asset('assets/admin/vendor/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/vendor/bootstrap-icons.css') }}" rel="stylesheet">
    <style>
        body,
        html {
            height: 100%;
        }

        body {
            background-image: url('https://images.unsplash.com/photo-1521587760476-6c12a4b040da?q=80&w=2070');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .login-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(3px);
        }

        .login-box {
            width: 100%;
            max-width: 420px;
            background: #fff;
            padding: 2.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <div class="login-box">

            <div class="text-center mb-4">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" width="80" height="80"
                    class="mb-3">
                <h3 class="fw-bold">Portal Administrasi</h3>
                <p class="text-muted">Selamat datang kembali, Petugas.</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    NIP atau Password salah. Silakan coba lagi.
                </div>
            @endif

            <form method="POST" action="#">
                @csrf

                <div class="form-floating mb-3">
                    <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip"
                        name="nip" value="{{ old('nip') }}" required autofocus placeholder="NIP">
                    <label for="nip">NIP (Nomor Induk Pegawai)</label>
                    @error('nip')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        name="password" required placeholder="Password">
                    <label for="password">Password</label>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check mb-4">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Ingat Saya</label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('assets/admin/vendor/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
