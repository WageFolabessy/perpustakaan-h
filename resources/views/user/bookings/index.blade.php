@extends('user.components.main')

@section('title', 'Booking Saya')
@section('page-title', 'Daftar Booking Buku Saya')

@section('content')

    @include('admin.components.flash_messages')
    @include('admin.components.validation_errors')

    <ul class="nav nav-tabs nav-fill mb-3" id="bookingsTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="active-booking-tab" data-bs-toggle="tab" data-bs-target="#active-booking-pane"
                type="button" role="tab" aria-controls="active-booking-pane" aria-selected="true">
                <i class="bi bi-journal-bookmark-fill me-1"></i> Booking Aktif
                <span class="badge rounded-pill bg-primary ms-1">{{ $activeBookings->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="past-booking-tab" data-bs-toggle="tab" data-bs-target="#past-booking-pane"
                type="button" role="tab" aria-controls="past-booking-pane" aria-selected="false">
                <i class="bi bi-clock-history me-1"></i> Riwayat Booking
                <span class="badge rounded-pill bg-secondary ms-1">{{ $pastBookings->total() }}</span>
            </button>
        </li>
    </ul>

    <div class="tab-content" id="bookingsTabContent">

        <div class="tab-pane fade show active" id="active-booking-pane" role="tabpanel" aria-labelledby="active-booking-tab"
            tabindex="0">
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    @if ($activeBookings->isEmpty())
                        <div class="p-5 text-center">
                            <i class="bi bi-journal-x fs-3 text-info"></i>
                            <p class="mb-0 mt-2">Anda tidak memiliki booking aktif. <a
                                    href="{{ route('catalog.index') }}">Cari buku untuk di-booking?</a></p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach ($activeBookings as $booking)
                                <div class="list-group-item p-3">
                                    <div class="row align-items-center g-3">
                                        <div class="col-md-6 d-flex align-items-center">
                                            <img src="{{ $booking->book?->cover_image ? asset('storage/' . $booking->book->cover_image) : asset('assets/images/no-image-book.png') }}"
                                                alt="{{ $booking->book?->title ?? 'Buku' }}"
                                                class="img-fluid rounded shadow-sm me-3 d-none d-sm-block"
                                                style="width: 60px; height: 85px; object-fit: cover;">
                                            <div>
                                                <a href="{{ route('catalog.show', $booking->book?->slug ?? '#') }}"
                                                    class="text-decoration-none text-dark fw-bold mb-1 d-block">
                                                    {{ $booking->book?->title ?? 'Judul Tidak Diketahui' }}
                                                </a>
                                                <div class="small text-muted">Dipesan:
                                                    {{ $booking->booking_date?->isoFormat('D MMM YY, HH:mm') }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div
                                                class="border rounded p-2 text-center @if ($booking->is_expired) border-danger bg-danger-subtle @elseif($booking->is_expiring_soon) border-warning bg-warning-subtle @endif">
                                                <div class="small text-muted">Batas Pengambilan</div>
                                                <div
                                                    class="fw-bold @if ($booking->is_expired) text-danger @elseif($booking->is_expiring_soon) text-warning-emphasis @endif">
                                                    {{ $booking->expiry_date?->isoFormat('D MMM YY, HH:mm') }}
                                                </div>
                                                @if ($booking->is_expired)
                                                    <span class="badge bg-danger mt-1">Kadaluarsa</span>
                                                @elseif($booking->is_expiring_soon)
                                                    <span class="badge bg-warning text-dark mt-1">Segera Ambil</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-2 text-md-end">
                                            <button type="button" class="btn btn-outline-danger btn-sm w-100"
                                                data-bs-toggle="modal"
                                                data-bs-target="#userCancelModal-{{ $booking->id }}">
                                                <i class="bi bi-x-circle"></i> Batal
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="past-booking-pane" role="tabpanel" aria-labelledby="past-booking-tab" tabindex="0">
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    @if ($pastBookings->isEmpty())
                        <div class="p-5 text-center">
                            <i class="bi bi-collection fs-3 text-secondary"></i>
                            <p class="mb-0 mt-2">Belum ada riwayat booking.</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach ($pastBookings as $booking)
                                <div class="list-group-item p-3">
                                    <div class="row align-items-center g-3">
                                        <div class="col-md-5">
                                            <a href="{{ route('catalog.show', $booking->book?->slug ?? '#') }}"
                                                class="text-decoration-none text-dark fw-bold">
                                                {{ $booking->book?->title ?? 'Judul Tidak Diketahui' }}
                                            </a>
                                            <div class="small text-muted">ID Booking: {{ $booking->id }}</div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="row">
                                                <div class="col-6"><span class="small text-muted">Tgl
                                                        Booking:</span><br>{{ $booking->booking_date?->isoFormat('D MMM YY') ?? '-' }}
                                                </div>
                                                <div class="col-6"><span class="small text-muted">Status
                                                        Akhir:</span><br><span
                                                        class="badge bg-{{ $booking->status->badgeColor() }}">{{ $booking->status->label() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 text-md-end">
                                            @if (!empty($booking->notes))
                                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#bookingNotesModal-{{ $booking->id }}">
                                                    <i class="bi bi-chat-left-text"></i> Lihat Catatan
                                                </button>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="p-3 d-flex justify-content-end">
                            {{ $pastBookings->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @foreach ($activeBookings as $booking)
        <div class="modal fade" id="userCancelModal-{{ $booking->id }}" tabindex="-1"
            aria-labelledby="userCancelModalLabel-{{ $booking->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('user.bookings.cancel', $booking) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="userCancelModalLabel-{{ $booking->id }}">Konfirmasi
                                Pembatalan Booking</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Anda yakin ingin membatalkan booking untuk buku:</p>
                            <p><strong>{{ $booking->book?->title ?? 'N/A' }}</strong>?</p>
                            <p class="text-muted small">Tindakan ini tidak dapat diurungkan.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                            <button type="submit" class="btn btn-danger">Ya, Batalkan Booking</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    @foreach ($pastBookings as $booking)
        @if (!empty($booking->notes))
            <div class="modal fade" id="bookingNotesModal-{{ $booking->id }}" tabindex="-1"
                aria-labelledby="bookingNotesModalLabel-{{ $booking->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="bookingNotesModalLabel-{{ $booking->id }}"><i
                                    class="bi bi-chat-left-text me-2"></i>Catatan Booking</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Buku:</strong> {{ $booking->book?->title ?? 'N/A' }}</p>
                            <p><strong>Status Akhir:</strong> <span
                                    class="badge bg-{{ $booking->status->badgeColor() }}">{{ $booking->status->label() }}</span>
                            </p>
                            <hr>
                            <p><strong>Catatan:</strong></p>
                            <div style="white-space: pre-wrap;">{!! nl2br(e($booking->notes)) !!}</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

@endsection

@section('css')
    @parent
    <style>
        .nav-tabs .nav-link {
            color: var(--bs-secondary-color);
            font-weight: 500;
        }

        .nav-tabs .nav-link.active {
            color: var(--bs-primary);
            font-weight: 700;
        }
    </style>
@endsection
