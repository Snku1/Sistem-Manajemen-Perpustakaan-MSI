@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-danger">
            <i class="bi bi-exclamation-triangle me-2"></i> Peminjaman Terlambat
        </h4>
        <div class="btn-group">
            <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Kalender
            </a>
            <a href="{{ route('reminder.terlambat') }}" class="btn btn-danger">
                <i class="bi bi-bell me-1"></i> Reminder Terlambat
            </a>
        </div>
    </div>

    {{-- Info Pengaturan Denda --}}
    @if($pengaturan)
    <div class="alert alert-danger mb-4">
        <div class="row">
            <div class="col-md-8">
                <i class="bi bi-exclamation-octagon me-2"></i>
                <strong>Aturan Denda Aktif:</strong>
                Masa tenggang {{ $pengaturan->masa_tenggang }} hari |
                Denda Rp {{ number_format($pengaturan->denda_per_hari, 0, ',', '.') }}/hari
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('denda.pengaturan') }}" class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil"></i> Ubah Pengaturan
                </a>
            </div>
        </div>
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
    $totalEstimasiDenda = $terlambat->sum('estimasi_denda');
    @endphp

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 bg-danger text-white shadow-sm">
                <div class="card-body text-center">
                    <h6>Total Terlambat</h6>
                    <h3>{{ $totalTerlambat }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 bg-warning text-dark shadow-sm">
                <div class="card-body text-center">
                    <h6>Masa Tenggang</h6>
                    <h3>{{ $dalamMasaTenggang }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 bg-dark text-white shadow-sm">
                <div class="card-body text-center">
                    <h6>Kena Denda</h6>
                    <h3>{{ $sudahKenaDenda }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 bg-secondary text-white shadow-sm">
                <div class="card-body text-center">
                    <h6>Total Denda</h6>
                    <h3>Rp {{ number_format($totalEstimasiDenda, 0, ',', '.') }}</h3>
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
                            <th class="text-center">Peminjam</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Batas Kembali</th>
                            <th class="text-center">Keterlambatan</th>
                            <th class="text-center">Status Denda</th>
                            <th class="text-center">Estimasi Denda</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($terlambat as $peminjaman)
                        @php
                        $tanggalKembali = \Carbon\Carbon::parse($peminjaman->tanggal_kembali);
                        $today = \Carbon\Carbon::today();
                        $hariTerlambat = $tanggalKembali->diffInDays($today);
                        $masaTenggangEnd = $tanggalKembali->copy()->addDays($pengaturan->masa_tenggang);
                        $isDalamMasaTenggang = $today->lte($masaTenggangEnd);
                        $hariKenaDenda = $isDalamMasaTenggang ? 0 : $masaTenggangEnd->diffInDays($today);
                        @endphp
                        <tr class="{{ $isDalamMasaTenggang ? 'table-warning' : 'table-danger' }}">
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <strong>{{ $peminjaman->buku->judul }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">{{ $peminjaman->user->name }}</td>
                            <td class="text-center">
                                <div>
                                    {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}
                                </div>
                            </td>
                            <td class="text-center">
                                <div>
                                    {{ $tanggalKembali->format('d M Y') }}
                                    <br>
                                    <small class="text-muted">
                                        {{ $tanggalKembali->diffForHumans() }}
                                    </small>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-danger">{{ $hariTerlambat }} hari</span>
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
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-clock me-1"></i> Masa Tenggang
                                </span>
                                <br>
                                <small class="text-muted">
                                    sampai {{ $masaTenggangEnd->format('d M') }}
                                </small>
                                @else
                                <span class="badge bg-danger">
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
                                    <i></i>
                                    Rp {{ number_format($peminjaman->estimasi_denda, 0, ',', '.') }}
                                </span>
                                @else
                                <span class="badge bg-success">Belum Ada</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('reminder.terlambat') }}" class="btn btn-sm btn-warning" title="Kirim Reminder">
                                        <i class="bi bi-bell"></i>
                                    </a>
                                    <a href="{{ route('denda.index') }}" class="btn btn-sm btn-danger" title="Kelola Denda">
                                        <i class="bi bi-cash-coin"></i>
                                    </a>
                                </div>
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

    {{-- Prosedur Penanganan Terlambat --}}
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="bi bi-clock me-2"></i> Penanganan Masa Tenggang</h6>
                </div>
                <div class="card-body">
                    <ol class="small mb-0">
                        <li>Kirim reminder kepada peminjam</li>
                        <li>Konfirmasi via telepon/email</li>
                        <li>Berikan informasi masa tenggang</li>
                        <li>Monitor hingga batas masa tenggang</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0"><i class="bi bi-cash-coin me-2"></i> Penanganan Kena Denda</h6>
                </div>
                <div class="card-body">
                    <ol class="small mb-0">
                        <li>Hitung denda sesuai aturan</li>
                        <li>Input data denda di sistem</li>
                        <li>Kirim notifikasi denda</li>
                        <li>Follow up pembayaran</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Timeline Keterlambatan --}}
    <div class="card border-info mt-4">
        <div class="card-header bg-info text-white">
            <h6 class="mb-0"><i class="bi bi-calendar-range me-2"></i> Timeline Keterlambatan</h6>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-2">
                    <div class="border rounded p-2 bg-success text-white">
                        <h6>H-0</h6>
                        <small>Batas Kembali</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="border rounded p-2 bg-warning">
                        <h6>H+1</h6>
                        <small>Mulai Terlambat</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="border rounded p-2 bg-warning">
                        <h6>H+{{ $pengaturan->masa_tenggang }}</h6>
                        <small>Akhir Tenggang</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="border rounded p-2 bg-danger text-white">
                        <h6>H+{{ $pengaturan->masa_tenggang + 1 }}</h6>
                        <small>Mulai Denda</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="border rounded p-2 bg-dark text-white">
                        <h6>H+{{ $pengaturan->masa_tenggang + 3 }}</h6>
                        <small>Denda 3 Hari</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="border rounded p-2 bg-secondary text-white">
                        <h6>H+{{ $pengaturan->masa_tenggang + 7 }}</h6>
                        <small>Denda 7 Hari</small>
                    </div>
                </div>
            </div>
            <div class="row mt-3 text-center">
                <div class="col-12">
                    <small class="text-muted">
                        <strong>Keterangan:</strong> H = Tanggal Batas Kembali | Denda = Rp {{ number_format($pengaturan->denda_per_hari, 0, ',', '.') }}/hari
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection