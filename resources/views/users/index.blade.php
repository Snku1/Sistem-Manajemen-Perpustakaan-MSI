@extends('layouts.app')

@section('content')
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">
        <i class="fas fa-users me-2"></i> Data Pengguna
      </h4>
      <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Tambah Pengguna
      </a>
    </div>

    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive shadow-sm rounded-3">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-primary">
          <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th width="120" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($users as $user)
            <tr>
              <td>{{ $user->name }}</td>
              <td>{{ $user->email }}</td>
              <td>
                <span class="badge bg-{{ $user->role == 'Admin' ? 'primary' : 'secondary' }}">
                  {{ $user->role }}
                </span>
              </td>
              <td class="text-center">
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                  <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Yakin hapus pengguna ini?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger btn-sm">
                    <i class="fas fa-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center">
                <i class="fas fa-info-circle me-1"></i> Belum ada pengguna.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{ $users->links() }}
  </div>
@endsection