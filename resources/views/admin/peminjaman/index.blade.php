@extends('layouts.app')

@section('content')
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
      <h4 class="fw-bold"><i class="bi bi-journal-arrow-down"></i> Daftar Peminjaman Buku</h4>
      <a href="{{ route('peminjaman.create') }}" class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-circle"></i> Tambah Peminjaman
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
                <th class="text-center">No</th>
                <th class="text-center">Buku</th>
                <th>Peminjam</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($peminjaman as $item)
                <tr>
                  <td class="text-center">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ $item->buku->judul }}</td>
                  <td>{{ $item->user->name }}</td>
                  <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
                  <td>{{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') : '-' }}
                  </td>
                  <td>
                    @if ($item->status == 'dipinjam')
                      <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Dipinjam</span>
                    @else
                      <span class="badge bg-success"><i class="bi bi-check-circle"></i> Dikembalikan</span>
                    @endif
                  </td>
                  <td class="text-center">
                    <a href="{{ route('peminjaman.edit', $item->id) }}" class="btn btn-warning btn-sm me-1">
                      <i class="bi bi-pencil-square"></i> Edit
                    </a>
                    <form action="{{ route('peminjaman.destroy', $item->id) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Yakin hapus peminjaman ini?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-danger btn-sm">
                        <i class="bi bi-trash"></i> Hapus
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center text-muted py-4">
                    <i class="bi bi-inbox"></i> Belum ada data peminjaman
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