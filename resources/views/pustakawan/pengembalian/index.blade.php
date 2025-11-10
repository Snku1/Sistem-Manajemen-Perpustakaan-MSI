@extends('layouts.app')

@section('content')
  <div class="container mt-4">
    <h3 class="mb-3">
      <i class="fas fa-undo text-primary"></i> Data Pengembalian Buku
    </h3>
    <p class="text-muted">Anda hanya dapat melihat data, tidak dapat mengubah.</p>
    <div class="card-body p-0">
      <div class="table-responsive shadow-sm rounded-3">
        <table class="table table-hover table-bordered mb-0 align-middle">
          <thead class="table-primary">
            <tr class="text-center">
              <th width="5%">No</th>
              <th>Nama Peminjam</th>
              <th>Judul Buku</th>
              <th>Tanggal Pinjam</th>
              <th>Tanggal Dikembalikan</th>
              <th>Denda</th>
              <th width="10%">Detail</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($pengembalian as $item)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $item->user->name ?? '-' }}</td>
                <td>{{ $item->buku->judul ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') ?? '-' }}</td>
                <td>{{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') : '-' }}
                </td>
                <td class="text-center">
                  @if($item->denda > 0)
                    <span class="badge bg-danger px-3 py-2">
                      Rp {{ number_format($item->denda, 0, ',', '.') }}
                    </span>
                  @else
                    <span class="badge bg-success px-3 py-2">Tidak Ada</span>
                  @endif
                </td>
                <td class="text-center">
                  <a href="{{ route('pustakawan.pengembalian.show', $item->id) }}"
                    class="btn btn-sm btn-primary rounded-pill">
                    <i class="fas fa-eye"></i> Lihat
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center text-muted py-4">
                  <i class="fas fa-info-circle"></i> Tidak ada data pengembalian.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="p-3 d-flex justify-content-end">
        {{ $pengembalian->links() }}
      </div>
    </div>
  </div>
@endsection