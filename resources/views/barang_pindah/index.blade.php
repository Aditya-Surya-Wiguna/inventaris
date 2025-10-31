@extends('layouts.main')
@section('title', 'Barang Pindah')

@section('content')
<h4 class="mb-4">🚚 Data Barang Pindah</h4>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

{{-- 🔍 FORM FILTER --}}
<div class="card shadow-sm mb-3">
  <div class="card-body">
    <form method="GET" action="{{ route('barang-pindah.index') }}" class="row g-3 align-items-end">
      <div class="col-md-3">
        <label class="form-label mb-1">Tanggal Awal</label>
        <input type="date" name="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
      </div>
      <div class="col-md-3">
        <label class="form-label mb-1">Tanggal Akhir</label>
        <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
      </div>
      <div class="col-md-3">
        <label class="form-label mb-1">Nama Barang</label>
        <input type="text" name="nama_barang" class="form-control" placeholder="Cari barang..." value="{{ request('nama_barang') }}">
      </div>
      <div class="col-md-3">
        <label class="form-label mb-1">Ruang Asal</label>
        <input type="text" name="ruang_asal" class="form-control" placeholder="Cari ruang asal..." value="{{ request('ruang_asal') }}">
      </div>
      <div class="col-md-3">
        <label class="form-label mb-1">Ruang Tujuan</label>
        <input type="text" name="ruang_tujuan" class="form-control" placeholder="Cari ruang tujuan..." value="{{ request('ruang_tujuan') }}">
      </div>
      <div class="col-md-3 d-flex align-items-end">
        <button type="submit" class="btn btn-success w-100">
          <i class="bi bi-search"></i> Filter
        </button>
      </div>
      <div class="col-md-3 d-flex align-items-end">
        <a href="{{ route('barang-pindah.index') }}" class="btn btn-secondary w-100">
          <i class="bi bi-arrow-repeat"></i> Reset
        </a>
      </div>
      <div class="col-md-3 d-flex align-items-end">
        <a href="{{ route('barang-pindah.cetak', request()->all()) }}" target="_blank" class="btn btn-danger w-100">
          <i class="bi bi-file-earmark-pdf"></i> Cetak PDF
        </a>
      </div>
    </form>
  </div>
</div>

{{-- 🔹 Tombol Tambah --}}
<div class="d-flex justify-content-between mb-3">
  <a href="{{ route('barang-pindah.create') }}" class="btn btn-primary">
    <i class="bi bi-plus"></i> Tambah Data Pindah
  </a>
</div>

{{-- 📋 TABEL DATA --}}
<div class="card shadow-sm">
  <div class="card-body table-responsive">
    <table class="table table-bordered align-middle">
      <thead class="table-info text-center">
        <tr>
          <th>No</th>
          <th>Kode Barang</th>
          <th>Nama Barang</th>
          <th>Lokasi Asal</th>
          <th>Lokasi Tujuan</th>
          <th>Tanggal Pindah</th>
          <th>Surat</th>
        </tr>
      </thead>
      <tbody>
        @forelse($barangPindah as $p)
        <tr>
          <td class="text-center">{{ $loop->iteration }}</td>
          <td>{{ $p->barang->kode_barang }}</td>
          <td>{{ $p->barang->nama_barang }}</td>
          <td>
            @if($p->asal)
              {{ $p->asal->gedung->fakultas->kode_fakultas }}/{{ $p->asal->gedung->kode_gedung }}/{{ $p->asal->nama_ruang }}
            @else
              <span class="text-muted">-</span>
            @endif
          </td>
          <td>
            @if($p->tujuan)
              {{ $p->tujuan->gedung->fakultas->kode_fakultas }}/{{ $p->tujuan->gedung->kode_gedung }}/{{ $p->tujuan->nama_ruang }}
            @else
              <span class="text-muted">-</span>
            @endif
          </td>
          <td class="text-center">{{ \Carbon\Carbon::parse($p->tanggal_pindah)->translatedFormat('d F Y') }}</td>
          <td class="text-center">
            @if($p->file_surat)
              <a href="{{ asset('storage/'.$p->file_surat) }}" target="_blank" class="btn btn-sm btn-outline-dark">
                <i class="bi bi-file-earmark-text"></i> Lihat
              </a>
            @else
              <span class="text-muted">-</span>
            @endif
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center text-muted">Belum ada data pemindahan barang.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
