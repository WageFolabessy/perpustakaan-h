<header class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" width="32" height="32"
                class="d-inline-block align-text-middle me-2">
            <span class="align-text-middle text-white">{{ config('app.name', 'SIMPerpus') }}</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainUserNavbar"
            aria-controls="mainUserNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainUserNavbar">
            @auth('web')
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('catalog.*') ? 'active' : '' }}"
                            href="{{ route('catalog.index') }}">
                            Buku
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('user.borrowings.history') ? 'active' : '' }}"
                            href="{{ route('user.borrowings.history') }}">
                            Riwayat Pinjam
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('user.bookings.index') ? 'active' : '' }}"
                            href="{{ route('user.bookings.index') }}">
                            Booking Saya
                        </a>
                    </li>
                </ul>
            @endauth

            <ul class="navbar-nav ms-auto align-items-lg-center">
                @auth('web')
                    <div class="d-flex align-items-center">
                        <div class="nav-item me-3" id="fcm-button-container" style="display: none;">
                            <button class="btn btn-outline-light btn-sm d-flex align-items-center" id="enable-fcm-button"
                                type="button" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                title="Aktifkan Notifikasi di Browser Ini">
                                <i class="bi bi-bell"></i> <span>Aktifkan Notifikasi Browser</span>
                            </button>
                        </div>

                        <div class="nav-item dropdown me-3">
                            @php
                                $unreadNotifications = Auth::user()->unreadNotifications()->take(5)->get();
                                $unreadCount = Auth::user()->unreadNotifications()->count();
                            @endphp
                            <a class="nav-link" href="#" id="notificationDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi">
                                <i class="bi bi-bell-fill position-relative fs-5">
                                    @if ($unreadCount > 0)
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                            style="font-size: 0.6em; padding: .3em .5em">
                                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                            <span class="visually-hidden">unread messages</span>
                                        </span>
                                    @endif
                                </i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-lg-end" aria-labelledby="notificationDropdown">
                                <li class="dropdown-header text-center fw-bold">Notifikasi Belum Dibaca</li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                @forelse ($unreadNotifications as $notification)
                                    <li>
                                        <a class="dropdown-item d-flex align-items-start small" href="#"
                                            onclick="event.preventDefault(); document.getElementById('mark-as-read-{{ $notification->id }}').submit();">
                                            <form id="mark-as-read-{{ $notification->id }}"
                                                action="{{ route('user.notifications.read', $notification->id) }}"
                                                method="POST" class="d-none">
                                                @csrf
                                                @method('PATCH')
                                            </form>
                                            <i
                                                class="bi {{ $notification->data['icon'] ?? 'bi-info-circle' }} text-primary mt-1 me-2"></i>
                                            <div>
                                                <div>{{ $notification->data['message'] ?? 'Notifikasi baru.' }}</div>
                                                <div class="text-muted" style="font-size: 0.8em;">
                                                    {{ $notification->created_at->diffForHumans() }}</div>
                                            </div>
                                        </a>
                                    </li>
                                @empty
                                    <li><a class="dropdown-item text-center text-muted disabled" href="#">Tidak ada
                                            notifikasi baru</a></li>
                                @endforelse
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-center text-primary fw-bold"
                                        href="{{ route('user.notifications.index') }}">Lihat Semua Notifikasi</a></li>
                            </ul>
                        </div>

                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle fs-4 me-lg-2"></i>
                                <span class="d-none d-lg-inline">{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item {{ Route::is('user.profile.edit') ? 'active' : '' }}"
                                        href="{{ route('user.profile.edit') }}">
                                        <i class="bi bi-person-fill me-2"></i>Profil Saya</a>
                                </li>
                                <li><a class="dropdown-item {{ Route::is('user.fines.index') ? 'active' : '' }}"
                                        href="{{ route('user.fines.index') }}">
                                        <i class="bi bi-cash-coin me-2"></i>Denda Saya</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                        @csrf
                                        <a href="{{ route('logout') }}" class="dropdown-item text-danger"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    @elseguest('web')
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-outline-light me-2" href="{{ route('login') }}">Login</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="btn btn-light" href="{{ route('register') }}">Register</a>
                        </li>
                    @endif
                @endguest
            </ul>
        </div>
    </div>
</header>
