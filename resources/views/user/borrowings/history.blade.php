@extends('user.components.main')

@section('title', 'Riwayat Peminjaman')
@section('page-title', 'Riwayat Peminjaman Buku')

@section('content')

    @include('admin.components.flash_messages')
    @include('admin.components.validation_errors')

    <ul class="nav nav-tabs nav-fill mb-3" id="borrowingsTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active-tab-pane" type="button"
                role="tab" aria-controls="active-tab-pane" aria-selected="true">
                <i class="bi bi-arrow-up-right-square-fill me-1"></i> Sedang Dipinjam
                <span class="badge rounded-pill bg-primary ms-1">{{ $activeBorrowings->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="past-tab" data-bs-toggle="tab" data-bs-target="#past-tab-pane" type="button"
                role="tab" aria-controls="past-tab-pane" aria-selected="false">
                <i class="bi bi-check-circle-fill me-1"></i> Riwayat Selesai
                <span class="badge rounded-pill bg-secondary ms-1">{{ $pastBorrowings->total() }}</span>
            </button>
        </li>
    </ul>

    <div class="tab-content" id="borrowingsTabContent">
        <div class="tab-pane fade show active" id="active-tab-pane" role="tabpanel" aria-labelledby="active-tab"
            tabindex="0">
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    @if ($activeBorrowings->isEmpty())
                        <div class="p-5 text-center">
                            <i class="bi bi-info-circle fs-3 text-info"></i>
                            <p class="mb-0 mt-2">Anda sedang tidak meminjam buku.</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach ($activeBorrowings as $borrowing)
                                <div class="list-group-item p-3">
                                    <div class="row align-items-center g-3">
                                        <div class="col-md-5 d-flex align-items-center">
                                            <img src="{{ $borrowing->bookCopy?->book?->cover_image ? asset('storage/' . $borrowing->bookCopy->book->cover_image) : asset('assets/images/no-image-book.png') }}"
                                                alt="{{ $borrowing->bookCopy?->book?->title ?? 'Buku' }}"
                                                class="img-fluid rounded shadow-sm me-3"
                                                style="width: 50px; height: 70px; object-fit: cover;">
                                            <div>
                                                <a href="{{ route('catalog.show', $borrowing->bookCopy?->book?->slug ?? '#') }}"
                                                    class="text-decoration-none text-dark fw-bold">
                                                    {{ $borrowing->bookCopy?->book?->title ?? 'Judul Tidak Diketahui' }}
                                                </a>
                                                <div class="small text-muted">Kode:
                                                    {{ $borrowing->bookCopy?->copy_code ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-2">
                                            <div class="small text-muted">Tgl Pinjam</div>
                                            <div class="fw-medium">
                                                {{ $borrowing->borrow_date?->isoFormat('D MMM YY') ?? '-' }}</div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="small text-muted">Jatuh Tempo</div>
                                            <div class="fw-bold {{ $borrowing->is_overdue ? 'text-danger' : '' }}">
                                                {{ $borrowing->due_date?->isoFormat('D MMM YY') ?? '-' }}
                                                @if ($borrowing->is_overdue)
                                                    <span class="badge bg-danger ms-1">Lewat Tempo!</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-2 text-md-end mt-2 mt-md-0">
                                            @if ($borrowing->lost_report_exists)
                                                <span class="text-success fst-italic small">
                                                    <i class="bi bi-check-circle me-1"></i>Sudah Dilaporkan
                                                </span>
                                            @else
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#reportLostModal-{{ $borrowing->id }}">
                                                    <i class="bi bi-exclamation-triangle"></i> Laporkan Hilang
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="past-tab-pane" role="tabpanel" aria-labelledby="past-tab" tabindex="0">
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    @if ($pastBorrowings->isEmpty())
                        <div class="p-5 text-center">
                            <i class="bi bi-collection fs-3 text-secondary"></i>
                            <p class="mb-0 mt-2">Belum ada riwayat peminjaman yang selesai.</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach ($pastBorrowings as $borrowing)
                                <div class="list-group-item p-3">
                                    <div class="row align-items-center g-3">
                                        <div class="col-lg-4 col-md-12">
                                            <a href="{{ route('catalog.show', $borrowing->bookCopy?->book?->slug ?? '#') }}"
                                                class="text-decoration-none text-dark fw-bold">
                                                {{ $borrowing->bookCopy?->book?->title ?? 'Judul Tidak Diketahui' }}
                                            </a>
                                            <div class="small text-muted">Kode:
                                                {{ $borrowing->bookCopy?->copy_code ?? 'N/A' }}</div>
                                        </div>
                                        <div class="col-lg-6 col-md-8">
                                            <div class="row">
                                                <div class="col-4"><span class="small text-muted">Tgl
                                                        Pinjam:</span><br>{{ $borrowing->borrow_date?->isoFormat('D MMM YY') ?? '-' }}
                                                </div>
                                                <div class="col-4"><span class="small text-muted">Tgl
                                                        Kembali:</span><br>{{ $borrowing->return_date?->isoFormat('D MMM YY') ?? '-' }}
                                                </div>
                                                <div class="col-4"><span class="small text-muted">Status:</span><br><span
                                                        class="badge bg-{{ $borrowing->status->badgeColor() }}">{{ $borrowing->status->label() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-4 text-md-end">
                                            @if ($borrowing->fine)
                                                <span class="fw-bold">Rp
                                                    {{ number_format($borrowing->fine->amount, 0, ',', '.') }}</span>
                                                <span
                                                    class="ms-1 badge bg-{{ $borrowing->fine->status->badgeColor() }}">{{ $borrowing->fine->status->label() }}</span>
                                                @if (!empty($borrowing->fine->notes))
                                                    <button type="button" class="btn btn-xs btn-outline-secondary ms-1"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#notesModal-{{ $borrowing->fine->id }}"
                                                        title="Lihat Catatan Denda">
                                                        <i class="bi bi-chat-left-text"></i>
                                                    </button>
                                                @endif
                                            @else
                                                <span class="text-muted small">- Tidak ada denda -</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="p-3 d-flex justify-content-end">
                            {{ $pastBorrowings->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @foreach ($pastBorrowings as $borrowing)
        @if ($borrowing->fine && !empty($borrowing->fine->notes))
            <div class="modal fade" id="notesModal-{{ $borrowing->fine->id }}" tabindex="-1"
                aria-labelledby="notesModalLabel-{{ $borrowing->fine->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="notesModalLabel-{{ $borrowing->fine->id }}"><i
                                    class="bi bi-chat-left-text me-2"></i>Catatan Denda</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Buku:</strong> {{ $borrowing->bookCopy?->book?->title ?? 'N/A' }}
                                ({{ $borrowing->bookCopy?->copy_code ?? 'N/A' }})</p>
                            <p><strong>Jumlah Denda:</strong> Rp {{ number_format($borrowing->fine->amount, 0, ',', '.') }}
                            </p>
                            <p><strong>Status:</strong> <span
                                    class="badge bg-{{ $borrowing->fine->status->badgeColor() }}">{{ $borrowing->fine->status->label() }}</span>
                            </p>
                            <hr>
                            <p><strong>Catatan:</strong></p>
                            <div style="white-space: pre-wrap;">{!! nl2br(e($borrowing->fine->notes)) !!}</div>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">Tutup</button></div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    @foreach ($activeBorrowings as $borrowing)
        @if (!$borrowing->lost_report_exists)
            <div class="modal fade" id="reportLostModal-{{ $borrowing->id }}" tabindex="-1"
                aria-labelledby="reportLostModalLabel-{{ $borrowing->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <form action="{{ route('user.borrowings.report-lost', $borrowing) }}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="reportLostModalLabel-{{ $borrowing->id }}"><i
                                        class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Konfirmasi Laporan
                                    Kehilangan</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Apakah Anda yakin ingin melaporkan buku berikut sebagai **hilang**?</p>
                                <ul>
                                    <li>Judul: <strong>{{ $borrowing->bookCopy?->book?->title ?? 'N/A' }}</strong></li>
                                    <li>Kode Eksemplar: <strong>{{ $borrowing->bookCopy?->copy_code ?? 'N/A' }}</strong>
                                    </li>
                                    <li>Dipinjam Tanggal:
                                        <strong>{{ $borrowing->borrow_date?->isoFormat('D MMM YY') ?? '-' }}</strong></li>
                                </ul>
                                <p class="text-danger small">Melaporkan buku hilang akan diteruskan ke petugas untuk
                                    diproses lebih lanjut dan mungkin akan ada konsekuensi denda penggantian.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">Ya, Laporkan Hilang</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endforeach
@endsection

@section('css')
    <style>
        .nav-tabs .nav-link {
            color: var(--bs-secondary-color);
            font-weight: 500;
        }

        .nav-tabs .nav-link.active {
            color: var(--bs-primary);
            font-weight: 700;
            border-color: #dee2e6 #dee2e6 #fff;
        }

        .btn-xs {
            --bs-btn-padding-y: .1rem;
            --bs-btn-padding-x: .3rem;
            --bs-btn-font-size: .75rem;
        }
    </style>
@endsection
