@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-info">
            <i class="bi bi-today me-2"></i> Peminjaman Hari Ini - Pustakawan
        </h4>
        <div class="btn-group">
            <a href="{{ route('pustakawan.jadwal.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Kalender
            </a>
        </div>
    </div>

    {{-- Tabel Peminjaman --}}
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h6 class="mb-0"><i class="bi bi-list me-2"></i> Aktivitas Hari Ini</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th>Buku</th>
                            <th>Peminjam</th>
                            <th class="text-center">Tanggal Pinjam</th>
                            <th class="text-center">Tanggal Kembali</th>
                            <th class="text-center">Jenis Aktivitas</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamanHariIni as $peminjaman)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $peminjaman->buku->judul }}</strong>
                            </td>
                            <td>{{ $peminjaman->user->name }}</td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}
                                @if($peminjaman->tanggal_pinjam == \Carbon\Carbon::today())
                                    <br><span class="badge bg-primary">Pinjam Hari Ini</span>
                                @endif
                            </td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') }}
                                @if($peminjaman->tanggal_kembali == \Carbon\Carbon::today())
                                    <br><span class="badge bg-warning text-dark">Batas Kembali</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($peminjaman->tanggal_pinjam == \Carbon\Carbon::today() && $peminjaman->tanggal_kembali == \Carbon\Carbon::today())
                                    <span class="badge bg-info">Pinjam & Kembali</span>
                                @elseif($peminjaman->tanggal_pinjam == \Carbon\Carbon::today())
                                    <span class="badge bg-success">Pinjam Baru</span>
                                @else
                                    <span class="badge bg-warning text-dark">Batas Kembali</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $peminjaman->status == 'dipinjam' ? 'warning' : 'success' }}">
                                    {{ $peminjaman->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-calendar-x" style="font-size: 2rem;"></i>
                                <p class="mt-2">Tidak ada aktivitas peminjaman hari ini</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Panduan Tindakan --}}
    <div class="card border-primary mt-3">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0"><i class="bi bi-journal-check me-2"></i> Panduan Tindakan</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="bi bi-check-circle text-success me-2"></i>Untuk Peminjaman Baru:</h6>
                    <ul class="small">
                        <li>Pastikan stok buku tersedia</li>
                        <li>Verifikasi data peminjam</li>
                        <li>Catat tanggal kembali dengan benar</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6><i class="bi bi-arrow-return-left text-warning me-2"></i>Untuk Pengembalian:</h6>
                    <ul class="small">
                        <li>Periksa kondisi buku</li>
                        <li>Hitung denda jika terlambat</li>
                        <li>Update status peminjaman</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection