@extends('layouts.main')
@section('title', 'Tambah Barang Rusak')

@section('content')
<h4 class="mb-4">⚠️ Tambah Barang Rusak</h4>

<form action="{{ route('barang-rusak.store') }}" method="POST" enctype="multipart/form-data" class="card shadow-sm p-4">
  @csrf

  <div class="row g-3">
    {{-- ==================== PILIH BARANG ==================== --}}
    <div class="col-md-6">
      <label class="form-label">Pilih Barang</label>
      <select name="id_barang" id="id_barang" class="form-select" required>
        <option value="">-- Pilih Barang --</option>
        @foreach($barang as $b)
          <option value="{{ $b->id_barang }}"
            data-kondisi="{{ $b->kondisi }}"
            data-lokasi="{{ $b->ruang->gedung->fakultas->kode_fakultas }}/{{ $b->ruang->gedung->kode_gedung }}/{{ $b->ruang->nama_ruang }}">
            {{ $b->kode_barang }} - {{ $b->nama_barang }}
            ({{ $b->ruang->gedung->fakultas->kode_fakultas }}/{{ $b->ruang->gedung->kode_gedung }}/{{ $b->ruang->nama_ruang }})
          </option>
        @endforeach
      </select>
    </div>

    {{-- ==================== LOKASI BARANG ==================== --}}
    <div class="col-md-6">
      <label class="form-label">Lokasi Barang</label>
      <input type="text" id="lokasi_barang" class="form-control" readonly placeholder="Otomatis muncul saat pilih barang">
    </div>

    {{-- ==================== KONDISI ==================== --}}
    <div class="col-md-6">
      <label class="form-label">Kondisi Awal</label>
      <input type="text" id="kondisi_awal" class="form-control" readonly placeholder="Otomatis muncul">
    </div>
    <div class="col-md-6">
      <label class="form-label">Kondisi Baru</label>
      <select name="kondisi_baru" class="form-select" required>
        <option value="">-- Pilih Kondisi Baru --</option>
        <option value="RR">Rusak Ringan</option>
        <option value="RB">Rusak Berat</option>
      </select>
    </div>

    {{-- ==================== FOTO BUKTI ==================== --}}
    <div class="col-md-6">
      <label class="form-label">Foto Bukti Kerusakan (Opsional)</label>
      <input type="file" name="foto_bukti" class="form-control" accept="image/*">
    </div>
  </div>

  <div class="mt-4 text-end">
    <button type="submit" class="btn btn-warning">Simpan</button>
    <a href="{{ route('barang-rusak.index') }}" class="btn btn-secondary">Kembali</a>
  </div>
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const barangSelect = document.getElementById('id_barang');
  const lokasiInput = document.getElementById('lokasi_barang');
  const kondisiInput = document.getElementById('kondisi_awal');

  barangSelect.addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    lokasiInput.value = selected.dataset.lokasi || '';
    const kondisi = selected.dataset.kondisi;
    if (kondisi === 'B') kondisiInput.value = 'Baik';
    else if (kondisi === 'RR') kondisiInput.value = 'Rusak Ringan';
    else if (kondisi === 'RB') kondisiInput.value = 'Rusak Berat';
    else kondisiInput.value = '-';
  });
});
</script>
@endpush
