<table>
  <tr>
    <td colspan="9" style="text-align:center; font-weight:bold; font-size:14pt;">
      DAFTAR BARANG RUANGAN
    </td>
  </tr>

  <tr><td colspan="9"></td></tr>

  <tr><td>Fakultas/Lembaga Unit</td><td colspan="8">: {{ $barang->first()->ruang->gedung->fakultas->nama_fakultas ?? '' }}</td></tr>
  <tr><td>Nama Gedung</td><td colspan="8">: {{ $barang->first()->ruang->gedung->nama_gedung ?? '' }}</td></tr>
  <tr><td>Nama Ruang</td><td colspan="8">: {{ $barang->first()->ruang->nama_ruang ?? '' }}</td></tr>

  <tr><td colspan="9"></td></tr>

  <tr style="text-align:center; font-weight:bold;">
    <td>No</td>
    <td>Nama Barang</td>
    <td>Merk/Tipe</td>
    <td>Tahun</td>
    <td>Jumlah</td>
    <td>Nomor BMN</td>
    <td>B</td>
    <td>RR</td>
    <td>RB</td>
  </tr>

  @foreach($barang as $i => $b)
    <tr>
      <td style="text-align:center;">{{ $i + 1 }}</td>
      <td>{{ $b->nama_barang }}</td>
      <td>{{ $b->merek_tipe ?? '-' }}</td>
      <td style="text-align:center;">{{ $b->tanggal_masuk ? date('Y', strtotime($b->tanggal_masuk)) : '-' }}</td>
      <td style="text-align:center;">{{ $b->jumlah }}</td>
      <td>{{ $b->nomor_bmn ?? '-' }}</td>
      <td style="text-align:center;">{{ $b->kondisi == 'B' ? '✔' : '' }}</td>
      <td style="text-align:center;">{{ $b->kondisi == 'RR' ? '✔' : '' }}</td>
      <td style="text-align:center;">{{ $b->kondisi == 'RB' ? '✔' : '' }}</td>
    </tr>
  @endforeach

  <tr><td colspan="9" style="height:15px;"></td></tr>
  <tr>
    <td colspan="9" style="font-style:italic;">Tidak dibenarkan memindahkan barang-barang tanpa sepengetahuan kepala...</td>
  </tr>

  <tr><td colspan="9" style="height:25px;"></td></tr>

  <tr>
    <td colspan="4" style="text-align:center;">Mengetahui<br>Dekan</td>
    <td></td>
    <td colspan="4" style="text-align:center;">Palembang, Oktober 2025<br>Penanggung Jawab Ruangan</td>
  </tr>

  <tr>
    <td colspan="4" style="text-align:center;">Dr. Muhammad Isnaini, M.Pd.<br>NIP.197202012000031000</td>
    <td></td>
    <td colspan="4" style="text-align:center;">Mila Gustahartati, S.Ag., M.Hum.<br>NIP.197008242000032004</td>
  </tr>
</table>
