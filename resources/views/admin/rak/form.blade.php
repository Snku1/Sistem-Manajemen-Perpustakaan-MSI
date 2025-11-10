<div class="card shadow-sm border-0 mb-3">
  <div class="card-body">
    <div class="mb-3">
      <label class="form-label fw-semibold">Kode Rak</label>
      <input type="text" name="kode" class="form-control" value="{{ old('kode', $rak->kode ?? '') }}" required>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">Nama Rak</label>
      <input type="text" name="nama" class="form-control" value="{{ old('nama', $rak->nama ?? '') }}" required>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">Lokasi</label>
      <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi', $rak->lokasi ?? '') }}">
    </div>
  </div>
</div>