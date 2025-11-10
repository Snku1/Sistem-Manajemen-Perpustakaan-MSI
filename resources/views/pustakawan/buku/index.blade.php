@extends('layouts.app')

@section('content')
  <div class="container mt-4">
    <h3 class="mb-4">
      <i class="fas fa-search text-primary"></i> Cari Buku
    </h3>

    <!-- Form Pencarian -->
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-body">
        <form method="GET" action="{{ route('pustakawan.buku.index') }}">
          <div class="input-group">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-lg"
              placeholder="Cari judul, pengarang, penerbit, kategori atau rak...">
            <button class="btn btn-primary btn-lg" type="submit">
              <i class="fas fa-search"></i> Cari
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Tabel Buku -->
    <div class="card-body p-0">
      <div class="table-responsive shadow-sm rounded-3">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-primary">
            <tr>
              <th class="text-center">No</th>
              <th>Judul</th>
              <th>Pengarang</th>
              <th>Penerbit</th>
              <th>Tahun</th>
              <th>Kategori</th>
              <th>Rak</th>
              <th class="text-center">Stok</th>
            </tr>
          </thead>
          <tbody>
            @forelse($bukus as $index => $buku)
              <tr>
                <td class="text-center fw-bold">{{ $bukus->firstItem() + $index }}</td>
                <td class="fw-semibold">{{ $buku->judul }}</td>
                <td>{{ $buku->pengarang }}</td>
                <td>{{ $buku->penerbit }}</td>
                <td>{{ $buku->tahun_terbit }}</td>
                <td>
                  <span class="badge bg-primary">{{ $buku->kategori->nama ?? '-' }}</span>
                </td>
                <td>
                  <span class="badge bg-info text-dark">{{ $buku->rak->nama ?? '-' }}</span>
                </td>
                <td class="text-center">
                  @if($buku->stok > 0)
                    <span class="badge bg-success">{{ $buku->stok }}</span>
                  @else
                    <span class="badge bg-danger">Habis</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center text-muted py-4">
                  <i class="fas fa-exclamation-circle"></i> Tidak ada buku ditemukan
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="p-3">
        {{ $bukus->withQueryString()->links() }}
      </div>
    </div>
  </div>
@endsection