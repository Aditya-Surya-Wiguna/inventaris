@extends('layouts.main')
@section('title', 'Barang Rusak')

@section('content')
<h4 class="mb-4">⚠️ Data Barang Rusak</h4>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

{{-- 🔍 FILTER --}}
<div class="card shadow-sm mb-3">
  <div class="card-body">
    <form method="GET" action="{{ route('barang-rusak.index') }}" class="row g-3 align-items-end">
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
        <label class="form-label mb-1">Kondisi Baru</label>
        <select name="kondisi_baru" class="form-select">
          <option value="">Semua</option>
          <option value="RR" {{ request('kondisi_baru') == 'RR' ? 'selected' : '' }}>Rusak Ringan</option>
          <option value="RB" {{ request('kondisi_baru') == 'RB' ? 'selected' : '' }}>Rusak Berat</option>
        </select>
      </div>
      <div class="col-md-3 d-flex align-items-end">
        <button type="submit" class="btn btn-success w-100">
          <i class="bi bi-search"></i> Filter
        </button>
      </div>
      <div class="col-md-3 d-flex align-items-end">
        <a href="{{ route('barang-rusak.index') }}" class="btn btn-secondary w-100">
          <i class="bi bi-arrow-repeat"></i> Reset
        </a>
      </div>
      <div class="col-md-3 d-flex align-items-end">
        <a href="{{ route('barang-rusak.cetak', request()->all()) }}" target="_blank" class="btn btn-danger w-100">
          <i class="bi bi-file-earmark-pdf"></i> Cetak PDF
        </a>
      </div>
    </form>
  </div>
</div>

{{-- 🔹 Tombol Tambah --}}
<div class="d-flex justify-content-between mb-3">
  <a href="{{ route('barang-rusak.create') }}" class="btn btn-primary">
    <i class="bi bi-plus"></i> Tambah Data Rusak
  </a>
</div>

{{-- 📋 Tabel Data --}}
<div class="card shadow-sm">
  <div class="card-body table-responsive">
    <table class="table table-bordered align-middle">
      <thead class="table-warning text-center">
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
              <img src="{{ asset('storage/'.$r->foto_bukti) }}" width="70" class="rounded shadow-sm" data-bs-toggle="modal" data-bs-target="#img{{ $r->id_rusak }}">
              <div class="modal fade" id="img{{ $r->id_rusak }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                  <img src="{{ asset('storage/'.$r->foto_bukti) }}" class="img-fluid rounded">
                </div>
              </div>
            @else
              <span class="text-muted">-</span>
            @endif
          </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center text-muted">Belum ada data rusak.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
