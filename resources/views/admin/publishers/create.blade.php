@extends('admin.components.main')

@section('title', 'Tambah Penerbit Baru')
@section('page-title', 'Tambah Penerbit Baru')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form action="{{ route('admin.publishers.store') }}" method="POST">
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 fw-semibold">Formulir Tambah Penerbit</h6>
                    </div>
                    <div class="card-body p-4">
                        @include('admin.components.flash_messages')
                        @include('admin.components.validation_errors')

                        @include('admin.publishers._form')
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-end border-0 pt-0 pb-3">
                        <a href="{{ route('admin.publishers.index') }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Penerbit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
