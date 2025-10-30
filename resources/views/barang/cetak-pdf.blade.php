<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>{{ $judul }}</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #444; padding: 6px; text-align: left; }
    th { background-color: #efefef; }
    h2, p { text-align: center; margin: 4px 0; }
  </style>
</head>
<body>
  <h2>{{ $judul }}</h2>
  <p><strong>Tanggal Cetak:</strong> {{ $tanggal }}</p>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Kode Barang</th>
        <th>Nama Barang</th>
        <th>Merek/Tipe</th>
        <th>Kondisi</th>
        <th>Lokasi (Ruang)</th>
        <th>Jumlah</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($barang as $b)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $b->kode_barang }}</td>
          <td>{{ $b->nama_barang }}</td>
          <td>{{ $b->merek_tipe ?? '-' }}</td>
          <td>
            @switch($b->kondisi)
              @case('B') Baik @break
              @case('RR') Rusak Ringan @break
              @case('RB') Rusak Berat @break
            @endswitch
          </td>
          <td>
            {{ optional($b->ruang->gedung->fakultas)->nama_fakultas ?? '-' }}
            / {{ optional($b->ruang->gedung)->kode_gedung ?? '-' }}
            / {{ optional($b->ruang)->nama_ruang ?? '-' }}
          </td>
          <td>{{ $b->jumlah }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
