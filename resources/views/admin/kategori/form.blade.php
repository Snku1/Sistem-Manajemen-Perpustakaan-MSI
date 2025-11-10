<div class="card shadow-sm border-0 mb-3">
  <div class="card-body">
    <div class="mb-3">
      <label class="form-label fw-semibold">Nama Kategori</label>
      <input type="text" name="nama" class="form-control" value="{{ old('nama', $kategori->nama ?? '') }}" required>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">Deskripsi</label>
      <textarea name="deskripsi" class="form-control"
        rows="3">{{ old('deskripsi', $kategori->deskripsi ?? '') }}</textarea>
    </div>
  </div>
</div>