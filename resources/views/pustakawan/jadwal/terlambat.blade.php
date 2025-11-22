@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-danger">
            <i class="bi bi-exclamation-triangle me-2"></i> Peminjaman Terlambat - Pustakawan
        </h4>
        <div class="btn-group">
            <a href="{{ route('pustakawan.jadwal.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Kalender
            </a>
        </div>
    </div>

    {{-- Info Pengaturan Denda --}}
    @if($pengaturan)
    <div class="alert alert-danger mb-4">
        <i class="bi bi-exclamation-octagon me-2"></i>
        <strong>Aturan Denda Aktif:</strong> 
        Masa tenggang {{ $pengaturan->masa_tenggang }} hari | 
        Denda Rp {{ number_format($pengaturan->denda_per_hari, 0, ',', '.') }}/hari
    </div>
    @endif

    {{-- Statistik Keterlambatan --}}
    @php
    $totalTerlambat = $terlambat->count();
    $dalamMasaTenggang = $terlambat->filter(function($item) {
        return $item->status_detail['status'] === 'masa_tenggang';
    })->count();
    $sudahKenaDenda = $terlambat->filter(function($item) {
        return $item->status_detail['status'] === 'kena_denda';
    })->count();
    @endphp

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 bg-danger text-white shadow-sm">
                <div class="card-body text-center">
                    <h6>Total Terlambat</h6>
                    <h3>{{ $totalTerlambat }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-warning text-dark shadow-sm">
                <div class="card-body text-center">
                    <h6>Masa Tenggang</h6>
                    <h3>{{ $dalamMasaTenggang }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-dark text-white shadow-sm">
                <div class="card-body text-center">
                    <h6>Kena Denda</h6>
                    <h3>{{ $sudahKenaDenda }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Peminjaman Terlambat --}}
    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
            <h6 class="mb-0"><i class="bi bi-exclamation-octagon me-2"></i> Daftar Peminjaman Terlambat</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th>Buku</th>
                            <th>Peminjam</th>
                            <th class="text-center">Tanggal Kembali</th>
                            <th class="text-center">Keterlambatan</th>
                            <th class="text-center">Status Denda</th>
                            <th class="text-center">Estimasi Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($terlambat as $peminjaman)
                        @php
                            $tanggalKembali = \Carbon\Carbon::parse($peminjaman->tanggal_kembali);
                            $today = \Carbon\Carbon::today();
                            $masaTenggangEnd = $tanggalKembali->copy()->addDays($pengaturan->masa_tenggang);
                            $isDalamMasaTenggang = $today->lte($masaTenggangEnd);
                            $hariKenaDenda = $isDalamMasaTenggang ? 0 : $masaTenggangEnd->diffInDays($today);
                        @endphp
                        <tr class="{{ $isDalamMasaTenggang ? 'table-warning' : 'table-danger' }}">
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $peminjaman->buku->judul }}</strong>
                            </td>
                            <td>{{ $peminjaman->user->name }}</td>
                            <td class="text-center">
                                <span class="fw-semibold">{{ $tanggalKembali->format('d M Y') }}</span>
                                <br>
                                <small class="text-muted">
                                    {{ $tanggalKembali->diffForHumans() }}
                                </small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-danger p-2">{{ $peminjaman->hari_terlambat }} hari</span>
                                <br>
                                <small class="text-muted">
                                    @if($isDalamMasaTenggang)
                                    Masa tenggang: {{ $today->diffInDays($masaTenggangEnd) }} hari lagi
                                    @else
                                    Kena denda: {{ $hariKenaDenda }} hari
                                    @endif
                                </small>
                            </td>
                            <td class="text-center">
                                @if($isDalamMasaTenggang)
                                    <span class="badge bg-warning text-dark p-2">
                                        <i class="bi bi-clock me-1"></i> Masa Tenggang
                                    </span>
                                    <br>
                                    <small class="text-muted">
                                        sampai {{ $masaTenggangEnd->format('d M Y') }}
                                    </small>
                                @else
                                    <span class="badge bg-danger p-2">
                                        <i class="bi bi-cash-coin me-1"></i> Kena Denda
                                    </span>
                                    <br>
                                    <small class="text-muted">
                                        sejak {{ $masaTenggangEnd->format('d M') }}
                                    </small>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($peminjaman->estimasi_denda > 0)
                                    <span class="badge bg-dark px-3 py-2">
                                        Rp {{ number_format($peminjaman->estimasi_denda, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="badge bg-success p-2">
                                        <i class="bi bi-check-circle me-1"></i>Belum Ada
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                                <p class="mt-2">Tidak ada peminjaman yang terlambat</p>
                                <small class="text-success">Selamat! Semua peminjaman tepat waktu.</small>
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