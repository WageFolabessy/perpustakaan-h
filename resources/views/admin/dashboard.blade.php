@extends('admin.components.main')

@section('title', 'Dashboard Utama')
@section('page-title', 'Dashboard Utama')

@section('content')
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="stats-icon bg-primary-subtle text-primary">
                                <i class="bi bi-journal-richtext"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h6 class="text-muted fw-bold text-uppercase mb-1">Total Judul Buku</h6>
                            <div class="h5 mb-0 fw-bold">{{ number_format($totalBooks, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.books.index') }}" class="text-decoration-none stretched-link"
                    title="Lihat Manajemen Buku"></a>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="stats-icon bg-info-subtle text-info-emphasis">
                                <i class="bi bi-book-fill"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h6 class="text-muted fw-bold text-uppercase mb-1">Total Eksemplar</h6>
                            <div class="h5 mb-0 fw-bold">{{ number_format($totalCopies, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.books.index') }}" class="text-decoration-none stretched-link"
                    title="Lihat Manajemen Buku"></a>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="stats-icon bg-success-subtle text-success-emphasis">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h6 class="text-muted fw-bold text-uppercase mb-1">Total Siswa</h6>
                            <div class="h5 mb-0 fw-bold">{{ number_format($totalStudents, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.site-users.index') }}" class="text-decoration-none stretched-link"
                    title="Lihat Manajemen Siswa"></a>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="stats-icon bg-primary-subtle text-primary">
                                <i class="bi bi-arrow-up-right-square-fill"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h6 class="text-muted fw-bold text-uppercase mb-1">Peminjaman Aktif</h6>
                            <div class="h5 mb-0 fw-bold">{{ number_format($activeBorrowingsCount, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.borrowings.index') }}" class="text-decoration-none stretched-link"
                    title="Lihat Manajemen Sirkulasi"></a>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="stats-icon bg-danger-subtle text-danger-emphasis">
                                <i class="bi bi-calendar-x-fill"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h6 class="text-muted fw-bold text-uppercase mb-1">Lewat Tempo</h6>
                            <div class="h5 mb-0 fw-bold {{ $overdueBorrowingsCount > 0 ? 'text-danger' : '' }}">
                                {{ number_format($overdueBorrowingsCount, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.borrowings.overdue') }}" class="text-decoration-none stretched-link"
                    title="Lihat Buku Lewat Tempo"></a>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="stats-icon bg-warning-subtle text-warning-emphasis">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h6 class="text-muted fw-bold text-uppercase mb-1">Laporan Hilang</h6>
                            <div class="h5 mb-0 fw-bold">{{ number_format($pendingLostReportsCount, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.lost-reports.index', ['status' => 'Reported']) }}"
                    class="text-decoration-none stretched-link" title="Lihat Laporan Kehilangan"></a>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="stats-icon bg-danger-subtle text-danger-emphasis">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h6 class="text-muted fw-bold text-uppercase mb-1">Denda Belum Bayar</h6>
                            <div class="h5 mb-0 fw-bold {{ $totalUnpaidFines > 0 ? 'text-danger' : '' }}">Rp
                                {{ number_format($totalUnpaidFines, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.fines.index', ['status' => \App\Enum\FineStatus::Unpaid->value]) }}"
                    class="text-decoration-none stretched-link" title="Lihat Denda Belum Dibayar"></a>
            </div>
        </div>
    </div>

    <div class="card shadow-sm rounded-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 fw-semibold">Peminjaman Terbaru</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover datatable" id="tableRecentActivity" width="100%">
                    <thead>
                        <tr>
                            <th width="20%">Waktu Pinjam</th>
                            <th>Peminjam</th>
                            <th>Buku</th>
                            <th>Kode Eksemplar</th>
                            <th class="text-center">Status</th>
                            <th class="text-center no-sort">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentBorrowings as $borrowing)
                            <tr class="align-middle">
                                <td>{{ $borrowing->borrow_date?->isoFormat('D MMM YY, HH:mm') ?? ($borrowing->created_at?->isoFormat('D MMM YY, HH:mm') ?? '-') }}
                                </td>
                                <td>{{ $borrowing->siteUser?->name ?? 'N/A' }}</td>
                                <td>{{ $borrowing->bookCopy?->book?->title ?? 'N/A' }}</td>
                                <td>{{ $borrowing->bookCopy?->copy_code ?? 'N/A' }}</td>
                                <td class="text-center">
                                    @if ($borrowing->status)
                                        <span
                                            class="badge rounded-pill bg-{{ $borrowing->status->badgeColor() }}">{{ $borrowing->status->label() }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center action-column">
                                    <a href="{{ route('admin.borrowings.show', $borrowing) }}"
                                        class="btn btn-sm btn-outline-primary" title="Lihat Detail Peminjaman">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada data peminjaman terbaru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stats-icon i {
            font-size: 2rem;
        }

        .card {
            border: none;
        }

        .table thead th {
            font-weight: 600;
            color: #6c757d;
            border-bottom-width: 1px;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .badge.rounded-pill {
            padding: 0.4em 0.8em;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .action-column {
            white-space: nowrap;
            width: 1%;
            text-align: center;
        }

        .action-column .btn .bi {
            vertical-align: middle;
        }
    </style>
@endsection

@section('script')
    @include('admin.components.datatable_script', ['table_id' => 'tableRecentActivity'])
@endsection
