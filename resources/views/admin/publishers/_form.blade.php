@csrf
<div class="mb-4">
    <label for="name" class="form-label fw-semibold">Nama Penerbit</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
        value="{{ old('name', $publisher->name ?? '') }}" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="mb-4">
    <label for="address" class="form-label fw-semibold">Alamat (Opsional)</label>
    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="4">{{ old('address', $publisher->address ?? '') }}</textarea>
    @error('address')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
