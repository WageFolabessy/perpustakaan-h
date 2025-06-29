@extends('admin.components.main')

@section('title', 'Detail Buku')
@section('page-title')
    Detail Buku
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="m-0">Profil Buku: <span class="fw-light">{{ $book->title }}</span></h5>
        <div>
            <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-warning" title="Edit Buku">
                <i class="bi bi-pencil-fill me-1"></i> Edit
            </a>
            <a href="{{ route('admin.books.index') }}" class="btn btn-secondary" title="Kembali ke Daftar">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow-sm rounded-4 border-0 mb-4">
                <div class="card-body text-center p-4">
                    <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : asset('assets/images/no-image.png') }}"
                        alt="Sampul {{ $book->title }}" class="img-fluid rounded-3" style="max-height: 400px;">
                </div>
            </div>

            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-semibold">Daftar Eksemplar ({{ $book->copies->count() }} Total)</h6>
                </div>
                <div class="card-body p-0">
                    @if ($book->copies->isEmpty())
                        <div class="alert alert-info text-center m-3">
                            Belum ada data eksemplar untuk buku ini.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Kode</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center pe-3">Kondisi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($book->copies as $copy)
                                        <tr class="align-middle">
                                            <td class="ps-3 fw-semibold">{{ $copy->copy_code }}</td>
                                            <td class="text-center">
                                                <span
                                                    class="badge rounded-pill bg-{{ $copy->status->badgeColor() }}">{{ $copy->status->label() }}</span>
                                            </td>
                                            <td class="text-center pe-3">
                                                <span
                                                    class="badge rounded-pill bg-{{ $copy->condition->badgeColor() }}">{{ $copy->condition->label() }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-body pt-3">
                    <ul class="nav nav-tabs nav-tabs-bordered" id="bookDetailTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="overview-tab" data-bs-toggle="tab"
                                data-bs-target="#book-overview" type="button" role="tab" aria-controls="book-overview"
                                aria-selected="true">Detail Utama</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="additional-tab" data-bs-toggle="tab"
                                data-bs-target="#book-additional" type="button" role="tab"
                                aria-controls="book-additional" aria-selected="false">Info Tambahan</button>
                        </li>
                    </ul>

                    <div class="tab-content pt-3" id="bookDetailTabContent">

                        <div class="tab-pane fade show active p-3" id="book-overview" role="tabpanel"
                            aria-labelledby="overview-tab">

                            <h5 class="card-title pb-2">Sinopsis</h5>
                            <p class="small fst-italic">{!! nl2br(e($book->synopsis)) ?: 'Tidak ada sinopsis.' !!}</p>

                            <h5 class="card-title pt-3 pb-2">Informasi Umum</h5>
                            <div class="detail-item row">
                                <div class="col-lg-3 col-md-4 label">Judul Lengkap</div>
                                <div class="col-lg-9 col-md-8">{{ $book->title }}</div>
                            </div>
                            <div class="detail-item row">
                                <div class="col-lg-3 col-md-4 label">Pengarang</div>
                                <div class="col-lg-9 col-md-8">{{ $book->author?->name ?: '-' }}</div>
                            </div>
                            <div class="detail-item row">
                                <div class="col-lg-3 col-md-4 label">Penerbit</div>
                                <div class="col-lg-9 col-md-8">{{ $book->publisher?->name ?: '-' }}</div>
                            </div>
                            <div class="detail-item row">
                                <div class="col-lg-3 col-md-4 label">Kategori</div>
                                <div class="col-lg-9 col-md-8">{{ $book->category?->name ?: '-' }}</div>
                            </div>
                            <div class="detail-item row">
                                <div class="col-lg-3 col-md-4 label">ISBN</div>
                                <div class="col-lg-9 col-md-8">{{ $book->isbn ?: '-' }}</div>
                            </div>
                        </div>

                        <div class="tab-pane fade p-3" id="book-additional" role="tabpanel"
                            aria-labelledby="additional-tab">
                            <h5 class="card-title pb-2">Data Lainnya</h5>
                            <div class="detail-item row">
                                <div class="col-lg-3 col-md-4 label">Tahun Terbit</div>
                                <div class="col-lg-9 col-md-8">{{ $book->publication_year ?: '-' }}</div>
                            </div>
                            <div class="detail-item row">
                                <div class="col-lg-3 col-md-4 label">Lokasi Rak</div>
                                <div class="col-lg-9 col-md-8">{{ $book->location ?: '-' }}</div>
                            </div>
                            <div class="detail-item row">
                                <div class="col-lg-3 col-md-4 label">Ditambahkan Pada</div>
                                <div class="col-lg-9 col-md-8">
                                    {{ $book->created_at ? $book->created_at->isoFormat('D MMMM YYYY, HH:mm') : '-' }}
                                </div>
                            </div>
                            <div class="detail-item row">
                                <div class="col-lg-3 col-md-4 label">Diperbarui Pada</div>
                                <div class="col-lg-9 col-md-8">
                                    {{ $book->updated_at ? $book->updated_at->isoFormat('D MMMM YYYY, HH:mm') : '-' }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
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
            padding-bottom: 1rem;
            margin-bottom: 0;
            color: var(--bs-primary);
            font-weight: 600;
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
