<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Sistem Perpustakaan') | Sistem Perpustakaan</title>
  <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <style>
    :root {
      --primary-color: #191970;
      --secondary-color: #191970;
      --accent-color: #36b9cc;
      --danger-color: #e74a3b;
      --dark-color: #343a40;
      --light-color: #f8f9fa;
      --sidebar-width: 250px;
      --sidebar-collapsed-width: 70px;
      --transition-speed: 0.3s;
    }

    body {
      display: flex;
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f5f7fb;
      margin: 0;
      transition: all var(--transition-speed);
    }

    /* Sidebar Styles */
    .sidebar {
      width: var(--sidebar-width);
      background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
      color: white;
      flex-shrink: 0;
      height: 100vh;
      position: fixed;
      left: 0;
      top: 0;
      z-index: 1000;
      overflow-y: auto;
      transition: all var(--transition-speed);
      box-shadow: 3px 0 10px rgba(0, 0, 0, 0.1);
    }

    .sidebar-header {
      padding: 20px 15px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.2);
      text-align: center;
    }

    .sidebar-header h4 {
      margin: 0;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .sidebar-menu {
      padding: 15px 0;
    }

    .sidebar-menu a {
      color: rgba(255, 255, 255, 0.9);
      text-decoration: none;
      display: flex;
      align-items: center;
      padding: 10px 10px;
      transition: all 0.2s;
      border-left: 4px solid transparent;
      gap: 12px;
    }

    .sidebar-menu a:hover,
    .sidebar-menu a.active {
      background-color: rgba(255, 255, 255, 0.1);
      color: white;
      border-left-color: white;
    }

    .sidebar-menu a i {
      width: 24px;
      text-align: center;
    }

    .sidebar-menu .logout:hover {
      background: var(--danger-color);
    }

    .menu-section {
      padding: 10px 10px;
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: rgba(255, 255, 255, 0.6);
      margin-top: 0px;
    }

    /* Content Area */
    .content {
      flex-grow: 1;
      margin-left: var(--sidebar-width);
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      transition: all var(--transition-speed);
    }

    /* Navbar Styles */
    .navbar {
      background-color: white;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      padding: 15px 20px;
      position: sticky;
      top: 0;
      z-index: 999;
    }

    .navbar-brand {
      font-weight: 600;
      color: var(--dark-color);
    }

    .user-info {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .user-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: var(--primary-color);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: bold;
    }

    /* Main Content */
    .main-content {
      flex-grow: 1;
      padding: 20px;
    }

    /* Footer */
    .footer {
      background-color: white;
      padding: 15px 20px;
      text-align: center;
      border-top: 1px solid #e9ecef;
      margin-top: auto;
    }

    /* Toggle Button for Sidebar */
    #sidebarToggle {
      position: fixed;
      bottom: 20px;
      left: 20px;
      z-index: 1001;
      background: var(--primary-color);
      color: white;
      border: none;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      display: none;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    /* Responsive Styles */
    @media (max-width: 992px) {
      .sidebar {
        transform: translateX(-100%);
        width: var(--sidebar-width);
      }

      .sidebar.active {
        transform: translateX(0);
      }

      .content {
        margin-left: 0;
      }

      #sidebarToggle {
        display: flex;
      }

      .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 999;
        display: none;
      }

      .sidebar-overlay.active {
        display: block;
      }
    }

    /* Animation for menu items */
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateX(-10px);
      }

      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    .sidebar-menu a {
      animation: fadeIn 0.3s ease-out;
    }

    /* Custom scrollbar for sidebar */
    .sidebar::-webkit-scrollbar {
      width: 5px;
    }

    .sidebar::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.3);
      border-radius: 10px;
    }

    .sidebar::-webkit-scrollbar-thumb:hover {
      background: rgba(255, 255, 255, 0.5);
    }
  </style>
</head>

<body>
  <div class="sidebar-overlay" id="sidebarOverlay"></div>
  @include('partials.sidebar')
  <!-- Toggle Button for Mobile -->
  <button id="sidebarToggle" class="btn">
    <i class="fas fa-bars"></i>
  </button>
  <div class="content">
    @include('partials.navbar')

    <main class="main-content">
      @yield('content')
    </main>

    @include('partials.footer')
  </div>
</body>
@stack('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('.sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const sidebarTexts = document.querySelectorAll('.sidebar-text');

    // Toggle sidebar on mobile
    function toggleSidebar() {
      sidebar.classList.toggle('active');
      sidebarOverlay.classList.toggle('active');
    }

    sidebarToggle.addEventListener('click', toggleSidebar);
    sidebarOverlay.addEventListener('click', toggleSidebar);

    // Handle window resize
    function handleResize() {
      if (window.innerWidth > 992) {
        sidebar.classList.remove('active');
        sidebarOverlay.classList.remove('active');
      }
    }

    window.addEventListener('resize', handleResize);

    // Add animation delay to menu items
    const menuItems = document.querySelectorAll('.sidebar-menu a');
    menuItems.forEach((item, index) => {
      item.style.animationDelay = `${index * 0.05}s`;
    });
  });
</script>

</html>