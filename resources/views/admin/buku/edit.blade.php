@extends('layouts.app')
@section('title', 'Edit Buku')
@section('content')
  <div class="container py-4">
    <h4 class="fw-bold mb-3"><i class="bi bi-pencil-square"></i> Edit Buku</h4>

    <form action="{{ route('buku.update', $buku->id) }}" method="POST">
      @csrf
      @method('PUT')
      @include('admin.buku.form')
      <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-success me-2">
          <i class="bi bi-check-circle"></i> Update
        </button>
        <a href="{{ route('buku.index') }}" class="btn btn-secondary">
          <i class="bi bi-arrow-left-circle"></i> Batal
        </a>
      </div>
    </form>
  </div>
@endsection