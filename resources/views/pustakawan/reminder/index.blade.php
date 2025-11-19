@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-warning">
            <i class="bi bi-bell me-2"></i> Monitoring Reminder - Pustakawan
        </h4>
        <div class="btn-group">
            <a href="{{ route('pustakawan.jadwal.akan-datang') }}" class="btn btn-info">
                <i class="bi bi-calendar me-1"></i> Lihat Jadwal
            </a>
        </div>
    </div>

    {{-- Info Role --}}
    <div class="alert alert-info">
        <i class="bi bi-person-check me-2"></i>
        <strong>Peran Pustakawan:</strong> Anda dapat memantau reminder yang akan dikirim. 
        Untuk pengiriman reminder, silakan hubungi Administrator.
    </div>

    {{-- Tabel Reminder --}}
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h6 class="mb-0"><i class="bi bi-clock me-2"></i> Daftar Peminjaman Perlu Diingatkan</h6>
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
                            <th class="text-center">Status Reminder</th>
                            <th class="text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reminders as $reminder)
                        @php
                            $hariTersisa = \Carbon\Carbon::parse($reminder->tanggal_kembali)->diffInDays(now());
                        @endphp
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $reminder->buku->judul }}</strong>
                            </td>
                            <td>{{ $reminder->user->name }}</td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($reminder->tanggal_kembali)->format('d M Y') }}
                            </td>
                            <td class="text-center">
                                @if($hariTersisa == 0)
                                    <span class="badge bg-danger">Hari Ini!</span>
                                @elseif($hariTersisa == 1)
                                    <span class="badge bg-warning">Besok</span>
                                @else
                                    <span class="badge bg-info">{{ $hariTersisa }} hari lagi</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">Monitoring</span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary" disabled>
                                    <i class="bi bi-eye"></i> Pantau
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                                <p class="mt-2">Tidak ada reminder untuk saat ini</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Prosedur Escalation --}}
    <div class="card border-info mt-3">
        <div class="card-header bg-info text-dark">
            <h6 class="mb-0"><i class="bi bi-telephone me-2"></i> Prosedur Eskalasi</h6>
        </div>
        <div class="card-body">
            <p class="mb-2">Jika terdapat peminjaman yang perlu segera diingatkan:</p>
            <ol class="mb-0">
                <li>Catat nama peminjam dan buku</li>
                <li>Hubungi Administrator untuk pengiriman reminder</li>
                <li>Lakukan follow up via telepon jika mendesak</li>
                <li>Update status setelah tindakan dilakukan</li>
            </ol>
        </div>
    </div>
</div>
@endsection