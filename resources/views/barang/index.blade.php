@extends('layouts.main')
@section('title', 'Data Barang')

@section('content')
<h4 class="mb-4 fw-semibold text-primary animate__animated animate__fadeInDown">
  📦 DATA BARANG
</h4>

@if (session('success'))
  <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<style>
  /* ===== Tampilan Modern Data Barang ===== */
  .btn {
    border-radius: 8px;
    font-size: 0.85rem;
  }
  .btn i {
    vertical-align: middle;
  }
  .card-modern {
    border: none;
    border-radius: 14px;
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(8px);
    box-shadow: 0 4px 14px rgba(0,0,0,0.05);
    transition: all 0.25s ease;
  }
  .card-modern:hover {
    box-shadow: 0 5px 16px rgba(0,0,0,0.07);
  }
  .table th {
    background: linear-gradient(90deg, #e7f0ff, #ffffff);
    color: #000000ff;
    font-weight: 800;
    font-size: 0.82rem;
    text-transform: uppercase;
  }
  .table td {
    font-size: 0.8rem;
    vertical-align: middle;
  }
  .table-hover tbody tr:hover {
    background-color: #f1f6ff !important;
    transition: 0.2s;
  }
  .badge {
    font-size: 0.72rem;
    padding: 4px 6px;
    font-weight: 600;
  }
  .filter-card label {
    font-weight: 500;
    color: #000000ff;
    font-size: 0.8rem;
  }
  .ukuran-select {
    border-radius: 6px;
    font-size: 0.8rem;
    padding: 3px 6px;
  }
</style>

{{-- TOMBOL AKSI UTAMA --}}
<div class="d-flex justify-content-between align-items-center flex-wrap mb-3 gap-2">
  <div class="d-flex gap-2 flex-wrap">
    <a href="{{ route('barang.create') }}" class="btn btn-primary shadow-sm">
      <i class="bi bi-plus-lg me-1"></i> Tambah Barang
    </a>
    <a href="{{ route('barang.createMultiple') }}" class="btn btn-outline-primary shadow-sm">
      <i class="bi bi-plus-square-dotted me-1"></i> Tambah Banyak
    </a>
  </div>

  <div class="d-flex gap-2 flex-wrap align-items-center">
    {{-- Pilihan ukuran QR --}}
    <form id="formBarcode" method="GET" action="{{ route('barang.cetak.barcode') }}">
      @foreach(request()->query() as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
      @endforeach
      <select name="ukuran_qr" id="ukuran_qr" class="ukuran-select me-1">
        <option value="kecil" {{ request('ukuran_qr') == 'kecil' ? 'selected' : '' }}>QR Kecil</option>
        <option value="sedang" {{ request('ukuran_qr') == 'sedang' ? 'selected' : '' }}>QR Sedang</option>
        <option value="besar" {{ request('ukuran_qr') == 'besar' ? 'selected' : '' }}>QR Besar</option>
      </select>
      <button type="submit" class="btn btn-success shadow-sm">
        <i class="bi bi-upc-scan me-1"></i> Cetak Barcode
      </button>
    </form>

    <a href="{{ route('barang.cetak.pdf', request()->query()) }}" class="btn btn-danger shadow-sm">
      <i class="bi bi-file-earmark-pdf me-1"></i> PDF
    </a>
    <a href="{{ route('barang.cetak.laporan', request()->query()) }}" class="btn btn-secondary shadow-sm">
      <i class="bi bi-file-earmark-text me-1"></i> Laporan
    </a>
  </div>
</div>

{{-- FILTER PENCARIAN --}}
<div class="card card-modern mb-4 animate__animated animate__fadeInUp filter-card">
  <div class="card-body">
    <form method="GET" action="{{ route('barang.index') }}" class="row g-2 align-items-end">
      <div class="col-md-2">
        <label class="form-label">Fakultas</label>
        <select name="fakultas" id="fakultas" class="form-select form-select-sm">
          <option value="">Semua</option>
          @foreach($fakultas as $f)
            <option value="{{ $f->id_fakultas }}" {{ request('fakultas') == $f->id_fakultas ? 'selected' : '' }}>
              {{ $f->kode_fakultas }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-2">
        <label class="form-label">Gedung</label>
        <select name="gedung" id="gedung" class="form-select form-select-sm">
          <option value="">Semua</option>
          @foreach($gedung as $g)
            <option value="{{ $g->id_gedung }}" {{ request('gedung') == $g->id_gedung ? 'selected' : '' }}>
              Gedung {{ $g->kode_gedung }} ({{ $g->fakultas->kode_fakultas }})
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-3">
        <label class="form-label">Ruang</label>
        <select name="ruang" id="ruang" class="form-select form-select-sm">
          <option value="">Semua</option>
          @foreach($ruang as $r)
            <option value="{{ $r->id_ruang }}" {{ request('ruang') == $r->id_ruang ? 'selected' : '' }}>
              {{ $r->nama_ruang }} ({{ $r->gedung->fakultas->kode_fakultas ?? '-' }} - Gedung {{ $r->gedung->kode_gedung ?? '-' }})
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-2">
        <label class="form-label">Kondisi</label>
        <select name="kondisi" class="form-select form-select-sm">
          <option value="">Semua</option>
          @foreach($kondisiList as $k)
            <option value="{{ $k }}" {{ request('kondisi') == $k ? 'selected' : '' }}>
              {{ $k == 'B' ? 'Baik' : ($k == 'RR' ? 'Rusak Ringan' : 'Rusak Berat') }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-2">
        <label class="form-label">Cari Barang</label>
        <input type="text" name="search" class="form-control form-control-sm" value="{{ request('search') }}" placeholder="Nama / Kode">
      </div>

      <div class="col-md-1 text-end">
        <button type="submit" class="btn btn-primary w-100 mb-1"><i class="bi bi-filter"></i></button>
        <a href="{{ route('barang.index') }}" class="btn btn-outline-secondary w-100"><i class="bi bi-arrow-clockwise"></i></a>
      </div>
    </form>
  </div>
</div>

{{-- TABEL DATA BARANG --}}
<div class="card card-modern shadow-sm animate__animated animate__fadeInUp">
  <div class="card-body table-responsive">
    <table class="table table-bordered table-hover align-middle mb-0">
      <thead class="text-center">
        <tr>
          <th>No</th>
          <th>Kode Barang</th>
          <th>Nama Barang</th>
          <th>Merek/Tipe</th>
          <th>Kondisi</th>
          <th>Lokasi (Ruang)</th>
          <th>Jumlah</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($barang as $b)
        <tr>
          <td class="text-center">{{ $loop->iteration }}</td>
          <td class="fw-semibold">{{ $b->kode_barang }}</td>
          <td>{{ $b->nama_barang }}</td>
          <td>{{ $b->merek_tipe ?? '-' }}</td>
          <td class="text-center">
            @if($b->kondisi == 'B')
              <span class="badge bg-success">Baik</span>
            @elseif($b->kondisi == 'RR')
              <span class="badge bg-warning text-dark">Ringan</span>
            @else
              <span class="badge bg-danger">Berat</span>
            @endif
          </td>
          <td class="text-center">
            @if ($b->ruang)
              <small>
                {{ $b->ruang->nama_ruang }} 
                ({{ $b->ruang->gedung->fakultas->kode_fakultas ?? '-' }} - Gedung {{ $b->ruang->gedung->kode_gedung ?? '-' }})
              </small>
            @else
              <small>-</small>
            @endif
          </td>
          <td class="text-center fw-semibold">{{ $b->jumlah }}</td>
          <td class="text-center">
            <a href="{{ route('barang.show', $b->id_barang) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
            <a href="{{ route('barang.edit', $b->id_barang) }}" class="btn btn-sm btn-warning text-white"><i class="bi bi-pencil-square"></i></a>
            <form action="{{ route('barang.destroy', $b->id_barang) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus barang ini?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" class="text-center text-muted">Belum ada data barang.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
