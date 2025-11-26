@extends('layouts.main')
@section('title', 'Tambah Lokasi')

@section('content')
<h4 class="mb-4 fw-semibold text-primary animate__animated animate__fadeInDown">➕ Tambah Lokasi</h4>

{{-- Alert Sukses --}}
@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

{{-- Alert Warning (duplikat atau lainnya) --}}
@if(session('warning'))
  <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
    <i class="bi bi-exclamation-triangle me-2"></i> {{ session('warning') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

{{-- Alert Validasi Error --}}
@if ($errors->any())
  <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
    <i class="bi bi-x-circle me-2"></i> Terdapat kesalahan input:
    <ul class="mb-0 mt-2 ps-3">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<style>

  .card {
    border-radius: 12px;
    background: #fff;
    box-shadow: 0 4px 14px rgba(0,0,0,0.06);
    transition: all 0.3s ease;
  }
  .card:hover { transform: translateY(-2px); }
  label.form-label {
    font-weight: 600;
    color: #004aad;
  }
  .btn {
    border-radius: 8px;
  }
  select.form-select, input.form-control {
    border-radius: 8px;
    font-size: 0.9rem;
  }
  .alert {
    border-radius: 10px;
  }
</style>

<form action="{{ route('lokasi.store') }}" method="POST" class="card p-4 shadow-sm animate__animated animate__fadeInUp">
  @csrf

  {{-- PILIH TIPE --}}
  <div class="mb-3">
    <label class="form-label fw-bold">Pilih Jenis Lokasi</label>
    <select name="tipe" id="tipe" class="form-select" required>
      <option value="">-- Pilih --</option>
      <option value="fakultas">Fakultas</option>
      <option value="gedung">Gedung</option>
      <option value="ruang">Ruang</option>
    </select>
  </div>

  {{-- FORM FAKULTAS --}}
  <div id="form-fakultas" class="d-none">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Kode Fakultas</label>
        <input type="text" name="kode_fakultas" class="form-control" placeholder="Misal: FST">
      </div>
      <div class="col-md-6">
        <label class="form-label">Nama Fakultas</label>
        <input type="text" name="nama_fakultas" class="form-control" placeholder="Misal: Sains dan Teknologi">
      </div>
    </div>
  </div>

  {{-- FORM GEDUNG --}}
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
        <input type="text" name="kode_gedung" class="form-control" placeholder="Misal: A, B, C...">
      </div>
    </div>
  </div>

  {{-- FORM RUANG (BISA TAMBAH BANYAK) --}}
  <div id="form-ruang" class="d-none">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Pilih Gedung</label>
        <select name="id_gedung" class="form-select">
          <option value="">-- Pilih Gedung --</option>
          @foreach($gedung as $g)
            <option value="{{ $g->id_gedung }}">
              Gedung {{ $g->kode_gedung }} ({{ $g->fakultas->nama_fakultas }})
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Nama Ruang</label>
        <div id="ruang-wrapper">
          <div class="input-group mb-2">
            <input type="text" name="nama_ruang[]" class="form-control" placeholder="Masukkan nama ruang">
            <button type="button" class="btn btn-success add-ruang"><i class="bi bi-plus-lg"></i></button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="mt-4 text-end">
    <button type="submit" class="btn btn-primary shadow-sm">
      <i class="bi bi-save me-1"></i> Simpan
    </button>
    <a href="{{ route('lokasi.index') }}" class="btn btn-secondary shadow-sm">
      <i class="bi bi-arrow-left-circle me-1"></i> Kembali
    </a>
  </div>
</form>

@push('scripts')
<script>
document.getElementById('tipe').addEventListener('change', function() {
  document.querySelectorAll('#form-fakultas, #form-gedung, #form-ruang')
    .forEach(e => e.classList.add('d-none'));
  const selected = this.value;
  if (selected) document.getElementById('form-' + selected).classList.remove('d-none');
});

// Tambah / Hapus Input Ruang Dinamis
document.addEventListener('click', function(e) {
  if (e.target.classList.contains('add-ruang') || e.target.closest('.add-ruang')) {
    const wrapper = document.getElementById('ruang-wrapper');
    const newInput = document.createElement('div');
    newInput.className = 'input-group mb-2';
    newInput.innerHTML = `
      <input type="text" name="nama_ruang[]" class="form-control" placeholder="Masukkan nama ruang lagi">
      <button type="button" class="btn btn-danger remove-ruang"><i class="bi bi-dash-lg"></i></button>
    `;
    wrapper.appendChild(newInput);
  }

  if (e.target.classList.contains('remove-ruang') || e.target.closest('.remove-ruang')) {
    e.target.closest('.input-group').remove();
  }
});
</script>
@endpush
@endsection
