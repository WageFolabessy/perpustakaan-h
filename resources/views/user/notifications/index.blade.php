@extends('user.components.main')

@section('title', 'Notifikasi Saya')
@section('page-title', 'Semua Notifikasi')

@section('content')

    @include('admin.components.flash_messages')

    <div class="d-flex justify-content-end mb-4">
        @if (Auth::user()->unreadNotifications()->count() > 0)
            <form action="{{ route('user.notifications.readall') }}" method="POST"
                onsubmit="return confirm('Anda yakin ingin menandai semua notifikasi sebagai sudah dibaca?');">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check2-all"></i> Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>

    <div class="timeline-container">
        @if ($notifications->isEmpty())
            <div class="text-center p-5">
                <i class="bi bi-bell-slash fs-1 text-muted"></i>
                <h4 class="mt-3">Tidak Ada Notifikasi</h4>
                <p class="text-muted">Semua notifikasi Anda akan muncul di sini.</p>
            </div>
        @else
            @foreach ($notifications as $notification)
                <div class="timeline-item">
                    @php
                        $isUnread = $notification->unread();
                        $icon = $isUnread
                            ? $notification->data['icon'] ?? 'bi-envelope-fill'
                            : $notification->data['icon'] ?? 'bi-envelope-open-fill';
                    @endphp
                    <div class="timeline-icon {{ $isUnread ? 'bg-primary text-white shadow' : 'bg-light text-muted' }}">
                        <i class="bi {{ $icon }}"></i>
                    </div>

                    <div class="timeline-content card shadow-sm {{ $isUnread ? 'border-primary' : '' }}">
                        <div class="card-body">
                            <p class="{{ $isUnread ? 'fw-bold text-dark' : '' }} mb-1">
                                {{ $notification->data['message'] ?? 'Notifikasi baru.' }}
                            </p>
                            <small class="text-muted">
                                <i class="bi bi-clock"></i> {{ $notification->created_at->diffForHumans() }}
                            </small>
                        </div>
                        @if ($isUnread || isset($notification->data['link']))
                            <div class="card-footer bg-transparent border-0 d-flex justify-content-end gap-2 py-2">
                                @if ($isUnread)
                                    <form action="{{ route('user.notifications.read', $notification->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-light" title="Tandai sudah dibaca">
                                            <i class="bi bi-check-lg"></i> Tandai Dibaca
                                        </button>
                                    </form>
                                @endif
                                @isset($notification->data['link'])
                                    <a href="{{ $notification->data['link'] }}" class="btn btn-sm btn-outline-primary"
                                        title="Lihat Detail">
                                        <i class="bi bi-box-arrow-up-right"></i> Detail
                                    </a>
                                @endisset
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    @if ($notifications->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $notifications->links('vendor.pagination.bootstrap-5') }}
        </div>
    @endif

@endsection

@section('css')
    @parent
    <style>
        .timeline-container {
            position: relative;
            padding: 20px 0;
        }

        .timeline-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 20px;
            height: 100%;
            width: 4px;
            background: #e9ecef;
            border-radius: 2px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 30px;
            padding-left: 60px;
        }

        .timeline-icon {
            position: absolute;
            left: 0;
            top: 0;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            border: 4px solid #fff;
        }

        .timeline-content {
            position: relative;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        @media (max-width: 767.98px) {
            .timeline-container::before {
                left: 20px;
            }

            .timeline-item {
                padding-left: 50px;
            }

            .timeline-icon {
                left: 0;
            }
        }
    </style>
@endsection
