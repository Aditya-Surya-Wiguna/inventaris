@extends('layouts.main')
@section('title', 'Edit Barang')

@section('content')
<h4 class="mb-4">✏️ Edit Barang</h4>

<form action="{{ route('barang.update', $barang->id_barang) }}" method="POST" enctype="multipart/form-data" class="card shadow-sm p-4">
  @csrf @method('PUT')

  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Nama Barang</label>
      <input type="text" name="nama_barang" value="{{ $barang->nama_barang }}" class="form-control" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">Merek / Tipe</label>
      <input type="text" name="merek_tipe" value="{{ $barang->merek_tipe }}" class="form-control">
    </div>

    <div class="col-md-4">
      <label class="form-label">Tanggal Masuk</label>
      <input type="date" name="tanggal_masuk" value="{{ $barang->tanggal_masuk }}" class="form-control">
    </div>

    <div class="col-md-4">
      <label class="form-label">Jumlah</label>
      <input type="number" name="jumlah" value="{{ $barang->jumlah }}" class="form-control" min="1">
    </div>

    <div class="col-md-4">
      <label class="form-label">Nomor BMN</label>
      <input type="text" name="nomor_bmn" value="{{ $barang->nomor_bmn }}" class="form-control">
    </div>

    <div class="col-md-4">
      <label class="form-label">Kondisi</label>
      <select name="kondisi" class="form-select">
        <option value="B" @selected($barang->kondisi == 'B')>Baik</option>
        <option value="RR" @selected($barang->kondisi == 'RR')>Rusak Ringan</option>
        <option value="RB" @selected($barang->kondisi == 'RB')>Rusak Berat</option>
      </select>
    </div>

    {{-- Lokasi Barang Dinamis --}}
    <div class="col-md-12 mt-2">
      <label class="form-label">Lokasi Barang</label>
      <div class="row g-3">
        {{-- Fakultas --}}
        <div class="col-md-4">
          <select id="fakultas" class="form-select" required>
            <option value="">-- Pilih Fakultas --</option>
            @foreach($fakultas as $f)
              <option value="{{ $f->id_fakultas }}"
                @if($barang->ruang && $barang->ruang->gedung->fakultas->id_fakultas == $f->id_fakultas) selected @endif>
                {{ $f->nama_fakultas }}
              </option>
            @endforeach
          </select>
        </div>

        {{-- Gedung --}}
        <div class="col-md-4">
          <select id="gedung" class="form-select" required>
            <option value="">-- Pilih Gedung --</option>
            @if($gedung)
              @foreach($gedung as $g)
                <option value="{{ $g->id_gedung }}"
                  @if($barang->ruang && $barang->ruang->gedung->id_gedung == $g->id_gedung) selected @endif>
                  Gedung {{ $g->kode_gedung }}
                </option>
              @endforeach
            @endif
          </select>
        </div>

        {{-- Ruang --}}
        <div class="col-md-4">
          <select name="id_ruang" id="ruang" class="form-select" required>
            <option value="">-- Pilih Ruang --</option>
            @if($ruang)
              @foreach($ruang as $r)
                <option value="{{ $r->id_ruang }}" @selected($barang->id_ruang == $r->id_ruang)>
                  {{ $r->nama_ruang }}
                </option>
              @endforeach
            @endif
          </select>
        </div>
      </div>
    </div>

    {{-- Foto surat --}}
    <div class="col-md-6 mt-3">
      <label class="form-label">Foto Surat Baru</label>
      <input type="file" name="foto_surat" class="form-control">
      @if($barang->foto_surat)
        <small class="text-muted">File lama: {{ $barang->foto_surat }}</small>
      @endif
    </div>

    {{-- Foto barang --}}
    <div class="col-md-6 mt-3">
      <label class="form-label">Foto Barang Baru</label>
      <input type="file" name="foto_barang" class="form-control">
      @if($barang->foto_barang)
        <img src="{{ asset('storage/'.$barang->foto_barang) }}" width="100" class="mt-2 rounded shadow-sm">
      @endif
    </div>
  </div>

  <div class="mt-4 text-end">
    <button type="submit" class="btn btn-success">Update</button>
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

  // Saat fakultas diganti
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
    } else {
      gedungSelect.disabled = true;
      ruangSelect.disabled = true;
    }
  });

  // Saat gedung diganti
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
    } else {
      ruangSelect.disabled = true;
    }
  });
});
</script>
@endpush
