@extends('user.components.main')

@section('title', 'Dashboard')

@section('content')
    <div class="p-4 p-md-5 mb-4 text-white rounded bg-primary shadow-sm">
        <h2 class="display-6 fw-bold">Halo, {{ $user->name }}!</h2>
        <p class="lead my-3">Selamat datang kembali di dasbor Anda. Berikut adalah ringkasan aktivitas perpustakaan Anda.</p>
    </div>

    <div class="row">

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-warning text-white shadow h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-75 text-uppercase small">Peminjaman Aktif</div>
                            <div class="h2 mb-0 fw-bold">{{ $activeBorrowingsCount }}</div>
                        </div>
                        <i class="bi bi-arrow-up-right-square-fill fs-1 text-white-50"></i>
                    </div>
                </div>
                <a href="{{ route('user.borrowings.history') }}" class="card-footer text-white stretched-link">
                    Lihat Detail <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-danger text-white shadow h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-75 text-uppercase small">Lewat Tempo</div>
                            <div class="h2 mb-0 fw-bold">{{ $overdueBorrowingsCount }}</div>
                        </div>
                        <i class="bi bi-calendar-x-fill fs-1 text-white-50"></i>
                    </div>
                </div>
                <a href="{{ route('user.borrowings.history') }}" class="card-footer text-white stretched-link">
                    Lihat Detail <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-info text-white shadow h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-75 text-uppercase small">Booking Aktif</div>
                            <div class="h2 mb-0 fw-bold">{{ $activeBookingsCount }}</div>
                        </div>
                        <i class="bi bi-journal-bookmark-fill fs-1 text-white-50"></i>
                    </div>
                </div>
                <a href="{{ route('user.bookings.index') }}" class="card-footer text-white stretched-link">
                    Lihat Detail <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-dark text-white shadow h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-75 text-uppercase small">Denda Belum Dibayar</div>
                            <div class="h4 mb-0 fw-bold">Rp {{ number_format($unpaidFinesAmount, 0, ',', '.') }}</div>
                        </div>
                        <i class="bi bi-cash-coin fs-1 text-white-50"></i>
                    </div>
                </div>
                <a href="{{ route('user.fines.index') }}" class="card-footer text-white stretched-link">
                    Lihat Detail <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-primary"><i class="bi bi-book-half me-2"></i>Buku yang Sedang Dipinjam</h6>
                    <span class="badge bg-primary rounded-pill">{{ $currentBorrowings->count() }}</span>
                </div>
                <div class="card-body p-0">
                    @if ($currentBorrowings->isEmpty())
                        <div class="p-4 text-center">
                            <i class="bi bi-info-circle fs-3 text-info"></i>
                            <p class="mb-0 mt-2">Anda sedang tidak meminjam buku.</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach ($currentBorrowings as $borrowing)
                                <div class="list-group-item px-3 py-2">
                                    <div class="d-flex w-100 align-items-center">
                                        <img src="{{ $borrowing->bookCopy?->book?->cover_image ? asset('/storage/' . $borrowing->bookCopy->book->cover_image) : asset('assets/images/no-image.png') }}"
                                            alt="{{ $borrowing->bookCopy?->book?->title ?? 'Buku' }}"
                                            style="width: 45px; height: 65px; object-fit: cover; border-radius: 4px;"
                                            class="me-3 shadow-sm">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 text-dark">
                                                {{ $borrowing->bookCopy?->book?->title ?? 'Judul Tidak Diketahui' }}</h6>
                                            <small class="text-muted">Dipinjam:
                                                {{ $borrowing->borrow_date?->isoFormat('D MMM YYYY') ?? '-' }}</small>
                                            <div class="mt-1">
                                                <small>Jatuh Tempo:
                                                    <strong
                                                        class="{{ $borrowing->due_date && \Carbon\Carbon::parse($borrowing->due_date)->startOfDay()->lt(\Carbon\Carbon::today()) ? 'text-danger' : 'text-success' }}">
                                                        {{ $borrowing->due_date?->isoFormat('D MMM YYYY') ?? '-' }}
                                                    </strong>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="card-footer text-center bg-light">
                    <a href="{{ route('user.borrowings.history') }}" class="btn btn-sm btn-outline-primary">Lihat Semua
                        Riwayat</a>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-primary"><i class="bi bi-journal-check me-2"></i>Booking Aktif</h6>
                    <span class="badge bg-primary rounded-pill">{{ $activeBookings->count() }}</span>
                </div>
                <div class="card-body p-0">
                    @if ($activeBookings->isEmpty())
                        <div class="p-4 text-center">
                            <i class="bi bi-info-circle fs-3 text-info"></i>
                            <p class="mb-0 mt-2">Anda tidak memiliki booking buku yang aktif.</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach ($activeBookings as $booking)
                                <div class="list-group-item px-3 py-2">
                                    <div class="d-flex w-100 align-items-center">
                                        <img src="{{ $booking->book->cover_image ? asset('/storage/' . $booking->book->cover_image) : asset('assets/images/no-image.png') }}"
                                            alt="{{ $booking->book?->title ?? 'Buku' }}"
                                            style="width: 45px; height: 65px; object-fit: cover; border-radius: 4px;"
                                            class="me-3 shadow-sm">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 text-dark">
                                                {{ $booking->book?->title ?? 'Judul Tidak Diketahui' }}</h6>
                                            <small class="text-muted">Dipesan:
                                                {{ $booking->booking_date?->isoFormat('D MMM, HH:mm') ?? '-' }}</small>
                                            <div class="mt-1">
                                                <small>Batas Ambil:
                                                    <strong
                                                        class="{{ $booking->expiry_date < now() ? 'text-danger fw-bold' : 'text-success' }}">
                                                        {{ $booking->expiry_date?->isoFormat('D MMM, HH:mm') ?? '-' }}
                                                        @if ($booking->expiry_date < now())
                                                            (Hangus)
                                                        @endif
                                                    </strong>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="card-footer text-center bg-light">
                    <a href="{{ route('user.bookings.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua
                        Booking</a>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('css')
    @parent
    <style>
        .card-footer.stretched-link {
            transition: background-color 0.2s ease-in-out;
        }

        .card-footer.stretched-link:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection
