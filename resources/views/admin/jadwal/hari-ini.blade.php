@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-info">
            <i class="bi bi-today me-2"></i> Peminjaman Hari Ini
        </h4>
        <div class="btn-group">
            <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Kalender
            </a>
            <a href="{{ route('reminder.index') }}" class="btn btn-warning">
                <i class="bi bi-bell me-1"></i> Kirim Reminder
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

    $perluReminder = $peminjamanHariIni->filter(function($item) use ($today) {
    $tanggalKembali = \Carbon\Carbon::parse($item->tanggal_kembali);
    return $tanggalKembali->eq($today) ||
    ($tanggalKembali->lt($today) && $item->status_detail['status'] === 'masa_tenggang');
    })->count();
    @endphp

    <div class="row mb-4">
        <div class="col-md-4">
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
        <div class="col-md-4">
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
        <div class="col-md-4">
            <div class="card border-0 bg-info text-white shadow-sm">
                <div class="card-body text-center">
                    <h6>Perlu Reminder</h6>
                    <h3>{{ $perluReminder }}</h3>
                    <small class="opacity-75">
                        @if($perluReminder > 0)
                        {{ $perluReminder }} perlu diingatkan
                        @else
                        Semua terkendali
                        @endif
                    </small>
                </div>
            </div>
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
                            <th class="text-center">Durasi</th>
                            <th class="text-center">Status Denda</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamanHariIni as $peminjaman)
                        @php
                        $tanggalKembali = \Carbon\Carbon::parse($peminjaman->tanggal_kembali);
                        $today = \Carbon\Carbon::today();
                        $isBatasKembali = $tanggalKembali->eq($today);
                        $isPinjamBaru = \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->eq($today);
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
                                @if($peminjaman->estimasi_denda > 0)
                                <span class="badge bg-danger">
                                    Rp {{ number_format($peminjaman->estimasi_denda, 0, ',', '.') }}
                                </span>
                                @elseif($isBatasKembali)
                                <span class="badge bg-warning text-dark">Batas Kembali</span>
                                @else
                                <span class="badge bg-success">Aman</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($isBatasKembali)
                                <a href="{{ route('reminder.index') }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-bell"></i> Remind
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
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
            <h6 class="mb-0"><i class="bi bi-lightbulb me-2"></i> Panduan Tindakan Hari Ini</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="bi bi-check-circle text-success me-2"></i>Untuk Batas Kembali Hari Ini:</h6>
                    <ul class="small">
                        <li>Kirim reminder kepada peminjam</li>
                        <li>Persiapkan penerimaan buku besok</li>
                        <li>Monitor pengembalian tepat waktu</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6><i class="bi bi-arrow-return-left text-warning me-2"></i>Untuk Peminjaman Baru:</h6>
                    <ul class="small">
                        <li>Verifikasi data peminjam</li>
                        <li>Pastikan stok buku terupdate</li>
                        <li>Catat tanggal kembali dengan benar</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection