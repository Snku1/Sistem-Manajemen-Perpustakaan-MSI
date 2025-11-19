@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-primary">
            <i class="bi bi-calendar-week me-2"></i> Kalender Peminjaman
        </h4>
        <div class="btn-group">
            <a href="{{ route('pustakawan.jadwal.hari-ini') }}" class="btn btn-info">
                <i class="bi bi-today me-1"></i> Hari Ini
            </a>
            <a href="{{ route('pustakawan.jadwal.akan-datang') }}" class="btn btn-warning">
                <i class="bi bi-alarm me-1"></i> Akan Datang
            </a>
        </div>
    </div>

    {{-- Statistik Cepat --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 bg-light shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted">Sedang Dipinjam</h6>
                    <h3 class="text-primary">{{ $sedangDipinjam }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-light shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted">Akan Kembali</h6>
                    <h3 class="text-warning">{{ $akanKembali }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-light shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted">Terlambat</h6>
                    <h3 class="text-danger">{{ $terlambat }}</h3>
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

    {{-- Legend --}}
    <div class="mt-3">
        <div class="d-flex gap-3 flex-wrap">
            <div class="d-flex align-items-center">
                <div class="legend-color bg-success me-2" style="width: 20px; height: 20px; border-radius: 3px;"></div>
                <small>Sedang Berjalan</small>
            </div>
            <div class="d-flex align-items-center">
                <div class="legend-color bg-warning me-2" style="width: 20px; height: 20px; border-radius: 3px;"></div>
                <small>Batas Kembali Hari Ini</small>
            </div>
            <div class="d-flex align-items-center">
                <div class="legend-color bg-danger me-2" style="width: 20px; height: 20px; border-radius: 3px;"></div>
                <small>Terlambat</small>
            </div>
        </div>
    </div>

    {{-- Info untuk Pustakawan --}}
    <div class="alert alert-info mt-3">
        <i class="bi bi-info-circle me-2"></i>
        <strong>Informasi Pustakawan:</strong> Kalender ini menampilkan timeline peminjaman aktif. 
        Fokus pada peminjaman yang akan datang untuk persiapan pengembalian.
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
            var detail = `
                <strong>Buku:</strong> ${event.extendedProps.buku}<br>
                <strong>Peminjam:</strong> ${event.extendedProps.peminjam}<br>
                <strong>Tanggal Pinjam:</strong> ${event.extendedProps.tanggal_pinjam}<br>
                <strong>Tanggal Kembali:</strong> ${event.extendedProps.tanggal_kembali}<br>
                <strong>Status:</strong> ${event.extendedProps.status}<br>
                <strong>Terlambat:</strong> ${event.extendedProps.terlambat ? 'Ya' : 'Tidak'}
            `;
            
            alert('Detail Peminjaman:\n\n' + detail);
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
        }
    });
    calendar.render();
});
</script>
@endsection