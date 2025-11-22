@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-primary">
            <i class="bi bi-calendar-week me-2"></i> Kalender Peminjaman - Pustakawan
        </h4>
        <div class="btn-group">
            <a href="{{ route('pustakawan.jadwal.hari-ini') }}" class="btn btn-info">
                <i class="bi bi-today me-1"></i> Hari Ini
            </a>
            <a href="{{ route('pustakawan.jadwal.akan-datang') }}" class="btn btn-warning">
                <i class="bi bi-alarm me-1"></i> Akan Datang
            </a>
            <a href="{{ route('pustakawan.jadwal.terlambat') }}" class="btn btn-danger">
                <i class="bi bi-exclamation-triangle me-1"></i> Terlambat
            </a>
        </div>
    </div>

    {{-- Info Pengaturan Denda --}}
    @if($pengaturan)
    <div class="alert alert-info mb-4">
        <div class="row">
            <div class="col-md-12">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Aturan Peminjaman:</strong>
                Maksimal {{ $pengaturan->maksimal_hari_peminjaman }} hari |
                Tenggang {{ $pengaturan->masa_tenggang }} hari |
                Denda Rp {{ number_format($pengaturan->denda_per_hari, 0, ',', '.') }}/hari
            </div>
        </div>
    </div>
    @endif

    {{-- Statistik Cepat --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 bg-primary text-white shadow-sm">
                <div class="card-body text-center">
                    <h6>Sedang Dipinjam</h6>
                    <h3>{{ $sedangDipinjam }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-success text-white shadow-sm">
                <div class="card-body text-center">
                    <h6>Akan Kembali</h6>
                    <h3>{{ $akanKembali }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-danger text-white shadow-sm">
                <div class="card-body text-center">
                    <h6>Terlambat</h6>
                    <h3>{{ $terlambat }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Kalender --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>

    {{-- Legend dengan Aturan Denda --}}
    <div class="mt-3">
        <div class="d-flex gap-3 flex-wrap">
            <div class="d-flex align-items-center">
                <div class="legend-color bg-success me-2" style="width: 20px; height: 20px; border-radius: 3px;"></div>
                <small>Aktif (Dalam Waktu)</small>
            </div>
            <div class="d-flex align-items-center">
                <div class="legend-color bg-orange me-2" style="width: 20px; height: 20px; border-radius: 3px; background-color: #fd7e14;"></div>
                <small>Batas Kembali (Hari Ini)</small>
            </div>
            <div class="d-flex align-items-center">
                <div class="legend-color bg-warning me-2" style="width: 20px; height: 20px; border-radius: 3px;"></div>
                <small>Masa Tenggang ({{ $pengaturan->masa_tenggang ?? 3 }} hari)</small>
            </div>
            <div class="d-flex align-items-center">
                <div class="legend-color bg-danger me-2" style="width: 20px; height: 20px; border-radius: 3px;"></div>
                <small>Terlambat (Kena Denda)</small>
            </div>
        </div>
    </div>
</div>

<!-- Include FullCalendar -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>

<style>
    .legend-color {
        border-radius: 3px;
    }
    #calendar {
        min-height: 600px;
    }
    .fc-event {
        cursor: pointer;
        border: none;
        padding: 2px 4px;
        font-weight: 500;
    }
    .fc-event-title {
        font-weight: 600;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: @json($events),
            eventClick: function(info) {
                var event = info.event;
                var props = event.extendedProps;

                var detail = `
                    <strong>Buku:</strong> ${props.buku}<br>
                    <strong>Peminjam:</strong> ${props.peminjam}<br>
                    <strong>Tanggal Pinjam:</strong> ${props.tanggal_pinjam}<br>
                    <strong>Tanggal Kembali:</strong> ${props.tanggal_kembali}<br>
                    <strong>Durasi:</strong> ${props.durasi_peminjaman} hari<br>
                    <strong>Status:</strong> ${props.status_kalender}<br>
                `;

                if (props.hari_terlambat > 0) {
                    detail += `<strong>Keterlambatan:</strong> ${props.hari_terlambat} hari<br>`;
                }

                // Using SweetAlert for better dialog
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Detail Peminjaman',
                        html: detail,
                        icon: props.status_kalender === 'Terlambat' ? 'error' : 
                              props.status_kalender === 'Masa Tenggang' ? 'warning' : 'info',
                        confirmButtonText: 'Tutup'
                    });
                } else {
                    // Fallback to basic alert
                    alert('Detail Peminjaman:\n\n' + detail.replace(/<br>/g, '\n').replace(/<[^>]*>/g, ''));
                }
            },
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: {
                today: 'Hari Ini',
                month: 'Bulan',
                week: 'Minggu',
                day: 'Hari'
            },
            eventDisplay: 'block',
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                meridiem: false
            }
        });
        calendar.render();
    });
</script>
@endsection