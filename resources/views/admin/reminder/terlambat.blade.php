@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-danger">
            <i class="bi bi-exclamation-triangle me-2"></i> Reminder - Peminjaman Terlambat
        </h4>
        <div class="btn-group">
            <a href="{{ route('reminder.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <a href="{{ route('jadwal.terlambat') }}" class="btn btn-warning">
                <i class="bi bi-calendar me-1"></i> Lihat Jadwal
            </a>
        </div>
    </div>

    {{-- Tabel Peminjaman Terlambat --}}
    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
            <h6 class="mb-0"><i class="bi bi-clock me-2"></i> Daftar Peminjaman Terlambat</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th>Buku</th>
                            <th>Peminjam</th>
                            <th class="text-center">Batas Kembali</th>
                            <th class="text-center">Keterlambatan</th>
                            <th class="text-center">Status Reminder</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($terlambat as $peminjaman)
                        @php
                            $hariTerlambat = \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->diffInDays(now());
                        @endphp
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $peminjaman->buku->judul }}</strong>
                            </td>
                            <td>{{ $peminjaman->user->name }}</td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') }}
                                <br>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->diffForHumans() }}
                                </small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-danger">{{ $hariTerlambat }} hari</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning text-dark">Perlu Reminder</span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-danger" 
                                        onclick="kirimReminderTerlambat({{ $peminjaman->id }})">
                                    <i class="bi bi-bell"></i> Remind
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                                <p class="mt-2">Tidak ada peminjaman terlambat</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Template Pesan Reminder --}}
    <div class="card border-info mt-4">
        <div class="card-header bg-info text-white">
            <h6 class="mb-0"><i class="bi bi-chat-text me-2"></i> Template Pesan Reminder</h6>
        </div>
        <div class="card-body">
            <div class="alert alert-warning">
                <strong>Subject:</strong> Peringatan Keterlambatan Pengembalian Buku<br>
                <strong>Isi Pesan:</strong><br>
                "Kepada Yth. [Nama Peminjam],<br><br>
                Kami informasikan bahwa peminjaman buku "[Judul Buku]" telah melewati batas waktu pengembalian 
                pada [Tanggal Kembali]. Terhitung [Jumlah Hari] hari keterlambatan.<br><br>
                Segera kembalikan buku tersebut untuk menghindari akumulasi denda. Terima kasih."
            </div>
        </div>
    </div>
</div>

<script>
function kirimReminderTerlambat(peminjamanId) {
    if (confirm('Kirim reminder khusus untuk keterlambatan?')) {
        // Show loading
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="bi bi-hourglass-split"></i> Sending...';
        button.disabled = true;

        fetch(`/admin/reminder/${peminjamanId}/kirim`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Reminder keterlambatan berhasil dikirim!');
                // Update status
                button.innerHTML = '<i class="bi bi-check"></i> Terkirim';
                button.classList.remove('btn-danger');
                button.classList.add('btn-success');
            } else {
                alert('Gagal: ' + data.message);
                button.innerHTML = originalText;
                button.disabled = false;
            }
        })
        .catch(error => {
            alert('Terjadi kesalahan: ' + error);
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
}
</script>
@endsection