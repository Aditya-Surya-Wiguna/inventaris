@extends('layouts.main')
@section('title', 'Tambah Barang')

@section('content')
<h4 class="mb-4">➕ Tambah Barang</h4>

<form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data" class="card shadow-sm p-4">
  @csrf
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Nama Barang</label>
      <input type="text" name="nama_barang" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Merek / Tipe</label>
      <input type="text" name="merek_tipe" class="form-control">
    </div>

    <div class="col-md-4">
      <label class="form-label">Tanggal Masuk</label>
      <input type="date" name="tanggal_masuk" class="form-control">
    </div>
    <div class="col-md-4">
      <label class="form-label">Jumlah</label>
      <input type="number" name="jumlah" class="form-control" value="1" min="1">
    </div>
    <div class="col-md-4">
      <label class="form-label">Nomor BMN</label>
      <input type="text" name="nomor_bmn" class="form-control">
    </div>

    <div class="col-md-4">
      <label class="form-label">Kondisi Barang</label>
      <select name="kondisi" class="form-select">
        <option value="B">Baik</option>
        <option value="RR">Rusak Ringan</option>
        <option value="RB">Rusak Berat</option>
      </select>
    </div>

    {{-- Lokasi Barang (3 level) --}}
    <div class="col-md-12 mt-2">
      <label class="form-label">Lokasi Barang</label>
      <div class="row g-3">
        <div class="col-md-4">
          <select id="fakultas" class="form-select" required>
            <option value="">-- Pilih Fakultas --</option>
            @foreach(\App\Models\Fakultas::all() as $f)
              <option value="{{ $f->id_fakultas }}">{{ $f->nama_fakultas }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-4">
          <select id="gedung" class="form-select" disabled>
            <option value="">-- Pilih Gedung --</option>
          </select>
        </div>

        <div class="col-md-4">
          <select name="id_ruang" id="ruang" class="form-select" disabled required>
            <option value="">-- Pilih Ruang --</option>
          </select>
        </div>
      </div>
    </div>

    <div class="col-md-6 mt-3">
      <label class="form-label">Foto Surat (PDF/JPG/PNG)</label>
      <input type="file" name="foto_surat" class="form-control">
    </div>

    <div class="col-md-6 mt-3">
      <label class="form-label">Foto Barang</label>
      <input type="file" name="foto_barang" class="form-control">
    </div>
  </div>

  <div class="mt-4 text-end">
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
  </div>
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const fakultasSelect = document.getElementById('fakultas');
  const gedungSelect = document.getElementById('gedung');
  const ruangSelect = document.getElementById('ruang');

  // Saat fakultas dipilih
  fakultasSelect.addEventListener('change', function() {
    const idFakultas = this.value;
    gedungSelect.innerHTML = '<option value="">-- Pilih Gedung --</option>';
    ruangSelect.innerHTML = '<option value="">-- Pilih Ruang --</option>';
    ruangSelect.disabled = true;

    if (idFakultas) {
      fetch(`/get-gedung/${idFakultas}`)
        .then(res => res.json())
        .then(data => {
          gedungSelect.disabled = false;
          data.forEach(g => {
            gedungSelect.innerHTML += `<option value="${g.id_gedung}">Gedung ${g.kode_gedung}</option>`;
          });
        });
    } else {
      gedungSelect.disabled = true;
    }
  });

  // Saat gedung dipilih
  gedungSelect.addEventListener('change', function() {
    const idGedung = this.value;
    ruangSelect.innerHTML = '<option value="">-- Pilih Ruang --</option>';

    if (idGedung) {
      fetch(`/get-ruang/${idGedung}`)
        .then(res => res.json())
        .then(data => {
          ruangSelect.disabled = false;
          data.forEach(r => {
            ruangSelect.innerHTML += `<option value="${r.id_ruang}">${r.nama_ruang}</option>`;
          });
        });
    } else {
      ruangSelect.disabled = true;
    }
  });
});
</script>
@endpush
