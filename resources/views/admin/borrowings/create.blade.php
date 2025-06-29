@extends('admin.components.main')

@section('title', 'Catat Peminjaman Baru')
@section('page-title', 'Catat Peminjaman Baru')

@section('content')
    <form action="{{ route('admin.borrowings.store') }}" method="POST">
        @csrf
        <div class="card shadow-sm rounded-4 border-0">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-semibold">Formulir Peminjaman Buku</h6>
            </div>
            <div class="card-body p-4">
                @include('admin.components.flash_messages')
                @include('admin.components.validation_errors')

                <div class="mb-4">
                    <h5 class="form-step-title">1. Pilih Peminjam & Buku</h5>
                    <p class="text-muted small">Gunakan kotak pencarian untuk menemukan siswa dan eksemplar buku yang
                        tersedia.</p>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="site_user_id" class="form-label fw-semibold">Siswa Peminjam</label>
                        <select class="form-select @error('site_user_id') is-invalid @enderror" id="site_user_id"
                            name="site_user_id" required data-placeholder="Ketik untuk mencari nama atau NIS siswa...">
                            <option></option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}"
                                    {{ old('site_user_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }} (NIS: {{ $student->nis }})
                                </option>
                            @endforeach
                        </select>
                        @error('site_user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="book_copy_id" class="form-label fw-semibold">Eksemplar Buku</label>
                        <select class="form-select @error('book_copy_id') is-invalid @enderror" id="book_copy_id"
                            name="book_copy_id" required data-placeholder="Ketik untuk mencari kode atau judul buku...">
                            <option></option>
                            @foreach ($availableCopies as $copy)
                                <option value="{{ $copy->id }}"
                                    {{ old('book_copy_id') == $copy->id ? 'selected' : '' }}>
                                    {{ $copy->copy_code }} - {{ Str::limit($copy->book?->title ?? 'N/A', 50) }}
                                </option>
                            @endforeach
                        </select>
                        @error('book_copy_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="section-divider"></div>

                <div class="mb-4">
                    <h5 class="form-step-title">2. Tentukan Tanggal</h5>
                    <p class="text-muted small">Tanggal kembali akan dihitung otomatis berdasarkan durasi peminjaman yang
                        diatur di sistem.</p>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="borrow_date" class="form-label fw-semibold">Tanggal Pinjam</label>
                        <input type="date" class="form-control @error('borrow_date') is-invalid @enderror"
                            id="borrow_date" name="borrow_date" value="{{ old('borrow_date', now()->format('Y-m-d')) }}">
                        @error('borrow_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Tanggal Kembali (Jatuh Tempo)</label>
                        <input type="text" class="form-control" id="due_date_display" readonly
                            style="background-color: #e9ecef; border: 1px solid #ced4da;">
                    </div>
                </div>

            </div>
            <div class="card-footer bg-white d-flex justify-content-end border-0 pt-0 pb-3 px-4">
                <a href="{{ route('admin.borrowings.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save-fill me-1"></i> Catat Peminjaman
                </button>
            </div>
        </div>
    </form>
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <style>
        .form-step-title {
            color: var(--bs-primary);
            font-weight: 600;
        }

        .section-divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.5rem 0;
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
    </style>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#site_user_id').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
            $('#book_copy_id').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const borrowDateInput = document.getElementById('borrow_date');
            const dueDateDisplay = document.getElementById('due_date_display');
            const loanDuration = parseInt("{{ $loanDuration ?? 7 }}");

            function calculateAndDisplayDueDate() {
                const borrowDateValue = borrowDateInput.value;
                if (borrowDateValue) {
                    try {
                        let borrowDate = new Date(borrowDateValue +
                            "T00:00:00");
                        if (isNaN(borrowDate.getTime())) {
                            dueDateDisplay.value = 'Tgl Pinjam Invalid';
                            return;
                        }
                        let dueDate = new Date(borrowDate.getTime());
                        dueDate.setDate(dueDate.getDate() + loanDuration);

                        const day = String(dueDate.getDate()).padStart(2, '0');
                        const month = String(dueDate.getMonth() + 1).padStart(2, '0');
                        const year = dueDate.getFullYear();

                        dueDateDisplay.value = `${day}-${month}-${year}`;
                    } catch (e) {
                        dueDateDisplay.value = 'Error';
                    }
                } else {
                    dueDateDisplay.value = '';
                }
            }

            borrowDateInput.addEventListener('change', calculateAndDisplayDueDate);
            calculateAndDisplayDueDate();
        });
    </script>
@endsection
