<ul class="nav flex-column sidebar-nav-custom">
    <li class="nav-item">
        <a class="nav-link d-flex align-items-center gap-3 {{ Route::is('admin.dashboard') ? 'active' : '' }}"
            href="{{ route('admin.dashboard') }}">
            <i class="bi bi-house-door-fill fs-5"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <li class="nav-item mt-3">
        <h6 class="sidebar-heading px-3 mb-2 text-uppercase">Master Buku</h6>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-3 {{ Route::is('admin.categories.*') ? 'active' : '' }}"
                    href="{{ route('admin.categories.index') }}">
                    <i class="bi bi-tags-fill"></i> Kategori
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-3 {{ Route::is('admin.authors.*') ? 'active' : '' }}"
                    href="{{ route('admin.authors.index') }}">
                    <i class="bi bi-person-fill"></i> Pengarang
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-3 {{ Route::is('admin.publishers.*') ? 'active' : '' }}"
                    href="{{ route('admin.publishers.index') }}">
                    <i class="bi bi-building-fill"></i> Penerbit
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-3 {{ Route::is('admin.books.*') ? 'active' : '' }}"
                    href="{{ route('admin.books.index') }}">
                    <i class="bi bi-book-half"></i> Manajemen Buku
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-item mt-3">
        <h6 class="sidebar-heading px-3 mb-2 text-uppercase">Pengguna</h6>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-3 {{ Route::is(['admin.site-users.index', 'admin.site-users.create', 'admin.site-users.edit', 'admin.site-users.show', 'admin.site-users.pending']) ? 'active' : '' }}"
                    href="{{ route('admin.site-users.index') }}">
                    <i class="bi bi-people-fill"></i> Manajemen Siswa
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-3 {{ Route::is('admin.admin-users.*') ? 'active' : '' }}"
                    href="{{ route('admin.admin-users.index') }}">
                    <i class="bi bi-person-badge-fill"></i> Manajemen Admin
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-item mt-3">
        <h6 class="sidebar-heading px-3 mb-2 text-uppercase">Sirkulasi</h6>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-3 {{ Route::is('admin.borrowings.*') && !Route::is('admin.borrowings.overdue') ? 'active' : '' }}"
                    href="{{ route('admin.borrowings.index') }}">
                    <i class="bi bi-arrow-repeat"></i> Peminjaman
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-3 {{ Route::is('admin.bookings.*') ? 'active' : '' }}"
                    href="{{ route('admin.bookings.index') }}">
                    <i class="bi bi-journal-bookmark-fill"></i> Booking
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-3 {{ Route::is('admin.borrowings.overdue') ? 'active' : '' }}"
                    href="{{ route('admin.borrowings.overdue') }}">
                    <i class="bi bi-calendar-x-fill"></i> Lewat Tempo
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-item mt-3">
        <h6 class="sidebar-heading px-3 mb-2 text-uppercase">Sistem</h6>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-3 {{ Route::is('admin.reports.*') ? 'active' : '' }}"
                    href="{{ route('admin.reports.borrowings') }}">
                    <i class="bi bi-file-earmark-bar-graph-fill"></i> Laporan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-3 {{ Route::is('admin.settings.*') ? 'active' : '' }}"
                    href="{{ route('admin.settings.index') }}">
                    <i class="bi bi-gear-fill"></i> Pengaturan
                </a>
            </li>
        </ul>
    </li>
</ul>
