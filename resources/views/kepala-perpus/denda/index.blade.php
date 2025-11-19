@extends('layouts.app')

@section('title', 'Statistik Denda')

@section('content')

<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
        <h4 class="fw-bold mb-2"><i class="fas fa-coins me-2"></i> Statistik Denda</h4>
    </div>

    <div class="row">

        {{-- Total Denda --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0" style="border-top: 4px solid #dc3545;">
                <div class="card-body d-flex align-items-center">

                    <div class="me-3">
                        <span class="bg-danger text-white rounded-circle d-flex justify-content-center align-items-center"
                            style="width:60px; height:60px; font-size:28px;">
                            <i class="fas fa-money-bill-wave"></i>
                        </span>
                    </div>

                    <div>
                        <h5 class="text-muted mb-1">Total Semua Denda</h5>
                        <h3 class="fw-bold text-dark">
                            Rp {{ number_format($totalDenda, 0, ',', '.') }}
                        </h3>
                    </div>

                </div>
            </div>
        </div>

        {{-- Pustakawan Terbanyak --}}
        <div class="col-md-8">
            <div class="card shadow-sm border-0" style="border-top: 4px solid #0d6efd;">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-user-tie text-primary me-1"></i> Pustakawan Dengan Total Denda Terbanyak
                    </h5>
                </div>
                <div class="card-body">

                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <span class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center"
                                style="width:60px; height:60px; font-size:26px;">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>

                        <div>
                            <p class="mb-1">
                                <strong>Nama:</strong>
                                {{ $pustakawanTerbanyak->name ?? '-' }}
                            </p>

                            <p class="mb-0">
                                <strong>Total Denda:</strong>
                                Rp {{ number_format($pustakawanTerbanyak->denda_sum_total_denda ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- Tombol Export --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-2 mt-4">
        <h5 class="fw-bold mb-2">
            <i class="fas fa-list me-2"></i> Detail Denda per Pustakawan
        </h5>
        <div class="d-flex justify-content-end mb-3 mt-2 gap-2">

            <a href="{{ route('kepala-perpus.laporan.denda.export.pdf') }}"
                class="btn btn-danger shadow-sm">
                <i class="fas fa-file-pdf me-1"></i> Export PDF
            </a>

            <a href="{{ route('kepala-perpus.laporan.denda.export.excel') }}"
                class="btn btn-success shadow-sm">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </a>

        </div>
    </div>

    {{-- =======================   --}}
    {{--TABEL DETAIL PER PUSTAKAWAN--}}
    {{-- =======================   --}}

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Jumlah Denda</th>
                            <th>Total Denda</th>
                            <th>Rata-rata</th>
                            <th>Denda Tertinggi</th>
                            <th>Denda Terendah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($detailDenda as $d)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $d->name }}</td>
                            <td class="text-center">
                                <span class="badge bg-danger">
                                    {{ $d->denda_count }}
                                </span>
                            </td>
                            <td>Rp {{ number_format($d->denda_sum_total_denda, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($d->denda_avg_total_denda ?? 0, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($d->denda_max_total_denda ?? 0, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($d->denda_min_total_denda ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-3 text-muted">
                                <i class="fas fa-info-circle"></i> Tidak ada data denda.
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