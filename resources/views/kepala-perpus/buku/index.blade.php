@extends('layouts.app')

@section('title', 'Laporan Stok Buku')

@section('content')
<div class="container-fluid mt-3">

  {{-- Tombol Export --}}
  <div class="d-flex justify-content-between align-items-center flex-wrap mb-2 mt-4">
    <h4 class="fw-bold mb-2"><i class="bi bi-book me-2"></i> Data Laporan Stok Buku</h4>
    <div class="d-flex justify-content-end mb-3 mt-2 gap-2">
      <a href="{{ route('kepala-perpus.buku.export.pdf') }}"
        class="btn btn-danger shadow-sm">
        <i class="fas fa-file-pdf me-1"></i> Export PDF
      </a>

      <a href="{{ route('kepala-perpus.buku.export.excel') }}"
        class="btn btn-success shadow-sm">
        <i class="fas fa-file-excel me-1"></i> Export Excel
      </a>
    </div>
  </div>

  <!-- Form Pencarian -->
  <div class="mb-3">
    <form action="{{ route('kepala-perpus.buku.index') }}" method="GET" class="d-flex">
      <input class="form-control me-2" type="search" placeholder="Cari judul buku..." name="search"
        value="{{ request('search') }}">
      <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
    </form>
  </div>

  {{-- ALERT SUCCESS --}}
  @if (session('success'))
  <div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  {{-- TABEL BUKU --}}
  <div class="card shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive shadow-sm rounded-3">
        <table class="table table-striped align-middle mb-0">
          <thead class="table-primary">
            <tr>
              <th class="text-center">No</th>
              <th>Judul Buku</th>
              <th>Stok Total</th>
              <th>Dipinjam</th>
              <th>Dikembalikan</th>
              <th>Tanggal Input</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($buku as $item)
            <tr>
              <td class="text-center">{{ $loop->iteration }}</td>
              <td>{{ $item->judul }}</td>

              {{-- TOTAL STOK --}}
              <td>
                <span class="badge bg-{{ $item->stok > 0 ? 'success' : 'danger' }}">
                  {{ $item->stok }}
                </span>
              </td>

              {{-- DIPINJAM SEKARANG --}}
              <td>
                <span class="badge bg-warning text-dark">
                  {{ $item->dipinjam ?? 0 }}
                </span>
              </td>

              {{-- DIKEMBALIKAN SEKARANG --}}
              <td>
                <span class="badge bg-info text-dark">
                  {{ $item->dikembalikan ?? 0 }}
                </span>
              </td>

              {{-- CREATED_AT --}}
              <td>{{ $item->created_at->format('d M Y') }}</td>

            </tr>
            @empty
            <tr>
              <td colspan="6" class="text-center text-muted py-4">
                @if (request('search'))
                <i class="bi bi-search-heart"></i> Tidak ada buku yang cocok dengan pencarian
                <strong>"{{ request('search') }}"</strong>.
                @else
                <i class="bi bi-inbox"></i> Tidak ada data buku.
                @endif
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- PAGINATION --}}
    <div class="card-footer">
      {{ $buku->links() }}
    </div>
  </div>

</div>
@endsection