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

  {{-- Font Modern --}}
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://api.fontshare.com/v2/css?f[]=satoshi@400,500,700&display=swap" rel="stylesheet">

  <style>
    html, body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 14px;
      color: #2c2c2c;
      background-color: #f9fafc;
      overflow-x: hidden;
    }

    /* ===== SIDEBAR ===== */
    .sidebar {
      width: 240px;
      background: linear-gradient(180deg, #022859, #004aad);
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      color: white;
      transition: transform 0.35s ease, opacity 0.3s ease;
      z-index: 1060;
      box-shadow: 2px 0 8px rgba(0,0,0,0.08);
      overflow-y: auto;
    }

    .sidebar .nav-link {
      color: rgba(255,255,255,0.9);
      border-radius: 8px;
      margin: 4px 10px;
      padding: 9px 14px;
      display: flex;
      align-items: center;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    .sidebar .nav-link:hover {
      background: rgba(255,255,255,0.15);
      transform: translateX(4px);
    }
    .sidebar .nav-link.active {
      background: linear-gradient(145deg, rgba(255,255,255,0.22), rgba(255,255,255,0.1));
      box-shadow: inset 3px 3px 6px rgba(0,0,0,0.3), inset -3px -3px 6px rgba(255,255,255,0.15);
      transform: translateX(5px);
      color: #fff;
    }
    .sidebar .nav-link.active::before {
      content: "";
      position: absolute;
      left: 0;
      top: 8px;
      bottom: 8px;
      width: 4px;
      background: #fff;
      border-radius: 0 3px 3px 0;
    }

    /* ===== OVERLAY ===== */
    #sidebarOverlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.45);
      backdrop-filter: blur(2px);
      z-index: 1050;
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    #sidebarOverlay.active {
      display: block;
      opacity: 1;
    }

    /* ===== CONTENT ===== */
    .content {
      margin-left: 240px;
      padding: 25px;
      transition: all 0.3s ease;
    }

    /* ===== NAVBAR ===== */
    .navbar-modern {
      background: linear-gradient(90deg, #ffffff 0%, #d6e9ff 30%, #b3d4ff 65%, #0d47a1 100%);
      color: #003cba;
      border-bottom: 1px solid rgba(0, 0, 0, 0.05);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      position: sticky;
      top: 0;
      z-index: 1100;
    }

    #sidebarToggle {
      background: rgba(13,110,253,0.1);
      border: none;
      color: #0d47a1;
      transition: all 0.3s ease;
    }
    #sidebarToggle:hover {
      background: rgba(13,110,253,0.2);
      transform: scale(1.05);
    }

    /* ===== MOBILE RESPONSIVE ===== */
    @media (max-width: 991.98px) {
      .sidebar {
        transform: translateX(-260px);
        opacity: 0;
      }
      .sidebar.show {
        transform: translateX(0);
        opacity: 1;
      }
      .content {
        margin-left: 0;
      }
      body.no-scroll {
        overflow: hidden;
      }
    }

    /* ===== IMAGE MODAL (ANTI BACKDROP) ===== */
    .modal-backdrop { display: none !important; }
    .image-modal {
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.8);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }
    .image-modal.active { display: flex !important; }
    .image-modal img {
      max-width: 90%;
      max-height: 90%;
      border-radius: 10px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.5);
    }
    .image-modal .close-btn {
      position: absolute;
      top: 20px;
      right: 30px;
      font-size: 2rem;
      color: #fff;
      cursor: pointer;
    }
  </style>
</head>

<body>
  {{-- 🧭 SIDEBAR --}}
  @include('layouts.partials.sidebar')

  {{-- 🌑 Overlay Transparan --}}
  <div id="sidebarOverlay"></div>

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
      const sidebar = document.getElementById('sidebar');
      const overlay = document.getElementById('sidebarOverlay');
      const toggle = document.getElementById('sidebarToggle');

      // 🟦 Toggle Sidebar
      if (toggle) {
        toggle.addEventListener('click', function() {
          sidebar.classList.toggle('show');
          overlay.classList.toggle('active');
          document.body.classList.toggle('no-scroll');
        });
      }

      // 🟨 Klik luar sidebar = tutup
      if (overlay) {
        overlay.addEventListener('click', function() {
          sidebar.classList.remove('show');
          overlay.classList.remove('active');
          document.body.classList.remove('no-scroll');
        });
      }

      // 🟩 Reset saat layar diperbesar lagi
      window.addEventListener('resize', function() {
        if (window.innerWidth > 992) {
          sidebar.classList.remove('show');
          overlay.classList.remove('active');
          document.body.classList.remove('no-scroll');
        }
      });

      // 🖼️ POPUP GAMBAR
      const imageModal = document.createElement("div");
      imageModal.classList.add("image-modal");
      imageModal.innerHTML = `
        <span class="close-btn">&times;</span>
        <img src="" alt="Preview">
      `;
      document.body.appendChild(imageModal);

      const modalImg = imageModal.querySelector("img");
      const closeBtn = imageModal.querySelector(".close-btn");

      document.body.addEventListener("click", function(e) {
        if (e.target.classList.contains("preview-img")) {
          modalImg.src = e.target.getAttribute("data-src") || e.target.src;
          imageModal.classList.add("active");
        }
      });

      closeBtn.addEventListener("click", () => imageModal.classList.remove("active"));
      imageModal.addEventListener("click", (e) => {
        if (e.target === imageModal) imageModal.classList.remove("active");
      });
    });
  </script>

  @stack('scripts')
</body>
</html>
