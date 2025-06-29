@extends('admin.components.main')

@section('title', 'Manajemen Admin')
@section('page-title', 'Manajemen Admin')

@section('content')
    <div class="card shadow-sm rounded-4 border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 fw-semibold">Daftar Semua Admin</h6>
            <a href="{{ route('admin.admin-users.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Tambah Admin
            </a>
        </div>
        <div class="card-body">
            @include('admin.components.flash_messages')

            @if ($adminUsers->isEmpty())
                <div class="alert alert-info text-center">
                    Belum ada data admin.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover datatable" id="dataTableAdminUsers" width="100%">
                        <thead>
                            <tr>
                                <th class="no-sort" width="1%">No</th>
                                <th>Admin</th>
                                <th>NIP</th>
                                <th class="text-center action-column no-sort">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($adminUsers as $user)
                                <tr class="align-middle">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-3">
                                                <div class="fw-semibold">{{ $user->name }}</div>
                                                <div class="text-muted small">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->nip }}</td>
                                    <td class="text-center action-column">
                                        @if (Auth::guard('admin')->id() !== $user->id)
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.admin-users.edit', $user) }}"
                                                    class="btn btn-outline-warning" title="Edit">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger" title="Hapus"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal-{{ $user->id }}">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </div>
                                        @else
                                            <span class="badge bg-primary-subtle text-primary-emphasis rounded-pill">(Akun
                                                Anda)</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @foreach ($adminUsers as $user)
                    @if (Auth::guard('admin')->id() !== $user->id)
                        <div class="modal fade" id="deleteModal-{{ $user->id }}" tabindex="-1"
                            aria-labelledby="deleteModalLabel-{{ $user->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="deleteModalLabel-{{ $user->id }}">Konfirmasi
                                            Hapus</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah Anda yakin ingin menghapus admin: <strong>{{ $user->name }}
                                            ({{ $user->nip }})</strong>?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <form action="{{ route('admin.admin-users.destroy', $user) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                        </form>
                                    </div>
                                </div>
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

    </style>
@endsection

@section('script')
    @include('admin.components.datatable_script', ['table_id' => 'dataTableAdminUsers'])
@endsection
