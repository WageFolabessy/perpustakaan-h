@extends('admin.components.main')

@section('title', 'Edit Profil Saya')
@section('page-title', 'Edit Profil Saya')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-body p-4">

                        <div class="profile-header text-center pb-4 border-bottom">
                            <div class="profile-avatar-xl mx-auto">
                                <span>{{ strtoupper(substr($adminUser->name, 0, 1)) }}</span>
                            </div>
                            <h4 class="mt-3 mb-0 fw-bold">{{ $adminUser->name }}</h4>
                            <p class="text-muted mb-0">NIP: {{ $adminUser->nip }}</p>
                        </div>

                        @include('admin.components.flash_messages')

                        <ul class="nav nav-tabs nav-tabs-bordered justify-content-center pt-3" id="profileTab"
                            role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="profile-edit-tab" data-bs-toggle="tab"
                                    data-bs-target="#profile-edit" type="button" role="tab"
                                    aria-controls="profile-edit" aria-selected="true">Edit Detail Profil</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="password-change-tab" data-bs-toggle="tab"
                                    data-bs-target="#password-change" type="button" role="tab"
                                    aria-controls="password-change" aria-selected="false">Ubah Password</button>
                            </li>
                        </ul>

                        <div class="tab-content pt-4">

                            <div class="tab-pane fade show active" id="profile-edit" role="tabpanel"
                                aria-labelledby="profile-edit-tab">

                                @include('admin.components.validation_errors', [
                                    'errorBag' => 'updateProfileInformation',
                                ])

                                <div class="mb-4">
                                    <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $adminUser->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="email" class="form-label fw-semibold">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', $adminUser->email) }}"
                                        required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="tab-pane fade" id="password-change" role="tabpanel"
                                aria-labelledby="password-change-tab">

                                @include('admin.components.validation_errors', [
                                    'errorBag' => 'updatePassword',
                                ])

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label fw-semibold">Password Baru</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi
                                            Password Baru</label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation">
                                    </div>
                                    <div class="col-12">
                                        <small class="form-text text-muted">Kosongkan jika Anda tidak ingin mengubah
                                            password saat ini.</small>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card-footer bg-white d-flex justify-content-end border-0 pt-0 pb-3">
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
    <style>
        .profile-header {
            position: relative;
        }

        .profile-avatar-xl {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: var(--bs-primary-subtle);
            color: var(--bs-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 3.5rem;
            border: 4px solid #fff;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }

        .nav-tabs-bordered {
            border-bottom: 2px solid #dee2e6;
        }

        .nav-tabs-bordered .nav-link {
            margin-bottom: -2px;
            border: none;
            color: #6c757d;
            border-bottom: 2px solid transparent;
        }

        .nav-tabs-bordered .nav-link:hover,
        .nav-tabs-bordered .nav-link:focus {
            color: var(--bs-primary);
        }

        .nav-tabs-bordered .nav-link.active {
            background-color: transparent;
            color: var(--bs-primary);
            border-bottom: 2px solid var(--bs-primary);
            font-weight: 600;
        }
    </style>
@endsection

@section('script')
@endsection
