<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top px-3 py-2">
  <div class="container-fluid">
    {{-- BRAND / LOGO --}}
    <span class="navbar-brand fw-bold text-primary">
      <i class="bi bi-box-seam-fill me-1 text-primary"></i> Gudang UIN Raden Fatah
    </span>

    {{-- RIGHT SIDE --}}
    <div class="d-flex align-items-center ms-auto gap-3">

      {{-- 🔔 NOTIFIKASI DROPDOWN --}}
      <div class="dropdown">
        <button class="btn btn-light position-relative" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-bell-fill fs-5 text-secondary"></i>
          @if($notifikasiCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              {{ $notifikasiCount }}
            </span>
          @endif
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="width: 280px;">
          <li class="dropdown-header fw-semibold">🔔 Notifikasi Terbaru</li>
          @forelse($notifikasi as $n)
            <li>
              <a class="dropdown-item small" href="{{ $n['link'] }}">
                <i class="bi {{ $n['icon'] }} text-primary me-2"></i> {{ $n['pesan'] }}
                <div class="text-muted small">{{ $n['waktu'] }}</div>
              </a>
            </li>
          @empty
            <li><span class="dropdown-item text-muted small">Tidak ada notifikasi baru</span></li>
          @endforelse
        </ul>
      </div>

      {{-- 👤 ADMIN DROPDOWN --}}
      <div class="dropdown">
        <button class="btn btn-outline-primary d-flex align-items-center px-2 py-1 rounded-pill" data-bs-toggle="dropdown">
          <i class="bi bi-person-circle fs-5 me-2"></i>
          <span class="fw-semibold small">Admin</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
          <li>
            <a class="dropdown-item" href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="bi bi-box-arrow-right me-2 text-danger"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="GET" class="d-none"></form>
          </li>
        </ul>
      </div>

    </div>
  </div>
</nav>
