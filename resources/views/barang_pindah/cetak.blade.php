<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Barang Pindah</title>
<style>
  /* ======================= GLOBAL ======================= */
  body { 
    font-family: DejaVu Sans, sans-serif; 
    font-size: 12px; 
    margin: 25px 35px;
    color: #000;
  }
  h1, h2, h3, h4, h5, h6, p { margin: 0; padding: 0; }
  table { border-collapse: collapse; width: 100%; }
  th, td { border: 1px solid #000; padding: 5px; text-align: center; }
  th { background-color: #f2f2f2; }
  .text-left { text-align: left; }

  /* ======================= HEADER ======================= */
  .header-table { width: 100%; margin-bottom: 5px; }
  .header-table td { border: none; vertical-align: middle; }
  .logo { width: 70px; }
  .kop-text { text-align: center; line-height: 1.2; }
  .kop-text h2 { font-size: 16px; font-weight: bold; }
  .kop-text p { font-size: 12px; margin-top: 2px; }
  hr { border: 1px solid #000; margin-top: 5px; margin-bottom: 15px; }

  /* ======================= INFO SECTION ======================= */
  .info { font-size: 12px; margin-bottom: 6px; }
  .info p { text-align: left; margin: 2px 0; }

  /* ======================= TABLE ======================= */
  table.data { margin-top: 5px; font-size: 12px; }
  table.data td, table.data th { padding: 4px; }
  table.data th { font-weight: bold; }
</style>
</head>
<body>

{{-- ================= HEADER LEMBAGA ================= --}}
<table class="header-table">
  <tr>
    <td style="width:80px;">
      <img src="{{ public_path('logo-uin.png') }}" alt="Logo UIN" class="logo">
    </td>
    <td class="kop-text">
      <h2>UNIVERSITAS ISLAM NEGERI RADEN FATAH PALEMBANG</h2>
      <p><strong>Fakultas Sains dan Teknologi</strong></p>
      <p>Jalan Prof. K.H. Zainal Abidin Fikri Km. 3, RW.5, 5 Ulu, Kecamatan Seberang Ulu I, Kota Palembang, Sumatera Selatan 30267</p>
    </td>
  </tr>
</table>

<hr>

{{-- ================= JUDUL ================= --}}
<h3 style="text-align:center; margin-bottom:10px;"><u>LAPORAN DATA BARANG PINDAH</u></h3>

{{-- ================= INFO ================= --}}
<div class="info">
  <p>Tanggal Cetak : {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
</div>

{{-- ================= TABEL DATA ================= --}}
<table class="data">
  <thead>
    <tr>
      <th>No</th>
      <th>Kode Barang</th>
      <th>Nama Barang</th>
      <th>Lokasi Asal</th>
      <th>Lokasi Tujuan</th>
      <th>Tanggal Pindah</th>
    </tr>
  </thead>
  <tbody>
    @forelse($barangPindah as $p)
    <tr>
      <td>{{ $loop->iteration }}</td>
      <td>{{ $p->barang->kode_barang }}</td>
      <td class="text-left">{{ $p->barang->nama_barang }}</td>
      <td>
        @if($p->asal)
          {{ $p->asal->gedung->fakultas->kode_fakultas ?? '-' }}/{{ $p->asal->gedung->kode_gedung ?? '-' }}/{{ $p->asal->nama_ruang ?? '-' }}
        @else
          <span class="text-muted">-</span>
        @endif
      </td>
      <td>
        @if($p->tujuan)
          {{ $p->tujuan->gedung->fakultas->kode_fakultas ?? '-' }}/{{ $p->tujuan->gedung->kode_gedung ?? '-' }}/{{ $p->tujuan->nama_ruang ?? '-' }}
        @else
          <span class="text-muted">-</span>
        @endif
      </td>
      <td>{{ \Carbon\Carbon::parse($p->tanggal_pindah)->translatedFormat('d F Y') }}</td>
    </tr>
    @empty
    <tr>
      <td colspan="6">Tidak ada data barang pindah.</td>
    </tr>
    @endforelse
  </tbody>
</table>

</body>
</html>
