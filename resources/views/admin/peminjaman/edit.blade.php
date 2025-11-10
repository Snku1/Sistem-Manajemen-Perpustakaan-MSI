@extends('layouts.app')

@section('content')
  <div class="container py-4">
    <h4 class="fw-bold mb-3"><i class="bi bi-pencil-square"></i> Edit Peminjaman</h4>

    <div class="card shadow-sm border-0">
      <div class="card-body">
        <form action="{{ route('peminjaman.update', $peminjaman->id) }}" method="POST">
          @csrf @method('PUT')

          <div class="mb-3">
            <label class="form-label fw-semibold">Buku</label>
            <select name="buku_id" class="form-select" required>
              @foreach($buku as $b)
                <option value="{{ $b->id }}" {{ $peminjaman->buku_id == $b->id ? 'selected' : '' }}>
                  {{ $b->judul }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Peminjam</label>
            <select name="user_id" class="form-select" required>
              @foreach($users as $u)
                <option value="{{ $u->id }}" {{ $peminjaman->user_id == $u->id ? 'selected' : '' }}>
                  {{ $u->name }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Tanggal Pinjam</label>
            <input type="date" name="tanggal_pinjam" value="{{ $peminjaman->tanggal_pinjam }}" class="form-control"
              required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Status</label>
            <select name="status" class="form-select" required>
              <option value="dipinjam" {{ $peminjaman->status == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
              <option value="dikembalikan" {{ $peminjaman->status == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan
              </option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Tanggal Kembali</label>
            <input type="date" name="tanggal_kembali" value="{{ $peminjaman->tanggal_kembali }}" class="form-control">
          </div>

          <div class="d-flex justify-content-end">
            <button class="btn btn-success me-2">
              <i class="bi bi-check-circle"></i> Update
            </button>
            <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">
              <i class="bi bi-arrow-left-circle"></i> Kembali
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection