@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-warning">
            <i class="bi bi-bell me-2"></i> Reminder & Notifikasi
        </h4>
        <div class="btn-group">
            <button class="btn btn-success" onclick="kirimReminderMassal('semua')">
                <i class="bi bi-send-check me-1"></i> Kirim Semua
            </button>
            <button class="btn btn-warning" onclick="kirimReminderMassal('pengingat_pengembalian')">
                <i class="bi bi-clock me-1"></i> Kirim Pengingat
            </button>
            <button class="btn btn-danger" onclick="kirimReminderMassal('peringatan_keterlambatan')">
                <i class="bi bi-exclamation-triangle me-1"></i> Kirim Peringatan
            </button>
        </div>
    </div>

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-4" id="reminderTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="semua-tab" data-bs-toggle="tab" data-bs-target="#semua" type="button">
                <i class="bi bi-list me-1"></i> Semua Reminder ({{ $semuaReminders->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pengingat-tab" data-bs-toggle="tab" data-bs-target="#pengingat" type="button">
                <i class="bi bi-clock me-1"></i> Pengingat Pengembalian ({{ $pengingatPengembalian->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="peringatan-tab" data-bs-toggle="tab" data-bs-target="#peringatan" type="button">
                <i class="bi bi-exclamation-triangle me-1"></i> Peringatan Keterlambatan ({{ $peringatanKeterlambatan->count() }})
            </button>
        </li>
    </ul>

    <div class="tab-content" id="reminderTabsContent">
        {{-- Tab Semua --}}
        <div class="tab-pane fade show active" id="semua">
            <div class="card shadow-sm">
                <div class="card-body">
                    @forelse($semuaReminders as $reminder)
                    @php
                    $tanggalKembali = \Carbon\Carbon::parse($reminder->tanggal_kembali);
                    $today = \Carbon\Carbon::today();
                    $isTerlambat = $tanggalKembali->lt($today);
                    $hariDisplay = $isTerlambat ? $tanggalKembali->diffInDays($today) : $today->diffInDays($tanggalKembali);

                    // Tentukan status untuk badge berdasarkan status_detail dari controller
                    $badgeClass = '';
                    $badgeText = '';

                    if ($reminder->status_detail['status'] === 'kena_denda') {
                    $badgeClass = 'bg-danger';
                    $badgeText = 'Terlambat';
                    } elseif ($reminder->status_detail['status'] === 'masa_tenggang') {
                    $badgeClass = 'bg-warning';
                    $badgeText = 'Masa Tenggang';
                    } else {
                    $badgeClass = 'bg-info';
                    $badgeText = 'Akan Kembali';
                    }
                    @endphp
                    <div class="reminder-item border-bottom pb-3 mb-3">
                        <div class="row align-items-center">
                            <div class="col-md-5">
                                <h6 class="mb-1">
                                    {{ $reminder->buku->judul }}
                                    <span class="badge {{ $badgeClass }} ms-2">{{ $badgeText }}</span>
                                </h6>
                                <small class="text-muted">
                                    <i class="bi bi-person me-1"></i>{{ $reminder->user->name }} |
                                    <i class="bi bi-envelope me-1"></i>{{ $reminder->user->email }}
                                </small>
                                <br>
                                <small class="{{ $isTerlambat ? 'text-danger' : 'text-info' }}">
                                    <i class="bi bi-calendar me-1"></i>
                                    Batas: {{ $tanggalKembali->format('d M Y') }}
                                    @if($isTerlambat)
                                    (Terlambat {{ $hariDisplay }} hari)
                                    @else
                                    (Sisa {{ $hariDisplay }} hari)
                                    @endif
                                </small>
                            </div>
                            <div class="col-md-4">
                                @if($reminder->status_detail['status'] === 'kena_denda')
                                <span class="badge bg-danger p-2">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    {{ $hariDisplay }} Hari Terlambat
                                </span>
                                @elseif($reminder->status_detail['status'] === 'masa_tenggang')
                                <span class="badge bg-warning p-2">
                                    <i class="bi bi-clock-history me-1"></i>
                                    {{ $hariDisplay }} Hari Terlambat
                                </span>
                                @else
                                @if($hariDisplay == 0)
                                <span class="badge bg-danger p-2">Hari Ini!</span>
                                @elseif($hariDisplay == 1)
                                <span class="badge bg-warning p-2">Besok</span>
                                @else
                                <span class="badge bg-info p-2">{{ $hariDisplay }} hari lagi</span>
                                @endif
                                @endif
                            </div>
                            <div class="col-md-3 text-end">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-warning"
                                        onclick="kirimEmailSpesifik({{ $reminder->id }}, 'pengingat_pengembalian')"
                                        title="Kirim Pengingat Pengembalian">
                                        <i class="bi bi-clock"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger"
                                        onclick="kirimEmailSpesifik({{ $reminder->id }}, 'peringatan_keterlambatan')"
                                        title="Kirim Peringatan Keterlambatan">
                                        <i class="bi bi-exclamation-triangle"></i>
                                    </button>
                                </div>
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

        {{-- Tab Pengingat Pengembalian --}}
        <div class="tab-pane fade" id="pengingat">
            <div class="card shadow-sm">
                <div class="card-body">
                    @forelse($pengingatPengembalian as $reminder)
                    @php
                    $tanggalKembali = \Carbon\Carbon::parse($reminder->tanggal_kembali);
                    $today = \Carbon\Carbon::today();
                    $isTerlambat = $tanggalKembali->lt($today);
                    $hariDisplay = $isTerlambat ? $today->diffInDays($tanggalKembali) : $today->diffInDays($tanggalKembali);
                    @endphp
                    <div class="reminder-item border-bottom pb-3 mb-3">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h6 class="mb-1">{{ $reminder->buku->judul }}</h6>
                                <small class="text-muted">
                                    <i class="bi bi-person me-1"></i>{{ $reminder->user->name }} |
                                    <i class="bi bi-envelope me-1"></i>{{ $reminder->user->email }}
                                </small>
                                <br>
                                <small class="{{ $isTerlambat ? : 'text-info' }}">
                                    <i class="bi bi-calendar me-1"></i>
                                    Batas: {{ $tanggalKembali->format('d M Y') }}
                                    @if($isTerlambat)
                                    (Terlambat {{ $hariDisplay }} hari)
                                    @else
                                    (Sisa {{ $hariDisplay }} hari)
                                    @endif
                                </small>
                            </div>
                            <div class="col-md-3">
                                @if($hariDisplay == 0)
                                <span class="badge bg-danger p-2">Hari Ini!</span>
                                @elseif($hariDisplay == 1)
                                <span class="badge bg-warning p-2">Besok</span>
                                @else
                                <span class="badge bg-info p-2">{{ $hariDisplay }} hari lagi</span>
                                @endif
                            </div>
                            <div class="col-md-3 text-end">
                                <button class="btn btn-sm btn-warning"
                                    onclick="kirimEmailSpesifik({{ $reminder->id }}, 'pengingat_pengembalian')">
                                    <i class="bi bi-envelope me-1"></i> Kirim Pengingat
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                        <p class="mt-2">Tidak ada peminjaman yang perlu diingatkan</p>
                        <small class="text-success">Semua peminjaman masih dalam waktu yang cukup!</small>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Tab Peringatan Keterlambatan --}}
        <div class="tab-pane fade" id="peringatan">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    {{-- Sub-kategori: Dalam Masa Tenggang --}}
                    @if($dalamMasaTenggang->count() > 0)
                    <div class="mb-0">
                        <div class="d-flex align-items-center mb-3 p-3 bg-warning bg-opacity-10 border-bottom">
                            <i class="bi bi-clock-history text-warning fs-4 me-3"></i>
                            <div>
                                <h6 class="text-warning mb-1">Dalam Masa Tenggang</h6>
                                <small class="text-muted">
                                    {{ $dalamMasaTenggang->count() }} peminjaman masih dalam masa tenggang
                                    @if($pengaturan)
                                    (Masa tenggang: {{ $pengaturan->masa_tenggang }} hari)
                                    @endif
                                </small>
                            </div>
                        </div>

                        @foreach($dalamMasaTenggang as $peminjaman)
                        <div class="reminder-item border-bottom pb-3 mb-0 p-3">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h6 class="mb-1">
                                        {{ $peminjaman->buku->judul }}
                                    </h6>
                                    <small class="text-muted">
                                        <i class="bi bi-person me-1"></i>{{ $peminjaman->user->name }} |
                                        <i class="bi bi-envelope me-1"></i>{{ $peminjaman->user->email }}
                                    </small>
                                    <br>
                                    <small class="text-warning">
                                        <i class="bi bi-calendar-x me-1"></i>
                                        Terlambat sejak {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') }}
                                    </small>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <span class="badge bg-warning text-dark p-2 mb-1 d-block">
                                            {{ $peminjaman->status_detail['hari'] }} hari terlambat
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3 text-end">
                                    <button class="btn btn-sm btn-warning"
                                        onclick="kirimEmailSpesifik({{ $peminjaman->id }}, 'peringatan_keterlambatan')">
                                        <i class="bi bi-envelope me-1"></i> Kirim Peringatan
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    {{-- Sub-kategori: Sudah Kena Denda --}}
                    @if($sudahKenaDenda->count() > 0)
                    <div class="mb-0">
                        <div class="d-flex align-items-center mb-3 p-3 bg-danger bg-opacity-10 border-bottom">
                            <i class="bi bi-cash-coin text-danger fs-4 me-3"></i>
                            <div>
                                <h6 class="text-danger mb-1">Sudah Kena Denda</h6>
                                <small class="text-muted">
                                    {{ $sudahKenaDenda->count() }} peminjaman sudah melewati masa tenggang
                                    @if($pengaturan)
                                    (Denda: Rp {{ number_format($pengaturan->denda_per_hari, 0, ',', '.') }}/hari)
                                    @endif
                                </small>
                            </div>
                        </div>

                        @foreach($sudahKenaDenda as $peminjaman)
                        <div class="reminder-item border-bottom pb-3 mb-0 p-3">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h6 class="mb-1">
                                        {{ $peminjaman->buku->judul }}
                                    </h6>
                                    <small class="text-muted">
                                        <i class="bi bi-person me-1"></i>{{ $peminjaman->user->name }} |
                                        <i class="bi bi-envelope me-1"></i>{{ $peminjaman->user->email }}
                                    </small>
                                    <br>
                                    <small class="text-danger">
                                        <i class="bi bi-calendar-x me-1"></i>
                                        Terlambat sejak {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') }}
                                    </small>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <span class="badge bg-danger p-2 mb-1 d-block">
                                            {{ $peminjaman->status_detail['hari'] }} hari terlambat
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3 text-end">
                                    <button class="btn btn-sm btn-danger"
                                        onclick="kirimEmailSpesifik({{ $peminjaman->id }}, 'peringatan_keterlambatan')">
                                        <i class="bi bi-envelope me-1"></i> Kirim Denda
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    {{-- Jika tidak ada data --}}
                    @if($dalamMasaTenggang->count() == 0 && $sudahKenaDenda->count() == 0)
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                        <p class="mt-2">Tidak ada peminjaman yang terlambat</p>
                        <small class="text-success">Selamat! Semua peminjaman tepat waktu.</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function kirimEmailSpesifik(peminjamanId, jenisEmail) {
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="bi bi-hourglass-split"></i> Mengirim...';
        button.disabled = true;

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('jenis_email', jenisEmail);

        fetch(`/reminder/${peminjamanId}/kirim-spesifik`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('text/html')) {
                    return response.text().then(html => {
                        throw new Error('Server mengembalikan halaman HTML');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('✅ Email ' + data.jenis_display + ' berhasil dikirim ke: ' + data.email);
                    button.innerHTML = '<i class="bi bi-check"></i> Terkirim';
                    button.classList.remove('btn-warning', 'btn-danger');
                    button.classList.add('btn-success');
                    button.disabled = true;
                } else {
                    alert('❌ Gagal: ' + data.message);
                    button.innerHTML = originalText;
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('❌ Terjadi kesalahan: ' + error.message);
                button.innerHTML = originalText;
                button.disabled = false;
            });
    }

    function kirimReminderMassal(jenisEmail) {
        let confirmMessage = '';
        let buttonText = '';

        switch (jenisEmail) {
            case 'pengingat_pengembalian':
                confirmMessage = 'Kirim email PENGINGAT PENGEMBALIAN ke semua peminjam yang akan datang?';
                buttonText = 'Pengingat';
                break;
            case 'peringatan_keterlambatan':
                confirmMessage = 'Kirim email PERINGATAN KETERLAMBATAN ke semua peminjam yang terlambat?';
                buttonText = 'Peringatan';
                break;
            default:
                confirmMessage = 'Kirim SEMUA jenis email reminder ke semua peminjam?';
                buttonText = 'Semua';
        }

        if (confirm(confirmMessage)) {
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="bi bi-hourglass-split"></i> Mengirim...';
            button.disabled = true;

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('jenis_email', jenisEmail);

            fetch(`/reminder/massal`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('text/html')) {
                        return response.text().then(html => {
                            throw new Error('Server mengembalikan halaman HTML');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('✅ ' + data.message);
                        if (data.errors && data.errors.length > 0) {
                            console.log('Errors:', data.errors);
                        }
                        location.reload();
                    } else {
                        alert('❌ Gagal: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('❌ Terjadi kesalahan: ' + error.message);
                })
                .finally(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
        }
    }
</script>
@endsection