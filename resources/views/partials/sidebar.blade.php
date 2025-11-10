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
      <a href="{{ route('backup.index') }}" class="{{ request()->is('backup*') ? 'active' : '' }}">
        <i class="fas fa-database"></i> <span class="sidebar-text">Backup Data</span>
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