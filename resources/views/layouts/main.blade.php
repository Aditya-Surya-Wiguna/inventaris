<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title') | Inventaris Gudang UIN Raden Fatah</title>

  {{-- Bootstrap & Icon --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css">

  <style>
    /* ===== BODY ===== */
    body {
      background-color: #f8f9fa;
      font-family: 'Poppins', sans-serif;
      overflow-x: hidden;
    }

    /* ===== SIDEBAR ===== */
    .sidebar {
      width: 240px;
      height: 100vh;
      background: linear-gradient(180deg, #0d6efd, #003cba);
      color: white;
      position: fixed;
      top: 0;
      left: 0;
      overflow-y: auto;
      box-shadow: 2px 0 8px rgba(0,0,0,0.1);
      z-index: 1030;
      transition: all 0.3s ease;
    }

    .sidebar .brand {
      font-size: 1.1rem;
      font-weight: 600;
      padding: 18px;
      border-bottom: 1px solid rgba(255,255,255,0.2);
    }

    .sidebar .nav-link {
      color: white;
      font-weight: 500;
      border-radius: 5px;
      margin: 3px 10px;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: all 0.2s;
    }

    .sidebar .nav-link.active,
    .sidebar .nav-link:hover {
      background-color: rgba(255,255,255,0.2);
      transform: translateX(5px);
    }

    /* ===== CONTENT WRAPPER ===== */
    .content {
      margin-left: 250px;
      padding: 25px;
      transition: all 0.3s ease;
    }

    /* ===== NAVBAR ===== */
    .navbar {
      backdrop-filter: blur(10px);
      background-color: rgba(255, 255, 255, 0.8) !important;
      border-bottom: 1px solid #dee2e6;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      z-index: 1050;
      transition: all 0.3s;
    }

    .navbar .navbar-brand {
      font-weight: 600;
      color: #0d6efd !important;
    }

    .navbar .btn-light:hover,
    .navbar .btn-outline-primary:hover {
      background-color: #0d6efd !important;
      color: white !important;
      transition: 0.3s;
    }

    /* ===== DROPDOWN & BADGES ===== */
    .dropdown-menu {
      border-radius: 10px;
      font-size: 0.85rem;
      animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(-5px);}
      to {opacity: 1; transform: translateY(0);}
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 992px) {
      .sidebar {
        width: 200px;
      }
      .content {
        margin-left: 210px;
        padding: 15px;
      }
    }

    @media (max-width: 768px) {
      .sidebar {
        position: fixed;
        left: -240px;
        transition: 0.3s;
      }
      .sidebar.show {
        left: 0;
      }
      .content {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>

  {{-- =========================
       🧭 SIDEBAR
  ========================== --}}
  @include('layouts.partials.sidebar')

  {{-- =========================
       🌐 NAVBAR + CONTENT
  ========================== --}}
  <div class="content">
    @include('layouts.partials.navbar')

    <div class="container-fluid mt-4 animate__animated animate__fadeIn">
      @yield('content')
    </div>
  </div>

  {{-- =========================
       ⚙️ SCRIPT
  ========================== --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Sidebar toggle (untuk mobile)
    document.addEventListener('DOMContentLoaded', function() {
      const toggleBtn = document.querySelector('.navbar-toggler');
      const sidebar = document.querySelector('.sidebar');
      if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function() {
          sidebar.classList.toggle('show');
        });
      }
    });
  </script>

  @stack('scripts')
</body>
</html>
