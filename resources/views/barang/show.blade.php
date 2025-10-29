@extends('layouts.main')
@section('title', 'Detail Barang')

@section('content')
<h4 class="mb-4">👁️ Detail Barang</h4>

<div class="card shadow-sm p-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">{{ $barang->nama_barang }}</h5>
    <span class="text-muted small">Kode: {{ $barang->kode_barang }}</span>
  </div>

  <div class="row g-3">
    <div class="col-md-6">
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
      @if($barang->nomor_bmn)
        <p><strong>Nomor BMN:</strong> {{ $barang->nomor_bmn }}</p>
      @endif
      <p><strong>Tanggal Masuk:</strong>
        {{ $barang->tanggal_masuk ? \Carbon\Carbon::parse($barang->tanggal_masuk)->format('d M Y') : '-' }}
      </p>
    </div>

    <div class="col-md-6">
      {{-- 📍 Lokasi tampil ringkas --}}
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

      {{-- 🧾 Barcode barang (pakai milon/barcode) --}}
      <div class="mt-3 text-center">
        <label class="fw-bold d-block mb-2">Kode QR Barang:</label>
        <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG(route('barang.show', $barang->id_barang), 'QRCODE') }}"
             alt="QR Code" width="140" height="140" class="border rounded p-2 shadow-sm">
        <p class="mt-2 fw-bold">{{ $barang->kode_barang }}</p>
        <p class="text-muted small mb-0">
          {{ $barang->tanggal_masuk ? \Carbon\Carbon::parse($barang->tanggal_masuk)->format('Y') : 'N/A' }}
        </p>
      </div>
    </div>
  </div>

  {{-- 📸 Foto Barang --}}
  @if($barang->foto_barang)
  <div class="mt-4">
    <label class="fw-bold d-block mb-2">Foto Barang:</label>
    <img src="{{ asset('storage/'.$barang->foto_barang) }}" width="220"
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

  <div class="mt-4 d-flex gap-2">
    <a href="{{ route('barang.cetak.barcode', ['search' => $barang->kode_barang]) }}" class="btn btn-outline-primary">
      <i class="bi bi-upc-scan"></i> Cetak Barcode Ini
    </a>
    <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
  </div>
</div>

<!-- Modal Foto Barang -->
@if($barang->foto_barang)
<div class="modal fade" id="fotoModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <img src="{{ asset('storage/'.$barang->foto_barang) }}" class="img-fluid rounded">
  </div>
</div>
@endif

<hr class="my-4">
<h5>🕓 Riwayat Perubahan Barang</h5>

{{-- 📛 RIWAYAT KERUSAKAN --}}
@if($barang->riwayatRusak->count() > 0)
  <h6 class="mt-3 text-danger">⚠️ Riwayat Kerusakan</h6>
  <table class="table table-bordered table-sm align-middle">
    <thead class="table-danger text-center">
      <tr>
        <th>Tanggal</th>
        <th>Kondisi Awal</th>
        <th>Kondisi Baru</th>
        <th>Foto Bukti</th>
      </tr>
    </thead>
    <tbody>
      @foreach($barang->riwayatRusak as $r)
      <tr class="text-center">
        <td>{{ \Carbon\Carbon::parse($r->tanggal_catat)->translatedFormat('d F Y') }}</td>
        <td>{{ $r->kondisi_awal }}</td>
        <td>{{ $r->kondisi_baru }}</td>
        <td>
          @if($r->foto_bukti)
            <a href="{{ asset('storage/'.$r->foto_bukti) }}" target="_blank">
              <img src="{{ asset('storage/'.$r->foto_bukti) }}" width="70" class="rounded shadow-sm">
            </a>
          @else
            <span class="text-muted">-</span>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
@endif

{{-- 🚚 RIWAYAT PEMINDAHAN --}}
@if($barang->riwayatPindah->count() > 0)
  <h6 class="mt-4 text-primary">🚚 Riwayat Pemindahan</h6>
  <table class="table table-bordered table-sm align-middle">
    <thead class="table-info text-center">
      <tr>
        <th>Tanggal</th>
        <th>Lokasi Asal</th>
        <th>Lokasi Tujuan</th>
        <th>Surat</th>
      </tr>
    </thead>
    <tbody>
      @foreach($barang->riwayatPindah as $p)
      <tr class="text-center">
        <td>{{ \Carbon\Carbon::parse($p->tanggal_pindah)->translatedFormat('d F Y') }}</td>
        <td>
          {{ $p->asal->gedung->fakultas->kode_fakultas ?? '-' }}/{{ $p->asal->gedung->kode_gedung ?? '-' }}/{{ $p->asal->nama_ruang ?? '-' }}
        </td>
        <td>
          {{ $p->tujuan->gedung->fakultas->kode_fakultas ?? '-' }}/{{ $p->tujuan->gedung->kode_gedung ?? '-' }}/{{ $p->tujuan->nama_ruang ?? '-' }}
        </td>
        <td>
          @if($p->file_surat)
            <a href="{{ asset('storage/'.$p->file_surat) }}" target="_blank" class="btn btn-sm btn-outline-dark">
              <i class="bi bi-file-earmark-text"></i> Lihat
            </a>
          @else
            <span class="text-muted">-</span>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
@endif

@if($barang->riwayatRusak->count() == 0 && $barang->riwayatPindah->count() == 0)
  <p class="text-muted mt-3">Belum ada riwayat perubahan untuk barang ini.</p>
@endif
@endsection
