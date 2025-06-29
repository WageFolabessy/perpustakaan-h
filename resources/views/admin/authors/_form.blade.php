@csrf
<div class="mb-4">
    <label for="name" class="form-label fw-semibold">Nama Pengarang</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
        value="{{ old('name', $author->name ?? '') }}" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="mb-4">
    <label for="bio" class="form-label fw-semibold">Bio (Opsional)</label>
    <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="4">{{ old('bio', $author->bio ?? '') }}</textarea>
    @error('bio')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
