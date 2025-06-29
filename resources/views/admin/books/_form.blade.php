@csrf
<div class="row">
    <div class="col-md-8">
        <div class="mb-4">
            <label for="title" class="form-label fw-semibold">Judul Buku</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                value="{{ old('title', $book->title ?? '') }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <label for="author_id" class="form-label fw-semibold">Pengarang</label>
                <select class="form-select @error('author_id') is-invalid @enderror" id="author_id" name="author_id">
                    <option value="">-- Pilih Pengarang --</option>
                    @foreach ($authors as $id => $name)
                        <option value="{{ $id }}"
                            {{ old('author_id', $book->author_id ?? '') == $id ? 'selected' : '' }}>{{ $name }}
                        </option>
                    @endforeach
                </select>
                @error('author_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 mb-4">
                <label for="publisher_id" class="form-label fw-semibold">Penerbit</label>
                <select class="form-select @error('publisher_id') is-invalid @enderror" id="publisher_id"
                    name="publisher_id">
                    <option value="">-- Pilih Penerbit --</option>
                    @foreach ($publishers as $id => $name)
                        <option value="{{ $id }}"
                            {{ old('publisher_id', $book->publisher_id ?? '') == $id ? 'selected' : '' }}>
                            {{ $name }}</option>
                    @endforeach
                </select>
                @error('publisher_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <label for="category_id" class="form-label fw-semibold">Kategori</label>
                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id"
                    name="category_id">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($categories as $id => $name)
                        <option value="{{ $id }}"
                            {{ old('category_id', $book->category_id ?? '') == $id ? 'selected' : '' }}>
                            {{ $name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 mb-4">
                <label for="isbn" class="form-label fw-semibold">ISBN</label>
                <input type="text" class="form-control @error('isbn') is-invalid @enderror" id="isbn"
                    name="isbn" value="{{ old('isbn', $book->isbn ?? '') }}">
                @error('isbn')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <label for="publication_year" class="form-label fw-semibold">Tahun Terbit</label>
                <input type="number" class="form-control @error('publication_year') is-invalid @enderror"
                    id="publication_year" name="publication_year"
                    value="{{ old('publication_year', $book->publication_year ?? '') }}" min="1000"
                    max="{{ date('Y') }}">
                @error('publication_year')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 mb-4">
                <label for="location" class="form-label fw-semibold">Lokasi Rak</label>
                <input type="text" class="form-control @error('location') is-invalid @enderror" id="location"
                    name="location" value="{{ old('location', $book->location ?? '') }}">
                @error('location')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <label for="synopsis" class="form-label fw-semibold">Sinopsis</label>
            <textarea class="form-control @error('synopsis') is-invalid @enderror" id="synopsis" name="synopsis" rows="6">{{ old('synopsis', $book->synopsis ?? '') }}</textarea>
            @error('synopsis')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Gambar Sampul</label>
        <div class="image-upload-wrapper">
            <input class="image-upload-input @error('cover_image') is-invalid @enderror" type="file" id="cover_image"
                name="cover_image" accept="image/*">

            <label for="cover_image" class="image-upload-label">
                @if (isset($book) && $book->cover_image)
                    <img id="image-preview" src="{{ asset('storage/' . $book->cover_image) }}" alt="Current Cover">
                    <div class="upload-instructions has-image">
                        <i class="bi bi-arrow-repeat"></i>
                        <p><strong>Ganti gambar</strong></p>
                    </div>
                @else
                    <img id="image-preview" src="#" alt="Image Preview" style="display: none;">
                    <div class="upload-instructions">
                        <i class="bi bi-cloud-arrow-up-fill"></i>
                        <p><strong>Pilih gambar sampul</strong></p>
                    </div>
                @endif
            </label>
            @error('cover_image')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <small class="form-text text-muted mt-2 d-block">Kosongkan jika tidak ingin mengubah gambar (saat
            edit).</small>
    </div>
</div>
