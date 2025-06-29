@extends('admin.components.main')

@section('title', 'Detail Pengarang')
@section('page-title')
    Detail Pengarang
@endsection

@section('content')
    <div class="card shadow-sm rounded-4 border-0">
        <div class="card-header bg-white p-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 fw-semibold">Profil Pengarang: {{ $author->name }}</h6>
            <div>
                <a href="{{ route('admin.authors.edit', $author) }}" class="btn btn-warning" title="Edit Pengarang">
                    <i class="bi bi-pencil-fill me-1"></i> Edit
                </a>
                <a href="{{ route('admin.authors.index') }}" class="btn btn-secondary" title="Kembali ke Daftar">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="detail-item row">
                <div class="col-lg-3 col-md-4 label">Nama Pengarang</div>
                <div class="col-lg-9 col-md-8">{{ $author->name }}</div>
            </div>
            <div class="detail-item row">
                <div class="col-lg-3 col-md-4 label">Bio</div>
                <div class="col-lg-9 col-md-8">{!! nl2br(e($author->bio)) ?: '-' !!}</div>
            </div>

            <div class="section-divider">
                <span>Karya dari Pengarang Ini ({{ $author->books->count() }})</span>
            </div>

            @if ($author->books->isEmpty())
                <div class="alert alert-light text-center">Belum ada karya dari pengarang ini.</div>
            @else
                <ul class="list-group list-group-flush">
                    @foreach ($author->books->take(10) as $book)
                        <li class="list-group-item d-flex justify-content-between align-items-center ps-0">
                            <div>
                                <a href="{{ route('admin.books.show', $book) }}"
                                    class="text-decoration-none fw-semibold">{{ $book->title }}</a>
                                <small class="d-block text-muted">Kategori: {{ $book->category?->name ?? 'N/A' }}</small>
                            </div>
                            <span
                                class="badge bg-primary-subtle text-primary-emphasis rounded-pill">{{ $book->publication_year }}</span>
                        </li>
                    @endforeach
                    @if ($author->books->count() > 10)
                        <li class="list-group-item text-center text-muted ps-0">... dan {{ $author->books->count() - 10 }}
                            karya lainnya.</li>
                    @endif
                </ul>
            @endif
        </div>
        <div class="card-footer bg-white text-muted small">
            Dibuat pada: {{ $author->created_at ? $author->created_at->isoFormat('D MMM YY, HH:mm') : '-' }} | Diperbarui
            pada: {{ $author->updated_at ? $author->updated_at->diffForHumans() : '-' }}
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
