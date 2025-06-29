<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin SIMPUS - @yield('title', 'Dashboard')</title>
    <meta name="description" content="Admin Sistem Informasi Perpustakaan" />
    <meta name="author" content="SMKN 1 Sanggau Ledo" />
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <link href="{{ asset('assets/admin/vendor/fa.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/vendor/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/vendor/datatables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/custom-admin.css') }}" rel="stylesheet" />

    @yield('css')

    <style>
        :root {
            --bs-primary: #435EBE;
            --bs-primary-rgb: 67, 94, 190;
        }

        .admin-header .navbar-toggler:focus {
            box-shadow: none;
        }

        .admin-header .dropdown-menu {
            border-radius: 0.5rem;
        }

        .sidebar-nav-custom .sidebar-heading {
            font-size: 0.75rem;
            font-weight: 600;
            color: #9ca3af;
            letter-spacing: .5px;
        }

        .sidebar-nav-custom .nav-link {
            color: #374151;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            margin: 0 0.75rem 0.25rem 0.75rem;
            border-radius: 0.5rem;
            transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
        }

        .sidebar-nav-custom .nav-link:hover {
            background-color: #f3f4f6;
            color: var(--bs-primary);
        }

        .sidebar-nav-custom .nav-link.active {
            background-color: var(--bs-primary);
            color: #ffffff;
            font-weight: 600;
        }

        .sidebar-nav-custom .nav-link.active i {
            color: #ffffff;
        }

        .sidebar-nav-custom ul ul .nav-link {
            padding-left: 3.5rem;
        }
    </style>
</head>

<body>

    @include('admin.components.header')

    <div class="container-fluid">
        <div class="row">

            <nav class="col-lg-2 d-none d-lg-block sidebar-desktop">
                <div class="sidebar-sticky">
                    @include('admin.components.sidebar-menu')
                </div>
            </nav>

            <main class="col-12 col-lg-10 main-content-desktop">

                <div class="page-header mt-4">
                    <h1 class="h2">@yield('page-title', 'Dashboard')</h1>
                </div>

                <div class="page-content">
                    @yield('content')
                </div>

            </main>
        </div>
    </div>

    @include('admin.components.sidebar-offcanvas')

    <script src="{{ asset('assets/admin/vendor/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/datatables.min.js') }}"></script>

    @yield('script')
</body>

</html>
