<footer class="footer mt-auto py-4 bg-primary text-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h5 class="text-uppercase fw-bold mb-3">Tentang</h5>
                <div class="d-flex align-items-center justify-content-center mb-2">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" width="32" height="32"
                        class="me-2">
                    <span class="fs-6">{{ config('app.name', 'SIMPerpus') }}</span>
                </div>
                <p class="footer-text mb-0">
                    Sistem Informasi Perpustakaan untuk memudahkan siswa dan pengelola di SMA Negeri 2 Sungai Kakap.
                </p>

            </div>
        </div>

        <hr class="my-4 border-light opacity-25">

        <div class="text-center footer-text-muted">
            <span>Hak Cipta &copy; {{ date('Y') }} {{ config('app.name', 'SIMPerpus') }} - SMA Negeri 2 Sungai
                Kakap.</span>
        </div>
    </div>
</footer>
