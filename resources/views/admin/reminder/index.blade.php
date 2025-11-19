@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-warning">
            <i class="bi bi-bell me-2"></i> Reminder & Notifikasi
        </h4>
        <div class="btn-group">
            <a href="{{ route('reminder.terlambat') }}" class="btn btn-danger">
                <i class="bi bi-exclamation-triangle me-1"></i> Yang Terlambat
            </a>
        </div>
    </div>

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-4" id="reminderTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="akan-datang-tab" data-bs-toggle="tab" data-bs-target="#akan-datang" type="button">
                <i class="bi bi-clock me-1"></i> Akan Kembali ({{ $reminders->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="notifikasi-tab" data-bs-toggle="tab" data-bs-target="#notifikasi" type="button">
                <i class="bi bi-bell me-1"></i> Notifikasi Terkirim
            </button>
        </li>
    </ul>

    <div class="tab-content" id="reminderTabsContent">
        {{-- Tab Akan Kembali --}}
        <div class="tab-pane fade show active" id="akan-datang">
            <div class="card shadow-sm">
                <div class="card-body">
                    @forelse($reminders as $reminder)
                    <div class="reminder-item border-bottom pb-3 mb-3">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h6 class="mb-1">{{ $reminder->buku->judul }}</h6>
                                <small class="text-muted">
                                    Peminjam: {{ $reminder->user->name }} | 
                                    Kembali: {{ \Carbon\Carbon::parse($reminder->tanggal_kembali)->format('d M Y') }}
                                </small>
                            </div>
                            <div class="col-md-4">
                                @php
                                    $hariTersisa = \Carbon\Carbon::parse($reminder->tanggal_kembali)->diffInDays(now());
                                @endphp
                                @if($hariTersisa == 0)
                                    <span class="badge bg-danger">Hari Ini!</span>
                                @elseif($hariTersisa == 1)
                                    <span class="badge bg-warning">Besok</span>
                                @else
                                    <span class="badge bg-info">{{ $hariTersisa }} hari lagi</span>
                                @endif
                            </div>
                            <div class="col-md-2 text-end">
                                <button class="btn btn-sm btn-primary" 
                                        onclick="kirimReminder({{ $reminder->id }})">
                                    <i class="bi bi-send"></i> Remind
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                        <p class="mt-2">Tidak ada reminder untuk saat ini</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Tab Notifikasi --}}
        <div class="tab-pane fade" id="notifikasi">
            <div class="card shadow-sm">
                <div class="card-body">
                    {{-- List notifikasi terkirim --}}
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-bell" style="font-size: 2rem;"></i>
                        <p class="mt-2">Riwayat notifikasi akan muncul di sini</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function kirimReminder(peminjamanId) {
    if (confirm('Kirim reminder kepada peminjam?')) {
        fetch(`/admin/reminder/${peminjamanId}/kirim`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            alert('Reminder berhasil dikirim!');
            location.reload();
        });
    }
}
</script>
@endsection