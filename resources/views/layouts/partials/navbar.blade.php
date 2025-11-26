<nav class="navbar navbar-expand-lg sticky-top py-2 px-3 navbar-modern shadow-lg">
  <div class="container-fluid d-flex align-items-center justify-content-between">

    {{-- ================= LEFT SIDE (Toggle + Logo) ================= --}}
    <div class="d-flex align-items-center">
      {{-- 🔘 Tombol Toggle Sidebar (Muncul di HP) --}}
      <button id="sidebarToggle" class="btn btn-sm btn-outline-primary me-3 d-lg-none">
        <i class="bi bi-list fs-5"></i>
      </button>

      {{-- 🧱 LOGO --}}
      <div class="logo-glow d-flex align-items-center justify-content-center me-2">
        <i class="bi bi-box-seam-fill fs-5 text-white"></i>
      </div>
      <span class="navbar-brand mb-0 fw-bold text-gradient">Gudang UIN Raden Fatah</span>
    </div>

    {{-- ================= RIGHT SIDE ================= --}}
    <div class="d-flex align-items-center gap-3">
      {{-- 👤 ADMIN DROPDOWN --}}
      <div class="dropdown">
        <button class="btn btn-admin d-flex align-items-center px-3 py-1 rounded-pill" data-bs-toggle="dropdown">
          <i class="bi bi-person-circle fs-5 me-2 text-primary"></i>
          <span class="fw-semibold small text-primary">Admin</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end user-dropdown shadow-sm border-0">
          <li>
            <a class="dropdown-item text-danger fw-semibold" href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="GET" class="d-none"></form>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>

{{-- 🔧 Script Toggle Sidebar --}}
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggle = document.getElementById('sidebarToggle');
    const navbar = document.querySelector('.navbar-modern');

    // Klik tombol ☰ = buka/tutup sidebar
    if (toggle) {
      toggle.addEventListener('click', function () {
        sidebar.classList.toggle('show');
        overlay.classList.toggle('active');
        navbar.classList.toggle('sidebar-open'); // 🌟 Navbar ikut menyesuaikan
        document.body.classList.toggle('no-scroll');
      });
    }

    // Klik luar sidebar = tutup
    if (overlay) {
      overlay.addEventListener('click', function () {
        sidebar.classList.remove('show');
        overlay.classList.remove('active');
        navbar.classList.remove('sidebar-open');
        document.body.classList.remove('no-scroll');
      });
    }

    // Reset kalau layar dibesarkan lagi
    window.addEventListener('resize', function () {
      if (window.innerWidth > 992) {
        sidebar.classList.remove('show');
        overlay.classList.remove('active');
        navbar.classList.remove('sidebar-open');
        document.body.classList.remove('no-scroll');
      }
    });
  });
</script>

<style>
  /* 🌈 NAVBAR STYLE (Biru Putih ala UIN) */
  .navbar-modern {
    background: linear-gradient(90deg, #ffffff 0%, #d6e9ff 30%, #b3d4ff 65%, #0d47a1 100%);
    color: #003cba;
    backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.35s ease-in-out;
    position: relative;
    z-index: 1100 !important; /* ✅ Selalu di atas sidebar dan overlay */
  }

  /* ✨ Logo Biru Glowing */
  .logo-glow {
    background: linear-gradient(135deg, #1565c0, #0d47a1);
    width: 36px;
    height: 36px;
    border-radius: 10px;
    box-shadow:
      0 0 10px rgba(13,110,253,0.4),
      0 0 20px rgba(13,110,253,0.25);
  }

  /* 🌀 Teks Brand Gradasi */
  .text-gradient {
    background: linear-gradient(90deg, #0047ab, #0d6efd);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    letter-spacing: 0.4px;
  }

  /* 👤 Tombol Admin */
  .btn-admin {
    background: linear-gradient(90deg, #ffffff, #e8f0ff);
    border: 1px solid rgba(13,110,253,0.3);
    box-shadow: 0 2px 6px rgba(13,110,253,0.15);
    transition: 0.3s ease;
  }
  .btn-admin:hover {
    background: linear-gradient(90deg, #f0f7ff, #ffffff);
    box-shadow: 0 3px 10px rgba(13,110,253,0.25);
    transform: translateY(-1px);
  }

  /* 🔘 Tombol Toggle Sidebar */
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

  /* 📱 Responsif: Sidebar + Navbar sinkron */
  @media (max-width: 991.98px) {
    #sidebarToggle {
      display: inline-flex !important;
    }
    body.no-scroll {
      overflow: hidden;
    }

    /* 🌟 Navbar ikut bergeser saat sidebar muncul */
    .navbar-modern.sidebar-open {
      transform: translateX(240px);
      width: calc(100% - 240px);
      backdrop-filter: blur(14px) brightness(0.97);
      transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1), width 0.35s ease;
    }
  }
</style>
