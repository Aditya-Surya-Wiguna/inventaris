<nav class="sidebar d-flex flex-column p-3 align-items-center" id="sidebar">
  {{-- LOGO & BRAND AREA --}}
  <div class="text-center mb-4 w-100">
    <div class="d-flex justify-content-center">
      <div class="bg-white rounded-circle shadow-sm mb-3"
           style="width: 90px; height: 90px; display: flex; justify-content: center; align-items: center; border: 2px solid rgba(255,255,255,0.25);">
        <img src="{{ asset('images/logo-uin.png') }}" alt="Logo UIN"
             style="width: 78px; height: 78px; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.35));">
      </div>
    </div>

    <h6 class="fw-bold text-white mb-0"
        style="font-size: 1rem; letter-spacing: 0.4px; text-shadow: 0 1px 2px rgba(0,0,0,0.3);">
      UIN Raden Fatah
    </h6>
    <p class="text-white-50 mb-1" style="font-size: 0.75rem;">Palembang</p>
    <hr class="text-white opacity-75 mx-auto mt-2 mb-2"
        style="width: 80%; border-top: 1.5px solid rgba(255,255,255,0.4);">
  </div>

  {{-- MENU NAVIGATION --}}
  <ul class="nav flex-column w-100 px-2">
    <li class="nav-item">
      <a href="{{ route('dashboard') }}" 
         class="nav-link {{ request()->is('/') ? 'active' : '' }}">
        <i class="bi bi-speedometer2 me-2"></i> Dashboard
      </a>
    </li>

    <li class="nav-item">
      <a href="{{ route('barang.index') }}" 
         class="nav-link {{ request()->is('barang*') && !request()->is('barang-pindah*') && !request()->is('barang-rusak*') ? 'active' : '' }}">
        <i class="bi bi-box me-2"></i> Data Barang
      </a>
    </li>

    <li class="nav-item">
      <a href="{{ route('lokasi.index') }}" 
         class="nav-link {{ request()->is('lokasi*') ? 'active' : '' }}">
        <i class="bi bi-geo-alt me-2"></i> Lokasi Barang
      </a>
    </li>

    <li class="nav-item">
      <a href="{{ route('barang-pindah.index') }}" 
         class="nav-link {{ request()->is('barang-pindah*') ? 'active' : '' }}">
        <i class="bi bi-arrow-left-right me-2"></i> Barang Pindah
      </a>
    </li>

    <li class="nav-item">
      <a href="{{ route('barang-rusak.index') }}" 
         class="nav-link {{ request()->is('barang-rusak*') ? 'active' : '' }}">
        <i class="bi bi-exclamation-triangle me-2"></i> Barang Rusak
      </a>
    </li>
  </ul>

  <hr class="text-white opacity-50 mt-4 mb-2 w-100">

  {{-- FOOTER SIDEBAR --}}
  <div class="mt-auto text-center small text-white-50">
    <p class="mb-0">© {{ date('Y') }} UIN Raden Fatah</p>
    <p class="mb-0">Admin Gudang</p>
  </div>
</nav>

{{-- Overlay transparan --}}
<div id="sidebarOverlay"></div>

<style>

  .sidebar {
    background: linear-gradient(180deg, #022859, #004aad);
    box-shadow: inset 0 0 10px rgba(255,255,255,0.04), 3px 0 8px rgba(0,0,0,0.1);
    font-size: 13px;
    color: white;
    width: 240px;
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease;
    z-index: 1060;
  }

  .sidebar .nav-link {
    color: rgba(255,255,255,0.85);
    border-radius: 10px;
    margin: 4px 10px;
    padding: 9px 14px;
    display: flex;
    align-items: center;
    font-weight: 500;
    transition: all 0.3s ease;
    position: relative;
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
    background: #ffffff;
    border-radius: 0 3px 3px 0;
  }

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

  @media (max-width: 991.98px) {
    .sidebar {
      transform: translateX(-260px);
      opacity: 0;
    }
    .sidebar.show {
      transform: translateX(0);
      opacity: 1;
    }
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebarOverlay');
  const toggle = document.getElementById('sidebarToggle');

  if (toggle) {
    toggle.addEventListener('click', function() {
      sidebar.classList.toggle('show');
      overlay.classList.toggle('active');
      document.body.classList.toggle('no-scroll');
    });
  }

  if (overlay) {
    overlay.addEventListener('click', function() {
      sidebar.classList.remove('show');
      overlay.classList.remove('active');
      document.body.classList.remove('no-scroll');
    });
  }

  window.addEventListener('resize', function() {
    if (window.innerWidth > 992) {
      sidebar.classList.remove('show');
      overlay.classList.remove('active');
      document.body.classList.remove('no-scroll');
    }
  });
});
</script>
