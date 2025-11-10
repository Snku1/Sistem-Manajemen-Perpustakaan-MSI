@extends('layouts.app')

@section('content')
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
      <h4 class="fw-bold mb-2"><i class="bi bi-grid-3x3-gap"></i> Rak Buku</h4>
      <a href="{{ route('rak.create') }}" class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-circle"></i> Tambah Rak
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
                <th>Kode</th>
                <th>Nama Rak</th>
                <th>Lokasi</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($rak as $item)
                <tr>
                  <td>{{ $item->kode }}</td>
                  <td>{{ $item->nama }}</td>
                  <td>{{ $item->lokasi }}</td>
                  <td class="text-center">
                    <a href="{{ route('rak.edit', $item->id) }}" class="btn btn-warning btn-sm me-1">
                      <i class="bi bi-pencil-square"></i> Edit
                    </a>
                    <form action="{{ route('rak.destroy', $item->id) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Yakin hapus rak ini?')">
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
                  <td colspan="4" class="text-center text-muted py-4">
                    <i class="bi bi-inbox"></i> Belum ada rak buku
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="mt-3">
      {{ $rak->links() }}
    </div>
  </div>
@endsection