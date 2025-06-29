@extends('admin.components.main')

@section('title', 'Detail Kategori')
@section('page-title')
    Detail Kategori
@endsection

@section('content')
    <div class="card shadow-sm rounded-4 border-0">
        <div class="card-header bg-white p-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 fw-semibold">Informasi: {{ $category->name }}</h6>
            <div>
                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning" title="Edit Kategori">
                    <i class="bi bi-pencil-fill me-1"></i> Edit
                </a>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary" title="Kembali ke Daftar">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="detail-item row">
                <div class="col-lg-3 col-md-4 label">Nama Kategori</div>
                <div class="col-lg-9 col-md-8">{{ $category->name }}</div>
            </div>
            <div class="detail-item row">
                <div class="col-lg-3 col-md-4 label">Slug</div>
                <div class="col-lg-9 col-md-8">{{ $category->slug }}</div>
            </div>
            <div class="detail-item row">
                <div class="col-lg-3 col-md-4 label">Deskripsi</div>
                <div class="col-lg-9 col-md-8">{!! nl2br(e($category->description)) ?: '-' !!}</div>
            </div>

            <div class="section-divider">
                <span>Buku dalam Kategori Ini ({{ $category->books->count() }})</span>
            </div>

            @if ($category->books->isEmpty())
                <div class="alert alert-light text-center">Tidak ada buku dalam kategori ini.</div>
            @else
                <ul class="list-group list-group-flush">
                    @foreach ($category->books->take(10) as $book)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('admin.books.show', $book) }}"
                                    class="text-decoration-none fw-semibold">{{ $book->title }}</a>
                                <small class="d-block text-muted">oleh {{ $book->author?->name ?? 'N/A' }}</small>
                            </div>
                            <span
                                class="badge bg-primary-subtle text-primary-emphasis rounded-pill">{{ $book->publication_year }}</span>
                        </li>
                    @endforeach
                    @if ($category->books->count() > 10)
                        <li class="list-group-item text-center text-muted">... dan {{ $category->books->count() - 10 }}
                            buku lainnya.</li>
                    @endif
                </ul>
            @endif
        </div>
        <div class="card-footer bg-white text-muted small">
            Dibuat pada: {{ $category->created_at ? $category->created_at->isoFormat('D MMM YYYY, HH:mm') : '-' }} |
            Diperbarui pada: {{ $category->updated_at ? $category->updated_at->diffForHumans() : '-' }}
        </div>
    </div>
@endsection

@section('css')
    <style>
        .detail-item {
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
            font-size: 0.95rem;
        }

        .detail-item .label {
            font-weight: 600;
            color: #6c757d;
        }

        .section-divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 2rem 0;
            color: #6c757d;
            font-size: .9rem;
            text-transform: uppercase;
            letter-spacing: .5px
        }

        .section-divider::before,
        .section-divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #dee2e6
        }

        .section-divider:not(:empty)::before {
            margin-right: .75em
        }

        .section-divider:not(:empty)::after {
            margin-left: .75em
        }
    </style>
@endsection
