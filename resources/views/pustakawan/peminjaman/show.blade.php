@extends('layouts.app')

@section('content')
  <div class="container mt-4">
    <h3 class="mb-4">
      <i class="fas fa-eye text-primary"></i> Detail Peminjaman
    </h3>

    <div class="card shadow-sm border-0 rounded-3">
      <div class="card-header bg-dark text-white">
        <i class="fas fa-book-reader"></i> Informasi Peminjaman
      </div>

      <div class="card-body">
        <div class="row mb-3">
          <div class="col-md-4 fw-bold text-muted">Nama Peminjam</div>
          <div class="col-md-8">{{ $data->user->name ?? '-' }}</div>
        </div>

        <div class="row mb-3">
          <div class="col-md-4 fw-bold text-muted">Judul Buku</div>
          <div class="col-md-8">{{ $data->buku->judul ?? '-' }}</div>
        </div>

        <div class="row mb-3">
          <div class="col-md-4 fw-bold text-muted">Tanggal Pinjam</div>
          <div class="col-md-8">{{ \Carbon\Carbon::parse($data->tanggal_pinjam)->format('d M Y') }}</div>
        </div>

        <div class="row mb-3">
          <div class="col-md-4 fw-bold text-muted">Tanggal Kembali</div>
          <div class="col-md-8">
            {{ $data->tanggal_kembali ? \Carbon\Carbon::parse($data->tanggal_kembali)->format('d M Y') : '-' }}
          </div>
        </div>

        <div class="row mb-4">
          <div class="col-md-4 fw-bold text-muted">Status</div>
          <div class="col-md-8">
            @if($data->status === 'dipinjam')
              <span class="badge bg-warning text-dark px-3 py-2">Dipinjam</span>
            @else
              <span class="badge bg-success px-3 py-2">Dikembalikan</span>
            @endif
          </div>
        </div>

        <div class="d-flex justify-content-end">
          <a href="{{ route('pustakawan.peminjaman.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
          </a>
        </div>
      </div>
    </div>
  </div>
@endsection