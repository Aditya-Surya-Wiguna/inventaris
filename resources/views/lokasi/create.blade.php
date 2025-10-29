@extends('layouts.main')
@section('title', 'Tambah Lokasi')

@section('content')
<h4 class="mb-4">➕ Tambah Lokasi</h4>

<form action="{{ route('lokasi.store') }}" method="POST" class="card p-4 shadow-sm">
  @csrf
  <div class="mb-3">
    <label class="form-label">Pilih Jenis Lokasi</label>
    <select name="tipe" id="tipe" class="form-select" required>
      <option value="">-- Pilih --</option>
      <option value="fakultas">Fakultas</option>
      <option value="gedung">Gedung</option>
      <option value="ruang">Ruang</option>
    </select>
  </div>

  <div id="form-fakultas" class="d-none">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Kode Fakultas</label>
        <input type="text" name="kode_fakultas" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label">Nama Fakultas</label>
        <input type="text" name="nama_fakultas" class="form-control">
      </div>
    </div>
  </div>

  <div id="form-gedung" class="d-none">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Pilih Fakultas</label>
        <select name="id_fakultas" class="form-select">
          <option value="">-- Pilih Fakultas --</option>
          @foreach($fakultas as $f)
            <option value="{{ $f->id_fakultas }}">{{ $f->nama_fakultas }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Kode Gedung</label>
        <select name="kode_gedung" class="form-select">
          <option value="">-- Pilih --</option>
          <option value="A">A</option>
          <option value="B">B</option>
        </select>
      </div>
    </div>
  </div>

  <div id="form-ruang" class="d-none">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Pilih Gedung</label>
        <select name="id_gedung" class="form-select">
          <option value="">-- Pilih Gedung --</option>
          @foreach($gedung as $g)
            <option value="{{ $g->id_gedung }}">Gedung {{ $g->kode_gedung }} ({{ $g->fakultas->nama_fakultas }})</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Nama Ruang</label>
        <input type="text" name="nama_ruang" class="form-control">
      </div>
    </div>
  </div>

  <div class="mt-4 text-end">
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="{{ route('lokasi.index') }}" class="btn btn-secondary">Kembali</a>
  </div>
</form>

@push('scripts')
<script>
document.getElementById('tipe').addEventListener('change', function() {
  document.querySelectorAll('#form-fakultas, #form-gedung, #form-ruang').forEach(e => e.classList.add('d-none'));
  const selected = this.value;
  if (selected) document.getElementById('form-' + selected).classList.remove('d-none');
});
</script>
@endpush
@endsection
