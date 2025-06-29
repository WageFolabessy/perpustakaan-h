@extends('admin.components.main')

@section('title', 'Detail Booking')
@section('page-title', 'Detail Transaksi Booking')

@section('content')
    <div class="card shadow-sm rounded-4 border-0">
        <div class="card-header bg-white p-3 d-flex flex-row align-items-center justify-content-between">
            <div>
                <h6 class="m-0 fw-semibold">Booking ID: #{{ $booking->id }}</h6>
                @if ($booking->status)
                    <span
                        class="badge rounded-pill fs-6 bg-{{ $booking->status->badgeColor() }}">{{ $booking->status->label() }}</span>
                    @if ($booking->status == App\Enum\BookingStatus::Active && $booking->expiry_date < now())
                        <i class="bi bi-clock-history text-danger ms-1" title="Sudah Melewati Batas Pengambilan"></i>
                    @endif
                @endif
            </div>
            <div>
                @if ($booking->status === App\Enum\BookingStatus::Active)
                    <button type="button" class="btn btn-success" title="Konversi ke Peminjaman" data-bs-toggle="modal"
                        data-bs-target="#convertModal-{{ $booking->id }}">
                        <i class="bi bi-check2-square me-1"></i> Konversi
                    </button>
                    <button type="button" class="btn btn-danger" title="Batalkan Booking" data-bs-toggle="modal"
                        data-bs-target="#cancelModal-{{ $booking->id }}">
                        <i class="bi bi-x-lg me-1"></i> Batal
                    </button>
                @endif
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary" title="Kembali ke Daftar Booking">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card-body p-4">
            @include('admin.components.flash_messages')
            @include('admin.components.validation_errors')

            <div class="row gx-4 gy-3 summary-box mb-4">
                <div class="col-12 col-md-6">
                    <div class="summary-item">
                        <i class="bi bi-calendar-plus-fill"></i>
                        <div>
                            <small>Tanggal Booking</small>
                            <strong>{{ $booking->booking_date ? $booking->booking_date->isoFormat('dddd, D MMM YY') : '-' }}</strong>
                            <span
                                class="text-muted small">{{ $booking->booking_date ? $booking->booking_date->isoFormat('HH:mm') : '' }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div
                        class="summary-item {{ $booking->status == App\Enum\BookingStatus::Active && $booking->expiry_date < now() ? 'expired' : '' }}">
                        <i class="bi bi-calendar-x-fill"></i>
                        <div>
                            <small>Batas Waktu Pengambilan</small>
                            <strong>{{ $booking->expiry_date ? $booking->expiry_date->isoFormat('dddd, D MMM YY') : '-' }}</strong>
                            <span
                                class="text-muted small">{{ $booking->expiry_date ? $booking->expiry_date->isoFormat('HH:mm') : '' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-divider"><span>Detail Pesanan</span></div>

            <div class="row">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="detail-block">
                        <h6 class="detail-block-title">Pemesan</h6>
                        <div class="d-flex align-items-center">
                            <div class="ms-3">
                                <div class="fw-bold">{{ $booking->siteUser?->name ?? 'N/A' }}</div>
                                <div class="text-muted">NIS: {{ $booking->siteUser?->nis ?? 'N/A' }}</div>
                            </div>
                            <a href="{{ $booking->siteUser ? route('admin.site-users.show', $booking->siteUser) : '#' }}"
                                class="btn btn-sm btn-outline-primary ms-auto">Profil Siswa</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="detail-block">
                        <h6 class="detail-block-title">Buku yang Dipesan</h6>
                        <div class="d-flex align-items-center">
                            <div class="ms-3">
                                <div class="fw-bold">{{ $booking->book?->title ?? 'N/A' }}</div>
                                <div class="text-muted">ISBN: {{ $booking->book?->isbn ?? 'N/A' }}</div>
                            </div>
                            <a href="{{ $booking->book ? route('admin.books.show', $booking->book) : '#' }}"
                                class="btn btn-sm btn-outline-primary ms-auto">Detail Buku</a>
                        </div>
                    </div>
                </div>
            </div>

            @if ($booking->notes)
                <div class="section-divider"><span>Catatan dari Pemesan</span></div>
                <div class="notes-block">
                    <i class="bi bi-quote"></i>
                    <p class="mb-0 fst-italic">
                        {!! nl2br(e($booking->notes)) !!}
                    </p>
                </div>
            @endif

        </div>
    </div>

    @if ($booking->status === App\Enum\BookingStatus::Active)
        <div class="modal fade" id="convertModal-{{ $booking->id }}" tabindex="-1"
            aria-labelledby="convertModalLabel-{{ $booking->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('admin.bookings.convert', $booking) }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="convertModalLabel-{{ $booking->id }}">Konversi Booking ke
                                Peminjaman</h1><button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Anda akan mengonversi booking ini menjadi peminjaman:</p>
                            <ul>
                                <li>Buku: <strong>{{ $booking->book?->title ?? 'N/A' }}</strong></li>
                                <li>Pemesan: <strong>{{ $booking->siteUser?->name ?? 'N/A' }}</strong></li>
                                <li>Eksemplar: <strong>{{ $booking->bookCopy?->copy_code ?? 'N/A (Harap Cek!)' }}</strong>
                                </li>
                            </ul>
                            <div class="mb-3">
                                <label for="admin_notes-convert-{{ $booking->id }}" class="form-label">Catatan Konversi
                                    (Opsional):</label>
                                <textarea class="form-control @error('admin_notes', 'convert_' . $booking->id) is-invalid @enderror"
                                    id="admin_notes-convert-{{ $booking->id }}" name="admin_notes" rows="2">{{ old('admin_notes') }}</textarea>
                                @error('admin_notes', 'convert_' . $booking->id)
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i
                                    class="bi bi-check2-square me-1"></i> Konversi Jadi Peminjaman</button></div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="cancelModal-{{ $booking->id }}" tabindex="-1"
            aria-labelledby="cancelModalLabel-{{ $booking->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="cancelModalLabel-{{ $booking->id }}">Konfirmasi Pembatalan
                                Booking</h1><button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Anda yakin ingin membatalkan booking untuk:</p>
                            <ul>
                                <li>Buku: <strong>{{ $booking->book?->title ?? 'N/A' }}</strong></li>
                                <li>Pemesan: <strong>{{ $booking->siteUser?->name ?? 'N/A' }}</strong></li>
                            </ul>
                            <div class="mb-3">
                                <label for="admin_notes-cancel-show-{{ $booking->id }}" class="form-label">Alasan /
                                    Catatan Pembatalan (Opsional):</label>
                                <textarea class="form-control @error('admin_notes') is-invalid @enderror"
                                    id="admin_notes-cancel-show-{{ $booking->id }}" name="admin_notes" rows="2">{{ old('admin_notes') }}</textarea>
                                @error('admin_notes')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">Tidak</button><button type="submit" class="btn btn-danger">Ya,
                                Batalkan Booking</button></div>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection

@section('css')
    <style>
        .summary-box {
            background-color: #f8f9fa;
            border-radius: 0.75rem;
            padding: 1.5rem;
            border: 1px solid #dee2e6;
        }

        .summary-item {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .summary-item i {
            font-size: 2rem;
            color: var(--bs-primary);
        }

        .summary-item.expired i {
            color: var(--bs-danger);
        }

        .summary-item.expired strong {
            color: var(--bs-danger);
        }

        .summary-item div {
            display: flex;
            flex-direction: column;
        }

        .summary-item small {
            color: #6c757d;
        }

        .summary-item strong {
            font-size: 1.1rem;
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

        .detail-block {
            border: 1px solid #dee2e6;
            border-radius: 0.75rem;
            padding: 1.25rem;
            height: 100%;
        }

        .detail-block-title {
            font-size: 0.8rem;
            text-transform: uppercase;
            color: #6c757d;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .user-avatar-sm {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--bs-primary-subtle);
            color: var(--bs-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            flex-shrink: 0;
        }

        .book-cover-sm {
            width: 40px;
            height: 60px;
            object-fit: cover;
            border-radius: 0.25rem;
            flex-shrink: 0;
        }

        .notes-block {
            padding: 1rem;
            background-color: #f8f9fa;
            border-left: 4px solid var(--bs-primary);
            color: #6c757d;
        }

        .notes-block i {
            float: left;
            margin-right: 0.75rem;
            font-size: 1.5rem;
            opacity: 0.5;
        }
    </style>
@endsection

@section('script')
    <script>
        @if ($errors->hasBag('convert_' . $booking->id))
            var convertModalInstance = document.getElementById('convertModal-{{ $booking->id }}');
            if (convertModalInstance) {
                new bootstrap.Modal(convertModalInstance).show();
            }
        @endif
        @if ($errors->hasBag('cancel_' . $booking->id))
            var cancelModalInstance = document.getElementById('cancelModal-{{ $booking->id }}');
            if (cancelModalInstance) {
                new bootstrap.Modal(cancelModalInstance).show();
            }
        @endif
    </script>
@endsection
