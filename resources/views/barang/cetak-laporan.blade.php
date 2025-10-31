<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Daftar Barang Ruangan</title>
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

  /* ======================= FOOTER / SIGNATURE ======================= */
  .note { font-size: 11px; margin-top: 8px; }
  .signature { width: 100%; margin-top: 18px; }
  .signature td { border: none; text-align: center; font-size: 12px; vertical-align: top; }

  .divider { margin-top: 3px; margin-bottom: 2px; }

  /* ======================= PAGE BREAK ======================= */
  .page-break { page-break-after: always; }
</style>
</head>
<body>

@foreach($barangGroup as $ruangId => $items)
@php
  $ruang = $items->first()->ruang;
  $gedung = $ruang->gedung;
  $fakultas = $gedung->fakultas;
@endphp

{{-- ================= HEADER LEMBAGA ================= --}}
<table class="header-table">
  <tr>
    <td style="width:80px;">
      <img src="{{ public_path('logo-uin.png') }}" alt="Logo UIN" class="logo">
    </td>
    <td class="kop-text">
      <h2>UNIVERSITAS ISLAM NEGERI RADEN FATAH PALEMBANG</h2>
      <p><strong>Fakultas {{ $fakultas->nama_fakultas ?? '-' }} </strong></p>
      <p>Jalan Prof.K.H.Zainal Abidin Fikri Km.3, RW.5, 5 Ulu, Kecamatan Seberang Ulu I, Kota Palembang, Sumatera Selatan 30267</p>
    </td>
  </tr>
</table>

<hr>

<h3 style="text-align:center; margin-bottom:5px;"><u>DAFTAR BARANG RUANGAN</u></h3>

{{-- ================= INFO UNIT ================= --}}
<div class="info">
  <p>Fakultas    : {{ $fakultas->nama_fakultas ?? '-' }}</p>
  <p>Nama Gedung : {{ $gedung->kode_gedung ?? '-' }}</p>
  <p>Nama Ruang  : {{ $ruang->nama_ruang ?? '-' }}</p>
</div>

{{-- ================= TABEL DATA ================= --}}
<table class="data">
  <thead>
    <tr>
      <th>No</th>
      <th>Nama Barang</th>
      <th>Merk / Type</th>
      <th>Tahun Perolehan</th>
      <th>Jumlah</th>
      <th>Nomor BMN</th>
      <th>Kondisi</th>
    </tr>
  </thead>
  <tbody>
    @foreach($items as $b)
    <tr>
      <td>{{ $loop->iteration }}</td>
      <td class="text-left">{{ $b->nama_barang }}</td>
      <td>{{ $b->merek_tipe ?? '-' }}</td>
      <td>{{ $b->tanggal_masuk ? date('Y', strtotime($b->tanggal_masuk)) : '-' }}</td>
      <td>{{ $b->jumlah }}</td>
      <td>{{ $b->nomor_bmn ?? '-' }}</td>
      <td>{{ $b->kondisi }}</td>
    </tr>
    @endforeach
  </tbody>
</table>

{{-- ================= CATATAN MELEKAT DI BAWAH TABEL ================= --}}
<p style="font-size:11px; text-align:justify; margin-top:3px; margin-bottom:0;">
  Tidak dibenarkan memindahkan barang-barang yang ada pada daftar ini tanpa sepengetahuan kepala ............. Penanggung Jawab Ruangan ini
</p>

{{-- ================= TANDA TANGAN ================= --}}
<table class="signature">
  <tr>
    <td style="width:50%;">
      Mengetahui<br>
      Dekan<br><br><br><br>
      <u>Dr. Muhammad Isnaini, M.Pd.</u><br>
      NIP.19720201200031000
    </td>
    <td style="width:50%;">
      Palembang, {{ $tanggalCetak }}<br>
      Penanggung Jawab Ruangan<br><br><br><br>
      <u>Mila Gustaharjati, S.Ag., M.Hum.</u><br>
      NIP.197008242000032004
    </td>
  </tr>
</table>

{{-- ================= CATATAN TAMBAHAN ================= --}}
<div class="note">
  <p><strong>Catatan:</strong></p>
  <ol style="margin-top:3px; padding-left:18px;">
    <li>Setiap perpindahan / penambahan barang agar dilaporkan kepada Kepala Bagian Umum.</li>
    <li>Setiap pergantian penanggung jawab ruangan agar dilaporkan kepada Kepala Bagian Umum.</li>
    <li>Setiap akhir tahun anggaran, data DBR akan dimutakhirkan sesuai keperluan.</li>
  </ol>
</div>

@if(!$loop->last)
  <div class="page-break"></div>
@endif
@endforeach

</body>
</html>
