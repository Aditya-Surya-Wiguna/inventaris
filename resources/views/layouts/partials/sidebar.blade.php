<nav class="sidebar d-flex flex-column p-3">
  <h4 class="mb-4 text-center"><i class="bi bi-box-seam"></i> Inventaris</h4>

  <ul class="nav nav-pills flex-column">
    <li class="nav-item">
      <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
        <i class="bi bi-speedometer2 me-2"></i> Dashboard
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ route('barang.index') }}" class="nav-link {{ request()->is('barang*') ? 'active' : '' }}">
        <i class="bi bi-box me-2"></i> Data Barang
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ route('lokasi.index') }}" class="nav-link {{ request()->is('lokasi*') ? 'active' : '' }}">
        <i class="bi bi-geo-alt me-2"></i> Lokasi Barang
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ route('barang-pindah.index') }}" class="nav-link {{ request()->is('barang-pindah*') ? 'active' : '' }}">
        <i class="bi bi-arrow-left-right me-2"></i> Barang Pindah
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ route('barang-rusak.index') }}" class="nav-link {{ request()->is('barang-rusak*') ? 'active' : '' }}">
        <i class="bi bi-exclamation-triangle me-2"></i> Barang Rusak
      </a>
    </li>
  </ul>

  <hr class="text-white">
  <div class="mt-auto text-center small">
    <p class="mb-1">© {{ date('Y') }} UIN Raden Fatah</p>
    <p class="text-white-50">Admin Gudang</p>
  </div>
</nav>
