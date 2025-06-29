@extends('admin.components.main')

@section('title', 'Laporan Peminjaman')
@section('page-title', 'Laporan Peminjaman Buku')

@section('content')
    <div class="card shadow-sm rounded-4 border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 fw-semibold">Laporan Peminjaman</h6>
            @if (
                !$errors->has('start_date') &&
                    !$errors->has('end_date') &&
                    isset($startDate) &&
                    isset($endDate) &&
                    isset($borrowings))
                <form action="{{ route('admin.reports.borrowings.export') }}" method="GET" class="d-inline-block">
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-file-earmark-excel-fill me-1"></i> Export Excel
                    </button>
                </form>
            @endif
        </div>
        <div class="card-body p-4">
            <div class="filter-panel p-3 rounded-3 mb-4">
                <form action="{{ route('admin.reports.borrowings') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label for="start_date" class="form-label fw-semibold">Tanggal Mulai</label>
                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date"
                            name="start_date" value="{{ $startDate ?? '' }}" required>
                        @error('start_date')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-5">
                        <label for="end_date" class="form-label fw-semibold">Tanggal Selesai</label>
                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date"
                            name="end_date" value="{{ $endDate ?? '' }}" required>
                        @error('end_date')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-filter"></i> Tampilkan</button>
                    </div>
                </form>
            </div>

            <div class="section-divider"><span>Hasil Laporan</span></div>

            <h5 class="text-center mb-3">
                @if (isset($startDate) && isset($endDate))
                    Menampilkan {{ $borrowings->count() }} Data Peminjaman
                    <br>
                    <small class="text-muted fw-normal">Periode:
                        {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMM YY') }} -
                        {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMM YY') }}</small>
                @else
                    <span class="text-muted fw-normal">Silakan pilih rentang tanggal untuk menampilkan laporan.</span>
                @endif
            </h5>

            @if ($errors->has('start_date') || $errors->has('end_date'))
                <div class="alert alert-warning text-center">Silakan perbaiki input tanggal pada filter di atas.</div>
            @elseif(isset($borrowings))
                @if ($borrowings->isEmpty())
                    <div class="alert alert-light text-center">Tidak ada data peminjaman ditemukan untuk rentang tanggal
                        yang dipilih.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover datatable" id="dataTableReportBorrowings" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Peminjam & Buku</th>
                                    <th>Periode & Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($borrowings as $index => $borrowing)
                                    <tr class="align-middle">
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="ms-3">
                                                    <div class="fw-semibold">{{ $borrowing->siteUser?->name ?? 'N/A' }}
                                                    </div>
                                                    <div class="text-muted small">
                                                        <i class="bi bi-book-fill me-1"></i>
                                                        {{ $borrowing->bookCopy?->book?->title ?? 'N/A' }}
                                                        (Kode: {{ $borrowing->bookCopy?->copy_code ?? 'N/A' }})
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="small">
                                            <div><span class="text-muted">Pinjam:</span>
                                                {{ $borrowing->borrow_date ? $borrowing->borrow_date->isoFormat('D MMM YY') : '-' }}
                                            </div>
                                            <div><span class="text-muted">Tempo:</span>
                                                {{ $borrowing->due_date ? $borrowing->due_date->isoFormat('D MMM YY') : '-' }}
                                            </div>
                                            @if ($borrowing->return_date)
                                                <div class="text-success"><span class="text-muted">Kembali:</span>
                                                    {{ $borrowing->return_date->isoFormat('D MMM YY') }}</div>
                                            @endif
                                            @if ($borrowing->status)
                                                <span
                                                    class="badge rounded-pill bg-{{ $borrowing->status->badgeColor() }} mt-1">{{ $borrowing->status->label() }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection

@section('css')
    <style>
        .filter-panel {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }

        .section-divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.5rem 0;
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

        .table thead th {
            font-weight: 600;
            color: #6c757d;
            border-bottom-width: 1px;
        }

        .badge.rounded-pill {
            font-weight: 600;
        }
    </style>
@endsection

@section('script')
    @if (isset($borrowings) && $borrowings->count() > 0 && !$errors->has('start_date') && !$errors->has('end_date'))
        @include('admin.components.datatable_script', ['table_id' => 'dataTableReportBorrowings'])
    @endif
@endsection
