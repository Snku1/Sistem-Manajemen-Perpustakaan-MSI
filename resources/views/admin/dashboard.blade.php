@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
  <div class="container py-4">
    <div class="text-center mb-5">
      <h2 class="fw-bold">
        <i class="fas fa-home me-2 text-primary"></i>Dashboard <?php echo Auth::user()->name; ?>
      </h2>
    </div>

    <div class="row g-3">
      <!-- Quick Action Cards -->
      <div class="col-md-3 col-sm-6">
        <div class="card shadow-sm text-center p-3">
          <h5>Data Buku</h5>
          <p class="text-muted">Kelola koleksi buku</p>
          <a href="{{ route('buku.index') }}" class="btn btn-primary btn-sm">Akses</a>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="card shadow-sm text-center p-3">
          <h5>Peminjaman</h5>
          <p class="text-muted">Kelola peminjaman</p>
          <a href="{{ route('peminjaman.index') }}" class="btn btn-success btn-sm">Akses</a>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="card shadow-sm text-center p-3">
          <h5>Pengembalian</h5>
          <p class="text-muted">Proses pengembalian</p>
          <a href="{{ route('pengembalian.index') }}" class="btn btn-warning btn-sm">Akses</a>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="card shadow-sm text-center p-3">
          <h5>Jadwal Transaksi</h5>
          <p class="text-muted">Timeline transaksi</p>
          <a href="{{ route('jadwal.index') }}" class="btn btn-dark btn-sm">Akses</a>
        </div>
      </div>
    </div>

    <h5 class="mt-5 mb-3"><i class="bi bi-bar-chart-fill"></i> Statistik Sistem</h5>

    <div class="row g-3">
      <div class="col-md-3 col-sm-6">
        <div class="card text-center shadow-sm p-3">
          <i class="fa-solid fa-users fs-1 text-primary"></i>
          <h5 class="mt-2">{{ $totalUsers }}</h5>
          <p class="text-muted">Pengguna</p>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="card text-center shadow-sm p-3">
          <i class="fa-solid fa-book fs-1 text-success"></i>
          <h5 class="mt-2">{{ $totalBooks }}</h5>
          <p class="text-muted">Buku</p>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="card text-center shadow-sm p-3">
          <i class="fa-solid fa-box-archive fs-1 text-warning"></i>
          <h5 class="mt-2">{{ $totalLoans }}</h5>
          <p class="text-muted">Peminjaman</p>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="card text-center shadow-sm p-3">
          <i class="fa-solid fa-box fs-1 text-danger"></i>
          <h5 class="mt-2">{{ $totalReturns }}</h5>
          <p class="text-muted">Pengembalian</p>
        </div>
      </div>
    </div>
  </div>
@endsection