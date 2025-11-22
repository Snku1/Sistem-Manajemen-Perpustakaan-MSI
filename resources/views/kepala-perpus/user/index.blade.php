@extends('layouts.app')

@section('title', 'Data Pengguna')

@section('content')
<div class="container-fluid mt-3">

    {{-- Tombol Export --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
        <h4 class="mb-0">
           <i class="fas fa-users me-2"></i> Data Pustakawan
        </h4>
        <div class="d-flex justify-content-end mb-2 gap-2">
            <a href="{{ route('kepala-perpus.user.export.pdf') }}"
                class="btn btn-danger shadow-sm">
                <i class="fas fa-file-pdf me-1"></i> Export PDF
            </a>

            <a href="{{ route('kepala-perpus.user.export.excel') }}"
                class="btn btn-success shadow-sm">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </a>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('kepala-perpus.user.index') }}" class="row g-2">
                <div class="col-12 col-md-10">
                    <input type="search" name="search" class="form-control" placeholder="Cari data pustakawan..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-12 col-md-2 d-grid">
                    <button class="btn btn-primary">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive shadow-sm rounded-3">
        <table class="table table-hover table-striped align-middle mb-0">
            <thead class="table-primary">
                <tr>
                    <th class="text-center">No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-success">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-3">
                            <i class="fas fa-info-circle me-1"></i> Belum ada data pustakawan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection