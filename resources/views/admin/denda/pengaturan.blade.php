@extends('layouts.app')

@section('content')
<div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0">
            <i class="fas fa-cog me-2"></i> Pengaturan Denda & Batas Waktu
          </h5>
        </div>
        <div class="card-body">
          <form action="{{ route('denda.update-pengaturan') }}" method="POST">
            @csrf

            {{-- Bagian Maksimal Hari Peminjaman --}}
            <div class="mb-4">
              <label for="maksimal_hari_peminjaman" class="form-label">
                <strong>Maksimal Hari Peminjaman</strong>
              </label>
              <div class="input-group">
                <input type="number"
                  class="form-control @error('maksimal_hari_peminjaman') is-invalid @enderror"
                  id="maksimal_hari_peminjaman"
                  name="maksimal_hari_peminjaman"
                  value="{{ old('maksimal_hari_peminjaman', $pengaturan->maksimal_hari_peminjaman ?? 7) }}"
                  min="1"
                  max="30"
                  required>
                <span class="input-group-text">hari</span>
                @error('maksimal_hari_peminjaman')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="form-text">
                Jumlah maksimal hari yang diperbolehkan untuk meminjam buku. User bisa memilih tanggal kembali dalam rentang ini.
              </div>
            </div>

            {{-- Bagian Masa Tenggang --}}
            <div class="mb-4">
              <label for="masa_tenggang" class="form-label">
                <strong>Masa Tenggang</strong>
              </label>
              <div class="input-group">
                <input type="number"
                  class="form-control @error('masa_tenggang') is-invalid @enderror"
                  id="masa_tenggang"
                  name="masa_tenggang"
                  value="{{ old('masa_tenggang', $pengaturan->masa_tenggang ?? 3) }}"
                  min="0"
                  max="30"
                  required>
                <span class="input-group-text">hari</span>
                @error('masa_tenggang')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="form-text">
                Jumlah hari toleransi setelah tanggal kembali sebelum dikenakan denda.
              </div>
            </div>

            {{-- Denda per Hari --}}
            <div class="mb-4">
              <label for="denda_per_hari" class="form-label">
                <strong>Denda per Hari</strong>
              </label>
              <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="number"
                  class="form-control @error('denda_per_hari') is-invalid @enderror"
                  id="denda_per_hari"
                  name="denda_per_hari"
                  value="{{ old('denda_per_hari', $pengaturan->denda_per_hari ?? 10000) }}"
                  min="0"
                  step="1000"
                  required>
                @error('denda_per_hari')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="form-text">
                Besaran denda yang dikenakan untuk setiap hari keterlambatan setelah masa tenggang.
              </div>
            </div>

            {{-- Ringkasan Perhitungan --}}
            <div class="card border-info mb-4">
              <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="fas fa-calculator me-2"></i> Ringkasan Perhitungan</h6>
              </div>
              <div class="card-body">
                <div class="row text-center">
                  <div class="col-md-4">
                    <h6>Total Waktu</h6>
                    <h4 class="text-primary">
                      {{ old('maksimal_hari_peminjaman', $pengaturan->maksimal_hari_peminjaman ?? 7) + old('masa_tenggang', $pengaturan->masa_tenggang ?? 3) }} hari
                    </h4>
                    <small class="text-muted">Batas + Tenggang</small>
                  </div>
                  <div class="col-md-4">
                    <h6>Mulai Denda</h6>
                    <h4 class="text-warning">
                      Hari ke-{{ (old('maksimal_hari_peminjaman', $pengaturan->maksimal_hari_peminjaman ?? 7) + old('masa_tenggang', $pengaturan->masa_tenggang ?? 3) + 1) }}
                    </h4>
                    <small class="text-muted">Setelah tenggang</small>
                  </div>
                  <div class="col-md-4">
                    <h6>Denda 5 Hari</h6>
                    <h4 class="text-danger">
                      Rp {{ number_format((old('denda_per_hari', $pengaturan->denda_per_hari ?? 10000) * 5), 0, ',', '.') }}
                    </h4>
                    <small class="text-muted">Contoh 5 hari terlambat</small>
                  </div>
                </div>
              </div>
            </div>

            @if($pengaturan)
            <div class="alert alert-warning">
              <i class="fas fa-exclamation-triangle me-2"></i>
              <strong>Perhatian:</strong> Pengaturan baru akan menggantikan pengaturan saat ini.
              <div class="mt-2">
                <strong>Pengaturan saat ini:</strong><br>
                • Batas Waktu: <strong>{{ $pengaturan->maksimal_hari_peminjaman }} hari</strong><br>
                • Masa Tenggang: <strong>{{ $pengaturan->masa_tenggang }} hari</strong><br>
                • Denda per Hari: <strong>Rp {{ number_format($pengaturan->denda_per_hari, 0, ',', '.') }}</strong>
              </div>
            </div>
            @endif

            <div class="d-flex justify-content-between">
              <a href="{{ route('denda.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Simpan Pengaturan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Update ringkasan secara real-time
  document.addEventListener('DOMContentLoaded', function() {
    const batasInput = document.getElementById('maksimal_hari_peminjaman');
    const tenggangInput = document.getElementById('masa_tenggang');
    const dendaInput = document.getElementById('denda_per_hari');

    function updateRingkasan() {
      const batas = parseInt(batasInput.value) || 7;
      const tenggang = parseInt(tenggangInput.value) || 3;
      const denda = parseInt(dendaInput.value) || 10000;

      // Update total waktu
      document.querySelector('.text-primary').textContent = (batas + tenggang) + ' hari';

      // Update mulai denda
      document.querySelector('.text-warning').textContent = 'Hari ke-' + (batas + tenggang + 1);

      // Update contoh denda
      document.querySelector('.text-danger').textContent = 'Rp ' + (denda * 5).toLocaleString('id-ID');
    }

    // Add event listeners
    batasInput.addEventListener('input', updateRingkasan);
    tenggangInput.addEventListener('input', updateRingkasan);
    dendaInput.addEventListener('input', updateRingkasan);
  });
</script>
@endsection