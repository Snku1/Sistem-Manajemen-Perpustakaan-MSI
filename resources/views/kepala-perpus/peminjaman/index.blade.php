@extends('layouts.app')

@section('content')
<div class="container mt-4">

  {{-- Tombol Export --}}
  <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
    <h3 class="mb-3">
      <i class="fas fa-arrow-circle-up text-primary"></i> Data Peminjaman Buku
    </h3>
    <div class="d-flex justify-content-end mb-2 gap-2">
      <a href="{{ route('kepala-perpus.peminjaman.export.pdf') }}"
        class="btn btn-danger shadow-sm">
        <i class="fas fa-file-pdf me-1"></i> Export PDF
      </a>

      <a href="{{ route('kepala-perpus.peminjaman.export.excel') }}"
        class="btn btn-success shadow-sm">
        <i class="fas fa-file-excel me-1"></i> Export Excel
      </a>
    </div>
  </div>

  <!-- Search Bar -->
  <div class="card shadow-sm mb-3">
    <div class="card-body">
      <form method="GET" action="{{ route('kepala-perpus.peminjaman.index', request()->query()) }}" class="row g-2">
        <div class="col-12 col-md-10">
          <input type="search" name="search" class="form-control" placeholder="Cari peminjaman buku..."
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
          <thead class="table-primary">
            <tr class="text-center">
              <th class="text-center">No</th>
              <th>Nama Peminjam</th>
              <th>Judul Buku</th>
              <th>Tanggal Pinjam</th>
              <th>Tanggal Kembali</th>
              <th>Status</th>
              <th>Detail</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($peminjaman as $item)
            <tr>
              <td class="text-center">{{ $loop->iteration }}</td>
              <td>{{ $item->user->name ?? '-' }}</td>
              <td>{{ $item->buku->judul ?? '-' }}</td>
              <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
              <td class="text-center">
                {{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') : '-' }}
              </td>
              <td class="text-center">
                @if($item->status === 'dipinjam')
                <span class="badge bg-warning text-dark px-3 py-2">Dipinjam</span>
                @else
                <span class="badge bg-success px-3 py-2">Dikembalikan</span>
                @endif
              </td>
              <td class="text-center">
                <a href="{{ route('kepala-perpus.peminjaman.show', $item->id) }}" class="btn btn-sm btn-info">
                  <i class="fas fa-eye"></i> Detail
                </a>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="text-center py-4 text-muted">
                <i class="fas fa-info-circle"></i> Tidak ada data peminjaman.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if ($peminjaman->hasPages())
      <div class="p-3">
        {{ $peminjaman->withQueryString()->links() }}
      </div>
      @endif
    </div>
  </div>
</div>
@endsection