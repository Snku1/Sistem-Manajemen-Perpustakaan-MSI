@extends('layouts.app')

@section('content')
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">
        <i class="fas fa-users me-2"></i> Data Pengguna (Admin & Pustakawan)
      </h4>
      <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Tambah Pengguna
      </a>
    </div>

    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Info --}}
    <div class="alert alert-info mb-3">
      <i class="fas fa-info-circle me-2"></i>
      Hanya menampilkan data <strong>Admin</strong> dan <strong>Pustakawan</strong>. 
      Role <strong>Kepala Perpustakaan</strong> tidak ditampilkan.
    </div>

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
                @if($user->role == 'admin')
                  <span class="badge bg-primary">
                    <i class="fas fa-user-shield me-1"></i> Admin
                  </span>
                @elseif($user->role == 'pustakawan')
                  <span class="badge bg-success">
                    <i class="fas fa-user-tie me-1"></i> Pustakawan
                  </span>
                @else
                  <span class="badge bg-secondary">
                    <i class="fas fa-user me-1"></i> {{ ucfirst($user->role) }}
                  </span>
                @endif
              </td>
              <td class="text-center">
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                  <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Yakin hapus pengguna {{ $user->name }}?')">
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
              <td colspan="4" class="text-center py-4">
                <i class="fas fa-users-slash text-muted me-2"></i>
                Belum ada data pengguna (Admin/Pustakawan).
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{ $users->links() }}
  </div>
@endsection