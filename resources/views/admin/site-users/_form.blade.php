@csrf
<div class="row">
    <div class="col-md-4 mb-4">
        <label for="nis" class="form-label fw-semibold">NIS</label>
        <input type="text" class="form-control @error('nis') is-invalid @enderror" id="nis" name="nis"
            value="{{ old('nis', $siteUser->nis ?? '') }}" required>
        @error('nis')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4 mb-4">
        <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
            value="{{ old('name', $siteUser->name ?? '') }}" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4 mb-4">
        <label for="email" class="form-label fw-semibold">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
            value="{{ old('email', $siteUser->email ?? '') }}" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="section-divider">
    <span>Informasi Akademik</span>
</div>
<div class="row">
    <div class="col-md-6 mb-4">
        <label for="class" class="form-label fw-semibold">Kelas</label>
        <input type="text" class="form-control @error('class') is-invalid @enderror" id="class" name="class"
            value="{{ old('class', $siteUser->class ?? '') }}">
        @error('class')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-4">
        <label for="major" class="form-label fw-semibold">Jurusan</label>
        <input type="text" class="form-control @error('major') is-invalid @enderror" id="major" name="major"
            value="{{ old('major', $siteUser->major ?? '') }}">
        @error('major')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="section-divider">
    <span>Keamanan Akun</span>
</div>
<div class="row">
    <div class="col-md-6 mb-4">
        <label for="password" class="form-label fw-semibold">Password</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
            name="password" {{ isset($siteUser) ? '' : 'required' }}>
        @if (isset($siteUser))
            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
        @endif
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-4">
        <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password_confirmation"
            name="password_confirmation">
    </div>
</div>
