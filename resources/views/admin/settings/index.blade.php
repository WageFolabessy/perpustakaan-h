@extends('admin.components.main')

@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Sistem')

@section('content')
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card shadow-sm rounded-4 border-0">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-semibold">Konfigurasi Pengaturan Sistem</h6>
            </div>
            <div class="card-body p-4">
                @include('admin.components.flash_messages')

                @if (
                    $errors->has('settings') &&
                        !$errors->hasAny(array_map(fn($key) => 'settings.' . $key, $settings->pluck('key')->all())))
                    @include('admin.components.validation_errors')
                @endif

                @if ($settings->isEmpty())
                    <div class="alert alert-warning text-center">
                        Belum ada data pengaturan di database. Silakan jalankan seeder atau tambahkan manual.
                    </div>
                @else
                    @foreach ($settings as $setting)
                        <div class="mb-4 row">
                            <label for="setting-{{ $setting->key }}" class="col-md-4 col-form-label">
                                <span
                                    class="fw-semibold">{{ $setting->description ?: Str::title(str_replace('_', ' ', $setting->key)) }}</span>
                                @if ($setting->description)
                                    <br><small
                                        class="text-muted">{{ Str::title(str_replace('_', ' ', $setting->key)) }}</small>
                                @endif
                            </label>
                            <div class="col-md-8">
                                @if (strlen($setting->value) > 100 || str_contains($setting->value, "\n"))
                                    <textarea class="form-control @error('settings.' . $setting->key) is-invalid @enderror" id="setting-{{ $setting->key }}"
                                        name="settings[{{ $setting->key }}]" rows="3">{{ old('settings.' . $setting->key, $setting->value) }}</textarea>
                                @else
                                    <input
                                        type="{{ in_array($setting->key, ['loan_duration', 'max_loan_books', 'fine_rate_per_day', 'booking_expiry_days', 'max_active_bookings']) ? 'number' : 'text' }}"
                                        class="form-control @error('settings.' . $setting->key) is-invalid @enderror"
                                        id="setting-{{ $setting->key }}" name="settings[{{ $setting->key }}]"
                                        value="{{ old('settings.' . $setting->key, $setting->value) }}">
                                @endif

                                @error('settings.' . $setting->key)
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            @if (!$settings->isEmpty())
                <div class="card-footer bg-white d-flex justify-content-end border-0 pt-0 pb-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save-fill me-1"></i> Simpan Pengaturan
                    </button>
                </div>
            @endif
        </div>
    </form>
@endsection

@section('css')
    <style>
        .col-form-label small {
            font-weight: normal;
            font-size: 0.8em;
        }

        .row+.row {
            margin-top: 1.5rem;
        }
    </style>
@endsection

@section('script')
@endsection
