@extends('admin.components.main')

@section('title', 'Manajemen Denda')
@section('page-title', 'Manajemen Denda')

@section('content')
    <div class="card shadow-sm rounded-4 border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 fw-semibold">Daftar Semua Denda</h6>
            <form action="{{ route('admin.fines.index') }}" method="GET" style="max-width: 220px;">
                <select name="status" class="form-select" onchange="this.form.submit()" aria-label="Filter Status Denda">
                    <option value="">-- Tampilkan Semua Status --</option>
                    @foreach (App\Enum\FineStatus::cases() as $status)
                        <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="card-body">
            @include('admin.components.flash_messages')

            @if ($fines->isEmpty())
                <div class="alert alert-info text-center">
                    Tidak ada data denda
                    @if (request('status'))
                        untuk status "{{ App\Enum\FineStatus::tryFrom(request('status'))?->label() }}"
                    @endif.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover datatable" id="dataTableFines" width="100%">
                        <thead>
                            <tr>
                                <th class="no-sort" width="1%">No</th>
                                <th>Denda Untuk</th>
                                <th class="text-end">Jumlah & Status</th>
                                <th>Detail Pembayaran</th>
                                <th class="text-center action-column no-sort">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fines as $fine)
                                <tr class="align-middle">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-3">
                                                <div class="fw-semibold">{{ $fine->borrowing?->siteUser?->name ?? 'N/A' }}
                                                </div>
                                                <div class="text-muted small">
                                                    <i class="bi bi-book-fill me-1"></i>
                                                    {{ $fine->borrowing?->bookCopy?->book?->title ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="fw-bold">Rp {{ number_format($fine->amount, 0, ',', '.') }}</div>
                                        @if ($fine->status)
                                            <span
                                                class="badge rounded-pill bg-{{ $fine->status->badgeColor() }}">{{ $fine->status->label() }}</span>
                                        @endif
                                    </td>
                                    <td class="small text-muted">
                                        @if ($fine->payment_date)
                                            <div><i
                                                    class="bi bi-calendar-check me-1"></i>{{ $fine->payment_date->isoFormat('D MMM YY, HH:mm') }}
                                            </div>
                                            <div><i
                                                    class="bi bi-person-check me-1"></i>{{ $fine->paymentProcessor?->name ?? '-' }}
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="action-column text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.fines.show', $fine) }}"
                                                class="btn btn-outline-primary" title="Detail Denda"><i
                                                    class="bi bi-eye-fill"></i></a>
                                            @if ($fine->status === App\Enum\FineStatus::Unpaid)
                                                <button type="button" class="btn btn-outline-success" title="Tandai Lunas"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#payModal-{{ $fine->id }}"><i
                                                        class="bi bi-cash-coin"></i></button>
                                                <button type="button" class="btn btn-outline-warning"
                                                    title="Bebaskan Denda" data-bs-toggle="modal"
                                                    data-bs-target="#waiveModal-{{ $fine->id }}"><i
                                                        class="bi bi-shield-slash"></i></button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @foreach ($fines as $fine)
                    @if ($fine->status === App\Enum\FineStatus::Unpaid)
                        <div class="modal fade" id="payModal-{{ $fine->id }}" tabindex="-1"
                            aria-labelledby="payModalLabel-{{ $fine->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('admin.fines.pay', $fine) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="payModalLabel-{{ $fine->id }}">Konfirmasi
                                                Pembayaran Denda</h1><button type="button" class="btn-close"
                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Anda akan menandai lunas denda sebesar <strong>Rp
                                                    {{ number_format($fine->amount, 0, ',', '.') }}</strong> untuk
                                                peminjaman buku
                                                <strong>{{ $fine->borrowing?->bookCopy?->book?->title ?? 'N/A' }}</strong>
                                                oleh <strong>{{ $fine->borrowing?->siteUser?->name ?? 'N/A' }}</strong>.
                                            </p>
                                            <div class="mb-3">
                                                <label for="payment_notes-{{ $fine->id }}" class="form-label">Catatan
                                                    Pembayaran (Opsional):</label>
                                                <textarea class="form-control @error('payment_notes', 'pay_' . $fine->id) is-invalid @enderror"
                                                    id="payment_notes-{{ $fine->id }}" name="payment_notes" rows="3">{{ old('payment_notes') }}</textarea>
                                                @error('payment_notes', 'pay_' . $fine->id)
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="modal-footer"><button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button><button type="submit"
                                                class="btn btn-success"><i class="bi bi-check-circle-fill me-1"></i> Ya,
                                                Tandai Lunas</button></div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="modal fade" id="waiveModal-{{ $fine->id }}" tabindex="-1"
                            aria-labelledby="waiveModalLabel-{{ $fine->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('admin.fines.waive', $fine) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="waiveModalLabel-{{ $fine->id }}">
                                                Konfirmasi Bebaskan Denda</h1><button type="button" class="btn-close"
                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Anda yakin ingin membebaskan denda sebesar <strong>Rp
                                                    {{ number_format($fine->amount, 0, ',', '.') }}</strong> untuk
                                                peminjaman buku
                                                <strong>{{ $fine->borrowing?->bookCopy?->book?->title ?? 'N/A' }}</strong>
                                                oleh <strong>{{ $fine->borrowing?->siteUser?->name ?? 'N/A' }}</strong>?
                                            </p>
                                            <div class="mb-3">
                                                <label for="waiver_notes-{{ $fine->id }}" class="form-label">Alasan /
                                                    Catatan Pembebasan (Wajib):</label>
                                                <textarea class="form-control @error('waiver_notes', 'waive_' . $fine->id) is-invalid @enderror"
                                                    id="waiver_notes-{{ $fine->id }}" name="waiver_notes" rows="3" required>{{ old('waiver_notes') }}</textarea>
                                                @error('waiver_notes', 'waive_' . $fine->id)
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="modal-footer"><button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button><button type="submit"
                                                class="btn btn-warning">Ya, Bebaskan Denda</button></div>
                                    </div>
                                </form>
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
            text-align: center;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--bs-primary-subtle);
            color: var(--bs-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .badge.rounded-pill {
            padding: 0.4em 0.8em;
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>
@endsection

@section('script')
    @include('admin.components.datatable_script', ['table_id' => 'dataTableFines'])
    <script>
        @foreach ($fines as $fine)
            @if ($errors->hasBag('pay_' . $fine->id))
                var payModal = new bootstrap.Modal(document.getElementById('payModal-{{ $fine->id }}'));
                if (payModal) {
                    payModal.show();
                }
            @endif
            @if ($errors->hasBag('waive_' . $fine->id))
                var waiveModal = new bootstrap.Modal(document.getElementById('waiveModal-{{ $fine->id }}'));
                if (waiveModal) {
                    waiveModal.show();
                }
            @endif
        @endforeach
    </script>
@endsection
