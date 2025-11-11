{{-- resources/views/users/create.blade.php --}}
@extends('layouts.app')

@section('content')
  <div class="container mt-4">
    <h4 class="mb-3">
      <i class="fas fa-user-plus me-2"></i> Tambah Pengguna
    </h4>

    <div class="card shadow-sm">
      <div class="card-body">

        {{-- Menampilkan Error Validasi --}}
        @if ($errors->any())
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i> Terjadi Kesalahan!</h5>
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST">
          @csrf

          <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
              placeholder="Masukkan nama lengkap" required>
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
              placeholder="Masukkan email pengguna" required>
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password"
                  required>
                @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              {{-- INI BAGIAN YANG DITAMBAHKAN --}}
              <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi password"
                  required>
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
              <option value="">-- Pilih Role --</option>
              <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
              <option value="pustakawan" {{ old('role') == 'pustakawan' ? 'selected' : '' }}>Pustakawan</option>
            </select>
            @error('role')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="d-flex justify-content-between mt-4">
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