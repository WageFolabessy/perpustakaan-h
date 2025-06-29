@extends('admin.components.main')

@section('title', 'Manajemen Kategori')
@section('page-title', 'Manajemen Kategori')

@section('content')
    <div class="card shadow-sm rounded-4 border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 fw-semibold">Daftar Kategori Buku</h6>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
            </a>
        </div>
        <div class="card-body">
            @include('admin.components.flash_messages')

            @if ($categories->isEmpty())
                <div class="alert alert-info text-center">
                    Belum ada data kategori.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover datatable" id="dataTableCategories" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center no-sort" width="1%">No</th>
                                <th>Kategori</th>
                                <th class="text-center">Jumlah Buku</th>
                                <th class="text-center action-column no-sort">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr class="align-middle">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $category->name }}</div>
                                        <div class="text-muted small">
                                            <span
                                                class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill me-2">Slug:
                                                {{ $category->slug }}</span>
                                        </div>
                                        @if ($category->description)
                                            <p class="mb-0 mt-1 text-muted small fst-italic">
                                                {{ Str::limit($category->description, 70, '...') }}
                                            </p>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-subtle text-primary-emphasis rounded-pill fs-6">
                                            {{ $category->books_count ?? $category->books->count() }}
                                        </span>
                                    </td>
                                    <td class="action-column text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.categories.show', $category) }}"
                                                class="btn btn-outline-primary" title="Detail">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                            <a href="{{ route('admin.categories.edit', $category) }}"
                                                class="btn btn-outline-warning" title="Edit">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" title="Hapus"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $category->id }}">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @foreach ($categories as $category)
                    <div class="modal fade" id="deleteModal-{{ $category->id }}" tabindex="-1"
                        aria-labelledby="deleteModalLabel-{{ $category->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="deleteModalLabel-{{ $category->id }}">Konfirmasi Hapus
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Apakah Anda yakin ingin menghapus kategori: <strong>{{ $category->name }}</strong>?
                                    Tindakan ini tidak dapat dibatalkan dan mungkin memengaruhi data buku terkait.
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
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

        .action-column {
            white-space: nowrap;
            width: 1%;
        }
    </style>
@endsection

@section('script')
    @include('admin.components.datatable_script', ['table_id' => 'dataTableCategories'])
@endsection
