@extends('admin.components.main')

@section('title', 'Tambah Buku Baru')
@section('page-title', 'Tambah Buku Baru')

@section('content')
    <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
        <div class="card shadow-sm rounded-4 border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-semibold">Formulir Tambah Buku</h6>
            </div>
            <div class="card-body">
                @include('admin.components.flash_messages')
                @include('admin.components.validation_errors')

                @include('admin.books._form')

                <div class="section-divider">
                    <span>Tambah Eksemplar Awal</span>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="initial_copies" class="form-label fw-semibold">Jumlah Eksemplar</label>
                        <input type="number" class="form-control @error('initial_copies') is-invalid @enderror"
                            id="initial_copies" name="initial_copies" value="{{ old('initial_copies', 1) }}" required
                            min="1">
                        @error('initial_copies')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="copy_code_prefix" class="form-label fw-semibold">Kode Awal Eksemplar (Prefix)</label>
                        <input type="text" class="form-control @error('copy_code_prefix') is-invalid @enderror"
                            id="copy_code_prefix" name="copy_code_prefix" value="{{ old('copy_code_prefix') }}" required
                            placeholder="Contoh: BK/INV/">
                        @error('copy_code_prefix')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="copy_code_start" class="form-label fw-semibold">Nomor Awal</label>
                        <input type="number" class="form-control @error('copy_code_start') is-invalid @enderror"
                            id="copy_code_start" name="copy_code_start" value="{{ old('copy_code_start', 1) }}" required
                            min="1">
                        @error('copy_code_start')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <small class="form-text text-muted">Contoh: Jika prefix 'BK-' dan nomor awal 1, eksemplar akan
                            menjadi BK-1, BK-2, dst.</small>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white d-flex justify-content-end border-0 pt-0 pb-3">
                <a href="{{ route('admin.books.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Buku & Eksemplar</button>
            </div>
        </div>
    </form>
@endsection

@section('css')
    <style>
        .image-upload-wrapper {
            position: relative;
            width: 100%;
            padding-top: 130%;
            border-radius: 0.75rem;
            overflow: hidden;
            background-color: #f8f9fa;
        }

        .image-upload-input {
            display: none;
        }

        .image-upload-label {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 2px dashed #dee2e6;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: border-color 0.2s;
        }

        .image-upload-label:hover {
            border-color: var(--bs-primary);
        }

        .image-upload-input:invalid+.image-upload-label {
            border-color: var(--bs-danger);
        }

        #image-preview {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 10;
        }

        .upload-instructions {
            text-align: center;
            color: #6c757d;
            z-index: 1;
        }

        .upload-instructions.has-image {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 0.5rem 0;
            opacity: 0;
            transition: opacity 0.2s;
            z-index: 20;
        }

        .image-upload-label:hover .upload-instructions.has-image {
            opacity: 1;
        }

        .upload-instructions i {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .upload-instructions p {
            margin-bottom: 0.25rem;
        }

        .section-divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 2.5rem 0;
            color: #6c757d;
        }

        .section-divider::before,
        .section-divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #dee2e6;
        }

        .section-divider:not(:empty)::before {
            margin-right: .5em;
        }

        .section-divider:not(:empty)::after {
            margin-left: .5em;
        }
    </style>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('cover_image');
            const imagePreview = document.getElementById('image-preview');
            const uploadInstructions = document.querySelector('.upload-instructions');

            if (imageInput) {
                imageInput.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        imagePreview.style.display = 'block';
                        reader.onload = function(e) {
                            imagePreview.src = e.target.result;
                            uploadInstructions.style.display =
                                'none';
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
@endsection
