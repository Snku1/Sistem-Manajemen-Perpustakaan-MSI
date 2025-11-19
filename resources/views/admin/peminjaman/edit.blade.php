@extends('layouts.app')

@section('content')
  <div class="container py-4">
    <h4 class="fw-bold mb-3"><i class="bi bi-pencil-square"></i> Edit Peminjaman</h4>

    {{-- Info Maksimal Peminjaman --}}
    @php
        $pengaturan = \App\Models\PengaturanDenda::getAktif();
        $maksimalHari = $pengaturan ? $pengaturan->maksimal_hari_peminjaman : 7;
        
        // Hitung durasi saat ini
        $tanggalPinjam = \Carbon\Carbon::parse($peminjaman->tanggal_pinjam);
        $tanggalKembali = \Carbon\Carbon::parse($peminjaman->tanggal_kembali);
        $durasiSaatIni = $tanggalPinjam->diffInDays($tanggalKembali);
    @endphp
    
    @if($peminjaman->status === 'dipinjam')
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>
        <strong>Informasi:</strong> Maksimal durasi peminjaman adalah <strong>{{ $maksimalHari }} hari</strong>. 
        Durasi saat ini: <strong>{{ $durasiSaatIni }} hari</strong>.
    </div>
    @else
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Peringatan:</strong> Peminjaman ini sudah <strong>dikembalikan</strong>. 
        Perubahan tanggal kembali tidak mempengaruhi perhitungan denda.
    </div>
    @endif

    <div class="card shadow-sm border-0">
      <div class="card-body">
        <form action="{{ route('peminjaman.update', $peminjaman->id) }}" method="POST">
          @csrf @method('PUT')

          <div class="mb-3">
            <label class="form-label fw-semibold">Peminjam</label>
            <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required
                    {{ $peminjaman->status === 'dikembalikan' ? 'disabled' : '' }}>
              @foreach($users as $u)
                <option value="{{ $u->id }}" {{ $peminjaman->user_id == $u->id ? 'selected' : '' }}>
                  {{ $u->name }} ({{ $u->role }})
                </option>
              @endforeach
            </select>
            @error('user_id')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            @if($peminjaman->status === 'dikembalikan')
              <input type="hidden" name="user_id" value="{{ $peminjaman->user_id }}">
            @endif
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Buku</label>
            <select name="buku_id" id="buku_id" class="form-select @error('buku_id') is-invalid @enderror" required 
                    {{ $peminjaman->status === 'dikembalikan' ? 'disabled' : '' }}>
              @foreach($buku as $b)
                <option value="{{ $b->id }}" 
                        {{ $peminjaman->buku_id == $b->id ? 'selected' : '' }}
                        data-stok="{{ $b->stok }}"
                        {{ $b->id == $peminjaman->buku_id ? '' : ($b->stok <= 0 ? 'disabled' : '') }}>
                  {{ $b->judul }} - Stok: {{ $b->stok }}
                  {{ $b->id == $peminjaman->buku_id ? '(sedang dipinjam)' : '' }}
                </option>
              @endforeach
            </select>
            @error('buku_id')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            @if($peminjaman->status === 'dikembalikan')
              <input type="hidden" name="buku_id" value="{{ $peminjaman->buku_id }}">
            @endif
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal Pinjam</label>
                <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" 
                       value="{{ $peminjaman->tanggal_pinjam }}" 
                       class="form-control @error('tanggal_pinjam') is-invalid @enderror" 
                       required
                       {{ $peminjaman->status === 'dikembalikan' ? 'disabled' : '' }}>
                @error('tanggal_pinjam')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @if($peminjaman->status === 'dikembalikan')
                  <input type="hidden" name="tanggal_pinjam" value="{{ $peminjaman->tanggal_pinjam }}">
                @endif
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal Kembali</label>
                <input type="date" name="tanggal_kembali" id="tanggal_kembali" 
                       value="{{ $peminjaman->tanggal_kembali }}" 
                       class="form-control @error('tanggal_kembali') is-invalid @enderror" 
                       required
                       {{ $peminjaman->status === 'dikembalikan' ? 'disabled' : '' }}>
                @error('tanggal_kembali')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">
                  @if($peminjaman->status === 'dipinjam')
                    Batas maksimal: {{ $maksimalHari }} hari dari tanggal pinjam
                  @else
                    Tanggal kembali aktual
                  @endif
                </small>
                @if($peminjaman->status === 'dikembalikan')
                  <input type="hidden" name="tanggal_kembali" value="{{ $peminjaman->tanggal_kembali }}">
                @endif
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Status</label>
            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
              <option value="dipinjam" {{ $peminjaman->status == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
              <option value="dikembalikan" {{ $peminjaman->status == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
            </select>
            @error('status')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Preview Durasi --}}
          @if($peminjaman->status === 'dipinjam')
          <div class="alert alert-warning mb-3" id="previewDurasi">
            <i class="bi bi-calendar me-2"></i>
            <strong>Preview:</strong> 
            Durasi pinjam: <span id="durasiHari">{{ $durasiSaatIni }}</span> hari 
            (Tanggal kembali: <span id="previewDate">{{ $tanggalKembali->format('d F Y') }}</span>)
          </div>
          @endif

          {{-- Info Peminjaman --}}
          <div class="alert alert-light border">
            <h6><i class="bi bi-info-circle me-2"></i>Informasi Peminjaman</h6>
            <div class="row">
              <div class="col-md-6">
                <small><strong>ID Peminjaman:</strong> #{{ $peminjaman->id }}</small><br>
                <small><strong>Dibuat:</strong> {{ $peminjaman->created_at->format('d M Y H:i') }}</small>
              </div>
              <div class="col-md-6">
                <small><strong>Diupdate:</strong> {{ $peminjaman->updated_at->format('d M Y H:i') }}</small><br>
                @if($peminjaman->denda)
                <small><strong>Denda:</strong> Rp {{ number_format($peminjaman->denda->total_denda, 0, ',', '.') }}</small>
                @endif
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-success me-2" 
                    {{ $peminjaman->status === 'dikembalikan' ? 'onclick="return confirm(\'Peminjaman sudah dikembalikan. Yakin update?\')"' : '' }}>
              <i class="bi bi-check-circle"></i> Update
            </button>
            <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">
              <i class="bi bi-arrow-left-circle"></i> Kembali
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>

  @if($peminjaman->status === 'dipinjam')
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
                  
                  // Set max date untuk tanggal kembali
                  const maxDate = new Date(pinjamDate);
                  maxDate.setDate(pinjamDate.getDate() + maksimalHari);
                  tanggalKembaliInput.max = maxDate.toISOString().split('T')[0];
                  
                  // Set min date untuk tanggal kembali
                  const minDate = new Date(pinjamDate);
                  minDate.setDate(pinjamDate.getDate() + 1);
                  tanggalKembaliInput.min = minDate.toISOString().split('T')[0];
              }
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

      // Update preview saat load
      updatePreview();

      // Validasi stok buku
      const bukuSelect = document.getElementById('buku_id');
      bukuSelect.addEventListener('change', function() {
          const selectedOption = this.options[this.selectedIndex];
          const stok = selectedOption.getAttribute('data-stok');
          const isDisabled = selectedOption.disabled;
          
          if (isDisabled) {
              alert('Buku ini tidak dapat dipilih karena stok habis atau sedang dipinjam oleh peminjaman lain.');
              this.value = '{{ $peminjaman->buku_id }}'; // Kembalikan ke nilai semula
          } else if (stok && parseInt(stok) <= 0) {
              alert('Peringatan: Stok buku ini sudah habis!');
          }
      });
  });
  </script>
  @endif
@endsection