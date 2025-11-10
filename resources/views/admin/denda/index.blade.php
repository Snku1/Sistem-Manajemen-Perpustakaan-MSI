@extends('layouts.app')

@section('content')
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="fw-bold text-danger">
        <i class="bi bi-cash-coin me-2"></i> Daftar Denda
      </h4>
      <a href="{{ route('denda.hitung') }}" class="btn btn-warning shadow-sm rounded-pill px-3">
        <i class="bi bi-calculator me-1"></i> Hitung Denda
      </a>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <div class="card shadow-sm border-0 rounded-3">
      <div class="card-body p-0">
        <div class="table-responsive shadow-sm rounded-3">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-danger">
              <tr>
                <th style="width: 5%">No</th>
                <th style="width: 20%">Nama Peminjam</th>
                <th style="width: 25%">Judul Buku</th>
                <th style="width: 15%">Tanggal Kembali</th>
                <th style="width: 15%">Hari Terlambat</th>
                <th style="width: 20%">Total Denda</th>
              </tr>
            </thead>
            <tbody>
              @forelse($denda as $d)
                <tr>
                  <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                  <td>{{ $d->peminjaman->user->name }}</td>
                  <td>{{ $d->peminjaman->buku->judul }}</td>
                  <td class="text-center">
                    <span class="badge bg-light text-dark shadow-sm">
                      {{ \Carbon\Carbon::parse($d->peminjaman->tanggal_kembali)->format('d M Y') }}
                    </span>
                  </td>
                  <td class="text-center">
                    <span class="badge bg-danger text-white px-3 py-2">
                      {{ $d->jumlah_hari }} hari
                    </span>
                  </td>
                  <td class="fw-bold text-danger text-end">
                    Rp {{ number_format($d->total_denda, 0, ',', '.') }}
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center py-4">
                    <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                    <p class="mt-2 mb-0 text-muted">Belum ada data denda</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection