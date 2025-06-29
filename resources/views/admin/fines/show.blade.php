@extends('admin.components.main')

@section('title', 'Detail Denda')
@section('page-title')
    Detail Denda (ID: {{ $fine->id }})
@endsection

@section('content')
    <div class="card shadow-sm rounded-4 border-0">
        <div class="card-header bg-white p-3 d-flex flex-row align-items-center justify-content-between">
            <div>
                <h6 class="m-0 fw-semibold">Jumlah Denda</h6>
                <div class="d-flex align-items-center">
                    <h4 class="fw-bold mb-0 me-2">Rp {{ number_format($fine->amount, 0, ',', '.') }}</h4>
                    @if ($fine->status)
                        <span
                            class="badge rounded-pill fs-6 bg-{{ $fine->status->badgeColor() }}">{{ $fine->status->label() }}</span>
                    @endif
                </div>
            </div>
            <div>
                @if ($fine->status === App\Enum\FineStatus::Unpaid)
                    <button type="button" class="btn btn-success" title="Tandai Lunas" data-bs-toggle="modal"
                        data-bs-target="#payModal-{{ $fine->id }}">
                        <i class="bi bi-cash-coin me-1"></i> Bayar
                    </button>
                    <button type="button" class="btn btn-warning" title="Bebaskan Denda" data-bs-toggle="modal"
                        data-bs-target="#waiveModal-{{ $fine->id }}">
                        <i class="bi bi-shield-slash me-1"></i> Bebaskan
                    </button>
                @endif
                <a href="{{ route('admin.fines.index') }}" class="btn btn-secondary ms-1" title="Kembali ke Daftar Denda">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card-body p-4">
            @include('admin.components.flash_messages')
            @include('admin.components.validation_errors')

            <div class="section-divider"><span>Detail Pihak & Objek Terkait</span></div>

            <div class="row">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="detail-block">
                        <h6 class="detail-block-title">Siswa Terdenda</h6>
                        <div class="d-flex align-items-center">
                            <div class="ms-3">
                                <div class="fw-bold">{{ $fine->borrowing?->siteUser?->name ?? 'N/A' }}</div>
                                <div class="text-muted">NIS: {{ $fine->borrowing?->siteUser?->nis ?? 'N/A' }}</div>
                            </div>
                            <a href="{{ $fine->borrowing?->siteUser ? route('admin.site-users.show', $fine->borrowing->siteUser) : '#' }}"
                                class="btn btn-sm btn-outline-primary ms-auto">Profil Siswa</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="detail-block">
                        <h6 class="detail-block-title">Buku Terkait</h6>
                        <div class="d-flex align-items-center">
                            <div class="ms-3">
                                <div class="fw-bold">{{ $fine->borrowing?->bookCopy?->book?->title ?? 'N/A' }}</div>
                                <div class="text-muted">Kode: {{ $fine->borrowing?->bookCopy?->copy_code ?? 'N/A' }}</div>
                            </div>
                            <a href="{{ $fine->borrowing?->bookCopy?->book ? route('admin.books.show', $fine->borrowing?->bookCopy?->book) : '#' }}"
                                class="btn btn-sm btn-outline-primary ms-auto">Detail Buku</a>
                        </div>
                    </div>
                </div>
            </div>

            @if ($fine->borrowing)
                <div class="section-divider"><span>Informasi Peminjaman Terkait</span></div>
                <div class="detail-block d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="detail-block-title mb-1">Denda ini terhubung dengan Peminjaman ID
                            #{{ $fine->borrowing->id }}</h6>
                        <span
                            class="badge bg-{{ $fine->borrowing->status->badgeColor() }}">{{ $fine->borrowing->status->label() }}</span>
                    </div>
                    <a href="{{ route('admin.borrowings.show', $fine->borrowing) }}" class="btn btn-primary">Lihat Detail
                        Peminjaman</a>
                </div>
            @endif

            @if ($fine->status !== App\Enum\FineStatus::Unpaid)
                <div class="section-divider"><span>Detail Penyelesaian</span></div>
                <dl class="row">
                    <dt class="col-sm-3">Tanggal Proses</dt>
                    <dd class="col-sm-9">
                        {{ $fine->payment_date ? $fine->payment_date->isoFormat('dddd, D MMMM YYYY - HH:mm') : '-' }}</dd>

                    <dt class="col-sm-3">Diproses Oleh</dt>
                    <dd class="col-sm-9">{{ $fine->paymentProcessor?->name ?? '-' }}</dd>

                    <dt class="col-sm-3">Catatan</dt>
                    <dd class="col-sm-9">{!! nl2br(e($fine->notes)) ?: '-' !!}</dd>
                </dl>
            @elseif($fine->notes)
                <div class="section-divider"><span>Catatan</span></div>
                <div class="notes-block">
                    <i class="bi bi-card-text"></i>
                    <p class="mb-0">{!! nl2br(e($fine->notes)) !!}</p>
                </div>
            @endif
        </div>
    </div>

    @if ($fine->status === App\Enum\FineStatus::Unpaid)
        <div class="modal fade" id="payModal-{{ $fine->id }}" tabindex="-1"
            aria-labelledby="payModalLabel-{{ $fine->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('admin.fines.pay', $fine) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="payModalLabel-{{ $fine->id }}">Konfirmasi Pembayaran Denda
                            </h1><button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Anda akan menandai lunas denda sebesar <strong>Rp
                                    {{ number_format($fine->amount, 0, ',', '.') }}</strong> untuk peminjaman oleh
                                <strong>{{ $fine->borrowing?->siteUser?->name ?? 'N/A' }}</strong>.
                            </p>
                            <div class="mb-3">
                                <label for="payment_notes-pay-{{ $fine->id }}" class="form-label">Catatan Pembayaran
                                    (Opsional):</label>
                                <textarea class="form-control @error('payment_notes', 'pay_' . $fine->id) is-invalid @enderror"
                                    id="payment_notes-pay-{{ $fine->id }}" name="payment_notes" rows="3">{{ old('payment_notes') }}</textarea>
                                @error('payment_notes', 'pay_' . $fine->id)
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-success"><i
                                    class="bi bi-check-circle-fill me-1"></i> Ya, Tandai Lunas</button></div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal fade" id="waiveModal-{{ $fine->id }}" tabindex="-1"
            aria-labelledby="waiveModalLabel-{{ $fine->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('admin.fines.waive', $fine) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="waiveModalLabel-{{ $fine->id }}">Konfirmasi Bebaskan
                                Denda</h1><button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Anda yakin ingin membebaskan denda sebesar <strong>Rp
                                    {{ number_format($fine->amount, 0, ',', '.') }}</strong> untuk peminjaman oleh
                                <strong>{{ $fine->borrowing?->siteUser?->name ?? 'N/A' }}</strong>?
                            </p>
                            <div class="mb-3">
                                <label for="waiver_notes-waive-{{ $fine->id }}" class="form-label">Alasan / Catatan
                                    Pembebasan (Wajib):</label>
                                <textarea class="form-control @error('waiver_notes', 'waive_' . $fine->id) is-invalid @enderror"
                                    id="waiver_notes-waive-{{ $fine->id }}" name="waiver_notes" rows="3" required>{{ old('waiver_notes') }}</textarea>
                                @error('waiver_notes', 'waive_' . $fine->id)
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-warning">Ya,
                                Bebaskan Denda</button></div>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection

