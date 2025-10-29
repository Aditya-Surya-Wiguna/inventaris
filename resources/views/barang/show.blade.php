@extends('layouts.main')
@section('title', 'Detail Barang')

@section('content')
<h4 class="mb-4">👁️ Detail Barang</h4>

<div class="card shadow-sm p-4">
  <h5 class="fw-bold mb-3">{{ $barang->nama_barang }}</h5>

  <div class="row g-3">
    <div class="col-md-6">
      <p><strong>Kode Barang:</strong> {{ $barang->kode_barang }}</p>
      <p><strong>Merek / Tipe:</strong> {{ $barang->merek_tipe ?? '-' }}</p>
      <p><strong>Kondisi:</strong>
        @if($barang->kondisi == 'B')
          <span class="badge bg-success">Baik</span>
        @elseif($barang->kondisi == 'RR')
          <span class="badge bg-warning text-dark">Rusak Ringan</span>
        @else
          <span class="badge bg-danger">Rusak Berat</span>
        @endif
      </p>
      <p><strong>Jumlah:</strong> {{ $barang->jumlah }}</p>
    </div>

    <div class="col-md-6">
      {{-- 📍 Lokasi tampil ringkas: Fakultas/Gedung/Ruang --}}
      <p><strong>Lokasi Barang:</strong><br>
        @if ($barang->ruang)
          <small>
            {{ $barang->ruang->gedung->fakultas->kode_fakultas ?? '-' }}/
            {{ $barang->ruang->gedung->kode_gedung ?? '-' }}/
            {{ $barang->ruang->nama_ruang ?? '-' }}
          </small>
        @else
          <small>-</small>
        @endif
      </p>

      @if($barang->nomor_bmn)
        <p><strong>Nomor BMN:</strong> {{ $barang->nomor_bmn }}</p>
      @endif

      <p><strong>Tanggal Masuk:</strong>
        {{ \Carbon\Carbon::parse($barang->tanggal_masuk)->format('d M Y') ?? '-' }}
      </p>
    </div>
  </div>

  {{-- 📸 Foto Barang --}}
  @if($barang->foto_barang)
  <div class="mt-3">
    <label class="fw-bold d-block mb-2">Foto Barang:</label>
    <img src="{{ asset('storage/'.$barang->foto_barang) }}" width="200"
         class="rounded shadow-sm img-thumbnail"
         style="cursor: zoom-in"
         data-bs-toggle="modal"
         data-bs-target="#fotoModal">
  </div>
  @endif

  {{-- 📄 Foto Surat --}}
  @if($barang->foto_surat)
  <div class="mt-3">
    <label class="fw-bold d-block mb-2">Foto / Surat Barang:</label>
    <a href="{{ asset('storage/'.$barang->foto_surat) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-file-earmark-arrow-down"></i> Lihat Surat
    </a>
  </div>
  @endif

  <div class="mt-4">
    <a href="{{ route('barang.barcode', $barang->id_barang) }}" class="btn btn-outline-primary">
      <i class="bi bi-upc"></i> Lihat Barcode
    </a>
    <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
  </div>
</div>

<!-- Modal foto -->
@if($barang->foto_barang)
<div class="modal fade" id="fotoModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <img src="{{ asset('storage/'.$barang->foto_barang) }}" class="img-fluid rounded">
  </div>
</div>
@endif
@endsection
