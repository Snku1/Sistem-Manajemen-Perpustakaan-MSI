@extends('layouts.app')

@section('content')
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
      <h4 class="fw-bold mb-2"><i class="bi bi-tags"></i> Kategori Buku</h4>
      <a href="{{ route('kategori.create') }}" class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-circle"></i> Tambah Kategori
      </a>
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
                <th>Nama</th>
                <th>Deskripsi</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($kategori as $item)
                <tr>
                  <td>{{ $item->nama }}</td>
                  <td>{{ $item->deskripsi }}</td>
                  <td class="text-center">
                    <a href="{{ route('kategori.edit', $item->id) }}" class="btn btn-warning btn-sm me-1">
                      <i class="bi bi-pencil-square"></i> Edit
                    </a>
                    <form action="{{ route('kategori.destroy', $item->id) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Yakin hapus kategori ini?')">
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
                  <td colspan="3" class="text-center text-muted py-4">
                    <i class="bi bi-inbox"></i> Belum ada kategori buku
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="mt-3">
      {{ $kategori->links() }}
    </div>
  </div>
@endsection