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

  {{-- 🌟 Font Modern: Plus Jakarta Sans + Satoshi --}}
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://api.fontshare.com/v2/css?f[]=satoshi@400,500,700&display=swap" rel="stylesheet">

  <style>
    /* ========= GLOBAL FONT STYLE ========= */
    html, body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 14px; /* 🔥 lebih kecil & halus */
      font-weight: 400;
      color: #2c2c2c;
      background-color: #f9fafc;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      overflow-x: hidden;
      letter-spacing: 0.1px;
      line-height: 1.55;
    }

    h1, h2, h3, h4, .fw-bold, .navbar-brand, .sidebar .brand {
      font-family: 'Satoshi', 'Poppins', sans-serif;
      font-weight: 600;
      letter-spacing: 0.3px;
    }

    /* ========= SIDEBAR ========= */
    .sidebar {
      width: 240px;
      height: 100vh;
      background: linear-gradient(180deg, #0d6efd, #003cba);
      color: white;
      position: fixed;
      top: 0;
      left: 0;
      overflow-y: auto;
      box-shadow: 2px 0 8px rgba(0,0,0,0.08);
      z-index: 1030;
      border-right: 1px solid rgba(255,255,255,0.1);
      transition: all 0.3s ease;
      font-size: 13px;
    }

    .sidebar .brand {
      font-size: 1rem;
      font-weight: 700;
      padding: 16px;
      border-bottom: 1px solid rgba(255,255,255,0.15);
    }

    .sidebar .nav-link {
      color: white;
      font-weight: 500;
      border-radius: 8px;
      margin: 3px 10px;
      display: flex;
      align-items: center;
      gap: 9px;
      padding: 7px 12px;
      font-size: 13px;
      transition: all 0.25s ease;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
      background-color: rgba(255,255,255,0.18);
      transform: translateX(5px);
    }

    /* ========= CONTENT ========= */
    .content {
      margin-left: 250px;
      padding: 25px;
      transition: all 0.3s ease;
    }

    /* ========= NAVBAR ========= */
    .navbar {
      backdrop-filter: blur(12px);
      background-color: rgba(255,255,255,0.9) !important;
      border-bottom: 1px solid #e4e6eb;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      z-index: 1050;
      font-size: 13px;
    }

    .navbar .navbar-brand {
      font-weight: 700;
      color: #0d6efd !important;
      font-size: 14px;
    }

    .navbar .btn-light:hover,
    .navbar .btn-outline-primary:hover {
      background-color: #0d6efd !important;
      color: white !important;
      transition: 0.3s;
    }

    /* ========= CARD & TEXT ========= */
    .card {
      border-radius: 12px;
      border: none;
      box-shadow: 0 3px 10px rgba(0,0,0,0.04);
      font-size: 13.2px;
    }

    .card-title {
      font-family: 'Satoshi', sans-serif;
      font-weight: 600;
      font-size: 14px;
      color: #333;
    }

    .table {
      font-size: 13px; /* 🔹 ukuran tabel disesuaikan */
    }

    .table th, .table td {
      vertical-align: middle;
      padding: 8px 10px;
    }

    /* ========= BUTTON ========= */
    .btn {
      font-size: 13px;
      padding: 6px 12px;
      border-radius: 8px;
    }

    /* ========= FORM INPUT ========= */
    .form-control, .form-select {
      font-size: 13px;
      border-radius: 6px;
      padding: 6px 10px;
    }

    /* ========= DROPDOWN ========= */
    .dropdown-menu {
      border-radius: 10px;
      font-size: 12.8px;
      animation: fadeIn 0.25s ease;
    }

    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(-5px);}
      to {opacity: 1; transform: translateY(0);}
    }

    /* ========= RESPONSIVE ========= */
    @media (max-width: 992px) {
      .sidebar { width: 200px; }
      .content { margin-left: 210px; padding: 15px; }
    }

    @media (max-width: 768px) {
      .sidebar {
        position: fixed;
        left: -240px;
        transition: 0.3s;
      }
      .sidebar.show { left: 0; }
      .content { margin-left: 0; }
    }
  </style>
</head>

<body>
  {{-- 🧭 SIDEBAR --}}
  @include('layouts.partials.sidebar')

  {{-- 🌐 NAVBAR + CONTENT --}}
  <div class="content">
    @include('layouts.partials.navbar')

    <div class="container-fluid mt-4 animate__animated animate__fadeIn">
      @yield('content')
    </div>
  </div>

  {{-- ⚙️ SCRIPT --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
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
