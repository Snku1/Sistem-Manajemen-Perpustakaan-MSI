@extends('layouts.app')

@section('content')
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

      {{-- Judul Halaman (dipindahkan ke sini) --}}
      <h4 class="fw-bold text-primary m-0"> {{-- m-0 untuk hapus margin default h4 --}}
        <i class="bi bi-hdd me-2"></i> Manajemen Backup
      </h4>

      {{-- Form Tombol (dipindahkan ke sini) --}}
      <form action="{{ route('backup.store') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary shadow-sm">
          <i class="bi bi-plus-circle me-2"></i> Buat Backup Baru
        </button>
      </form>
    </div>

    {{-- Pesan sukses --}}
    @if (session('success'))
      <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    {{-- Table Backup --}}
    <div class="table-responsive shadow-sm rounded-3">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-primary">
          <tr>
            <th class="text-center" style="width:5%">No</th>
            <th>Nama File</th>
            <th class="text-center" style="width:15%">Tanggal Backup</th>
            <th class="text-center" style="width:10%">Ukuran</th>
            <th class="text-center" style="width:15%">Dibuat Oleh</th>
            <th class="text-center" style="width:20%">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($backups as $backup)
            <tr>
              <td class="text-center">{{ $loop->iteration }}</td>
              <td class="fw-semibold">{{ $backup->file_name }}</td>
              <td class="text-center">{{ \Carbon\Carbon::parse($backup->backup_date)->format('d M Y H:i') }}</td>
              <td class="text-center">{{ number_format($backup->file_size / 1024, 2) }} KB</td>
              <td class="text-center">{{ $backup->user->name ?? 'Unknown' }}</td>
              <td class="text-center">
                <a href="{{ route('backup.download', $backup->id) }}" class="btn btn-success btn-sm rounded-pill me-1">
                  <i class="bi bi-download"></i> Download
                </a>
                <a href="{{ route('backup.restore', $backup->id) }}" class="btn btn-warning btn-sm rounded-pill me-1"
                  onclick="return confirm('Apakah Anda yakin ingin merestore database dari backup ini? Semua data akan diganti!')">
                  <i class="bi bi-arrow-repeat"></i> Restore
                </a>
                <form action="{{ route('backup.destroy', $backup->id) }}" method="POST" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm rounded-pill"
                    onclick="return confirm('Hapus backup ini?')">
                    <i class="bi bi-trash"></i> Hapus
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-muted py-4">
                <i class="bi bi-exclamation-circle me-2"></i> Belum ada data backup
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
      {{ $backups->links() }}
    </div>
  </div>
@endsection