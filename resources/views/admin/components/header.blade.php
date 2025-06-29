<header class="navbar navbar-expand-lg bg-white fixed-top p-0 border-bottom admin-header">
    <div class="container-fluid">

        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 text-dark fw-bold" href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" width="30" height="30"
                class="d-inline-block align-text-top me-2">
            Perpustakaan Admin
        </a>

        <button class="navbar-toggler border-0 d-lg-none" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#sidebarMenuOffcanvas" aria-controls="sidebarMenuOffcanvas" aria-label="Toggle navigation">
            <i class="bi bi-list"></i>
        </button>

        <div class="d-flex align-items-center ms-auto">
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle px-3 text-secondary" href="#" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-5 me-1 align-middle"></i>
                    <span class="d-none d-sm-inline">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow-sm mt-2 border-0">
                    <li>
                        <a class="dropdown-item d-flex align-items-center py-2"
                            href="{{ route('admin.profile.edit') }}">
                            <i class="bi bi-person-fill me-2"></i>
                            Profil Saya
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('admin.logout') }}" class="mb-0">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex align-items-center text-danger py-2">
                                <i class="bi bi-box-arrow-right me-2"></i>
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</header>
