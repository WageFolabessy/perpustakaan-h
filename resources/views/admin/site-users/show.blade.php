@extends('admin.components.main')

@section('title', 'Detail Siswa')
@section('page-title', 'Profil Siswa')

@section('content')
    <div class="card shadow-sm rounded-4 border-0 mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="profile-avatar-lg">
                    <span>{{ strtoupper(substr($siteUser->name, 0, 1)) }}</span>
                </div>
                <div class="ms-3">
                    <h4 class="mb-0 fw-bold">{{ $siteUser->name }}</h4>
                    <span class="text-muted">NIS: {{ $siteUser->nis }}</span>
                </div>
                <div class="ms-auto">
                    <a href="{{ route('admin.site-users.edit', $siteUser) }}" class="btn btn-warning" title="Edit Siswa">
                        <i class="bi bi-pencil-fill me-1"></i> Edit Profil
                    </a>
                    <a href="{{ route('admin.site-users.index') }}" class="btn btn-secondary" title="Kembali ke Daftar">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm rounded-4 border-0">
        <div class="card-body pt-3">
            <ul class="nav nav-tabs nav-tabs-bordered" id="studentProfileTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="history-tab" data-bs-toggle="tab"
                        data-bs-target="#borrowing-history" type="button" role="tab" aria-controls="borrowing-history"
                        aria-selected="true">Riwayat Peminjaman</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="details-tab" data-bs-toggle="tab" data-bs-target="#user-details"
                        type="button" role="tab" aria-controls="user-details" aria-selected="false">Informasi
                        Detail</button>
                </li>
            </ul>

            <div class="tab-content pt-3" id="studentProfileTabContent">

                <div class="tab-pane fade show active" id="borrowing-history" role="tabpanel" aria-labelledby="history-tab">
                    <div class="p-3">
                        @if ($siteUser->borrowings->isEmpty())
                            <div class="alert alert-info text-center">
                                Siswa ini belum pernah melakukan peminjaman.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Judul Buku</th>
                                            <th>Tgl Pinjam</th>
                                            <th>Jatuh Tempo</th>
                                            <th>Tgl Kembali</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($siteUser->borrowings as $borrowing)
                                            <tr class="align-middle">
                                                <td>
                                                    <div class="fw-semibold">
                                                        {{ $borrowing->bookCopy?->book?->title ?: 'N/A' }}</div>
                                                    <small class="text-muted">Kode:
                                                        {{ $borrowing->bookCopy?->copy_code ?: 'N/A' }}</small>
                                                </td>
                                                <td>{{ $borrowing->borrow_date ? \Carbon\Carbon::parse($borrowing->borrow_date)->isoFormat('D MMM YYYY') : '-' }}
                                                </td>
                                                <td>{{ $borrowing->due_date ? \Carbon\Carbon::parse($borrowing->due_date)->isoFormat('D MMM YYYY') : '-' }}
                                                </td>
                                                <td>{{ $borrowing->return_date ? \Carbon\Carbon::parse($borrowing->return_date)->isoFormat('D MMM YYYY') : '-' }}
                                                </td>
                                                <td class="text-center">
                                                    @if ($borrowing->status)
                                                        <span
                                                            class="badge rounded-pill bg-{{ $borrowing->status->badgeColor() }}">{{ $borrowing->status->label() }}</span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="tab-pane fade" id="user-details" role="tabpanel" aria-labelledby="details-tab">
                    <div class="p-3">
                        <h5 class="card-title pb-2">Data Kontak & Akademik</h5>
                        <div class="detail-item row">
                            <div class="col-lg-3 col-md-4 label">Email</div>
                            <div class="col-lg-9 col-md-8">{{ $siteUser->email }}</div>
                        </div>
                        <div class="detail-item row">
                            <div class="col-lg-3 col-md-4 label">Kelas</div>
                            <div class="col-lg-9 col-md-8">{{ $siteUser->class ?: '-' }}</div>
                        </div>
                        <div class="detail-item row">
                            <div class="col-lg-3 col-md-4 label">Jurusan</div>
                            <div class="col-lg-9 col-md-8">{{ $siteUser->major ?: '-' }}</div>
                        </div>

                        <h5 class="card-title pt-4 pb-2">Data Sistem</h5>
                        <div class="detail-item row">
                            <div class="col-lg-3 col-md-4 label">Tanggal Daftar</div>
                            <div class="col-lg-9 col-md-8">
                                {{ $siteUser->created_at ? $siteUser->created_at->isoFormat('dddd, D MMMM YYYY - HH:mm') : '-' }}
                            </div>
                        </div>
                        <div class="detail-item row">
                            <div class="col-lg-3 col-md-4 label">Terakhir Diperbarui</div>
                            <div class="col-lg-9 col-md-8">
                                {{ $siteUser->updated_at ? $siteUser->updated_at->diffForHumans() : '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .profile-avatar-lg {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: var(--bs-primary-subtle);
            color: var(--bs-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 2.5rem;
        }

        .nav-tabs-bordered {
            border-bottom: 2px solid #dee2e6;
        }

        .nav-tabs-bordered .nav-link {
            margin-bottom: -2px;
            border: none;
            color: #6c757d;
            border-bottom: 2px solid transparent;
        }

        .nav-tabs-bordered .nav-link:hover,
        .nav-tabs-bordered .nav-link:focus {
            color: var(--bs-primary);
        }

        .nav-tabs-bordered .nav-link.active {
            background-color: transparent;
            color: var(--bs-primary);
            border-bottom: 2px solid var(--bs-primary);
            font-weight: 600;
        }

        .card-title {
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
            color: var(--bs-primary);
            font-weight: 600;
            border-bottom: 1px solid #eee;
        }

        .detail-item {
            padding: 10px 0;
            font-size: 0.9rem;
        }

        .detail-item .label {
            font-weight: 600;
            color: #6c757d;
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
    </style>
@endsection
