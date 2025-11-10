@extends('layouts.app')

@section('content')
  <div class="container my-4">
    <!-- Judul Dashboard -->
    <div class="text-center mb-5">
      <h2 class="fw-bold">
        <i class="fas fa-home me-2 text-primary"></i>Dashboard <?php echo Auth::user()->name; ?>
      </h2>
      <p class="text-muted">
        Selamat datang, Anda login sebagai <strong>Pustakawan</strong>.
        Anda hanya dapat melihat data peminjaman, pengembalian, dan mencari buku.
      </p>
    </div>

    <!-- Statistik Card -->
    <div class="row g-4">
      <!-- Total Buku -->
      <div class="col-12 col-md-4">
        <div class="card shadow-lg border-0 rounded-3 h-100">
          <div class="card-body text-center">
            <div class="mb-3 text-primary">
              <i class="fas fa-book fa-3x"></i>
            </div>
            <h3 class="fw-bold mb-1">{{ $totalBuku }}</h3>
            <p class="text-muted mb-0">Total Buku</p>
          </div>
        </div>
      </div>

      <!-- Total Peminjaman -->
      <div class="col-12 col-md-4">
        <div class="card shadow-lg border-0 rounded-3 h-100">
          <div class="card-body text-center">
            <div class="mb-3 text-warning">
              <i class="fas fa-arrow-circle-up fa-3x"></i>
            </div>
            <h3 class="fw-bold mb-1">{{ $totalPeminjaman }}</h3>
            <p class="text-muted mb-0">Total Peminjaman</p>
          </div>
        </div>
      </div>

      <!-- Total Pengembalian -->
      <div class="col-12 col-md-4">
        <div class="card shadow-lg border-0 rounded-3 h-100">
          <div class="card-body text-center">
            <div class="mb-3 text-success">
              <i class="fas fa-arrow-circle-down fa-3x"></i>
            </div>
            <h3 class="fw-bold mb-1">{{ $totalPengembalian }}</h3>
            <p class="text-muted mb-0">Total Pengembalian</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Info Section -->
    <div class="alert alert-info mt-5 shadow-sm border-0">
      <i class="fas fa-info-circle me-2"></i>
      Anda memiliki akses terbatas. Silakan gunakan menu di sidebar untuk melihat data peminjaman, pengembalian, dan
      pencarian buku.
    </div>
  </div>
@endsection

@push('styles')
  <style>
    .card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.15);
    }
  </style>
@endpush