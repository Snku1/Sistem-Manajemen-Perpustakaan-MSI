@extends('layouts.app')
@section('title', 'Data Buku')
@section('content')
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
      <h4 class="fw-bold mb-2"><i class="bi bi-book"></i> Data Buku</h4>
      <div class="d-flex gap-2">
        <a href="{{ route('buku.create') }}" class="btn btn-primary shadow-sm">
          <i class="bi bi-plus-circle"></i> Tambah Buku
        </a>
        <button type="button" class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#importModal">
          <i class="bi bi-file-earmark-excel"></i> Import
        </button>
      </div>
    </div>

    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="importModalLabel">Import Data Buku dari Excel</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          {{-- Form untuk import --}}
          <form action="{{ route('buku.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
              <p class="mb-3">
                Pastikan file Excel Anda memiliki header kolom: <strong>judul, pengarang, kategori_id, rak_id,
                  stok</strong>.
              </p>
              <div class="mb-3">
                <label for="file" class="form-label">Pilih file Excel (.xlsx atau .xls)</label>
                <input class="form-control" type="file" id="file" name="file" accept=".xlsx, .xls" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-upload"></i> Unggah dan Import
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Form Pencarian -->
    <div class="mb-3">
      <form action="{{ route('buku.index') }}" method="GET" class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Cari judul atau pengarang..." name="search"
          value="{{ request('search') }}">
        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
      </form>
    </div>

    @if (session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="card shadow-sm border-0">
      <div class="card-body p-0">
        <div class="table-responsive shadow-sm rounded-3">
          <table class="table table-striped align-middle mb-0">
            <thead class="table-primary">
              <tr>
                <th>Judul</th>
                <th>Pengarang</th>
                <th>Kategori</th>
                <th>Rak</th>
                <th>Stok</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($buku as $item)
                <tr>
                  <td>{{ $item->judul }}</td>
                  <td>{{ $item->pengarang }}</td>
                  <td>{{ $item->kategori->nama }}</td>
                  <td>{{ $item->rak->nama }}</td>
                  <td>
                    <span class="badge bg-{{ $item->stok > 0 ? 'success' : 'danger' }}">
                      {{ $item->stok }}
                    </span>
                  </td>
                  <td class="text-center">
                    <a href="{{ route('buku.edit', $item->id) }}" class="btn btn-warning btn-sm me-1">
                      <i class="bi bi-pencil-square"></i> Edit
                    </a>
                    <form action="{{ route('buku.destroy', $item->id) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Yakin hapus buku ini?')">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-danger btn-sm">
                        <i class="bi bi-trash"></i> Hapus
                      </button>
                    </form>
                  </td>
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
    </div>

    <div class="mt-3">
      {{ $buku->links() }}
    </div>
  </div>
@endsection