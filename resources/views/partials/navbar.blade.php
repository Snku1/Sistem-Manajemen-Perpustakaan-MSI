<nav class="navbar">
  <div class="container-fluid">
    <span class="navbar-brand">Selamat Datang, {{ auth()->user()->name }}</span>

    <div class="d-flex align-items-center">
      <div class="user-info me-3">
        <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
        <div class="user-avatar">
          {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
      </div>
    </div>
  </div>
</nav>