@section('css')
    <style>
        .section-divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 2rem 0;
            color: #6c757d;
            font-size: .9rem;
            text-transform: uppercase;
            letter-spacing: .5px
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

        .detail-block {
            border: 1px solid #dee2e6;
            border-radius: 0.75rem;
            padding: 1.25rem;
            height: 100%;
        }

        .detail-block-title {
            font-size: 0.8rem;
            text-transform: uppercase;
            color: #6c757d;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .book-cover-sm {
            width: 40px;
            height: 60px;
            object-fit: cover;
            border-radius: 0.25rem;
            flex-shrink: 0;
        }

        .notes-block {
            padding: 1rem;
            background-color: #f8f9fa;
            border-left: 4px solid var(--bs-primary);
            color: #6c757d;
        }

        .notes-block i {
            float: left;
            margin-right: 0.75rem;
            font-size: 1.5rem;
            opacity: 0.5;
        }

        dl.row dt {
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #6c757d;
        }

        dl.row dd {
            margin-bottom: 0.5rem;
        }
    </style>
@endsection

@section('script')
    <script>
        @if ($fine->status === App\Enum\FineStatus::Unpaid)
            @if ($errors->hasBag('pay_' . $fine->id))
                var payModalInstance = document.getElementById('payModal-{{ $fine->id }}');
                if (payModalInstance) {
                    new bootstrap.Modal(payModalInstance).show();
                }
            @endif
            @if ($errors->hasBag('waive_' . $fine->id))
                var waiveModalInstance = document.getElementById('waiveModal-{{ $fine->id }}');
                if (waiveModalInstance) {
                    new bootstrap.Modal(waiveModalInstance).show();
                }
            @endif
        @endif
    </script>
@endsection
