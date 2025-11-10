{{-- resources/views/users/create.blade.php --}}
@extends('layouts.app')

@section('content')
  <div class="container mt-4">
    <h4 class="mb-3">
      <i class="fas fa-user-plus me-2"></i> Tambah Pengguna
    </h4>

    <div class="card shadow-sm">
      <div class="card-body">
        <form action="{{ route('users.store') }}" method="POST">
          @csrf

          <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}"
              placeholder="Masukkan nama lengkap" required>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}"
              placeholder="Masukkan email pengguna" required>
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password"
              required>
          </div>

          <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" id="role" class="form-select" required>
              <option value="">-- Pilih Role --</option>
              <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
              <option value="pustakawan" {{ old('role') == 'pustakawan' ? 'selected' : '' }}>Pustakawan</option>
            </select>
          </div>

          <div class="d-flex justify-content-between">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
              <i class="fas fa-arrow-left me-1"></i> Batal
            </a>
            <button type="submit" class="btn btn-success">
              <i class="fas fa-save me-1"></i> Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection