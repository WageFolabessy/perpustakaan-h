@csrf
<div class="mb-4">
    <label for="name" class="form-label fw-semibold">Nama Kategori</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
        value="{{ old('name', $category->name ?? '') }}" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="mb-4">
    <label for="description" class="form-label fw-semibold">Deskripsi (Opsional)</label>
    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
        rows="4">{{ old('description', $category->description ?? '') }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
