@extends('admin.components.main')

@section('title', 'Buku Lewat Tempo')
@section('page-title', 'Daftar Buku Lewat Tempo')

@section('content')
    <div class="card shadow-sm rounded-4 border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 fw-semibold">Daftar Peminjaman Lewat Tempo</h6>
            <a href="{{ route('admin.borrowings.index') }}" class="btn btn-secondary">
                <i class="bi bi-list-ul me-1"></i> Lihat Semua Peminjaman
            </a>
        </div>
        <div class="card-body">
            @include('admin.components.flash_messages')
            @include('admin.components.validation_errors')

            @if ($overdueBorrowings->isEmpty())
                <div class="alert alert-success text-center">
                    <i class="bi bi-check-circle-fill fs-3 d-block mb-2"></i>
                    Bagus! Tidak ada buku yang sedang lewat tempo saat ini.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover datatable" id="dataTableOverdue" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center no-sort" width="1%">No</th>
                                <th>Peminjam & Buku</th>
                                <th>Informasi Keterlambatan</th>
                                <th class="text-center action-column no-sort">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($overdueBorrowings as $borrowing)
                                <tr class="align-middle">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-3">
                                                <div class="fw-semibold">{{ $borrowing->siteUser?->name ?? 'N/A' }}</div>
                                                <div class="text-muted small">
                                                    <i class="bi bi-book-fill me-1"></i>
                                                    {{ $borrowing->bookCopy?->book?->title ?? 'N/A' }}
                                                    (Kode: {{ $borrowing->bookCopy?->copy_code ?? 'N/A' }})
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="small">
                                        <div>
                                            <span class="text-muted">Jatuh Tempo:</span>
                                            <strong
                                                class="text-danger">{{ $borrowing->due_date ? $borrowing->due_date->isoFormat('D MMM YYYY') : '-' }}</strong>
                                        </div>
                                        <div class="mt-1">
                                            <span class="badge rounded-pill bg-danger-subtle text-danger-emphasis fs-6">
                                                Terlambat {{ $borrowing->days_overdue ?? 'N/A' }} Hari
                                            </span>
                                        </div>
                                        <div class="text-muted mt-1">Dipinjam sejak:
                                            {{ $borrowing->borrow_date ? $borrowing->borrow_date->isoFormat('D MMM YY') : '-' }}
                                        </div>
                                    </td>
                                    <td class="action-column text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.borrowings.show', $borrowing) }}"
                                                class="btn btn-outline-primary" title="Detail">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-success"
                                                title="Proses Pengembalian" data-bs-toggle="modal"
                                                data-bs-target="#returnModal-{{ $borrowing->id }}">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @foreach ($overdueBorrowings as $borrowing)
                    <div class="modal fade" id="returnModal-{{ $borrowing->id }}" tabindex="-1"
                        aria-labelledby="returnModalLabel-{{ $borrowing->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="{{ route('admin.borrowings.return', $borrowing) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="returnModalLabel-{{ $borrowing->id }}">Konfirmasi
                                            Pengembalian</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Anda akan memproses pengembalian untuk buku
                                            <strong>{{ $borrowing->bookCopy?->book?->title ?? 'N/A' }}</strong> oleh
                                            <strong>{{ $borrowing->siteUser?->name ?? 'N/A' }}</strong>.
                                        </p>
                                        <ul class="list-unstyled">
                                            <li>Jatuh Tempo: <strong
                                                    class="text-danger">{{ $borrowing->due_date ? $borrowing->due_date->isoFormat('D MMM YYYY') : '-' }}</strong>
                                            </li>
                                            <li>Terlambat: <strong
                                                    class="text-danger">{{ $borrowing->days_overdue ?? 'N/A' }}
                                                    Hari</strong></li>
                                        </ul>
                                        <p>Sistem akan menghitung denda secara otomatis.</p>
                                        <div class="mb-3">
                                            <label for="return_notes-ovd-{{ $borrowing->id }}" class="form-label">Catatan
                                                Pengembalian (Opsional):</label>
                                            <textarea class="form-control @error('return_notes') is-invalid @enderror" id="return_notes-ovd-{{ $borrowing->id }}"
                                                name="return_notes" rows="3">{{ old('return_notes') }}</textarea>
                                            @error('return_notes')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-success"><i
                                                class="bi bi-check-circle-fill me-1"></i> Ya, Proses Pengembalian</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
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
    </style>
@endsection

@section('script')
    @include('admin.components.datatable_script', ['table_id' => 'dataTableOverdue'])
@endsection
