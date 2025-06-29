@extends('user.components.main')

@section('title', 'Denda Saya')
@section('page-title', 'Rincian Denda Saya')

@section('content')

    <div class="card border-0 shadow-sm mb-4">
        <div
            class="card-body p-4 text-center {{ $totalUnpaidFines > 0 ? 'bg-danger-subtle text-danger-emphasis' : 'bg-success-subtle text-success-emphasis' }}">
            <div class="row align-items-center">
                <div class="col-md-2 text-center mb-3 mb-md-0">
                    <i
                        class="bi {{ $totalUnpaidFines > 0 ? 'bi-exclamation-triangle-fill' : 'bi-check-circle-fill' }} display-4"></i>
                </div>
                <div class="col-md-10 text-md-start">
                    @if ($totalUnpaidFines > 0)
                        <h5 class="card-title fw-bold">Anda Memiliki Tanggungan Denda</h5>
                        <p class="mb-0">Total denda yang **belum Anda bayar** saat ini adalah
                            <strong class="fs-5">Rp {{ number_format($totalUnpaidFines, 0, ',', '.') }}</strong>.
                            Segera selesaikan pembayaran di petugas perpustakaan untuk dapat meminjam buku kembali.
                        </p>
                    @else
                        <h5 class="card-title fw-bold">Tidak Ada Tanggungan Denda</h5>
                        <p class="mb-0">Anda tidak memiliki tanggungan denda saat ini. Terima kasih telah menjadi anggota
                            yang tertib!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('admin.components.flash_messages')

    @php
        use App\Enum\FineStatus;

        $unpaidFines = $fines->filter(function ($fine) {
            return $fine->status === FineStatus::Unpaid;
        });

        $paidFines = $fines->filter(function ($fine) {
            return $fine->status === FineStatus::Paid;
        });
    @endphp

    <ul class="nav nav-tabs nav-fill mb-3" id="finesTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="unpaid-tab" data-bs-toggle="tab" data-bs-target="#unpaid-tab-pane"
                type="button" role="tab" aria-controls="unpaid-tab-pane" aria-selected="true">
                <i class="bi bi-hourglass-split me-1"></i> Belum Dibayar
                <span class="badge rounded-pill bg-danger ms-1">{{ $unpaidFinesCount }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="paid-tab" data-bs-toggle="tab" data-bs-target="#paid-tab-pane" type="button"
                role="tab" aria-controls="paid-tab-pane" aria-selected="false">
                <i class="bi bi-patch-check-fill me-1"></i> Riwayat Lunas
                <span class="badge rounded-pill bg-secondary ms-1">{{ $paidFinesCount }}</span>
            </button>
        </li>
    </ul>

    <div class="tab-content" id="finesTabContent">
        <div class="tab-pane fade show active" id="unpaid-tab-pane" role="tabpanel" aria-labelledby="unpaid-tab"
            tabindex="0">
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    @forelse ($unpaidFines as $fine)
                        <div class="list-group-item list-group-item-danger-soft p-3">
                            <div class="row align-items-center g-3">
                                <div class="col-md-5">
                                    <a href="{{ route('catalog.show', $fine->borrowing?->bookCopy?->book?->slug ?? '#') }}"
                                        class="text-decoration-none fw-bold text-dark d-block">
                                        {{ $fine->borrowing?->bookCopy?->book?->title ?? 'Judul Tidak Diketahui' }}
                                    </a>
                                    <small class="text-muted">Denda dibuat:
                                        {{ $fine->created_at?->isoFormat('D MMM YY') }}</small>
                                </div>
                                <div class="col-md-3">
                                    <span class="fw-bold fs-5 text-danger-emphasis">Rp
                                        {{ number_format($fine->amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    @if (!empty($fine->notes))
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                            data-bs-toggle="modal" data-bs-target="#fineNotesModal-{{ $fine->id }}">
                                            <i class="bi bi-chat-left-text"></i> Lihat Catatan
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-5 text-center">
                            <i class="bi bi-emoji-smile fs-3 text-success"></i>
                            <p class="mb-0 mt-2">Luar biasa! Tidak ada denda yang perlu dibayar.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="paid-tab-pane" role="tabpanel" aria-labelledby="paid-tab" tabindex="0">
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    @forelse ($paidFines as $fine)
                        <div class="list-group-item p-3">
                            <div class="row align-items-center g-3">
                                <div class="col-md-5">
                                    <a href="{{ route('catalog.show', $fine->borrowing?->bookCopy?->book?->slug ?? '#') }}"
                                        class="text-decoration-none text-dark d-block">
                                        {{ Str::limit($fine->borrowing?->bookCopy?->book?->title ?? 'Judul Tidak Diketahui', 50) }}
                                    </a>
                                    <small class="text-muted">Dibayar pada:
                                        {{ $fine->payment_date?->isoFormat('D MMM YY, HH:mm') }}</small>
                                </div>
                                <div class="col-md-3">
                                    <span class="fw-bold">Rp {{ number_format($fine->amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="col-md-2 text-md-center">
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Lunas</span>
                                </div>
                                <div class="col-md-2 text-md-end">
                                    @if (!empty($fine->notes))
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                            data-bs-toggle="modal" data-bs-target="#fineNotesModal-{{ $fine->id }}">
                                            <i class="bi bi-chat-left-text"></i> Lihat Catatan
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-5 text-center">
                            <i class="bi bi-collection fs-3 text-muted"></i>
                            <p class="mb-0 mt-2">Belum ada riwayat denda yang lunas.</p>
                        </div>
                    @endforelse
                </div>
                @if ($fines->hasPages())
                    <div class="card-footer bg-light">
                        {{ $fines->links('vendor.pagination.bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @foreach ($fines as $fine)
        @if (!empty($fine->notes))
            <div class="modal fade" id="fineNotesModal-{{ $fine->id }}" tabindex="-1"
                aria-labelledby="fineNotesModalLabel-{{ $fine->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="fineNotesModalLabel-{{ $fine->id }}"><i
                                    class="bi bi-chat-left-text me-2"></i>Catatan Denda</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Buku:</strong> {{ $fine->borrowing?->bookCopy?->book?->title ?? 'N/A' }}</p>
                            <p><strong>Jumlah:</strong> Rp {{ number_format($fine->amount, 0, ',', '.') }}</p>
                            <p><strong>Status:</strong> <span
                                    class="badge bg-{{ $fine->status->badgeColor() }}">{{ $fine->status->label() }}</span>
                            </p>
                            <hr>
                            <p><strong>Catatan dari Petugas:</strong></p>
                            <div class="p-2 bg-light rounded" style="white-space: pre-wrap;">{!! nl2br(e($fine->notes)) !!}</div>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">Tutup</button></div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

@endsection

@section('css')
    @parent
    <style>
        .nav-tabs .nav-link {
            color: var(--bs-secondary-color);
            font-weight: 500;
        }

        .nav-tabs .nav-link.active {
            color: var(--bs-primary);
            font-weight: 700;
        }

        .list-group-item-danger-soft {
            background-color: var(--bs-danger-bg-subtle);
        }
    </style>
@endsection
