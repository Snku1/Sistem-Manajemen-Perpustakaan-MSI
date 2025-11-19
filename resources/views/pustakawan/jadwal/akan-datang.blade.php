@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-warning">
            <i class="bi bi-alarm me-2"></i> Peminjaman Akan Datang - Pustakawan
        </h4>
        <div class="btn-group">
            <a href="{{ route('pustakawan.jadwal.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Kalender
            </a>
            <a href="{{ route('pustakawan.reminder.index') }}" class="btn btn-info">
                <i class="bi bi-bell me-1"></i> Lihat Reminder
            </a>
        </div>
    </div>

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
                            <th class="text-center">Tanggal Pinjam</th>
                            <th class="text-center">Tanggal Kembali</th>
                            <th class="text-center">Sisa Waktu</th>
                            <th class="text-center">Persiapan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamanAkanDatang as $peminjaman)
                        @php
                            $hariTersisa = \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->diffInDays(now());
                            $isBesok = $hariTersisa == 1;
                            $isHariIni = $hariTersisa == 0;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $peminjaman->buku->judul }}</strong>
                            </td>
                            <td>{{ $peminjaman->user->name }}</td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}
                            </td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') }}
                            </td>
                            <td class="text-center">
                                @if($isHariIni)
                                    <span class="badge bg-danger">HARI INI!</span>
                                @elseif($isBesok)
                                    <span class="badge bg-warning text-dark">BESOK</span>
                                @else
                                    <span class="badge bg-info">{{ $hariTersisa }} hari lagi</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($isHariIni)
                                    <small class="text-danger">Siapkan penerimaan</small>
                                @elseif($isBesok)
                                    <small class="text-warning">Persiapan besok</small>
                                @else
                                    <small class="text-muted">Monitor</small>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
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

    {{-- Checklist Persiapan --}}
    <div class="card border-success mt-3">
        <div class="card-header bg-success text-white">
            <h6 class="mb-0"><i class="bi bi-clipboard-check me-2"></i> Checklist Persiapan</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="check1">
                        <label class="form-check-label" for="check1">
                            Siapkan area penerimaan buku
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="check2">
                        <label class="form-check-label" for="check2">
                            Periksa kalkulator denda
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="check3">
                        <label class="form-check-label" for="check3">
                            Siapkan form pengembalian
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="check4">
                        <label class="form-check-label" for="check4">
                            Update daftar stok buku
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection