@extends('layouts.app')

@section('content')
  <div class="container py-4">
    <h4 class="fw-bold mb-3"><i class="bi bi-plus-circle"></i> Tambah Peminjaman</h4>

    <div class="card shadow-sm border-0">
      <div class="card-body">
        <form action="{{ route('peminjaman.store') }}" method="POST">
          @csrf

          <div class="mb-3">
            <label class="form-label fw-semibold">Buku</label>
            <select name="buku_id" class="form-select" required>
              <option value="">-- Pilih Buku --</option>
              @foreach($buku as $b)
                <option value="{{ $b->id }}">{{ $b->judul }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Peminjam</label>
            <select name="user_id" class="form-select" required>
              <option value="">-- Pilih Peminjam --</option>
              @foreach($users as $u)
                <option value="{{ $u->id }}">{{ $u->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Tanggal Pinjam</label>
            <input type="date" name="tanggal_pinjam" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Tanggal Kembali</label>
            <input type="date" name="tanggal_kembali" class="form-control" required>
          </div>

          <div class="d-flex justify-content-end">
            <button class="btn btn-success me-2">
              <i class="bi bi-save"></i> Simpan
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