<div class="card shadow-sm border-0 mb-3">
  <div class="card-body">
    <div class="mb-3">
      <label class="form-label fw-semibold">Judul</label>
      <input type="text" name="judul" class="form-control" value="{{ old('judul', $buku->judul ?? '') }}" required>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">Pengarang</label>
      <input type="text" name="pengarang" class="form-control" value="{{ old('pengarang', $buku->pengarang ?? '') }}"
        required>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">Penerbit</label>
      <input type="text" name="penerbit" class="form-control" value="{{ old('penerbit', $buku->penerbit ?? '') }}">
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">Tahun Terbit</label>
      <input type="number" name="tahun_terbit" class="form-control"
        value="{{ old('tahun_terbit', $buku->tahun_terbit ?? '') }}">
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">ISBN</label>
      <input type="text" name="isbn" class="form-control" value="{{ old('isbn', $buku->isbn ?? '') }}">
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">Kategori</label>
      <select name="kategori_id" class="form-select" required>
        <option value="">-- Pilih Kategori --</option>
        @foreach($kategori as $kat)
          <option value="{{ $kat->id }}" {{ old('kategori_id', $buku->kategori_id ?? '') == $kat->id ? 'selected' : '' }}>
            {{ $kat->nama }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">Rak</label>
      <select name="rak_id" class="form-select" required>
        <option value="">-- Pilih Rak --</option>
        @foreach($rak as $r)
          <option value="{{ $r->id }}" {{ old('rak_id', $buku->rak_id ?? '') == $r->id ? 'selected' : '' }}>
            {{ $r->nama }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">Stok</label>
      <input type="number" name="stok" class="form-control" value="{{ old('stok', $buku->stok ?? 0) }}" required>
    </div>
  </div>
</div>