@extends('layouts.app')

@section('title', 'Statistik Perpustakaan')

@section('content')
<style>
    .stats-row {
        display: flex;
        flex-wrap: wrap;
        margin: -10px;
    }
    
    .stat-card-wrapper {
        flex: 1;
        min-width: 250px;
        padding: 10px;
    }

    .stat-card {
        border-radius: 18px;
        padding: 25px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        transition: transform .2s ease, box-shadow .2s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 20px rgba(0, 0, 0, 0.25);
    }

    .stat-icon {
        position: absolute;
        right: 20px;
        top: 20px;
        font-size: 55px;
        opacity: .25;
    }

    .stat-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .stat-value {
        font-size: 40px;
        font-weight: bold;
        margin-bottom: 5px;
        line-height: 1.2;
    }

    .stat-subtitle {
        font-size: 18px;
        opacity: .95;
        margin-bottom: 10px;
        line-height: 1.3;
    }
    
    /* Styling untuk grafik yang diperbarui */
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    
    .chart-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .chart-card:hover {
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    }

    .chart-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 20px;
        margin-bottom: 2rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .stat-card-wrapper {
            min-width: 100%;
        }
        
        .stat-value {
            font-size: 35px;
        }
        
        .stat-subtitle {
            font-size: 16px;
        }
    }
</style>

