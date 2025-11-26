@extends('layouts.main')
@section('title', 'Detail Barang')

@section('content')
<h5 class="mb-3 fw-semibold"><i class="bi bi-eye me-2"></i> Detail Barang</h5>

<style>
  .detail-header {
    background: linear-gradient(90deg, #0c2a61ff, #5bc0ff);
    color: #fff;
    border-radius: 14px;
    padding: 1.2rem 1.6rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
  }
  .detail-header h5 { margin: 0; font-weight: 700; font-size: 1.2rem; }

  .detail-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    padding: 1.6rem;
    margin-top: 1rem;
  }

  .detail-grid {
    display: grid;
    grid-template-columns: 160px 20px auto;
    row-gap: 8px;
    align-items: center;
  }
  .detail-label { font-weight: 600; color: #333; font-size: 0.9rem; }
  .detail-separator { text-align: center; color: #aaa; }
  .detail-value { color: #555; font-size: 0.9rem; }

  .media-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1rem;
    margin-top: 1.2rem;
  }
  .media-box {
    background: #f9fbff;
    border: 1px solid #e6ecf7;
    border-radius: 12px;
    padding: 1rem;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
  }
  .media-box img {
    border-radius: 8px;
    transition: 0.3s;
    cursor: zoom-in;
  }
  .media-box img:hover { transform: scale(1.05); }
  .media-box label {
    font-weight: 600;
    color: #004aad;
    display: block;
    margin-bottom: 0.4rem;
  }

  .detail-divider {
    border-top: 2px dashed #e6e9f0;
    margin: 1.5rem 0;
  }

  .table-custom th {
    background-color: #f5f8ff;
    color: #0b3675ff;
    font-size: 0.82rem;
    text-transform: uppercase;
  }
  .table-custom td {
    font-size: 0.8rem;
    color: #444;
  }
</style>

{{--  HEADER --}}
<div class="detail-header animate__animated animate__fadeInDown">
  <h5 class="mb-0">{{ $barang->nama_barang }}</h5>
  <span class="kode">Kode: {{ $barang->kode_barang }}</span>
</div>

{{-- DETAIL BARANG --}}
<div class="detail-card animate__animated animate__fadeInUp">
  <div class="detail-grid">
    <div class="detail-label">Merek / Tipe</div><div class="detail-separator">:</div>
    <div class="detail-value">{{ $barang->merek_tipe ?? '-' }}</div>

    <div class="detail-label">Kondisi</div><div class="detail-separator">:</div>
    <div class="detail-value">
      @if($barang->kondisi == 'B')
        <span class="badge bg-success">Baik</span>
      @elseif($barang->kondisi == 'RR')
        <span class="badge bg-warning text-dark">Rusak Ringan</span>
      @else
        <span class="badge bg-danger">Rusak Berat</span>
      @endif
    </div>

    <div class="detail-label">Jumlah</div><div class="detail-separator">:</div>
    <div class="detail-value">{{ $barang->jumlah }}</div>

    @if($barang->nomor_bmn)
      <div class="detail-label">Nomor BMN</div><div class="detail-separator">:</div>
      <div class="detail-value">{{ $barang->nomor_bmn }}</div>
    @endif

    <div class="detail-label">Tanggal Masuk</div><div class="detail-separator">:</div>
    <div class="detail-value">
      {{ $barang->tanggal_masuk ? \Carbon\Carbon::parse($barang->tanggal_masuk)->translatedFormat('d F Y') : '-' }}
    </div>

    <div class="detail-label">Lokasi Barang</div><div class="detail-separator">:</div>
    <div class="detail-value">
      <small class="text-muted">
        @if ($barang->ruang)
          {{ $barang->ruang->gedung->fakultas->kode_fakultas ?? '-' }}/
          {{ $barang->ruang->gedung->kode_gedung ?? '-' }}/
          {{ $barang->ruang->nama_ruang ?? '-' }}
        @else
          -
        @endif
      </small>
    </div>
  </div>

  <div class="detail-divider"></div>

  {{-- FOTO, SURAT, BARCODE (3 kolom sejajar) --}}
  <div class="media-row">
    {{--  FOTO BARANG --}}
    <div class="media-box">
      <label>Foto Barang</label>
      @if($barang->foto_barang)
        <img src="{{ asset('storage/'.$barang->foto_barang) }}"
             data-src="{{ asset('storage/'.$barang->foto_barang) }}"
             width="200"
             class="shadow-sm preview-img">
      @else
        <p class="text-muted small mb-0">📸 Belum ada foto</p>
      @endif
    </div>

    {{-- SURAT BARANG --}}
    <div class="media-box">
      <label>Surat Barang</label>
      @if($barang->foto_surat)
        <a href="{{ asset('storage/'.$barang->foto_surat) }}" target="_blank" class="btn btn-outline-primary btn-sm">
          <i class="bi bi-file-earmark-text"></i> Lihat Surat
        </a>
      @else
        <p class="text-muted small mb-0">Tidak ada surat</p>
      @endif
    </div>

    {{-- BARCODE --}}
    <div class="media-box">
      <label>Kode QR Barang</label>
      <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG(route('barang.show', $barang->id_barang), 'QRCODE') }}"
           alt="QR Code" width="130" height="130" class="border bg-white p-2 shadow-sm">
      <p class="mt-2 fw-bold mb-0">{{ $barang->kode_barang }}</p>
      <small class="text-muted">
        {{ $barang->tanggal_masuk ? \Carbon\Carbon::parse($barang->tanggal_masuk)->translatedFormat('Y') : 'N/A' }}
      </small>
    </div>
  </div>

  <div class="mt-4 d-flex gap-2 justify-content-end">
    <a href="{{ route('barang.cetak.barcode', ['search' => $barang->kode_barang]) }}" class="btn btn-outline-primary">
      <i class="bi bi-upc-scan"></i> Cetak Barcode
    </a>
    <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
  </div>
</div>

<hr class="my-4">

{{-- RIWAYAT --}}
<h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-1"></i> Riwayat Perubahan Barang</h6>

@if($barang->riwayatRusak->count() > 0)
  <h6 class="text-danger mt-2">⚠️ Riwayat Kerusakan</h6>
  <table class="table table-bordered table-sm table-custom align-middle mt-2">
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
            <img src="{{ asset('storage/'.$r->foto_bukti) }}"
                 data-src="{{ asset('storage/'.$r->foto_bukti) }}"
                 width="65" class="rounded shadow-sm preview-img">
          @else
            <span class="text-muted">-</span>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
@endif

@if($barang->riwayatPindah->count() > 0)
  <h6 class="text-primary mt-4">🚚 Riwayat Pemindahan</h6>
  <table class="table table-bordered table-sm table-custom align-middle mt-2">
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
        <td>{{ $p->asal->gedung->fakultas->kode_fakultas ?? '-' }}/{{ $p->asal->gedung->kode_gedung ?? '-' }}/{{ $p->asal->nama_ruang ?? '-' }}</td>
        <td>{{ $p->tujuan->gedung->fakultas->kode_fakultas ?? '-' }}/{{ $p->tujuan->gedung->kode_gedung ?? '-' }}/{{ $p->tujuan->nama_ruang ?? '-' }}</td>
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
  <p class="text-muted mt-3">🧾 Belum ada riwayat perubahan untuk barang ini.</p>
@endif
@endsection
