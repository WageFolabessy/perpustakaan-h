@extends('admin.components.main')

@section('title', 'Edit Buku')
@section('page-title')
    Edit Buku: <span class="fw-normal fst-italic">{{ $book->title }}</span>
@endsection

@section('content')
    <form action="{{ route('admin.books.update', $book) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <div class="card shadow-sm rounded-4 border-0 mb-4">
            <div class="card-body pt-3">
                <ul class="nav nav-tabs nav-tabs-bordered" id="bookEditTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#book-details"
                            type="button" role="tab" aria-controls="book-details" aria-selected="true">Detail
                            Buku</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="copies-tab" data-bs-toggle="tab" data-bs-target="#book-copies"
                            type="button" role="tab" aria-controls="book-copies" aria-selected="false">Manajemen
                            Eksemplar</button>
                    </li>
                </ul>

                <div class="tab-content pt-2" id="bookEditTabContent">

                    <div class="tab-pane fade show active" id="book-details" role="tabpanel" aria-labelledby="details-tab">
                        <div class="p-3">
                            @include('admin.components.validation_errors')
                            @include('admin.books._form', ['book' => $book])
                        </div>
                    </div>

                    <div class="tab-pane fade" id="book-copies" role="tabpanel" aria-labelledby="copies-tab">
                        <div class="p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="m-0 fw-semibold">Daftar Eksemplar</h6>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#addCopyModal">
                                    <i class="bi bi-plus-lg me-1"></i> Tambah Eksemplar
                                </button>
                            </div>

                            @if (session('success_copy'))
                                <div class="alert alert-success d-flex align-items-center" role="alert">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    <div>{{ session('success_copy') }}</div>
                                </div>
                            @endif
                            @if (session('error_copy'))
                                <div class="alert alert-danger d-flex align-items-center" role="alert">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <div>{{ session('error_copy') }}</div>
                                </div>
                            @endif
                            @if ($errors->storeCopy->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <h6 class="alert-heading">Gagal Menambahkan Eksemplar:</h6>
                                    <ul>
                                        @foreach ($errors->storeCopy->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            @php $editErrorFound = false; @endphp
                            @foreach ($book->copies as $copy)
                                @if ($errors->{'updateCopy_' . $copy->id}->any() && !$editErrorFound)
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <h6 class="alert-heading">Gagal Memperbarui Eksemplar ({{ $copy->copy_code }}):</h6>
                                        <ul>
                                            @foreach ($errors->{'updateCopy_' . $copy->id}->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                    @php $editErrorFound = true; @endphp
                                @endif
                            @endforeach

                            @if ($book->copies->isEmpty())
                                <div class="alert alert-info text-center">
                                    Belum ada data eksemplar untuk buku ini.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Kode Eksemplar</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Kondisi</th>
                                                <th>Ditambahkan</th>
                                                <th class="action-column">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($book->copies as $copy)
                                                <tr class="align-middle">
                                                    <td class="fw-semibold">{{ $copy->copy_code }}</td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge rounded-pill bg-{{ $copy->status->badgeColor() }}">{{ $copy->status->label() }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge rounded-pill bg-{{ $copy->condition->badgeColor() }}">{{ $copy->condition->label() }}</span>
                                                    </td>
                                                    <td>{{ $copy->created_at ? $copy->created_at->diffForHumans() : '-' }}
                                                    </td>
                                                    <td class="action-column">
                                                        <div class="btn-group btn-group-sm">
                                                            <button type="button" class="btn btn-outline-warning"
                                                                title="Edit Eksemplar" data-bs-toggle="modal"
                                                                data-bs-target="#editCopyModal-{{ $copy->id }}">
                                                                <i class="bi bi-pencil-fill"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-outline-danger"
                                                                title="Hapus Eksemplar" data-bs-toggle="modal"
                                                                data-bs-target="#deleteCopyModal-{{ $copy->id }}">
                                                                <i class="bi bi-trash-fill"></i>
                                                            </button>
                                                        </div>
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
            </div>
            <div class="card-footer bg-white d-flex justify-content-end border-0 pt-0 pb-3">
                <a href="{{ route('admin.books.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan Buku</button>
            </div>
        </div>
    </form>

    <div class="modal fade" id="addCopyModal" tabindex="-1" aria-labelledby="addCopyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addCopyModalLabel">Tambah Eksemplar Baru</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.books.copies.store', $book) }}" method="POST">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="copy_code" class="form-label">Kode Eksemplar</label>
                            <input type="text"
                                class="form-control @error('copy_code', 'storeCopy') is-invalid @enderror" id="copy_code"
                                name="copy_code" value="{{ old('copy_code') }}" required>
                            @error('copy_code', 'storeCopy')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="condition" class="form-label">Kondisi Awal</label>
                            <select class="form-select @error('condition', 'storeCopy') is-invalid @enderror"
                                id="condition" name="condition">
                                @foreach ($conditions as $conditionEnum)
                                    <option value="{{ $conditionEnum->value }}"
                                        {{ old('condition') == $conditionEnum->value || $conditionEnum === App\Enum\BookCondition::Good ? 'selected' : '' }}>
                                        {{ $conditionEnum->label() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('condition', 'storeCopy')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Eksemplar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($book->copies as $copy)
        <div class="modal fade" id="editCopyModal-{{ $copy->id }}" tabindex="-1"
            aria-labelledby="editCopyModalLabel-{{ $copy->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editCopyModalLabel-{{ $copy->id }}">Edit Eksemplar:
                            {{ $copy->copy_code }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.book-copies.update', $copy) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="edit_status_{{ $copy->id }}" class="form-label">Status</label>
                                <select
                                    class="form-select @error('status', 'updateCopy_' . $copy->id) is-invalid @enderror"
                                    id="edit_status_{{ $copy->id }}" name="status"
                                    {{ $copy->status === App\Enum\BookCopyStatus::Borrowed || $copy->status === App\Enum\BookCopyStatus::Booked ? 'disabled' : '' }}>
                                    @foreach ($statuses as $statusEnum)
                                        <option value="{{ $statusEnum->value }}"
                                            {{ old('status', $copy->status->value) == $statusEnum->value ? 'selected' : '' }}>
                                            {{ $statusEnum->label() }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($copy->status === App\Enum\BookCopyStatus::Borrowed || $copy->status === App\Enum\BookCopyStatus::Booked)
                                    <small class="text-danger d-block mt-1">Status tidak bisa diubah jika sedang
                                        dipinjam/dibooking.</small>
                                @endif
                                @error('status', 'updateCopy_' . $copy->id)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="edit_condition_{{ $copy->id }}" class="form-label">Kondisi</label>
                                <select
                                    class="form-select @error('condition', 'updateCopy_' . $copy->id) is-invalid @enderror"
                                    id="edit_condition_{{ $copy->id }}" name="condition">
                                    @foreach ($conditions as $conditionEnum)
                                        <option value="{{ $conditionEnum->value }}"
                                            {{ old('condition', $copy->condition->value) == $conditionEnum->value ? 'selected' : '' }}>
                                            {{ $conditionEnum->label() }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('condition', 'updateCopy_' . $copy->id)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary"
                                {{ $copy->status === App\Enum\BookCopyStatus::Borrowed || $copy->status === App\Enum\BookCopyStatus::Booked ? 'disabled' : '' }}>Simpan
                                Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteCopyModal-{{ $copy->id }}" tabindex="-1"
            aria-labelledby="deleteCopyModalLabel-{{ $copy->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="deleteCopyModalLabel-{{ $copy->id }}">Konfirmasi Hapus
                            Eksemplar</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus eksemplar dengan kode: <strong>{{ $copy->copy_code }}</strong>?
                        Tindakan ini tidak dapat dibatalkan. Pastikan eksemplar tidak sedang dipinjam atau dibooking.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('admin.book-copies.destroy', $copy) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                {{ $copy->status === App\Enum\BookCopyStatus::Borrowed || $copy->status === App\Enum\BookCopyStatus::Booked ? 'disabled' : '' }}>Ya,
                                Hapus Eksemplar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

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

        .image-upload-wrapper {
            position: relative;
            width: 100%;
            padding-top: 130%;
            border-radius: 0.75rem;
            overflow: hidden;
            background-color: #f8f9fa;
        }

        .image-upload-input {
            display: none;
        }

        .image-upload-label {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 2px dashed #dee2e6;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: border-color 0.2s;
        }

        .image-upload-label:hover {
            border-color: var(--bs-primary);
        }

        .image-upload-input:invalid+.image-upload-label {
            border-color: var(--bs-danger);
        }

        #image-preview {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 10;
        }

        .upload-instructions {
            text-align: center;
            color: #6c757d;
            z-index: 1;
        }

        .upload-instructions.has-image {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 0.5rem 0;
            opacity: 0;
            transition: opacity 0.2s;
            z-index: 20;
        }

        .image-upload-label:hover .upload-instructions.has-image {
            opacity: 1;
        }

        .upload-instructions i {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .upload-instructions p {
            margin-bottom: 0.25rem;
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
    </style>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('cover_image');
            const imagePreview = document.getElementById('image-preview');
            const uploadInstructions = document.querySelector('.upload-instructions');
            const instructionsWrapper = document.querySelector('.has-image');

            if (imageInput) {
                imageInput.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        imagePreview.style.display = 'block';
                        reader.onload = function(e) {
                            imagePreview.src = e.target.result;
                            if (instructionsWrapper) {
                                instructionsWrapper.style.display =
                                    'flex';
                            } else {
                                if (uploadInstructions) uploadInstructions.style.display = 'none';
                            }
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }
        });

        $(document).ready(function() {
            @if ($errors->hasBag('storeCopy'))
                var addModal = new bootstrap.Modal(document.getElementById('addCopyModal'));
                if (addModal) {
                    addModal.show();
                }
            @endif

            @php $errorCopyId = null; @endphp
            @foreach ($book->copies as $copy)
                @if ($errors->hasBag('updateCopy_' . $copy->id))
                    @php $errorCopyId = $copy->id; @endphp
                    @break
                @endif
            @endforeach

            @if ($errorCopyId)
                var editModal = new bootstrap.Modal(document.getElementById('editCopyModal-{{ $errorCopyId }}'));
                if (editModal) {
                    editModal.show();
                }
            @endif
        });
    </script>
@endsection