<!-- Stats Cards Section dengan tinggi sama rata -->
<div class="stats-row mb-4">
    <!-- Total Peminjaman -->
    <div class="stat-card-wrapper">
        <div class="stat-card" style="background: linear-gradient(135deg, #007bff, #0056d2);">
            <div class="stat-content">
                <div class="stat-subtitle">Total Peminjaman</div>
                <div class="stat-value">{{ $totalPeminjaman }}</div>
            </div>
            <i class="fas fa-book-reader stat-icon"></i>
        </div>
    </div>

    <!-- Total Pengembalian -->
    <div class="stat-card-wrapper">
        <div class="stat-card" style="background: linear-gradient(135deg, #28a745, #1f7f33);">
            <div class="stat-content">
                <div class="stat-subtitle">Total Pengembalian</div>
                <div class="stat-value">{{ $totalPengembalian }}</div>
            </div>
            <i class="fas fa-undo stat-icon"></i>
        </div>
    </div>

    <!-- Buku Paling Banyak Dipinjam -->
    <div class="stat-card-wrapper">
        <div class="stat-card" style="background: linear-gradient(135deg, #ffc107, #d39e00); color: #4a3e00;">
            <div class="stat-content">
                <div class="stat-subtitle">Buku Paling Banyak Dipinjam</div>
                <div class="stat-value" style="font-size: 22px; line-height: 1.3;">
                    {{ $bukuTerbanyak->judul ?? '-' }}
                </div>
                @if($bukuTerbanyak)
                <div style="margin-top: 8px; font-size: 14px; opacity: 0.8;">
                    Dipinjam {{ $bukuTerbanyak->peminjaman_count }} kali
                </div>
                @endif
            </div>
            <i class="fas fa-chart-line stat-icon"></i>
        </div>
    </div>

    <!-- Buku Paling Sedikit Dipinjam -->
    <div class="stat-card-wrapper">
        <div class="stat-card" style="background: linear-gradient(135deg, #dc3545, #b21f2d);">
            <div class="stat-content">
                <div class="stat-subtitle">Buku Paling Sedikit Dipinjam</div>
                <div class="stat-value" style="font-size: 22px; line-height: 1.3;">
                    {{ $bukuTersedikit->judul ?? '-' }}
                </div>
                @if($bukuTersedikit)
                <div style="margin-top: 8px; font-size: 14px; opacity: 0.8;">
                    Dipinjam {{ $bukuTersedikit->peminjaman_count }} kali
                </div>
                @endif
            </div>
            <i class="fas fa-chart-pie stat-icon"></i>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="chart-grid">
    {{-- Grafik Peminjaman per Bulan --}}
    <div class="card chart-card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <strong>Grafik Peminjaman Tahun {{ date('Y') }}</strong>
            <div class="badge bg-light text-primary">{{ $totalPeminjamanTahunIni }} Peminjaman</div>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="chartPeminjaman"></canvas>
            </div>
        </div>
    </div>

    {{-- Grafik Pengembalian per Bulan --}}
    <div class="card chart-card">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <strong>Grafik Pengembalian Tahun {{ date('Y') }}</strong>
            <div class="badge bg-light text-success">{{ $totalPengembalianTahunIni }} Pengembalian</div>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="chartPengembalian"></canvas>
            </div>
        </div>
    </div>

    {{-- Grafik Perbandingan Peminjaman vs Pengembalian --}}
    <div class="card chart-card">
        <div class="card-header bg-info text-white">
            <strong>Perbandingan Peminjaman vs Pengembalian</strong>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="chartPerbandingan"></canvas>
            </div>
        </div>
    </div>
    
    {{-- Grafik Perbandingan Buku Terpopuler --}}
    <div class="card chart-card">
        <div class="card-header bg-warning text-white">
            <strong>Top 5 Buku Terpopuler</strong>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="chartBukuPopuler"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    {{-- Tabel 10 Buku Paling Banyak Dipinjam --}}
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Top 10 Buku Paling Banyak Dipinjam</strong>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Jumlah Dipinjam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($top10Banyak as $buku)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $buku->judul }}</td>
                            <td class="text-center">
                                <span class="badge bg-success">{{ $buku->peminjaman_count }}</span>
                            </td>
                        </tr>
                        @endforeach
                        @if ($top10Banyak->isEmpty())
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada data.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Tabel 10 Buku Paling Sedikit Dipinjam --}}
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-danger text-white">
                <strong>Top 10 Buku Paling Sedikit Dipinjam</strong>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead class="table-danger text-center">
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Jumlah Dipinjam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($top10Sedikit as $buku)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $buku->judul }}</td>
                            <td class="text-center">
                                <span class="badge bg-success">{{ $buku->peminjaman_count }}</span>
                            </td>
                        </tr>
                        @endforeach
                        @if ($top10Sedikit->isEmpty())
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada data.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Data untuk grafik
    const bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const peminjamanData = @json($chartPeminjaman->values());
    const pengembalianData = @json($chartPengembalian->values());
    
    // Data untuk grafik buku populer
    const bukuPopulerLabels = @json($top5Banyak->pluck('judul')->toArray());
    const bukuPopulerData = @json($top5Banyak->pluck('peminjaman_count')->toArray());

    // Grafik Peminjaman per Bulan
    const ctxPeminjaman = document.getElementById('chartPeminjaman');
    new Chart(ctxPeminjaman, {
        type: 'line',
        data: {
            labels: bulanLabels,
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: peminjamanData,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#007bff',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Grafik Pengembalian per Bulan
    const ctxPengembalian = document.getElementById('chartPengembalian');
    new Chart(ctxPengembalian, {
        type: 'bar',
        data: {
            labels: bulanLabels,
            datasets: [{
                label: 'Jumlah Pengembalian',
                data: pengembalianData,
                backgroundColor: 'rgba(40, 167, 69, 0.7)',
                borderColor: 'rgb(40, 167, 69)',
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Grafik Perbandingan Peminjaman vs Pengembalian
    const ctxPerbandingan = document.getElementById('chartPerbandingan');
    new Chart(ctxPerbandingan, {
        type: 'line',
        data: {
            labels: bulanLabels,
            datasets: [
                {
                    label: 'Peminjaman',
                    data: peminjamanData,
                    borderColor: '#007bff',
                    backgroundColor: 'transparent',
                    borderWidth: 3,
                    tension: 0.4,
                    pointBackgroundColor: '#007bff'
                },
                {
                    label: 'Pengembalian',
                    data: pengembalianData,
                    borderColor: '#28a745',
                    backgroundColor: 'transparent',
                    borderWidth: 3,
                    tension: 0.4,
                    pointBackgroundColor: '#28a745'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Grafik Buku Populer
    const ctxBukuPopuler = document.getElementById('chartBukuPopuler');
    new Chart(ctxBukuPopuler, {
        type: 'doughnut',
        data: {
            labels: bukuPopulerLabels,
            datasets: [{
                label: 'Jumlah Dipinjam',
                data: bukuPopulerData,
                backgroundColor: [
                    '#007bff',
                    '#28a745', 
                    '#ffc107',
                    '#dc3545',
                    '#6f42c1'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>

@endsection