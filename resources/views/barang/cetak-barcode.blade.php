<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Cetak Barcode Barang</title>
  <style>
    @page {
        size: A4 portrait;
        margin: 8px;
    }

    body {
        font-family: sans-serif;
        font-size: 11px;
        margin: 0;
        padding: 0;
    }

    h3 {
        text-align: center;
        margin: 8px 0 10px 0;
        font-weight: bold;
    }
    .barcode-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;
        align-items: flex-start;
        gap: 3px;
    }
    .item {
        text-align: center;
        border: 1px solid #ccc;
        border-radius: 3px;
        padding: 3px 4px;
        margin: 2px;
        box-sizing: border-box;
        display: inline-block;
        vertical-align: top;
    }

    img {
        display: block;
        margin: 0 auto;
    }

    .kode {
        font-weight: bold;
        font-size: 10.5px;
        margin-top: 2px;
        color: #111;
    }

    .nama-barang {
        font-size: 9.5px;
        color: #555;
        font-style: italic;
        margin-top: 1px;
    }
    .form-ukuran {
        text-align: center;
        margin-bottom: 6px;
    }

    .form-ukuran select {
        font-size: 13px;
        padding: 3px 6px;
    }

    @media print {
        .form-ukuran {
            display: none !important;
        }
    }
  </style>
</head>
<body>

@php
  use Carbon\Carbon;
  $dns = new \Milon\Barcode\DNS2D();

  // Tentukan ukuran QR & jumlah kolom
  $ukuran = $ukuran_qr ?? (request('ukuran_qr') ?? 'sedang');
  switch ($ukuran) {
      case 'kecil':
          $lebar = $tinggi = 65;
          $kolom = 6;
          break;
      case 'besar':
          $lebar = $tinggi = 125;
          $kolom = 4;
          break;
      default:
          $lebar = $tinggi = 95;
          $kolom = 5;
          break;
  }

  // Lebar item otomatis sesuai kolom (kurangi sedikit agar rapi)
  $lebarItem = (100 / $kolom) - 0.8;
@endphp

{{-- Pilihan ukuran QR (hanya tampil di browser, tidak di PDF) --}}
@if(empty($pdf))
<div class="form-ukuran no-print">
  <form method="GET" action="">
      <label for="ukuran_qr"><strong>Pilih Ukuran QR:</strong></label>
      <select name="ukuran_qr" id="ukuran_qr" onchange="this.form.submit()">
          <option value="kecil" {{ $ukuran=='kecil' ? 'selected' : '' }}>Kecil (6/baris)</option>
          <option value="sedang" {{ $ukuran=='sedang' ? 'selected' : '' }}>Sedang (5/baris)</option>
          <option value="besar" {{ $ukuran=='besar' ? 'selected' : '' }}>Besar (4/baris)</option>
      </select>
  </form>
</div>
@endif

<h3>Daftar Barcode Barang</h3>

<div class="barcode-container">
  @foreach($barang as $b)
    <div class="item" style="width: {{ $lebarItem }}%;">
        <img 
            src="data:image/png;base64,{{ $dns->getBarcodePNG(route('barang.show', $b->id_barang), 'QRCODE') }}" 
            alt="QR Code"
            width="{{ $lebar }}"
            height="{{ $tinggi }}"
        >
        <div class="kode">
            {{ $b->kode_barang }} - {{ $b->tanggal_masuk ? Carbon::parse($b->tanggal_masuk)->format('Y') : 'N/A' }}
        </div>
        <div class="nama-barang">
            {{ $b->nama_barang }}
        </div>
    </div>
  @endforeach
</div>

</body>
</html>
