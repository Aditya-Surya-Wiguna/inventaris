@extends('layouts.main')
@section('title', 'Tambah Barang Pindah')

@section('content')
<h4 class="mb-4">🔁 Tambah Barang Pindah</h4>

<form action="{{ route('barang-pindah.store') }}" method="POST" enctype="multipart/form-data" class="card shadow-sm p-4">
  @csrf

  <div class="row g-3">

    {{-- ==================== PILIH BARANG ==================== --}}
    <div class="col-md-6">
      <label class="form-label">Pilih Barang</label>
      <select name="id_barang" id="id_barang" class="form-select" required>
        <option value="">-- Pilih Barang --</option>
        @foreach($barang as $b)
          <option value="{{ $b->id_barang }}"
            data-lokasi="{{ $b->ruang->gedung->fakultas->kode_fakultas }}/{{ $b->ruang->gedung->kode_gedung }}/{{ $b->ruang->nama_ruang }}">
            {{ $b->kode_barang }} - {{ $b->nama_barang }} 
            ({{ $b->ruang->gedung->fakultas->kode_fakultas }}/{{ $b->ruang->gedung->kode_gedung }}/{{ $b->ruang->nama_ruang }})
          </option>
        @endforeach
      </select>
    </div>

    {{-- ==================== LOKASI ASAL OTOMATIS ==================== --}}
    <div class="col-md-6">
      <label class="form-label">Lokasi Asal</label>
      <input type="text" id="lokasi_asal" class="form-control" readonly placeholder="Otomatis muncul saat pilih barang">
    </div>

    {{-- ==================== LOKASI TUJUAN ==================== --}}
    <div class="col-md-12 mt-3">
      <label class="form-label">Lokasi Tujuan</label>
      <div class="row g-3">
        <div class="col-md-4">
          <select id="fakultas" class="form-select" required>
            <option value="">-- Pilih Fakultas --</option>
            @foreach($fakultas as $f)
              <option value="{{ $f->id_fakultas }}">{{ $f->kode_fakultas }} - {{ $f->nama_fakultas }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-4">
          <select id="gedung" class="form-select" disabled>
            <option value="">-- Pilih Gedung --</option>
          </select>
        </div>
        <div class="col-md-4">
          <select name="id_ruang_tujuan" id="ruang" class="form-select" disabled required>
            <option value="">-- Pilih Ruang --</option>
          </select>
        </div>
      </div>
    </div>

    {{-- ==================== FILE SURAT ==================== --}}
    <div class="col-md-6 mt-3">
      <label class="form-label">Surat Pemindahan (PDF/JPG/PNG)</label>
      <input type="file" name="file_surat" class="form-control">
    </div>

  </div>

  <div class="mt-4 text-end">
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="{{ route('barang-pindah.index') }}" class="btn btn-secondary">Kembali</a>
  </div>
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const barangSelect = document.getElementById('id_barang');
  const lokasiAsal = document.getElementById('lokasi_asal');

  // Tampilkan lokasi asal otomatis saat pilih barang
  barangSelect.addEventListener('change', function () {
    const selected = this.options[this.selectedIndex];
    lokasiAsal.value = selected.dataset.lokasi || '';
  });

  // Dropdown Fakultas → Gedung → Ruang (lokasi tujuan)
  const fakultasSelect = document.getElementById('fakultas');
  const gedungSelect = document.getElementById('gedung');
  const ruangSelect = document.getElementById('ruang');

  fakultasSelect.addEventListener('change', function() {
    gedungSelect.innerHTML = '<option value="">-- Pilih Gedung --</option>';
    ruangSelect.innerHTML = '<option value="">-- Pilih Ruang --</option>';

    if (this.value) {
      fetch(`/get-gedung/${this.value}`)
        .then(res => res.json())
        .then(data => {
          gedungSelect.disabled = false;
          data.forEach(g => {
            gedungSelect.innerHTML += `<option value="${g.id_gedung}">Gedung ${g.kode_gedung}</option>`;
          });
        });
    } else gedungSelect.disabled = true;
  });

  gedungSelect.addEventListener('change', function() {
    ruangSelect.innerHTML = '<option value="">-- Pilih Ruang --</option>';
    if (this.value) {
      fetch(`/get-ruang/${this.value}`)
        .then(res => res.json())
        .then(data => {
          ruangSelect.disabled = false;
          data.forEach(r => {
            ruangSelect.innerHTML += `<option value="${r.id_ruang}">${r.nama_ruang}</option>`;
          });
        });
    } else ruangSelect.disabled = true;
  });
});
</script>
@endpush
