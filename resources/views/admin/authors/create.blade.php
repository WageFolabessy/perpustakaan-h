@extends('admin.components.main')

@section('title', 'Tambah Pengarang Baru')
@section('page-title', 'Tambah Pengarang Baru')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form action="{{ route('admin.authors.store') }}" method="POST">
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 fw-semibold">Formulir Tambah Pengarang</h6>
                    </div>
                    <div class="card-body p-4">
                        @include('admin.components.flash_messages')
                        @include('admin.components.validation_errors')

                        @include('admin.authors._form')
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-end border-0 pt-0 pb-3">
                        <a href="{{ route('admin.authors.index') }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Pengarang</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
