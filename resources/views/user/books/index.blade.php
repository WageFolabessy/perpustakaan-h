@extends('user.components.main')

@section('title', 'Katalog Buku')

{{-- Judul halaman dibuat lebih ringkas --}}
@section('page-title', 'Temukan Buku Favoritmu')

@section('content')
    {{-- BAGIAN 1: HERO PENCARIAN BARU --}}
    <div class="card card-body shadow-sm border-0 mb-4 p-4 text-center bg-light">
        <h4 class="fw-bold">Pencarian Buku</h4>
        <p class="text-muted">Cari berdasarkan judul, nama pengarang, atau ISBN.</p>
        <div class="row justify-content-center">
            <div class="col-lg-6">
                {{-- Form hanya berisi input, tanpa tombol submit karena menggunakan AJAX --}}
                <form action="{{ route('catalog.index') }}" method="GET" id="search-form">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" id="search" name="search"
                            placeholder="Contoh: Laskar Pelangi..." value="{{ $searchQuery ?? '' }}">
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- BAGIAN 2: FILTER KATEGORI SEBAGAI TOMBOL --}}
    <div class="mb-4 text-center">
        <a href="{{ route('catalog.index') }}"
            class="btn btn-primary rounded-pill me-1 mb-2 {{ !request('category') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i> Semua Kategori
        </a>
        @foreach ($categories as $id => $name)
            <a href="{{ route('catalog.index', ['category' => $id]) }}"
                class="btn btn-outline-primary rounded-pill me-1 mb-2 {{ (request('category') ?? '') == $id ? 'active' : '' }}">
                {{ $name }}
            </a>
        @endforeach
    </div>

    {{-- BAGIAN 3: KONTAINER DAFTAR BUKU --}}
    <div class="row g-3 g-lg-4" id="book-list-container">
        {{-- Partial view tetap dipanggil di sini --}}
        @include('user.books._book_list', ['books' => $books])
    </div>

    {{-- Indikator loading tidak berubah --}}
    <div id="loading-indicator" class="text-center mt-4" style="display: none;">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
@endsection

@section('css')
    {{-- CSS untuk kartu buku akan dipindahkan ke file _book_list untuk kerapian --}}
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            let searchTimeout;
            const searchInput = $('#search');
            const resultsContainer = $('#book-list-container');
            const loadingIndicator = $('#loading-indicator');
            // Mengambil kategori saat ini dari URL untuk pencarian AJAX
            const currentCategory = "{{ request('category', '') }}";

            function performSearch(query) {
                if (query.length >= 3) {
                    loadingIndicator.show();
                    resultsContainer.css('opacity', 0.5);

                    $.ajax({
                        url: "{{ route('catalog.search.api') }}",
                        type: "GET",
                        data: {
                            search: query,
                            // Kirimkan filter kategori saat ini bersamaan dengan pencarian
                            category: currentCategory
                        },
                        success: function(response) {
                            resultsContainer.html(response.html);
                        },
                        error: function(xhr) {
                            console.error("Error searching:", xhr);
                            resultsContainer.html(
                                '<div class="col-12"><div class="alert alert-danger">Gagal memuat hasil pencarian.</div></div>'
                            );
                        },
                        complete: function() {
                            loadingIndicator.hide();
                            resultsContainer.css('opacity', 1);
                        }
                    });
                } else if (query.length === 0) {
                    // Jika input kosong, muat ulang halaman ke kondisi filter kategori awal
                    window.location.href = "{{ route('catalog.index') }}" + (currentCategory ? '?category=' +
                        currentCategory : '');
                } else {
                    // Tampilkan pesan jika input kurang dari 3 karakter
                    resultsContainer.html(
                        '<div class="col-12 vh-50 d-flex flex-column justify-content-center align-items-center text-center text-muted">' +
                        '<i class="bi bi-search fs-1"></i>' +
                        '<h4>Ketik minimal 3 karakter</h4>' +
                        '<p>untuk memulai pencarian buku.</p>' +
                        '</div>'
                    );
                }
            }

            searchInput.on('keyup', function() {
                clearTimeout(searchTimeout);
                const query = $(this).val().trim();
                searchTimeout = setTimeout(() => performSearch(query), 500); // delay 500ms
            });

            // Mencegah form submit default jika menekan enter
            $('#search-form').on('submit', function(e) {
                e.preventDefault();
                performSearch(searchInput.val().trim());
            });
        });
    </script>
@endsection
