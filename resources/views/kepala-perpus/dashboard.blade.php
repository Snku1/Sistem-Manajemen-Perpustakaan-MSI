@extends('layouts.app')
@section('title', 'Dashboard Kepala Perpustakaan')

@section('content')
  <div class="container py-4">

    <!-- Title -->
    <div class="text-center mb-5">
      <h2 class="fw-bold">
        <i class="fas fa-home me-2 text-primary"></i>
        Dashboard {{ Auth::user()->name }}
      </h2>
    </div>

    <!-- Quick Actions -->
    <div class="row g-3">
      <div class="col-md-3 col-sm-6">
        <div class="card shadow-sm text-center p-3">
          <h5>Data Stok Buku</h5>
          <p class="text-muted">Laporan Stok buku</p>
          <a href="{{ route('kepala-perpus.buku.index') }}" class="btn btn-primary btn-sm">Akses</a>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="card shadow-sm text-center p-3">
          <h5>Data Peminjaman</h5>
          <p class="text-muted">Laporan peminjaman</p>
          <a href="{{ route('kepala-perpus.peminjaman.index') }}" class="btn btn-success btn-sm">Akses</a>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="card shadow-sm text-center p-3">
          <h5>Data Pengembalian</h5>
          <p class="text-muted">Laporan pengembalian</p>
          <a href="{{ route('kepala-perpus.pengembalian.index') }}" class="btn btn-warning btn-sm">Akses</a>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="card shadow-sm text-center p-3">
          <h5>Data Denda</h5>
          <p class="text-muted">Laporan denda</p>
          <a href="{{ route('kepala-perpus.denda.index') }}" class="btn btn-danger btn-sm">Akses</a>
        </div>
      </div>
    </div>

    <!-- Statistics -->
    <h5 class="mt-5 mb-3">
      <i class="bi bi-bar-chart-fill"></i> Statistik Sistem
    </h5>

    <div class="row g-3">

      <div class="col-md-3 col-sm-6">
        <div class="card text-center shadow-sm p-3">
          <i class="fa-solid fa-book fs-1 text-success"></i>
          <h5 class="mt-2">{{ $totalBuku }}</h5>
          <p class="text-muted">Total Buku</p>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="card text-center shadow-sm p-3">
          <i class="fa-solid fa-box-archive fs-1 text-warning"></i>
          <h5 class="mt-2">{{ $totalPeminjaman }}</h5>
          <p class="text-muted">Total Peminjaman</p>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="card text-center shadow-sm p-3">
          <i class="fa-solid fa-box fs-1 text-danger"></i>
          <h5 class="mt-2">{{ $totalPengembalian }}</h5>
          <p class="text-muted">Total Pengembalian</p>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="card text-center shadow-sm p-3">
          <i class="fa-solid fa-users fs-1 text-primary"></i>
          <h5 class="mt-2">{{ $totalUsers }}</h5>
          <p class="text-muted">Total Pengguna</p>
        </div>
      </div>
    </div>

    <!-- Additional Info -->
    <h5 class="mt-5 mb-3">
      <i class="fas fa-info-circle"></i> Informasi Lainnya
    </h5>

    <div class="row g-3">

      <div class="col-md-4">
        <div class="card text-center shadow-sm p-3">
          <i class="fas fa-fire fs-1 text-danger"></i>
          <h5 class="mt-2">
            {{ $bukuPopuler ? $bukuPopuler->judul : '-' }}
          </h5>
          <p class="text-muted">Buku Terpopuler</p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card text-center shadow-sm p-3">
          <i class="fas fa-book-reader fs-1 text-primary"></i>
          <h5 class="mt-2">
            {{ $pustakawanAktif ? $pustakawanAktif->name : '-' }}
          </h5>
          <p class="text-muted">Pustakawan Paling Aktif</p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card text-center shadow-sm p-3">
          <i class="fas fa-money-bill fs-1 text-success"></i>
          <h5 class="mt-2">Rp {{ number_format($totalDenda, 0, ',', '.') }}</h5>
          <p class="text-muted">Total Denda</p>
        </div>
      </div>

    </div>

  </div>
@endsection
