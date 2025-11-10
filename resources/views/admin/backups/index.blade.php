@extends('layouts.app')

{{-- Mengatur judul halaman agar sesuai dengan konten --}}
@section('title', 'Fitur Segera Hadir')

@section('content')
  <div class="container ">
    <div class="d-flex flex-column align-items-center justify-content-center text-center" style="min-height: 75vh;">

      {{-- Ilustrasi SVG --}}
      <div class="mb-4">
        <svg width="250" height="250" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M100 25C108.284 25 115 31.7157 115 40V60H85V40C85 31.7157 91.7157 25 100 25Z" fill="#BFDBFE" />
          <rect x="65" y="60" width="70" height="95" rx="8" fill="#3B82F6" />
          <path d="M75 155V175H125V155H75Z" fill="#60A5FA" />
          <path d="M92 80H108V90H92V80Z" fill="#DBEAFE" />
          <path d="M92 100H108V110H92V100Z" fill="#DBEAFE" />
          <path d="M92 120H108V130H92V120Z" fill="#DBEAFE" />
          <rect x="135" y="115" width="20" height="40" rx="4" fill="#93C5FD" />
          <rect x="45" y="115" width="20" height="40" rx="4" fill="#93C5FD" />
          <path d="M40 95L65 115V95H40Z" fill="#60A5FA" />
          <path d="M160 95L135 115V95H160Z" fill="#60A5FA" />
          <g filter="url(#filter0_d_1_1)">
            <circle cx="100" cy="40" r="10" fill="#FBBF24" />
          </g>
          <defs>
            <filter id="filter0_d_1_1" x="80" y="20" width="40" height="40" filterUnits="userSpaceOnUse"
              color-interpolation-filters="sRGB">
              <feFlood flood-opacity="0" result="BackgroundImageFix" />
              <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
                result="hardAlpha" />
              <feOffset dy="0" />
              <feGaussianBlur stdDeviation="5" />
              <feComposite in2="hardAlpha" operator="out" />
              <feColorMatrix type="matrix" values="0 0 0 0 0.984314 0 0 0 0 0.74902 0 0 0 0 0.141176 0 0 0 0.5 0" />
              <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_1_1" />
              <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_1_1" result="shape" />
            </filter>
          </defs>
        </svg>
      </div>

      {{-- Judul Utama --}}
      <h1 class="display-5 fw-bold text-primary">Segera Hadir!</h1>

      {{-- Deskripsi --}}
      <p class="lead text-muted col-md-8 col-lg-6 mx-auto">
        Fitur Manajemen Backup sedang dalam tahap akhir pengembangan. Kami bekerja keras untuk memastikan semuanya
        berjalan sempurna untuk Anda.
      </p>

      {{-- Tombol Aksi --}}
      <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg mt-4 rounded-pill shadow">
        <i class="bi bi-arrow-left me-2"></i> Kembali ke Dashboard
      </a>

    </div>
  </div>
@endsection