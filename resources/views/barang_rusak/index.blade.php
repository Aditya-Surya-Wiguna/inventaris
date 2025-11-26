@extends('layouts.main')
@section('title', 'Barang Rusak')

@section('content')
<h4 class="mb-4 fw-semibold text-primary animate__animated animate__fadeInDown">
  ⚠️ Data Barang Rusak
</h4>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<style>

  .card-modern {
    border: none;
    border-radius: 14px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
    transition: all 0.25s ease;
  }
  .card-modern:hover { box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08); }

  .form-label {
    font-weight: 600;
    font-size: 0.8rem;
    color: #004aad;
  }

  .form-control, .form-select {
    font-size: 0.85rem;
    padding: 6px 10px;
    border-radius: 8px;
  }

  .btn-filter {
    font-size: 0.83rem;
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 500;
  }

  .btn-filter i { font-size: 0.9rem; margin-right: 3px; }

  .table th {
    background: linear-gradient(90deg, #bad8ffff, #ffffff);
    color: #000;
    font-weight: 700;
    font-size: 0.85rem;
  }

  .table td {
    font-size: 0.82rem;
    vertical-align: middle;
  }

  .table-hover tbody tr:hover {
    background-color: #fff8e8 !important;
    transition: 0.2s;
  }

  .filter-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    align-items: end;
  }

  .filter-grid .actions {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
  }

  @media (max-width: 1200px) {
    .filter-grid { grid-template-columns: repeat(3, 1fr); }
  }

  @media (max-width: 992px) {
    .filter-grid { grid-template-columns: repeat(2, 1fr); }
  }

  @media (max-width: 576px) {
    .filter-grid { grid-template-columns: 1fr; }
    .filter-grid .actions { justify-content: flex-start; }
  }

  .filter-toggle {
    background: linear-gradient(90deg, #0d6efd, #004aad);
    color: #fff;
    font-weight: 600;
    border: none;
    border-radius: 8px;
    padding: 6px 12px;
    margin-bottom: 10px;
    transition: 0.3s ease;
  }
  .filter-toggle:hover {
    opacity: 0.9;
    transform: translateY(-1px);
  }
</style>

{{-- FILTER --}}
<div class="card card-modern mb-4 animate__animated animate__fadeInUp">
  <div class="card-body pb-3 pt-3">

    {{-- Tombol Collapse (Muncul di layar kecil) --}}
    <button class="filter-toggle d-lg-none w-100" type="button" data-bs-toggle="collapse" data-bs-target="#filterBox">
      <i class="bi bi-funnel-fill me-1"></i> Tampilkan / Sembunyikan Filter
    </button>

    <div id="filterBox" class="collapse show">
      <form method="GET" action="{{ route('barang-rusak.index') }}" class="filter-grid">
        
        {{-- Tanggal Awal --}}
        <div>
          <label class="form-label mb-1">Tanggal Awal</label>
          <input type="date" name="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
        </div>

        {{-- Tanggal Akhir --}}
        <div>
          <label class="form-label mb-1">Tanggal Akhir</label>
          <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
        </div>

        {{--  Nama Barang --}}
        <div>
          <label class="form-label mb-1">Barang</label>
          <input type="text" name="nama_barang" class="form-control" placeholder="Cari barang..." value="{{ request('nama_barang') }}">
        </div>

        {{--  Kondisi --}}
        <div>
          <label class="form-label mb-1">Kondisi</label>
          <select name="kondisi_baru" class="form-select">
            <option value="">Semua</option>
            <option value="RR" {{ request('kondisi_baru') == 'RR' ? 'selected' : '' }}>Ringan</option>
            <option value="RB" {{ request('kondisi_baru') == 'RB' ? 'selected' : '' }}>Berat</option>
          </select>
        </div>

        {{--  Tombol Aksi --}}
        <div class="actions">
          <button type="submit" class="btn btn-success btn-filter shadow-sm">
            <i class="bi bi-search"></i> Filter
          </button>
          <a href="{{ route('barang-rusak.index') }}" class="btn btn-outline-secondary btn-filter shadow-sm">
            <i class="bi bi-arrow-repeat"></i> Reset
          </a>
          <a href="{{ route('barang-rusak.cetak', request()->all()) }}" target="_blank" class="btn btn-danger btn-filter shadow-sm">
            <i class="bi bi-file-earmark-pdf"></i> Cetak
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Tambah --}}
<div class="d-flex justify-content-between mb-3 animate__animated animate__fadeInUp">
  <a href="{{ route('barang-rusak.create') }}" class="btn btn-primary shadow-sm">
    <i class="bi bi-plus-lg me-1"></i> Tambah Data Rusak
  </a>
</div>

{{-- Tabel Data --}}
<div class="card card-modern animate__animated animate__fadeInUp">
  <div class="card-body table-responsive">
    <table class="table table-bordered table-hover align-middle mb-0">
      <thead class="text-center">
        <tr>
          <th>No</th>
          <th>Nama Barang</th>
          <th>Kode Barang</th>
          <th>Kondisi Awal</th>
          <th>Kondisi Baru</th>
          <th>Tanggal</th>
          <th>Foto Bukti</th>
        </tr>
      </thead>
      <tbody>
        @forelse($barangRusak as $r)
        <tr>
          <td class="text-center">{{ $loop->iteration }}</td>
          <td>{{ $r->barang->nama_barang }}</td>
          <td>{{ $r->barang->kode_barang }}</td>
          <td class="text-center">{{ $r->kondisi_awal }}</td>
          <td class="text-center">{{ $r->kondisi_baru }}</td>
          <td class="text-center">{{ \Carbon\Carbon::parse($r->tanggal_catat)->translatedFormat('d F Y') }}</td>
          <td class="text-center">
            @if($r->foto_bukti)
              <img src="{{ asset('storage/'.$r->foto_bukti) }}"
                   data-src="{{ asset('storage/'.$r->foto_bukti) }}"
                   width="70"
                   class="rounded shadow-sm preview-img">
            @else
              <span class="text-muted">-</span>
            @endif
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center text-muted">Belum ada data rusak.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Script Auto-collapse Filter di HP --}}
@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const filterBox = document.getElementById('filterBox');
    const filterForm = filterBox.querySelector('form');
    filterForm.addEventListener('submit', function() {
      if (window.innerWidth < 768) {
        const collapse = bootstrap.Collapse.getInstance(filterBox);
        collapse.hide(); // otomatis menutup filter di layar kecil
      }
    });
  });
</script>
@endpush
@endsection
