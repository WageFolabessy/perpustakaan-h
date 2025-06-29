@extends('admin.components.main')

@section('title', 'Detail Penerbit')
@section('page-title')
    Detail Penerbit
@endsection

@section('content')
    <div class="card shadow-sm rounded-4 border-0">
        <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 fw-semibold">Informasi: {{ $publisher->name }}</h6>
            <div>
                <a href="{{ route('admin.publishers.edit', $publisher) }}" class="btn btn-warning" title="Edit Penerbit">
                    <i class="bi bi-pencil-fill me-1"></i> Edit
                </a>
                <a href="{{ route('admin.publishers.index') }}" class="btn btn-secondary" title="Kembali ke Daftar">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="detail-item row">
                <div class="col-lg-3 col-md-4 label">Nama Penerbit</div>
                <div class="col-lg-9 col-md-8">{{ $publisher->name }}</div>
            </div>
            <div class="detail-item row">
                <div class="col-lg-3 col-md-4 label">Alamat</div>
                <div class="col-lg-9 col-md-8">{!! nl2br(e($publisher->address)) ?: '-' !!}</div>
            </div>
            <div class="detail-item row">
                <div class="col-lg-3 col-md-4 label">Tanggal Dibuat</div>
                <div class="col-lg-9 col-md-8">
                    {{ $publisher->created_at ? $publisher->created_at->isoFormat('dddd, D MMMM YYYY - HH:mm') : '-' }}
                </div>
            </div>
            <div class="detail-item row">
                <div class="col-lg-3 col-md-4 label">Tanggal Diperbarui</div>
                <div class="col-lg-9 col-md-8">{{ $publisher->updated_at ? $publisher->updated_at->diffForHumans() : '-' }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .detail-item {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
            font-size: 0.95rem;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-item .label {
            font-weight: 600;
            color: #6c757d;
        }
    </style>
@endsection
