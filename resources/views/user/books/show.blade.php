@extends('user.components.main')

@section('title', $book->title)

@section('content')
    <div class="row g-4 g-lg-5">

        <div class="col-lg-8">

            <div class="row g-4">
                <div class="col-md-4">
                    <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : asset('assets/images/no-image-book-detail.png') }}"
                        class="img-fluid rounded shadow w-100 book-detail-cover" alt="{{ $book->title }}">
                </div>
                <div class="col-md-8 d-flex flex-column justify-content-center">
                    <a href="{{ route('catalog.index', ['category' => $book->category?->id]) }}" class="text-decoration-none">
                        <span class="badge bg-primary rounded-pill mb-2">{{ $book->category?->name ?? 'N/A' }}</span>
                    </a>
                    <h1 class="display-6 fw-bold mb-2">{{ $book->title }}</h1>
                    <p class="fs-5 text-muted mb-3">
                        oleh <span class="fw-semibold">{{ $book->author?->name ?? 'N/A' }}</span>
                    </p>
                </div>
            </div>

            <hr class="my-4">

            <ul class="nav nav-tabs nav-fill mb-3" id="bookTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details-tab-pane"
                        type="button" role="tab" aria-controls="details-tab-pane" aria-selected="true">
                        <i class="bi bi-info-circle me-1"></i> Detail Lengkap
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="synopsis-tab" data-bs-toggle="tab" data-bs-target="#synopsis-tab-pane"
                        type="button" role="tab" aria-controls="synopsis-tab-pane" aria-selected="false">
                        <i class="bi bi-text-left me-1"></i> Sinopsis
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="bookTabContent">
                <div class="tab-pane fade show active" id="details-tab-pane" role="tabpanel" aria-labelledby="details-tab"
                    tabindex="0">
                    <dl class="row">
                        <dt class="col-sm-3">Penerbit</dt>
                        <dd class="col-sm-9">{{ $book->publisher?->name ?? '-' }}</dd>

                        <dt class="col-sm-3">Tahun Terbit</dt>
                        <dd class="col-sm-9">{{ $book->publication_year ?? '-' }}</dd>

                        <dt class="col-sm-3">ISBN</dt>
                        <dd class="col-sm-9">{{ $book->isbn ?? '-' }}</dd>

                        <dt class="col-sm-3">Lokasi Rak</dt>
                        <dd class="col-sm-9">{{ $book->location ?? '-' }}</dd>
                    </dl>
                </div>
                <div class="tab-pane fade" id="synopsis-tab-pane" role="tabpanel" aria-labelledby="synopsis-tab"
                    tabindex="0">
                    <div class="text-muted synopsis-text">
                        {!! nl2br(e($book->synopsis ?: 'Sinopsis tidak tersedia.')) !!}
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <a href="{{ url()->previous(route('catalog.index')) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 position-sticky" style="top: 1.5rem;">
                <div class="card-header bg-light text-center">
                    <h6 class="m-0 fw-bold">Status & Ketersediaan</h6>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-3">
                        <h5 class="fw-bold">Ketersediaan</h5>
                        @if ($totalCopies > 0)
                            <p class="h1 fw-bolder {{ $availableCopiesCount > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $availableCopiesCount }}
                            </p>
                            <p class="text-muted">dari <strong>{{ $totalCopies }}</strong> eksemplar tersedia</p>
                        @else
                            <p class="text-danger fw-bold">Tidak ada eksemplar terdaftar</p>
                        @endif
                    </div>

                    <hr>

                    <div class="mt-3">
                        @auth('web')
                            @if ($userStatus === 'borrowing' && $statusDetails)
                                <div class="alert alert-info d-flex align-items-center" role="alert">
                                    <i class="bi bi-info-circle-fill me-2"></i>
                                    <div>
                                        Anda sedang meminjam buku ini. Jatuh tempo pada:
                                        <strong>{{ \Carbon\Carbon::parse($statusDetails)->isoFormat('dddd, D MMMM YYYY') }}</strong>.
                                    </div>
                                </div>
                            @elseif ($userStatus === 'booked' && $statusDetails)
                                <div class="alert alert-info d-flex align-items-center" role="alert">
                                    <i class="bi bi-journal-bookmark-fill me-2"></i>
                                    <div>
                                        Booking aktif. Batas pengambilan:
                                        <strong>{{ \Carbon\Carbon::parse($statusDetails)->isoFormat('D MMMM YYYY, HH:mm') }}</strong>.
                                    </div>
                                </div>
                            @elseif ($userStatus === 'unavailable')
                                <div class="alert alert-warning text-center" role="alert">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    {{ $statusDetails ?? 'Stok buku ini sedang tidak tersedia.' }}
                                </div>
                            @elseif ($userStatus === 'limit_reached')
                                <div class="alert alert-warning" role="alert">
                                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                                    Anda sudah mencapai batas maksimal booking.
                                </div>
                            @elseif($userStatus === 'inactive')
                                <div class="alert alert-danger text-center" role="alert">
                                    <i class="bi bi-exclamation-octagon-fill me-2"></i>
                                    Akun Anda belum aktif.
                                </div>
                            @endif

                            <div class="d-grid mt-3">
                                @if ($userStatus === 'can_book')
                                    <form action="{{ route('user.bookings.store', $book) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-lg w-100">
                                            <i class="bi bi-journal-bookmark-fill me-1"></i> Booking Buku Ini
                                        </button>
                                    </form>
                                    <small class="text-muted text-center d-block mt-2">
                                        Batas pengambilan: {{ setting('booking_expiry_days', 2) }} hari setelah booking.
                                    </small>
                                @else
                                    <button type="button" class="btn btn-secondary btn-lg" disabled>
                                        <i class="bi bi-journal-x me-1"></i> Tidak Bisa Booking
                                    </button>
                                @endif
                            </div>
                        @else
                            <div class="d-grid mt-3">
                                <a href="{{ route('login') }}?redirect={{ url()->current() }}"
                                    class="btn btn-primary btn-lg">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> Login untuk Booking
                                </a>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .book-detail-cover {
            aspect-ratio: 4 / 4;
            object-fit: cover;
        }

        .synopsis-text {
            line-height: 1.7;
            text-align: justify;
        }

        .nav-tabs .nav-link {
            color: var(--bs-secondary-color);
        }

        .nav-tabs .nav-link.active {
            color: var(--bs-primary);
            font-weight: 600;
        }
    </style>
@endsection
