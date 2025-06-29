@extends('admin.components.main')

@section('title', 'Laporan Denda')
@section('page-title', 'Laporan Denda')

@section('content')
    <div class="card shadow-sm rounded-4 border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 fw-semibold">Laporan Denda</h6>
            @if (!$errors->any() && isset($startDate) && isset($endDate) && isset($fines))
                <form action="{{ route('admin.reports.fines.export') }}" method="GET" class="d-inline-block">
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                    <input type="hidden" name="status" value="{{ $statusFilter ?? '' }}">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-file-earmark-excel-fill me-1"></i> Export Excel
                    </button>
                </form>
            @endif
        </div>
        <div class="card-body p-4">
            <div class="filter-panel p-3 rounded-3 mb-4">
                <form action="{{ route('admin.reports.fines') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label fw-semibold">Tanggal Mulai</label>
                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date"
                            name="start_date" value="{{ $startDate ?? '' }}" required>
                        @error('start_date')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label fw-semibold">Tanggal Selesai</label>
                        <input type="text" class="form-control @error('end_date') is-invalid @enderror" id="end_date"
                            name="end_date" value="{{ $endDate ?? '' }}" required>
                        @error('end_date')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label fw-semibold">Status Denda</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" id="status">
                            @foreach ($filterOptions as $value => $label)
                                <option value="{{ $value }}"
                                    {{ ($statusFilter ?? '') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-filter"></i> Tampilkan</button>
                    </div>
                    <div class="col-12 mt-2">
                        <small class="text-muted fst-italic">*Rentang tanggal berlaku untuk **Tanggal Dibuat** jika
                            Status = 'Semua' atau 'Belum Lunas'. Untuk status lainnya, rentang berlaku untuk **Tanggal
                            Proses**.</small>
                    </div>
                </form>
            </div>

            <div class="section-divider"><span>Hasil Laporan</span></div>

            @if (!$errors->any() && isset($fines))
                <div class="row gx-4 gy-3 summary-box mb-4">
                    <div class="col-12 col-md-4">
                        <div class="summary-item"><i class="bi bi-receipt"></i>
                            <div><small>Total Transaksi Denda</small><strong>{{ $fines->count() }}</strong></div>
                        </div>
                    </div>
                </div>

                @if ($fines->isEmpty())
                    <div class="alert alert-light text-center">Tidak ada data denda ditemukan untuk filter yang dipilih.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover datatable" id="dataTableReportFines" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Denda Untuk</th>
                                    <th class="text-end">Jumlah & Status</th>
                                    <th>Kronologi</th>
                                    <th class="text-center no-sort">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fines as $index => $fine)
                                    <tr class="align-middle">
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="ms-3">
                                                    <div class="fw-semibold">
                                                        {{ $fine->borrowing?->siteUser?->name ?? 'N/A' }}</div>
                                                    <div class="text-muted small"><i
                                                            class="bi bi-book-fill me-1"></i>{{ $fine->borrowing?->bookCopy?->book?->title ?? 'N/A' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <div class="fw-bold">Rp {{ number_format($fine->amount, 0, ',', '.') }}</div>
                                            @if ($fine->status)
                                                <span
                                                    class="badge rounded-pill bg-{{ $fine->status->badgeColor() }}">{{ $fine->status->label() }}</span>
                                            @endif
                                        </td>
                                        <td class="small">
                                            <div><span class="text-muted">Dibuat:</span>
                                                {{ $fine->created_at ? $fine->created_at->isoFormat('D MMM YY') : '-' }}
                                            </div>
                                            @if ($fine->payment_date)
                                                <div><span class="text-muted">Proses:</span>
                                                    {{ $fine->payment_date->isoFormat('D MMM YY') }}</div>
                                                <div class="text-muted">oleh {{ $fine->paymentProcessor?->name ?? '-' }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center"><a href="{{ route('admin.fines.show', $fine) }}"
                                                class="btn btn-sm btn-outline-primary" title="Lihat Detail Denda"><i
                                                    class="bi bi-eye-fill"></i></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @else
                <div class="alert alert-warning text-center">Silakan perbaiki input filter di atas untuk menampilkan
                    laporan.</div>
            @endif
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
            font-size: 1.3rem;
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
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    @if (isset($fines) && $fines->count() > 0 && !$errors->any())
        @include('admin.components.datatable_script', ['table_id' => 'dataTableReportFines'])
    @endif

    <script>
        $(function() {
            const startDateInput = $('#start_date');
            const endDateInput = $('#end_date');
            const dateRangeDisplay = $(
                '<input type="text" class="form-control" placeholder="Pilih rentang tanggal...">');

            startDateInput.hide().parent().append(dateRangeDisplay);
            endDateInput.hide();

            dateRangeDisplay.daterangepicker({
                startDate: moment(startDateInput.val() || moment()),
                endDate: moment(endDateInput.val() || moment()),
                locale: {
                    format: 'YYYY-MM-DD'
                }
            }, function(start, end, label) {
                startDateInput.val(start.format('YYYY-MM-DD'));
                endDateInput.val(end.format('YYYY-MM-DD'));
            });

            endDateInput.parent().remove();
            dateRangeDisplay.parent().removeClass('col-md-4').addClass('col-md-5');
            $('#status').parent().removeClass('col-md-2').addClass('col-md-4');
        });
    </script>
@endsection
