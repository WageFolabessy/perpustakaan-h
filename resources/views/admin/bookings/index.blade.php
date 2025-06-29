@extends('admin.components.main')

@section('title', 'Manajemen Booking')
@section('page-title', 'Manajemen Booking')

@section('content')
    <div class="card shadow-sm rounded-4 border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 fw-semibold">Daftar Booking Buku Aktif & Riwayat</h6>
        </div>
        <div class="card-body">
            @include('admin.components.flash_messages')
            @include('admin.components.validation_errors')

            @if ($bookings->isEmpty())
                <div class="alert alert-info text-center">
                    Tidak ada data booking.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover datatable" id="dataTableBookings" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center no-sort" width="1%">ID</th>
                                <th>Pemesan & Buku</th>
                                <th>Waktu Booking</th>
                                <th class="text-center">Status</th>
                                <th class="text-center action-column no-sort">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookings as $booking)
                                <tr class="align-middle">
                                    <td class="text-center">{{ $booking->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-3">
                                                <div class="fw-semibold">{{ $booking->siteUser?->name ?? 'N/A' }}</div>
                                                <div class="text-muted small"><i
                                                        class="bi bi-book me-1"></i>{{ $booking->book?->title ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="small">
                                        <div><span class="text-muted">Booking:</span>
                                            {{ $booking->booking_date ? $booking->booking_date->isoFormat('D MMM YY, HH:mm') : '-' }}
                                        </div>
                                        <div
                                            class="{{ $booking->status == App\Enum\BookingStatus::Active && $booking->expiry_date < now() ? 'text-danger fw-bold' : '' }}">
                                            <span class="text-muted">Kadaluarsa:</span>
                                            {{ $booking->expiry_date ? $booking->expiry_date->isoFormat('D MMM YY, HH:mm') : '-' }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if ($booking->status)
                                            <span
                                                class="badge rounded-pill bg-{{ $booking->status->badgeColor() }}">{{ $booking->status->label() }}</span>
                                            @if ($booking->status == App\Enum\BookingStatus::Active && $booking->expiry_date < now())
                                                <i class="bi bi-clock-history text-danger ms-1"
                                                    title="Sudah Melewati Batas Pengambilan"></i>
                                            @endif
                                        @else
                                            <span class="badge rounded-pill bg-secondary">-</span>
                                        @endif
                                    </td>
                                    <td class="action-column text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.bookings.show', $booking) }}"
                                                class="btn btn-outline-primary" title="Detail">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                            @if ($booking->status === App\Enum\BookingStatus::Active)
                                                <button type="button" class="btn btn-outline-success"
                                                    title="Konversi ke Peminjaman" data-bs-toggle="modal"
                                                    data-bs-target="#convertModal-{{ $booking->id }}">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger"
                                                    title="Batalkan Booking" data-bs-toggle="modal"
                                                    data-bs-target="#cancelModal-{{ $booking->id }}">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @foreach ($bookings as $booking)
                    @if ($booking->status === App\Enum\BookingStatus::Active)
                        <div class="modal fade" id="convertModal-{{ $booking->id }}" tabindex="-1"
                            aria-labelledby="convertModalLabel-{{ $booking->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('admin.bookings.convert', $booking) }}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="convertModalLabel-{{ $booking->id }}">
                                                Konversi Booking ke Peminjaman</h1><button type="button" class="btn-close"
                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Anda akan mengonversi booking ini menjadi peminjaman:</p>
                                            <ul>
                                                <li>Buku: <strong>{{ $booking->book?->title ?? 'N/A' }}</strong></li>
                                                <li>Pemesan: <strong>{{ $booking->siteUser?->name ?? 'N/A' }}</strong></li>
                                                <li>Eksemplar:
                                                    <strong>{{ $booking->bookCopy?->copy_code ?? 'N/A (Harap Cek!)' }}</strong>
                                                </li>
                                            </ul>
                                            <div class="mb-3">
                                                <label for="admin_notes-convert-{{ $booking->id }}"
                                                    class="form-label">Catatan Konversi (Opsional):</label>
                                                <textarea class="form-control @error('admin_notes', 'convert_' . $booking->id) is-invalid @enderror"
                                                    id="admin_notes-convert-{{ $booking->id }}" name="admin_notes" rows="2">{{ old('admin_notes') }}</textarea>
                                                @error('admin_notes', 'convert_' . $booking->id)
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="modal-footer"><button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button><button type="submit"
                                                class="btn btn-primary"><i class="bi bi-check2-square me-1"></i> Konversi
                                                Jadi Peminjaman</button></div>
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
                                            <h1 class="modal-title fs-5" id="cancelModalLabel-{{ $booking->id }}">
                                                Konfirmasi Pembatalan Booking</h1><button type="button" class="btn-close"
                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Anda yakin ingin membatalkan booking untuk:</p>
                                            <ul>
                                                <li>Buku: <strong>{{ $booking->book?->title ?? 'N/A' }}</strong></li>
                                                <li>Pemesan: <strong>{{ $booking->siteUser?->name ?? 'N/A' }}</strong></li>
                                            </ul>
                                            <div class="mb-3">
                                                <label for="admin_notes-cancel-{{ $booking->id }}"
                                                    class="form-label">Alasan / Catatan Pembatalan (Opsional):</label>
                                                <textarea class="form-control @error('admin_notes') is-invalid @enderror" id="admin_notes-cancel-{{ $booking->id }}"
                                                    name="admin_notes" rows="2">{{ old('admin_notes') }}</textarea>
                                                @error('admin_notes')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="modal-footer"><button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Tidak</button><button type="submit"
                                                class="btn btn-danger">Ya, Batalkan Booking</button></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
@endsection

@section('css')
    <style>
        .table thead th {
            font-weight: 600;
            color: #6c757d;
            border-bottom-width: 1px;
        }

        .action-column {
            white-space: nowrap;
            width: 1%;
            text-align: center;
        }

        .badge.rounded-pill {
            padding: 0.4em 0.8em;
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>
@endsection

@section('script')
    @include('admin.components.datatable_script', [
        'table_id' => 'dataTableBookings',
    ])
@endsection
