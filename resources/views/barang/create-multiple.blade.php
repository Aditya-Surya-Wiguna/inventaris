@extends('layouts.main')
@section('title', 'Tambah Banyak Barang')

@section('content')
<h4 class="mb-4">🧾 Tambah Banyak Barang Sekaligus</h4>

<form action="{{ route('barang.storeMultiple') }}" method="POST" enctype="multipart/form-data" class="card shadow-sm p-4">
  @csrf

  {{-- =========================
       PILIH LOKASI + SURAT (sekali saja)
     ========================= --}}
  <div class="row g-3 mb-3">
    <div class="col-md-3">
      <label class="form-label">Fakultas</label>
      <select id="fakultas" class="form-select" required>
        <option value="">-- Pilih Fakultas --</option>
        @foreach(\App\Models\Fakultas::all() as $f)
          <option value="{{ $f->id_fakultas }}">{{ $f->nama_fakultas }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-3">
      <label class="form-label">Gedung</label>
      <select id="gedung" class="form-select" disabled>
        <option value="">-- Pilih Gedung --</option>
      </select>
    </div>

    <div class="col-md-3">
      <label class="form-label">Ruang</label>
      <select name="id_ruang" id="ruang" class="form-select" disabled required>
        <option value="">-- Pilih Ruang --</option>
      </select>
    </div>

    <div class="col-md-3">
      <label class="form-label">Upload Surat (PDF/JPG/PNG)</label>
      <input type="file" name="foto_surat" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
    </div>
  </div>

  {{-- =========================
       DAFTAR BARANG (DINAMIS)
     ========================= --}}
  <div id="barangList">
    <div class="card mb-3 border-1">
      <div class="card-body">
        <div class="row g-3 align-items-end">
          <div class="col-md-6">
            <label class="form-label">Nama Barang</label>
            <input type="text" name="barang[0][nama_barang]" class="form-control" placeholder="Masukkan nama barang" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Merek / Tipe</label>
            <input type="text" name="barang[0][merek_tipe]" class="form-control" placeholder="Masukkan merek atau tipe">
          </div>

          <div class="col-md-3">
            <label class="form-label">Tanggal Masuk</label>
            <input type="date" name="barang[0][tanggal_masuk]" class="form-control">
          </div>
          <div class="col-md-3">
            <label class="form-label">Jumlah</label>
            <input type="number" name="barang[0][jumlah]" class="form-control" value="1" min="1">
          </div>
          <div class="col-md-3">
            <label class="form-label">Nomor BMN</label>
            <input type="text" name="barang[0][nomor_bmn]" class="form-control" placeholder="Opsional">
          </div>
          <div class="col-md-3">
            <label class="form-label">Kondisi Barang</label>
            <select name="barang[0][kondisi]" class="form-select">
              <option value="B">Baik</option>
              <option value="RR">Rusak Ringan</option>
              <option value="RB">Rusak Berat</option>
            </select>
          </div>

          <div class="col-md-6 mt-2">
            <label class="form-label">Foto Barang</label>
            <input type="file" name="barang[0][foto_barang]" class="form-control" accept=".jpg,.jpeg,.png">
          </div>
        </div>

        <div class="mt-3 text-end">
          <button type="button" class="btn btn-outline-danger btn-sm removeRow" style="display:none;">
            <i class="bi bi-trash"></i> Hapus Baris
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="text-start mb-4">
    <button type="button" id="addRow" class="btn btn-outline-primary btn-sm">
      <i class="bi bi-plus-circle"></i> Tambah Baris Barang
    </button>
  </div>

  <div class="mt-4 text-end">
    <button type="submit" class="btn btn-primary">Simpan Semua</button>
    <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
  </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  let rowCount = 1;
  const barangList = document.getElementById('barangList');
  const addRowBtn = document.getElementById('addRow');

  // Tambah baris baru
  addRowBtn.addEventListener('click', () => {
    const newCard = barangList.firstElementChild.cloneNode(true);
    newCard.querySelectorAll('input, select').forEach(el => {
      const name = el.getAttribute('name');
      if (name) el.setAttribute('name', name.replace(/\[\d+\]/, `[${rowCount}]`));
      if (el.tagName === 'INPUT') el.value = '';
    });
    newCard.querySelector('.removeRow').style.display = 'inline-block';
    barangList.appendChild(newCard);
    rowCount++;
  });

  // Hapus baris barang
  barangList.addEventListener('click', e => {
    if (e.target.closest('.removeRow')) {
      e.target.closest('.card').remove();
    }
  });

  // Dropdown lokasi dinamis
  const fakultas = document.getElementById('fakultas');
  const gedung = document.getElementById('gedung');
  const ruang = document.getElementById('ruang');

  fakultas.addEventListener('change', function() {
    gedung.innerHTML = '<option value="">-- Pilih Gedung --</option>';
    ruang.innerHTML = '<option value="">-- Pilih Ruang --</option>';
    ruang.disabled = true;
    if (this.value) {
      fetch(`/get-gedung/${this.value}`)
        .then(res => res.json())
        .then(data => {
          gedung.disabled = false;
          data.forEach(g => gedung.innerHTML += `<option value="${g.id_gedung}">Gedung ${g.kode_gedung}</option>`);
        });
    } else gedung.disabled = true;
  });

  gedung.addEventListener('change', function() {
    ruang.innerHTML = '<option value="">-- Pilih Ruang --</option>';
    if (this.value) {
      fetch(`/get-ruang/${this.value}`)
        .then(res => res.json())
        .then(data => {
          ruang.disabled = false;
          data.forEach(r => ruang.innerHTML += `<option value="${r.id_ruang}">${r.nama_ruang}</option>`);
        });
    } else ruang.disabled = true;
  });
});
</script>
@endpush
@endsection
