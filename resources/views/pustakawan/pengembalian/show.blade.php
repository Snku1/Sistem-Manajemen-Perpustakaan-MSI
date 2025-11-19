@extends('layouts.app')

@section('content')
  <div class="container mt-4">
    <h3 class="mb-4">
      <i class="fas fa-eye text-primary"></i> Detail Pengembalian Buku
    </h3>

    <div class="card shadow-sm border-0 rounded-3">
      <div class="card-header bg-dark text-white">
        <i class="fas fa-info-circle"></i> Informasi Pengembalian
      </div>

      <div class="card-body">
        <div class="row mb-3">
          <div class="col-md-6">
            <p class="mb-1 text-muted">Nama Peminjam</p>
            <h6 class="fw-bold">{{ $data->user->name ?? '-' }}</h6>
          </div>
          <div class="col-md-6">
            <p class="mb-1 text-muted">Judul Buku</p>
            <h6 class="fw-bold">{{ $data->buku->judul ?? '-' }}</h6>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <p class="mb-1 text-muted">Tanggal Pinjam</p>
            <h6 class="fw-bold">
              {{ \Carbon\Carbon::parse($data->tanggal_pinjam)->format('d M Y') ?? '-' }}
            </h6>
          </div>
          <div class="col-md-6">
            <p class="mb-1 text-muted">Tanggal Dikembalikan</p>
            <h6 class="fw-bold">
              {{ $data->tanggal_kembali ? \Carbon\Carbon::parse($data->tanggal_kembali)->format('d M Y') : '-' }}
            </h6>
          </div>
        </div>

        <div class="mb-3">
          <p class="mb-1 text-muted">Denda</p>
          @if($data->peminjaman->denda)
            <span class="badge bg-danger fs-6 px-3 py-2">
              Rp {{ number_format($data->peminjaman->denda->total_denda, 0, ',', '.') }}
            </span>
          @else
            <span class="badge bg-success fs-6 px-3 py-2">Tidak Ada</span>
          @endif
        </div>

        <div class="d-flex justify-content-end">
          <a href="{{ route('pustakawan.pengembalian.index') }}" class="btn btn-outline-primary rounded-pill">
            <i class="fas fa-arrow-left"></i> Kembali
          </a>
        </div>
      </div>
    </div>
  </div>
@endsection