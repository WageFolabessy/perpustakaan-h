@extends('admin.components.main')

@section('title', 'Detail Laporan Kehilangan')
@section('page-title')
    Detail Laporan Kehilangan #{{ $lost_report->id }}
@endsection

@section('content')
    <div class="card shadow-sm rounded-4 border-0">
        <div class="card-header bg-white p-3 d-flex flex-row align-items-center justify-content-between">
            <div>
                <h6 class="m-0 fw-semibold">Laporan ID: #{{ $lost_report->id }}</h6>
                @if ($lost_report->status)
                    <span
                        class="badge rounded-pill fs-6 bg-{{ $lost_report->status->badgeColor() }}">{{ $lost_report->status->label() }}</span>
                @endif
            </div>
            <div>
                @if ($lost_report->status === App\Enum\LostReportStatus::Reported)
                    <form action="{{ route('admin.lost-reports.verify', $lost_report) }}" method="POST" class="d-inline ms-1"
                        onsubmit="return confirm('Verifikasi laporan ini? Buku akan ditandai sebagai \'Dalam Investigasi\'.');">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-primary" title="Verifikasi Laporan"><i
                                class="bi bi-check-circle me-1"></i> Verifikasi</button>
                    </form>
                @endif
                @if (in_array($lost_report->status, [App\Enum\LostReportStatus::Reported, App\Enum\LostReportStatus::Verified]))
                    <button type="button" class="btn btn-success ms-1" title="Selesaikan Laporan" data-bs-toggle="modal"
                        data-bs-target="#resolveModal-{{ $lost_report->id }}">
                        <i class="bi bi-check2-all me-1"></i> Selesaikan
                    </button>
                @endif
                <a href="{{ route('admin.lost-reports.index') }}" class="btn btn-secondary ms-1" title="Kembali ke Daftar">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card-body p-4">
            @include('admin.components.flash_messages')
            @include('admin.components.validation_errors')

            <div class="row gx-4 gy-3 summary-box mb-4">
                <div class="col-12 col-md-4">
                    <div class="summary-item">
                        <i class="bi bi-calendar-plus-fill"></i>
                        <div><small>Tgl.
                                Dilaporkan</small><strong>{{ $lost_report->report_date ? $lost_report->report_date->isoFormat('D MMM YYYY, HH:mm') : '-' }}</strong>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="summary-item">
                        <i class="bi bi-person-check-fill"></i>
                        <div><small>Diverifikasi Oleh</small><strong>{{ $lost_report->verifier?->name ?? '-' }}</strong>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="summary-item">
                        <i class="bi bi-calendar-check-fill"></i>
                        <div><small>Tgl.
                                Diselesaikan</small><strong>{{ $lost_report->resolution_date ? $lost_report->resolution_date->isoFormat('D MMM YYYY, HH:mm') : '-' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-divider"><span>Detail Pihak & Objek Terkait</span></div>

            <div class="row">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="detail-block">
                        <h6 class="detail-block-title">Siswa Pelapor</h6>
                        <div class="d-flex align-items-center">
                            <div class="ms-3">
                                <div class="fw-bold">{{ $lost_report->reporter?->name ?? 'N/A' }}</div>
                                <div class="text-muted">NIS: {{ $lost_report->reporter?->nis ?? 'N/A' }}</div>
                            </div>
                            <a href="{{ $lost_report->reporter ? route('admin.site-users.show', $lost_report->reporter) : '#' }}"
                                class="btn btn-sm btn-outline-primary ms-auto">Profil Siswa</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="detail-block">
                        <h6 class="detail-block-title">Buku yang Hilang</h6>
                        <div class="d-flex align-items-center">
                            <div class="ms-3">
                                <div class="fw-bold">{{ $lost_report->bookCopy?->book?->title ?? 'N/A' }}</div>
                                <div class="text-muted">Kode: {{ $lost_report->bookCopy?->copy_code ?? 'N/A' }}</div>
                            </div>
                            <a href="{{ $lost_report->bookCopy?->book ? route('admin.books.show', $lost_report->bookCopy?->book) : '#' }}"
                                class="btn btn-sm btn-outline-primary ms-auto">Detail Buku</a>
                        </div>
                    </div>
                </div>
            </div>

            @if ($lost_report->borrowing)
                <div class="section-divider"><span>Informasi Peminjaman Terkait</span></div>
                <div class="detail-block d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="detail-block-title mb-1">Laporan ini terhubung dengan Peminjaman ID
                            #{{ $lost_report->borrowing->id }}</h6>
                        <span
                            class="badge bg-{{ $lost_report->borrowing->status->badgeColor() }}">{{ $lost_report->borrowing->status->label() }}</span>
                    </div>
                    <a href="{{ route('admin.borrowings.show', $lost_report->borrowing) }}" class="btn btn-primary">Lihat
                        Detail Peminjaman</a>
                </div>
            @endif

            @if ($lost_report->resolution_notes)
                <div class="section-divider"><span>Catatan Penyelesaian</span></div>
                <div class="notes-block">
                    <i class="bi bi-card-text"></i>
                    <p class="mb-0">{!! nl2br(e($lost_report->resolution_notes)) !!}</p>
                    <small class="d-block text-muted mt-2">Diselesaikan oleh:
                        {{ $lost_report->resolver?->name ?? '-' }}</small>
                </div>
            @endif
        </div>
    </div>

    @if (in_array($lost_report->status, [App\Enum\LostReportStatus::Reported, App\Enum\LostReportStatus::Verified]))
        <div class="modal fade" id="resolveModal-{{ $lost_report->id }}" tabindex="-1"
            aria-labelledby="resolveModalLabel-{{ $lost_report->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('admin.lost-reports.resolve', $lost_report) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="resolveModalLabel-{{ $lost_report->id }}">Selesaikan Laporan
                                Kehilangan</h1><button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Anda akan menyelesaikan laporan kehilangan untuk buku
                                <strong>{{ $lost_report->bookCopy?->book?->title ?? 'N/A' }}</strong>.
                            </p>
                            <p>Status buku akan diubah menjadi 'Hilang'. Jika terhubung dengan peminjaman dan ada biaya
                                penggantian, denda akan dibuat/diperbaharui.</p>
                            <div class="mb-3">
                                <label for="resolution_notes-show-{{ $lost_report->id }}" class="form-label">Catatan
                                    Penyelesaian <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('resolution_notes') is-invalid @enderror"
                                    id="resolution_notes-show-{{ $lost_report->id }}" name="resolution_notes" rows="3" required>{{ old('resolution_notes') }}</textarea>
                                @error('resolution_notes')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-success"><i
                                    class="bi bi-check2-all me-1"></i> Ya, Selesaikan Laporan</button></div>
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
