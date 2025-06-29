@extends('admin.components.main')

@section('title', 'Laporan Kehilangan')
@section('page-title', 'Laporan Kehilangan Buku')

@section('content')
    <div class="card shadow-sm rounded-4 border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 fw-semibold">Daftar Laporan Kehilangan</h6>
            <form action="{{ route('admin.lost-reports.index') }}" method="GET" style="max-width: 220px;">
                <select name="status" class="form-select" onchange="this.form.submit()" aria-label="Filter Status Laporan">
                    <option value="">-- Tampilkan Semua Status --</option>
                    @foreach ($validStatuses as $status)
                        <option value="{{ $status->value }}" {{ $statusFilter == $status->value ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="card-body">
            @include('admin.components.flash_messages')
            @include('admin.components.validation_errors')

            @if ($lostReports->isEmpty())
                <div class="alert alert-info text-center">
                    Tidak ada data laporan kehilangan
                    @if ($statusFilter)
                        untuk status "{{ App\Enum\LostReportStatus::tryFrom($statusFilter)?->label() }}"
                    @endif.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover datatable" id="dataTableLostReports" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center no-sort" width="1%">ID</th>
                                <th>Laporan</th>
                                <th>Waktu</th>
                                <th class="text-center">Status</th>
                                <th class="text-center action-column no-sort">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lostReports as $report)
                                <tr class="align-middle">
                                    <td class="text-center">{{ $report->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-3">
                                                <div class="fw-semibold">{{ $report->reporter?->name ?? 'N/A' }}</div>
                                                <div class="text-muted small">
                                                    <i class="bi bi-book-fill me-1"></i>
                                                    {{ $report->bookCopy?->book?->title ?? 'N/A' }} (Kode:
                                                    {{ $report->bookCopy?->copy_code ?? 'N/A' }})
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="small">
                                        <div><span class="text-muted">Dilaporkan:</span>
                                            {{ $report->report_date ? $report->report_date->isoFormat('D MMM YY, HH:mm') : '-' }}
                                        </div>
                                        @if ($report->verifier)
                                            <div class="text-success"><span class="text-muted">Diverifikasi:</span>
                                                {{ $report->verifier->name }}</div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($report->status)
                                            <span
                                                class="badge rounded-pill bg-{{ $report->status->badgeColor() }}">{{ $report->status->label() }}</span>
                                        @endif
                                    </td>
                                    <td class="action-column text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.lost-reports.show', $report) }}"
                                                class="btn btn-outline-primary" title="Detail Laporan">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                            @if ($report->status === App\Enum\LostReportStatus::Reported)
                                                <form action="{{ route('admin.lost-reports.verify', $report) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Verifikasi laporan ini? Buku akan ditandai sebagai \'Dalam Investigasi\'.');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-outline-info"
                                                        title="Verifikasi Laporan"><i
                                                            class="bi bi-check-circle"></i></button>
                                                </form>
                                            @endif
                                            @if (in_array($report->status, [App\Enum\LostReportStatus::Reported, App\Enum\LostReportStatus::Verified]))
                                                <button type="button" class="btn btn-outline-success"
                                                    title="Selesaikan Laporan" data-bs-toggle="modal"
                                                    data-bs-target="#resolveModal-{{ $report->id }}">
                                                    <i class="bi bi-check2-all"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @foreach ($lostReports as $report)
                    @if (in_array($report->status, [App\Enum\LostReportStatus::Reported, App\Enum\LostReportStatus::Verified]))
                        <div class="modal fade" id="resolveModal-{{ $report->id }}" tabindex="-1"
                            aria-labelledby="resolveModalLabel-{{ $report->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('admin.lost-reports.resolve', $report) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="resolveModalLabel-{{ $report->id }}">
                                                Selesaikan Laporan Kehilangan</h1><button type="button" class="btn-close"
                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Anda akan menyelesaikan laporan kehilangan untuk buku
                                                <strong>{{ $report->bookCopy?->book?->title ?? 'N/A' }}</strong>.</p>
                                            <p>Status buku akan diubah menjadi 'Hilang'. Jika terhubung dengan peminjaman
                                                dan ada biaya penggantian, denda akan dibuat/diperbaharui.</p>
                                            <div class="mb-3">
                                                <label for="resolution_notes-{{ $report->id }}"
                                                    class="form-label">Catatan Penyelesaian <span
                                                        class="text-danger">*</span></label>
                                                <textarea class="form-control @error('resolution_notes') is-invalid @enderror"
                                                    id="resolution_notes-{{ $report->id }}" name="resolution_notes" rows="3" required>{{ old('resolution_notes') }}</textarea>
                                                @error('resolution_notes')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="modal-footer"><button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button><button type="submit"
                                                class="btn btn-success"><i class="bi bi-check2-all me-1"></i> Ya, Selesaikan
                                                Laporan</button></div>
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
        }

        .badge.rounded-pill {
            padding: 0.4em 0.8em;
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>
@endsection

@section('script')
    @include('admin.components.datatable_script', ['table_id' => 'dataTableLostReports'])
@endsection
