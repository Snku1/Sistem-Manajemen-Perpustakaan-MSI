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

    {{-- Info Pengaturan --}}
    @if($pengaturan)
    <div class="alert alert-info mb-4">
        <i class="bi bi-info-circle me-2"></i>
        <strong>Aturan Denda:</strong> Masa tenggang {{ $pengaturan->masa_tenggang }} hari |
        Denda Rp {{ number_format($pengaturan->denda_per_hari, 0, ',', '.') }}/hari setelah masa tenggang
    </div>
    @endif

    {{-- Statistik --}}
    @php
    $today = \Carbon\Carbon::today();
    $pinjamHariIni = $peminjamanHariIni->filter(function($item) use ($today) {
        return \Carbon\Carbon::parse($item->tanggal_pinjam)->eq($today);
    })->count();

    $kembaliHariIni = $peminjamanHariIni->filter(function($item) use ($today) {
        return \Carbon\Carbon::parse($item->tanggal_kembali)->eq($today);
    })->count();
    @endphp

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 bg-primary text-white shadow-sm">
                <div class="card-body text-center">
                    <h6>Pinjam Hari Ini</h6>
                    <h3>{{ $pinjamHariIni }}</h3>
                    <small class="opacity-75">
                        @if($pinjamHariIni > 0)
                            {{ $pinjamHariIni }} peminjaman baru
                        @else
                            Tidak ada peminjaman baru
                        @endif
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 bg-warning text-dark shadow-sm">
                <div class="card-body text-center">
                    <h6>Batas Kembali</h6>
                    <h3>{{ $kembaliHariIni }}</h3>
                    <small class="opacity-75">
                        @if($kembaliHariIni > 0)
                            {{ $kembaliHariIni }} harus dikembalikan
                        @else
                            Tidak ada yang harus dikembalikan
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Peminjaman --}}
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h6 class="mb-0"><i class="bi bi-list me-2"></i> Aktivitas Hari Ini (Total: {{ $peminjamanHariIni->count() }})</h6>
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
                            <th class="text-center">Durasi</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamanHariIni as $peminjaman)
                        @php
                            $tanggalKembali = \Carbon\Carbon::parse($peminjaman->tanggal_kembali);
                            $today = \Carbon\Carbon::today();
                            $isBatasKembali = $tanggalKembali->eq($today);
                            $isPinjamBaru = \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->eq($today);
                            
                            // Tentukan status
                            $statusBadge = '';
                            $statusText = '';
                            
                            if ($peminjaman->estimasi_denda > 0) {
                                $statusBadge = 'bg-danger';
                                $statusText = 'Kena Denda';
                            } elseif ($peminjaman->status_detail['status'] === 'masa_tenggang') {
                                $statusBadge = 'bg-warning text-dark';
                                $statusText = 'Masa Tenggang';
                            } elseif ($isBatasKembali) {
                                $statusBadge = 'bg-warning text-dark';
                                $statusText = 'Batas Kembali';
                            } else {
                                $statusBadge = 'bg-success';
                                $statusText = 'Aman';
                            }
                        @endphp
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $peminjaman->buku->judul }}</strong>
                            </td>
                            <td>{{ $peminjaman->user->name }}</td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}
                                @if($isPinjamBaru)
                                    <br><span class="badge bg-primary">Baru</span>
                                @endif
                            </td>
                            <td class="text-center">
                                {{ $tanggalKembali->format('d M Y') }}
                                @if($isBatasKembali)
                                    <br><span class="badge bg-warning text-dark">Batas Hari Ini</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @php
                                    $durasi = \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->diffInDays($tanggalKembali);
                                @endphp
                                <span class="badge bg-info">{{ $durasi }} hari</span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $statusBadge }}">
                                    {{ $statusText }}
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
</div>
@endsection