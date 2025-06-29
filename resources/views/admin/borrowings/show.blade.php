@extends('admin.components.main')

@section('title', 'Detail Peminjaman')
@section('page-title', 'Detail Transaksi Peminjaman')

@section('content')
    <div class="card shadow-sm rounded-4 border-0">
        <div class="card-header bg-white p-3 d-flex flex-row align-items-center justify-content-between">
            <div>
                <h6 class="m-0 fw-semibold">ID Transaksi: #{{ $borrowing->id }}</h6>
                @if ($borrowing->status)
                    <span
                        class="badge rounded-pill fs-6 bg-{{ $borrowing->status->badgeColor() }}">{{ $borrowing->status->label() }}</span>
                @endif
            </div>
            <div>
                @if (in_array($borrowing->status, [\App\Enum\BorrowingStatus::Borrowed, \App\Enum\BorrowingStatus::Overdue]))
                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                        data-bs-target="#returnModal-{{ $borrowing->id }}">
                        <i class="bi bi-check-circle-fill me-1"></i> Proses Pengembalian
                    </button>
                @endif
                <a href="{{ route('admin.borrowings.index') }}" class="btn btn-secondary" title="Kembali ke Daftar">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card-body p-4">
            @include('admin.components.flash_messages')
            @if ($errors->hasBag('return_' . $borrowing->id))
                <div class="alert alert-danger">Gagal memproses pengembalian, silakan cek detail di form pengembalian.</div>
            @endif

            <div class="row gx-4 gy-3 summary-box mb-4">
                <div class="col-12 col-md-4">
                    <div class="summary-item">
                        <i class="bi bi-calendar-check-fill"></i>
                        <div>
                            <small>Tgl. Pinjam</small>
                            <strong>{{ $borrowing->borrow_date ? $borrowing->borrow_date->isoFormat('D MMM YYYY') : '-' }}</strong>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="summary-item">
                        <i class="bi bi-calendar-x-fill"></i>
                        <div>
                            <small>Jatuh Tempo</small>
                            <strong>{{ $borrowing->due_date ? $borrowing->due_date->isoFormat('D MMM YYYY') : '-' }}</strong>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="summary-item">
                        @if ($borrowing->fine)
                            <i class="bi bi-cash-coin text-danger"></i>
                            <div>
                                <small>Denda</small>
                                <strong>Rp {{ number_format($borrowing->fine->amount, 0, ',', '.') }}
                                    <span
                                        class="ms-1 badge bg-{{ $borrowing->fine->status->badgeColor() }}">{{ $borrowing->fine->status->label() }}</span>
                                </strong>
                            </div>
                        @else
                            <i class="bi bi-cash-coin"></i>
                            <div>
                                <small>Denda</small>
                                <strong>-</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="section-divider"><span>Detail Pihak Terlibat</span></div>

            <div class="row">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="detail-block">
                        <h6 class="detail-block-title">Peminjam</h6>
                        <div class="d-flex align-items-center">
                            <div class="ms-3">
                                <div class="fw-bold">{{ $borrowing->siteUser?->name ?: 'N/A' }}</div>
                                <div class="text-muted">NIS: {{ $borrowing->siteUser?->nis ?: 'N/A' }}</div>
                            </div>
                            <a href="{{ $borrowing->siteUser ? route('admin.site-users.show', $borrowing->siteUser) : '#' }}"
                                class="btn btn-sm btn-outline-primary ms-auto">Profil Siswa</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="detail-block">
                        <h6 class="detail-block-title">Buku yang Dipinjam</h6>
                        <div class="d-flex align-items-center">
                            <div class="ms-3">
                                <div class="fw-bold">{{ $borrowing->bookCopy?->book?->title ?: 'N/A' }}</div>
                                <div class="text-muted">Kode: {{ $borrowing->bookCopy?->copy_code ?: 'N/A' }}</div>
                            </div>
                            <a href="{{ $borrowing->bookCopy?->book ? route('admin.books.show', $borrowing->bookCopy?->book) : '#' }}"
                                class="btn btn-sm btn-outline-primary ms-auto">Detail Buku</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-divider"><span>Informasi Tambahan</span></div>
            <dl class="row small text-muted px-2">
                <dt class="col-sm-3">Admin Peminjam</dt>
                <dd class="col-sm-9">{{ $borrowing->loanProcessor?->name ?: '-' }}</dd>
                <dt class="col-sm-3">Admin Pengembali</dt>
                <dd class="col-sm-9">{{ $borrowing->returnProcessor?->name ?: '-' }}</dd>
                <dt class="col-sm-3">Tgl. Pengembalian Aktual</dt>
                <dd class="col-sm-9">
                    {{ $borrowing->return_date ? $borrowing->return_date->isoFormat('dddd, D MMMM YYYY') : '-' }}</dd>
            </dl>

        </div>
    </div>

    @if (in_array($borrowing->status, [\App\Enum\BorrowingStatus::Borrowed, \App\Enum\BorrowingStatus::Overdue]))
        <div class="modal fade" id="returnModal-{{ $borrowing->id }}" tabindex="-1"
            aria-labelledby="returnModalLabel-{{ $borrowing->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('admin.borrowings.return', $borrowing) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="returnModalLabel-{{ $borrowing->id }}">Konfirmasi Pengembalian
                            </h1><button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Anda akan memproses pengembalian untuk buku
                                <strong>{{ $borrowing->bookCopy?->book?->title ?? 'N/A' }}</strong> oleh
                                <strong>{{ $borrowing->siteUser?->name ?? 'N/A' }}</strong>.
                            </p>
                            <div class="mb-3">
                                <label for="return_notes-{{ $borrowing->id }}" class="form-label">Catatan Pengembalian
                                    (Opsional):</label>
                                <textarea class="form-control @error('return_notes', 'return_' . $borrowing->id) is-invalid @enderror"
                                    id="return_notes-{{ $borrowing->id }}" name="return_notes" rows="3">{{ old('return_notes') }}</textarea>
                                @error('return_notes', 'return_' . $borrowing->id)
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success"><i class="bi bi-check-circle-fill me-1"></i> Ya,
                                Proses Pengembalian</button>
                        </div>
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
            padding: 1rem;
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

        dl.row dt {
            margin-bottom: 0.5rem;
        }
    </style>
@endsection

@section('script')
    <script>
        @if ($errors->hasBag('return_' . $borrowing->id))
            var returnModalInstance = document.getElementById('returnModal-{{ $borrowing->id }}');
            if (returnModalInstance) {
                var modal = new bootstrap.Modal(returnModalInstance);
                modal.show();
            }
        @endif
    </script>
@endsection
