@extends('layouts.app')

@section('content')
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="fw-bold">
        <i class="bi bi-journal-check me-2"></i> Daftar Buku yang Masih Dipinjam
      </h4>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <div class="card shadow-sm border-0 rounded-3">
      <div class="card-body p-0">
        <div class="table-responsive shadow-sm rounded-3">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-primary">
              <tr>
                <th class="text-center" style="width: 5%">No</th>
                <th style="width: 30%">Buku</th>
                <th style="width: 25%">Peminjam</th>
                <th class="text-center">Tanggal Pinjam</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($peminjaman as $item)
                <tr>
                  <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                  <td>{{ $item->buku->judul }}</td>
                  <td>{{ $item->user->name }}</td>
                  <td class="text-center">
                    <span class="badge bg-light text-dark shadow-sm">
                      {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}
                    </span>
                  </td>
                  <td class="text-center">
                    <a href="{{ route('pengembalian.create', $item->id) }}"
                      class="btn btn-success btn-sm rounded-pill shadow-sm px-3">
                      <i class="bi bi-arrow-return-left me-1"></i> Proses
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center py-4">
                    <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                    <p class="mt-2 mb-0 text-muted">Tidak ada buku yang sedang dipinjam.</p>
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