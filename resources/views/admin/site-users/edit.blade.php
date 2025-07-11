@extends('admin.components.main')

@section('title', 'Edit Siswa')
@section('page-title', 'Edit Siswa: ' . $siteUser->name)

@section('content')
    <form action="{{ route('admin.site-users.update', $siteUser) }}" method="POST">
        @method('PUT')
        <div class="card shadow-sm rounded-4 border-0">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-semibold">Formulir Edit Siswa</h6>
            </div>
            <div class="card-body">
                @include('admin.components.flash_messages')
                @include('admin.components.validation_errors')

                @include('admin.site-users._form', ['siteUser' => $siteUser])
            </div>
            <div class="card-footer bg-white d-flex justify-content-end border-0 pt-0 pb-3">
                <a href="{{ route('admin.site-users.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </div>
    </form>
@endsection

@section('css')
    <style>
        .section-divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 2rem 0;
            color: #6c757d;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .section-divider::before,
        .section-divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #dee2e6;
        }

        .section-divider:not(:empty)::before {
            margin-right: .75em;
        }

        .section-divider:not(:empty)::after {
            margin-left: .75em;
        }
    </style>
@endsection
