@extends('admin.components.main')

@section('title', 'Manajemen Buku')
@section('page-title', 'Manajemen Buku')

@section('content')
    <div class="card shadow-sm rounded-4 border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 fw-semibold">Daftar Buku</h6>
            <a href="{{ route('admin.books.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Tambah Buku
            </a>
        </div>
        <div class="card-body">
            @include('admin.components.flash_messages')

            @if ($books->isEmpty())
                <div class="alert alert-info text-center">
                    Belum ada data buku.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover datatable" id="dataTableBooks" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center no-sort" width="1%">No</th>
                                <th class="text-center no-sort" width="5%">Sampul</th>
                                <th width="30%">Judul & ISBN</th>
                                <th>Pengarang</th>
                                <th>Kategori</th>
                                <th class="text-center">Jml Eksemplar</th>
                                <th class="text-center action-column no-sort">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($books as $book)
                                <tr class="align-middle">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : asset('assets/images/no-image.png') }}"
                                            alt="{{ $book->title }}" class="book-cover">
                                    </td>
                                    <td>
                                        <div class="fw-semibold book-title" title="{{ $book->title }}">{{ $book->title }}
                                        </div>
                                        <small class="text-muted d-block">ISBN: {{ $book->isbn ?: '-' }}</small>
                                    </td>
                                    <td>{{ $book->author?->name ?: '-' }}</td>
                                    <td>{{ $book->category?->name ?: '-' }}</td>
                                    <td class="text-center">{{ $book->copies_count }}</td>
                                    <td class="action-column text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.books.show', $book) }}"
                                                class="btn btn-outline-primary" title="Detail">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                            <a href="{{ route('admin.books.edit', $book) }}"
                                                class="btn btn-outline-warning" title="Edit">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" title="Hapus"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $book->id }}">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @foreach ($books as $book)
                    <div class="modal fade" id="deleteModal-{{ $book->id }}" tabindex="-1"
                        aria-labelledby="deleteModalLabel-{{ $book->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="deleteModalLabel-{{ $book->id }}">Konfirmasi Hapus
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Apakah Anda yakin ingin menghapus buku: <strong>{{ $book->title }}</strong>? Menghapus
                                    judul buku akan dicegah jika masih ada eksemplar terdaftar.
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <form action="{{ route('admin.books.destroy', $book) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                    </form>
                                </div>
                            </div>
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

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .book-cover {
            height: 60px;
            width: 50px;
            object-fit: cover;
            border-radius: 0.25rem;
        }

        .book-title {
            max-width: 350px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .action-column {
            white-space: nowrap;
            width: 1%;
        }
    </style>
@endsection

@section('script')
    @include('admin.components.datatable_script', ['table_id' => 'dataTableBooks'])
@endsection
