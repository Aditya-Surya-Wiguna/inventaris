@extends('layouts.main')
@section('title', 'Data Barang')

@section('content')
<h4 class="mb-4">📦 Data Barang</h4>

@if (session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<div class="d-flex justify-content-between mb-3">
  <a href="{{ route('barang.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg"></i> Tambah Barang
  </a>

  {{-- Tombol Cetak Barcode --}}
  <a href="{{ route('barang.cetak.barcode', request()->query()) }}" class="btn btn-success">
    <i class="bi bi-upc-scan"></i> Cetak Barcode
  </a>
</div>


<div class="card mb-3 shadow-sm">
  <div class="card-body">
    <form method="GET" action="{{ route('barang.index') }}" class="row g-2 align-items-end">
      <div class="col-md-2">
        <label class="form-label mb-1">Fakultas</label>
        <select name="fakultas" id="fakultas" class="form-select">
          <option value="">Semua</option>
          @foreach($fakultas as $f)
            <option value="{{ $f->id_fakultas }}" {{ request('fakultas') == $f->id_fakultas ? 'selected' : '' }}>
              {{ $f->kode_fakultas }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-2">
        <label class="form-label mb-1">Gedung</label>
        <select name="gedung" id="gedung" class="form-select">
          <option value="">Semua</option>
          @foreach($gedung as $g)
            <option value="{{ $g->id_gedung }}" {{ request('gedung') == $g->id_gedung ? 'selected' : '' }}>
              {{ $g->kode_gedung }} ({{ $g->fakultas->kode_fakultas }})
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-2">
        <label class="form-label mb-1">Ruang</label>
        <select name="ruang" id="ruang" class="form-select">
          <option value="">Semua</option>
          @foreach($ruang as $r)
            <option value="{{ $r->id_ruang }}" {{ request('ruang') == $r->id_ruang ? 'selected' : '' }}>
              {{ $r->nama_ruang }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-2">
        <label class="form-label mb-1">Kondisi</label>
        <select name="kondisi" class="form-select">
          <option value="">Semua</option>
          @foreach($kondisiList as $k)
            <option value="{{ $k }}" {{ request('kondisi') == $k ? 'selected' : '' }}>
              {{ $k == 'B' ? 'Baik' : ($k == 'RR' ? 'Rusak Ringan' : 'Rusak Berat') }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-2">
        <label class="form-label mb-1">Cari Barang</label>
        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Nama / Kode">
      </div>

      <div class="col-md-2 text-end">
        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-filter"></i> Filter</button>
        <a href="{{ route('barang.index') }}" class="btn btn-outline-secondary w-100 mt-2">
          <i class="bi bi-arrow-clockwise"></i> Reset
        </a>
      </div>
    </form>
  </div>
</div>



<div class="card shadow-sm">
  <div class="card-body table-responsive">
    <table class="table table-bordered align-middle">
      <thead class="table-primary text-center">
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
          <td>{{ $b->kode_barang }}</td>
          <td>{{ $b->nama_barang }}</td>
          <td>{{ $b->merek_tipe ?? '-' }}</td>

          <td class="text-center">
            @if($b->kondisi == 'B')
              <span class="badge bg-success">Baik</span>
            @elseif($b->kondisi == 'RR')
              <span class="badge bg-warning text-dark">Rusak Ringan</span>
            @else
              <span class="badge bg-danger">Rusak Berat</span>
            @endif
          </td>

          {{-- 📍 Lokasi tampil ringkas: Fakultas/Gedung/Ruang --}}
          <td class="text-center">
            @if ($b->ruang)
              <small>
                {{ $b->ruang->gedung->fakultas->kode_fakultas ?? '-' }}/
                {{ $b->ruang->gedung->kode_gedung ?? '-' }}/
                {{ $b->ruang->nama_ruang ?? '-' }}
              </small>
            @else
              <small>-</small>
            @endif
          </td>

          <td class="text-center">{{ $b->jumlah }}</td>

          <td class="text-center">
            <a href="{{ route('barang.show', $b->id_barang) }}" class="btn btn-sm btn-info">
              <i class="bi bi-eye"></i>
            </a>
            <a href="{{ route('barang.edit', $b->id_barang) }}" class="btn btn-sm btn-warning">
              <i class="bi bi-pencil-square"></i>
            </a>
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
