@extends('user.components.main')

@section('title', 'Profil Saya')
@section('page-title', 'Pengaturan Profil Saya')

@section('content')

    @include('admin.components.flash_messages')
    @include('admin.components.validation_errors')

    <div class="row g-4">

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 text-center h-100">
                <div class="card-body d-flex flex-column justify-content-center">
                    <i class="bi bi-person-circle display-1 text-primary mx-auto"></i>
                    <h4 class="card-title mt-3 mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-2">NIS: {{ $user->nis }}</p>
                    <hr>
                    <div class="text-start">
                        <p class="mb-1"><strong class="me-2">Kelas:</strong> {{ $user->class ?? '-' }}</p>
                        <p class="mb-0"><strong class="me-2">Jurusan:</strong> {{ $user->major ?? '-' }}</p>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <small class="text-muted">Anggota Sejak: {{ $user->created_at->isoFormat('D MMMM YYYY') }}</small>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <form action="{{ route('user.profile.update') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom-0">
                        <ul class="nav nav-tabs nav-tabs-primary" id="profileTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="info-tab" data-bs-toggle="tab"
                                    data-bs-target="#info-tab-pane" type="button" role="tab"
                                    aria-controls="info-tab-pane" aria-selected="true">
                                    <i class="bi bi-person-lines-fill me-1"></i> Informasi Profil
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="password-tab" data-bs-toggle="tab"
                                    data-bs-target="#password-tab-pane" type="button" role="tab"
                                    aria-controls="password-tab-pane" aria-selected="false">
                                    <i class="bi bi-key-fill me-1"></i> Ubah Password
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body p-4">
                        <div class="tab-content" id="profileTabContent">
                            <div class="tab-pane fade show active" id="info-tab-pane" role="tabpanel"
                                aria-labelledby="info-tab" tabindex="0">
                                <h5 class="fw-bold mb-3">Data Diri</h5>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', $user->name) }}" required placeholder="Nama Lengkap">
                                    <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email', $user->email) }}" required placeholder="Alamat Email">
                                    <label for="email">Alamat Email <span class="text-danger">*</span></label>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="tab-pane fade" id="password-tab-pane" role="tabpanel" aria-labelledby="password-tab"
                                tabindex="0">
                                <h5 class="fw-bold mb-3">Keamanan Akun</h5>
                                <p class="text-muted small">Kosongkan kolom di bawah ini jika Anda tidak ingin mengubah
                                    password.</p>
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" autocomplete="new-password"
                                        placeholder="Password Baru">
                                    <label for="password">Password Baru</label>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" autocomplete="new-password"
                                        placeholder="Konfirmasi Password Baru">
                                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-end bg-light">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save-fill me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('css')
    @parent
    <style>
        .nav-tabs-primary .nav-link {
            color: var(--bs-secondary-color);
            font-weight: 500;
        }

        .nav-tabs-primary .nav-link.active {
            color: var(--bs-primary);
            font-weight: 600;
            border-color: #dee2e6 #dee2e6 #fff;
        }
    </style>
@endsection
