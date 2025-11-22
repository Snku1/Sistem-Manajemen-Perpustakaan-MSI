@extends('layouts.app')

@section('content')
<div class="container mt-4">

  {{-- Header dengan Tombol Export --}}
  <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
    <h3 class="mb-3">
      <i class="fas fa-arrow-circle-down text-success"></i> Data Pengembalian Buku
    </h3>
    <div class="d-flex justify-content-end mb-2 gap-2">
      <a href="{{ route('kepala-perpus.pengembalian.export.pdf', request()->query()) }}"
        class="btn btn-danger shadow-sm">
        <i class="fas fa-file-pdf me-1"></i> Export PDF
      </a>

      <a href="{{ route('kepala-perpus.pengembalian.export.excel', request()->query()) }}"
        class="btn btn-success shadow-sm">
        <i class="fas fa-file-excel me-1"></i> Export Excel
      </a>
    </div>
  </div>

  <!-- Search Bar -->
  <div class="card shadow-sm mb-3">
    <div class="card-body">
      <form method="GET" class="row g-2">
        <div class="col-12 col-md-10">
          <input type="search" name="search" class="form-control" placeholder="Cari pengembalian buku..."
            value="{{ request('search') }}">
        </div>
        <div class="col-12 col-md-2 d-grid">
          <button class="btn btn-primary">
            <i class="fas fa-search"></i> Cari
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Table -->
  <div class="card shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive shadow-sm rounded-3">
        <table class="table table-hover table-bordered align-middle mb-0">
          <thead class="table-success">
            <tr class="text-center">
              <th class="text-center">No</th>
              <th>Nama Peminjam</th>
              <th>Judul Buku</th>
              <th>Tanggal Pinjam</th>
              <th>Tanggal Kembali</th>
              <th>Status</th>
              <th>Denda</th>
              <th>Detail</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($pengembalian as $item)
            <tr>
              <td class="text-center">{{ $loop->iteration }}</td>
              <td>{{ $item->peminjaman->user->name ?? '-' }}</td>
              <td>{{ $item->peminjaman->buku->judul ?? '-' }}</td>
              <td class="text-center">
                {{ \Carbon\Carbon::parse($item->peminjaman->tanggal_pinjam)->format('d M Y') }}
              </td>
              <td class="text-center">
                {{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') }}
              </td>
              <td class="text-center">
                @if($item->peminjaman->denda)
                <span class="badge bg-danger px-3 py-2">
                  <i class="fas fa-clock me-1"></i> Terlambat
                </span>
                @else
                <span class="badge bg-success px-3 py-2">
                  <i class="fas fa-check me-1"></i> Tepat Waktu
                </span>
                @endif
              </td>
              <td class="text-center">
                @if($item->peminjaman->denda)
                <span class="badge bg-warning text-dark px-3 py-2">
                  <i></i> 
                  Rp {{ number_format($item->peminjaman->denda->total_denda, 0, ',', '.') }}
                </span>
                @else
                <span class="badge bg-secondary px-3 py-2">
                  <i class="fas fa-times me-1"></i> Tidak Ada
                </span>
                @endif
              </td>
              <td class="text-center">
                @if($item->peminjaman->denda)
                <span class="badge bg-info px-3 py-2">
                  <i></i> 
                  {{ $item->peminjaman->denda->jumlah_hari }} hari
                </span>
                @else
                <span class="badge bg-light text-dark px-3 py-2">
                  <i class="fas fa-thumbs-up me-1"></i> On Time
                </span>
                @endif
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="8" class="text-center py-4 text-muted">
                <i class="fas fa-info-circle"></i> Tidak ada data pengembalian.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if ($pengembalian->hasPages())
      <div class="p-3">
        {{ $pengembalian->withQueryString()->links() }}
      </div>
      @endif
    </div>
  </div>
</div>
@endsection