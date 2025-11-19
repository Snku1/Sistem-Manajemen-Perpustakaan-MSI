@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-warning">
            <i class="bi bi-alarm me-2"></i> Peminjaman Akan Datang
        </h4>
        <div class="btn-group">
            <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Kalender
            </a>
            <a href="{{ route('reminder.index') }}" class="btn btn-primary">
                <i class="bi bi-bell me-1"></i> Reminder
            </a>
        </div>
    </div>

    {{-- Info Pengaturan --}}
    @if($pengaturan)
    <div class="alert alert-warning mb-4">
        <i class="bi bi-info-circle me-2"></i>
        <strong>Pengingat:</strong> Peminjaman akan masuk masa tenggang {{ $pengaturan->masa_tenggang }} hari setelah tanggal kembali. 
        Denda mulai berlaku setelah masa tenggang.
    </div>
    @endif

    {{-- Tabel Peminjaman Akan Datang --}}
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h6 class="mb-0"><i class="bi bi-clock me-2"></i> Daftar Peminjaman Akan Kembali</h6>
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
                            <th class="text-center">Sisa Waktu</th>
                            <th class="text-center">Masa Tenggang Mulai</th>
                            <th class="text-center">Estimasi Denda</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamanAkanDatang as $peminjaman)
                        @php
                            $tanggalKembali = \Carbon\Carbon::parse($peminjaman->tanggal_kembali);
                            $masaTenggangStart = $tanggalKembali->copy()->addDay();
                            $masaTenggangEnd = $tanggalKembali->copy()->addDays($pengaturan->masa_tenggang + 1);
                        @endphp
                        <tr>
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
                                @if($peminjaman->hari_tersisa == 0)
                                    <span class="badge bg-danger">HARI INI!</span>
                                @elseif($peminjaman->hari_tersisa == 1)
                                    <span class="badge bg-warning">BESOK</span>
                                @elseif($peminjaman->hari_tersisa <= 3)
                                    <span class="badge bg-info">{{ $peminjaman->hari_tersisa }} hari lagi</span>
                                @else
                                    <span class="badge bg-secondary">{{ $peminjaman->hari_tersisa }} hari lagi</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <small>{{ $masaTenggangStart->format('d M') }} - {{ $masaTenggangEnd->format('d M') }}</small>
                                <br>
                                <span class="badge bg-warning text-dark">+{{ $pengaturan->masa_tenggang }} hari</span>
                            </td>
                            <td class="text-center">
                                @if($peminjaman->estimasi_denda > 0)
                                    <span class="badge bg-danger">
                                        Rp {{ number_format($peminjaman->estimasi_denda, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="badge bg-success">Tidak Ada</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('reminder.index') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-bell"></i> Remind
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                                <p class="mt-2">Tidak ada peminjaman yang akan datang</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Timeline Masa Tenggang --}}
    <div class="card border-warning mt-3">
        <div class="card-header bg-warning text-dark">
            <h6 class="mb-0"><i class="bi bi-calendar-range me-2"></i> Timeline Masa Tenggang</h6>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="border rounded p-2 bg-light">
                        <h6 class="text-success">H-3</h6>
                        <small>Persiapan</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-2 bg-light">
                        <h6 class="text-warning">H-1</h6>
                        <small>Reminder</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-2 bg-warning">
                        <h6 class="text-dark">H+0</h6>
                        <small>Batas Kembali</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-2 bg-danger text-white">
                        <h6>H+{{ $pengaturan->masa_tenggang + 1 }}</h6>
                        <small>Mulai Denda</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection