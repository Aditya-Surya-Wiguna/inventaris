<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title') | Inventaris Gudang UIN Raden Fatah</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .sidebar {
      width: 240px;
      height: 100vh;
      background: #0d6efd;
      color: white;
      position: fixed;
      top: 0;
      left: 0;
      overflow-y: auto;
    }
    .sidebar .nav-link {
      color: white;
      font-weight: 500;
      border-radius: 5px;
      margin: 4px 0;
    }
    .sidebar .nav-link.active, .sidebar .nav-link:hover {
      background-color: rgba(255,255,255,0.15);
    }
    .content {
      margin-left: 250px;
      padding: 25px;
    }
    .navbar {
      background-color: #fff;
      border-bottom: 1px solid #dee2e6;
    }
  </style>
</head>
<body>

  {{-- Sidebar --}}
  @include('layouts.partials.sidebar')

  {{-- Konten Utama --}}
  <div class="content">
    @include('layouts.partials.navbar')
    <div class="container-fluid mt-4">
      @yield('content')
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
