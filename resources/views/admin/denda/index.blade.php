@extends('layouts.app')

@section('content')
<div class="container mt-4">
  {{-- Header dengan Tombol Pengaturan --}}
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-danger">
      <i class="bi bi-cash-coin me-2"></i> Daftar Denda
    </h4>
    <a href="{{ route('denda.pengaturan') }}" class="btn btn-warning shadow-sm rounded-pill px-3">
      <i class="bi bi-gear me-1"></i> Atur Denda & Batas Waktu
    </a>
  </div>

  {{-- Notifikasi --}}
  @if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  {{-- Info Pengaturan Saat Ini --}}
  @if($pengaturan)
  <div class="alert alert-info alert-dismissible fade show shadow-sm border-0 rounded-3" role="alert">
    <div class="row align-items-center">
      <div class="col-md-8">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>Pengaturan Saat Ini:</strong><br>
        <span class="badge bg-primary me-2">
          <i class="bi bi-calendar me-1"></i> Batas Waktu: {{ $pengaturan->maksimal_hari_peminjaman }} hari
        </span>
        <span class="badge bg-warning text-dark me-2">
          <i class="bi bi-clock me-1"></i> Masa Tenggang: {{ $pengaturan->masa_tenggang }} hari
        </span>
        <span class="badge bg-success me-2">
          <i class="bi bi-currency-dollar me-1"></i> Denda: Rp {{ number_format($pengaturan->denda_per_hari, 0, ',', '.') }}/hari
        </span>
      </div>
      <div class="col-md-4 text-end">
        <small class="text-muted">
          Berlaku sejak: {{ $pengaturan->created_at->format('d M Y') }}
        </small>
      </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  {{-- Tabel Denda --}}
  <div class="card shadow-sm border-0 rounded-3">
    <div class="card-body p-0">
      <div class="table-responsive shadow-sm rounded-3">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-danger">
            <tr>
              <th class="text-center" style="width: 5%">No</th>
              <th style="width: 20%">Nama Peminjam</th>
              <th style="width: 25%">Judul Buku</th>
              <th style="width: 15%">Tanggal Kembali</th>
              <th style="width: 15%">Hari Terlambat</th>
              <th style="width: 20%">Total Denda</th>
            </tr>
          </thead>
          <tbody>
            @forelse($denda as $d)
            @php
            // Ambil tanggal dikembalikan dari data pengembalian
            $tanggalDikembalikan = $d->peminjaman->pengembalian->tanggal_kembali ?? $d->peminjaman->tanggal_kembali ?? $d->created_at;
            @endphp
            <tr>
              <td class="text-center fw-bold">{{ $loop->iteration }}</td>
              <td>{{ $d->peminjaman->user->name ?? '-' }}</td>
              <td>{{ $d->peminjaman->buku->judul ?? '-' }}</td>
              <td class="text-center">
                <span class="badge bg-light text-dark shadow-sm">
                  {{ \Carbon\Carbon::parse($tanggalDikembalikan)->format('d M Y') }}
                </span>
              </td>
              <td class="text-center">
                <span class="badge bg-danger text-white px-3 py-2">
                  <i class="bi bi-clock-history me-1"></i>
                  {{ $d->jumlah_hari }} hari
                </span>
              </td>
              <td class="fw-bold text-danger">
                <span class="badge bg-warning text-dark px-3 py-2">
                  Rp {{ number_format($d->total_denda, 0, ',', '.') }}
                </span>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="text-center py-4">
                <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                <p class="mt-2 mb-0 text-muted">Belum ada data denda</p>
                @if(!$pengaturan)
                <div class="mt-3">
                  <a href="{{ route('denda.pengaturan') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-gear me-1"></i> Atur Denda Pertama Kali
                  </a>
                </div>
                @endif
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- Pagination --}}
  @if($denda->hasPages())
  <div class="mt-3">
    {{ $denda->links() }}
  </div>
  @endif
</div>
@endsection