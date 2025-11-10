@extends('layouts.app')

@section('content')
  <div class="container mt-4">
    <h4>Proses Pengembalian Buku</h4>

    <div class="card p-3 mb-3">
      <p><strong>Buku:</strong> {{ $peminjaman->buku->judul }}</p>
      <p><strong>Peminjam:</strong> {{ $peminjaman->user->name }}</p>
      <p><strong>Tanggal Pinjam:</strong> {{ $peminjaman->tanggal_pinjam }}</p>
    </div>

    <form action="{{ route('pengembalian.store', $peminjaman->id) }}" method="POST">
      @csrf
      <div class="mb-3">
        <label>Tanggal Kembali</label>
        <input type="date" name="tanggal_kembali" value="{{ now()->toDateString() }}" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-primary">Konfirmasi Pengembalian</button>
      <a href="{{ route('pengembalian.index') }}" class="btn btn-secondary">Batal</a>
    </form>
  </div>
@endsection