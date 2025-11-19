@extends('layouts.app')

@section('content')
  <div class="container py-4">
    <h4 class="fw-bold mb-3"><i class="bi bi-plus-circle"></i> Tambah Peminjaman</h4>

    {{-- Info Maksimal Peminjaman --}}
    @php
        $pengaturan = \App\Models\PengaturanDenda::getAktif();
        $maksimalHari = $pengaturan ? $pengaturan->maksimal_hari_peminjaman : 7;
    @endphp
    
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>
        <strong>Informasi:</strong> Maksimal durasi peminjaman adalah <strong>{{ $maksimalHari }} hari</strong>. 
        Pilih tanggal kembali dalam rentang waktu ini.
    </div>

    <div class="card shadow-sm border-0">
      <div class="card-body">
        <form action="{{ route('peminjaman.store') }}" method="POST">
          @csrf

          <div class="mb-3">
            <label class="form-label fw-semibold">Peminjam</label>
            <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
              <option value="">-- Pilih Peminjam --</option>
              @foreach($users as $u)
                <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                  {{ $u->name }} ({{ $u->role }})
                </option>
              @endforeach
            </select>
            @error('user_id')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Buku</label>
            <select name="buku_id" id="buku_id" class="form-select @error('buku_id') is-invalid @enderror" required>
              <option value="">-- Pilih Buku --</option>
              @foreach($buku as $b)
                <option value="{{ $b->id }}" {{ old('buku_id') == $b->id ? 'selected' : '' }} data-stok="{{ $b->stok }}">
                  {{ $b->judul }} - Stok: {{ $b->stok }}
                </option>
              @endforeach
            </select>
            @error('buku_id')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal Pinjam</label>
                <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" 
                       class="form-control @error('tanggal_pinjam') is-invalid @enderror" 
                       value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                @error('tanggal_pinjam')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal Kembali</label>
                <input type="date" name="tanggal_kembali" id="tanggal_kembali" 
                       class="form-control @error('tanggal_kembali') is-invalid @enderror" 
                       value="{{ old('tanggal_kembali') }}" required>
                @error('tanggal_kembali')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">
                  Pilih tanggal kembali (maksimal {{ $maksimalHari }} hari dari tanggal pinjam)
                </small>
              </div>
            </div>
          </div>

          {{-- Preview Durasi --}}
          <div class="alert alert-warning mb-3" id="previewDurasi" style="display: none;">
            <i class="bi bi-calendar me-2"></i>
            <strong>Preview:</strong> 
            Durasi pinjam: <span id="durasiHari"></span> hari 
            (Tanggal kembali: <span id="previewDate"></span>)
          </div>

          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-success me-2">
              <i class="bi bi-save"></i> Simpan
            </button>
            <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">
              <i class="bi bi-arrow-left-circle"></i> Kembali
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
      const tanggalPinjamInput = document.getElementById('tanggal_pinjam');
      const tanggalKembaliInput = document.getElementById('tanggal_kembali');
      const previewDiv = document.getElementById('previewDurasi');
      const durasiSpan = document.getElementById('durasiHari');
      const previewDateSpan = document.getElementById('previewDate');
      const maksimalHari = {{ $maksimalHari }};

      function updatePreview() {
          const tanggalPinjam = tanggalPinjamInput.value;
          const tanggalKembali = tanggalKembaliInput.value;
          
          if (tanggalPinjam && tanggalKembali) {
              const pinjamDate = new Date(tanggalPinjam);
              const kembaliDate = new Date(tanggalKembali);
              const selisihMs = kembaliDate - pinjamDate;
              const selisihHari = Math.ceil(selisihMs / (1000 * 60 * 60 * 24));
              
              if (selisihHari > 0) {
                  durasiSpan.textContent = selisihHari;
                  
                  // Format tanggal untuk preview
                  const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                  previewDateSpan.textContent = kembaliDate.toLocaleDateString('id-ID', options);
                  
                  // Warning jika melebihi maksimal
                  if (selisihHari > maksimalHari) {
                      previewDiv.className = 'alert alert-danger';
                      durasiSpan.innerHTML = `<strong class="text-danger">${selisihHari} (Melebihi maksimal ${maksimalHari} hari!)</strong>`;
                  } else {
                      previewDiv.className = 'alert alert-warning';
                      durasiSpan.innerHTML = selisihHari;
                  }
                  
                  previewDiv.style.display = 'block';
                  
                  // Set max date untuk tanggal kembali
                  const maxDate = new Date(pinjamDate);
                  maxDate.setDate(pinjamDate.getDate() + maksimalHari);
                  tanggalKembaliInput.max = maxDate.toISOString().split('T')[0];
                  
                  // Set min date untuk tanggal kembali
                  const minDate = new Date(pinjamDate);
                  minDate.setDate(pinjamDate.getDate() + 1);
                  tanggalKembaliInput.min = minDate.toISOString().split('T')[0];
              }
          } else {
              previewDiv.style.display = 'none';
          }
      }

      // Event listeners
      tanggalPinjamInput.addEventListener('change', function() {
          updatePreview();
          // Reset tanggal kembali jika melebihi batas
          if (tanggalKembaliInput.value) {
              const pinjamDate = new Date(this.value);
              const kembaliDate = new Date(tanggalKembaliInput.value);
              const maxDate = new Date(pinjamDate);
              maxDate.setDate(pinjamDate.getDate() + maksimalHari);
              
              if (kembaliDate > maxDate) {
                  tanggalKembaliInput.value = '';
                  updatePreview();
              }
          }
      });

      tanggalKembaliInput.addEventListener('change', updatePreview);

      // Update preview saat load jika ada nilai
      if (tanggalPinjamInput.value && tanggalKembaliInput.value) {
          updatePreview();
      }

      // Validasi stok buku
      const bukuSelect = document.getElementById('buku_id');
      bukuSelect.addEventListener('change', function() {
          const selectedOption = this.options[this.selectedIndex];
          const stok = selectedOption.getAttribute('data-stok');
          
          if (stok && parseInt(stok) <= 0) {
              alert('Peringatan: Stok buku ini sudah habis! Silakan pilih buku lain.');
              this.value = '';
          }
      });

      // Set default tanggal kembali (maksimal hari dari hari ini)
      if (!tanggalKembaliInput.value) {
          const defaultKembali = new Date();
          defaultKembali.setDate(defaultKembali.getDate() + maksimalHari);
          tanggalKembaliInput.value = defaultKembali.toISOString().split('T')[0];
          updatePreview();
      }
  });
  </script>
@endsection