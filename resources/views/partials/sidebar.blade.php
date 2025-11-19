<!-- Sidebar -->
<div class="sidebar">
  <div class="sidebar-header">
    <h4><i class="fas fa-book-open"></i> <span class="sidebar-text">Perpustakaan</span></h4>
  </div>

  <div class="sidebar-menu">
    @php
    $role = auth()->user()->role;
    @endphp

    @if($role === 'admin')
    <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
      <i class="fas fa-home"></i> <span class="sidebar-text">Dashboard</span>
    </a>
    <a href="{{ route('users.index') }}" class="{{ request()->is('users*') ? 'active' : '' }}">
      <i class="fas fa-users"></i> <span class="sidebar-text">Data Pengguna</span>
    </a>

    <div class="menu-section">Master Data</div>
    <a href="{{ route('buku.index') }}" class="{{ request()->is('buku*') ? 'active' : '' }}">
      <i class="fas fa-book"></i> <span class="sidebar-text">Data Buku</span>
    </a>
    <a href="{{ route('kategori.index') }}" class="{{ request()->is('kategori*') ? 'active' : '' }}">
      <i class="fas fa-tags"></i> <span class="sidebar-text">Kategori Buku</span>
    </a>
    <a href="{{ route('rak.index') }}" class="{{ request()->is('rak*') ? 'active' : '' }}">
      <i class="fas fa-layer-group"></i> <span class="sidebar-text">Rak Buku</span>
    </a>

    <div class="menu-section">Transaksi</div>
    <a href="{{ route('peminjaman.index') }}" class="{{ request()->is('peminjaman*') ? 'active' : '' }}">
      <i class="fas fa-arrow-circle-up"></i> <span class="sidebar-text">Peminjaman</span>
    </a>
    <a href="{{ route('pengembalian.index') }}" class="{{ request()->is('pengembalian*') ? 'active' : '' }}">
      <i class="fas fa-arrow-circle-down"></i> <span class="sidebar-text">Pengembalian</span>
    </a>
    <a href="{{ route('denda.index') }}" class="{{ request()->is('denda*') ? 'active' : '' }}">
      <i class="fas fa-money-bill-wave"></i> <span class="sidebar-text">Denda</span>
    </a>

    <div class="menu-section">OPERASIONAL</div>
    <a href="{{ route('jadwal.index') }}" class="{{ request()->is('jadwal*') ? 'active' : '' }}">
      <i class="bi bi-calendar-week me-2"></i> Jadwal
    </a>
    <a href="{{ route('reminder.index') }}" class="{{ request()->is('reminder*') ? 'active' : '' }}">
      <i class="bi bi-bell me-2"></i> Reminder
    </a>
    @elseif($role === 'pustakawan')
    <a href="{{ route('pustakawan.dashboard') }}"
      class="{{ request()->is('pustakawan/dashboard') ? 'active' : '' }}">Dashboard</a>
    <div class="menu-section">Transaksi</div>
    <a href="{{ route('pustakawan.peminjaman.index') }}"
      class="{{ request()->is('pustakawan/peminjaman*') ? 'active' : '' }}">
      <i class="fas fa-arrow-circle-up"></i> <span class="sidebar-text">Data Peminjaman</span>
    </a>
    <a href="{{ route('pustakawan.pengembalian.index') }}"
      class="{{ request()->is('pustakawan/pengembalian*') ? 'active' : '' }}">
      <i class="fas fa-undo"></i> <span class="sidebar-text">Data Pengembalian</span>
    </a>

    <h6 class="menu-section">Cari Buku</h6>
    <a href="{{ route('pustakawan.buku.index') }}" class="{{ request()->is('pustakawan/buku*') ? 'active' : '' }}">
      <i class="fas fa-search"></i> <span class="sidebar-text">Cari Buku</span>
    </a>

    <div class="menu-section">OPERASIONAL</div>
    <a href="{{ route('pustakawan.jadwal.index') }}" class="{{ request()->is('jadwal*') ? 'active' : '' }}">
      <i class="bi bi-calendar-week me-2"></i> Jadwal
    </a>
    <a href="{{ route('pustakawan.reminder.index') }}" class="{{ request()->is('reminder*') ? 'active' : '' }}">
      <i class="bi bi-bell me-2"></i> Reminder
    </a>
    @elseif($role === 'kepala_perpus')
    <a href="{{ route('kepala-perpus.dashboard') }}" class="{{ request()->is('kepala/dashboard') ? 'active' : '' }}">
      <i class="fas fa-home"></i> <span class="sidebar-text">Dashboard</span>
    </a>

    <div class="menu-section">Statistik</div>
    <a href="{{ route('kepala-perpus.statistik.index') }}"
      class="{{ request()->is('kepala-perpus/statistik*') ? 'active' : '' }}">
      <i class="fas fa-chart-bar"></i> <span class="sidebar-text">Statistik</span>
    </a>
    <a href="{{ route('kepala-perpus.buku.index') }}"
      class="{{ request()->is('kepala-perpus/buku*') ? 'active' : '' }}">
      <i class="fas fa-book"></i> <span class="sidebar-text">Laporan Stok Buku</span>
    </a>
    <a href="{{ route('kepala-perpus.denda.index') }}"
      class="{{ request()->is('kepala-perpus/denda*') ? 'active' : '' }}">
      <i class="fas fa-money-bill-wave"></i> <span class="sidebar-text">Laporan Denda</span>
    </a>

    <div class="menu-section">Data</div>
    <a href="{{ route('kepala-perpus.user.index') }}"
      class="{{ request()->is('kepala-perpus/user*') ? 'active' : '' }}">
      <i class="fas fa-users"></i> <span class="sidebar-text">Data Pengguna</span>
    </a>

    <div class="menu-section">Transaksi</div>
    <a href="{{ route('kepala-perpus.peminjaman.index') }}"
      class="{{ request()->is('kepala-perpus/peminjaman*') ? 'active' : '' }}">
      <i class="fas fa-arrow-circle-up"></i> <span class="sidebar-text">Data Peminjaman</span>
    </a>
    <a href="{{ route('kepala-perpus.pengembalian.index') }}"
      class="{{ request()->is('kepala-perpus/pengembalian*') ? 'active' : '' }}">
      <i class="fas fa-arrow-circle-down"></i> <span class="sidebar-text">Data Pengembalian</span>
    </a>
    @endif
    <hr>
    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
      class="logout">
      <i class="fas fa-sign-out-alt"></i> <span class="sidebar-text">Logout</span>
    </a>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
      @csrf
    </form>
  </div>
</div>