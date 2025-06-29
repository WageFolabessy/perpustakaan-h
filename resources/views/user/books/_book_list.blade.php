@section('css')
    @parent
    <style>
        .book-card {
            transition: transform .2s ease-in-out, box-shadow .2s ease-in-out;
            background-color: #fff;
        }

        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        }

        .book-cover-wrapper {
            position: relative;
            aspect-ratio: 4 / 4;
            background-color: #f8f9fa;
        }

        .book-cover-wrapper .book-cover {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .book-availability {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 0.75rem;
        }

        .book-title {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            min-height: 2.5em;
            font-size: 0.9rem;
        }
    </style>
@endsection

@forelse ($books as $book)
    <div class="col-xl-3 col-lg-4 col-md-6 col-6 mb-4">
        <div class="card h-100 shadow-sm border-0 book-card">
            <div class="book-cover-wrapper card-img-top">
                <a href="{{ route('catalog.show', $book->slug) }}">
                    <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : asset('assets/images/no-image-book.png') }}"
                        class="book-cover" alt="{{ $book->title }}">
                </a>
                @if (isset($book->available_copies_count))
                    <span
                        class="badge shadow-sm book-availability {{ $book->available_copies_count > 0 ? 'bg-success' : 'bg-secondary' }}">
                        {{ $book->available_copies_count > 0 ? 'Tersedia' : 'Dipinjam' }}
                    </span>
                @endif
            </div>

            <div class="card-body p-3 d-flex flex-column">
                <h6 class="card-title fw-bold book-title flex-grow-1">
                    <a href="{{ route('catalog.show', $book->slug) }}" class="text-dark text-decoration-none">
                        {{ $book->title }}
                    </a>
                </h6>
                <p class="card-text text-muted small mb-2">
                    <i class="bi bi-person"></i> {{ $book->author?->name ?? 'N/A' }}
                </p>
            </div>

            <div class="card-footer bg-transparent border-0 p-3 pt-0">
                <a href="{{ route('catalog.show', $book->slug) }}" class="btn btn-primary w-100">
                    Lihat Detail
                </a>
            </div>
        </div>
    </div>
@empty
    <div class="col-12 vh-50 d-flex flex-column justify-content-center align-items-center text-center text-muted">
        <i class="bi bi-book-half fs-1"></i>
        <h4>Oops! Buku tidak ditemukan.</h4>
        <p>Coba gunakan kata kunci atau filter kategori yang lain.</p>
    </div>
@endforelse

@if (isset($books) && $books instanceof \Illuminate\Pagination\LengthAwarePaginator && !request()->ajax())
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div>
            <small class="text-muted">
                Menampilkan {{ $books->firstItem() }}
                hingga {{ $books->lastItem() }}
                dari {{ $books->total() }} hasil
            </small>
        </div>
        <div>
            {{ $books->withQueryString()->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
@endif
