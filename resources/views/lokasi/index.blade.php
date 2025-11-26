@extends('layouts.main')
@section('title', 'Lokasi Barang')

@section('content')
<h4 class="mb-4 fw-semibold text-primary animate__animated animate__fadeInDown">
  📍 Lokasi Barang
</h4>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
    <button class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<style>

  .btn {
    border-radius: 8px;
    font-size: 0.85rem;
    padding: 6px 12px;
  }
  .btn i {
    vertical-align: middle;
  }

  .accordion-button {
    font-weight: 700;
    font-size: 0.9rem;
    background: linear-gradient(90deg, #eaf2ff, #ffffff);
    color: #003cba;
    border-radius: 8px !important;
    transition: all 0.25s ease;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
  }
  .accordion-button:hover {
    background: linear-gradient(90deg, #dce8ff, #f5f9ff);
    transform: translateY(-1px);
  }
  .accordion-button:not(.collapsed) {
    background: linear-gradient(90deg, #0d6efd, #004aad);
    color: #fff;
    box-shadow: 0 3px 8px rgba(13,110,253,0.3);
  }
  .accordion-body {
    background: rgba(255,255,255,0.9);
    border-radius: 10px;
    padding: 15px 20px;
    backdrop-filter: blur(10px);
    box-shadow: inset 0 0 10px rgba(0,0,0,0.03);
  }

  .card {
    border: none;
    border-radius: 10px;
    background: rgba(255,255,255,0.95);
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    transition: all 0.2s ease;
  }
  .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 14px rgba(0,0,0,0.07);
  }
  .card h6 {
    font-weight: 600;
    color: #004aad;
    margin-bottom: 10px;
  }

  .list-group-item {
    border: none;
    border-radius: 6px;
    margin-bottom: 6px;
    background: linear-gradient(90deg, #f8faff, #ffffff);
    font-size: 0.85rem;
  }
  .list-group-item:hover {
    background: #eef4ff;
  }
  .list-group-item span {
    color: #004aad;
    font-weight: 600;
  }
</style>

{{-- Tombol Tambah Lokasi --}}
<div class="d-flex justify-content-between mb-3 animate__animated animate__fadeInUp">
  <a href="{{ route('lokasi.create') }}" class="btn btn-primary shadow-sm">
    <i class="bi bi-plus-lg me-1"></i> Tambah Lokasi
  </a>
</div>

{{-- Accordion Lokasi Barang --}}
<div class="accordion animate__animated animate__fadeInUp" id="accordionLokasi">
  @forelse($fakultas as $f)
  <div class="accordion-item border-0 mb-2">
    <h2 class="accordion-header" id="heading{{ $f->id_fakultas }}">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $f->id_fakultas }}">
        🏛️ {{ $f->nama_fakultas }} <span class="text-muted ms-2">({{ $f->kode_fakultas }})</span>
      </button>
    </h2>
    <div id="collapse{{ $f->id_fakultas }}" class="accordion-collapse collapse" data-bs-parent="#accordionLokasi">
      <div class="accordion-body">
        <form action="{{ route('lokasi.destroy', $f->id_fakultas) }}" method="POST" class="text-end mb-3 delete-form">
          @csrf @method('DELETE')
          <input type="hidden" name="tipe" value="fakultas">
          <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm">
            <i class="bi bi-trash"></i> Hapus Fakultas
          </button>
        </form>

        @foreach($f->gedung as $g)
        <div class="card mb-3 border-0">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <h6>🏢 Gedung {{ $g->kode_gedung }}</h6>
              <form action="{{ route('lokasi.destroy', $g->id_gedung) }}" method="POST" class="delete-form">
                @csrf @method('DELETE')
                <input type="hidden" name="tipe" value="gedung">
                <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm">
                  <i class="bi bi-trash"></i> Hapus Gedung
                </button>
              </form>
            </div>

            <ul class="list-group list-group-flush">
              @forelse($g->ruang as $r)
              <li class="list-group-item d-flex justify-content-between align-items-center shadow-sm">
                <span>🚪 {{ $r->nama_ruang }}</span>
                <form action="{{ route('lokasi.destroy', $r->id_ruang) }}" method="POST" class="d-inline delete-form">
                  @csrf @method('DELETE')
                  <input type="hidden" name="tipe" value="ruang">
                  <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm">
                    <i class="bi bi-x-circle"></i>
                  </button>
                </form>
              </li>
              @empty
              <li class="list-group-item text-muted">Belum ada ruang.</li>
              @endforelse
            </ul>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
  @empty
  <p class="text-muted text-center mt-3">Belum ada data lokasi.</p>
  @endforelse
</div>

{{-- Script Konfirmasi Hapus --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
  const forms = document.querySelectorAll('.delete-form');

  forms.forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();

      // Ambil tipe data (fakultas / gedung / ruang)
      const tipe = this.querySelector('input[name="tipe"]').value;
      let pesan = '';

      switch (tipe) {
        case 'fakultas':
          pesan = 'Apakah kamu yakin ingin menghapus fakultas ini beserta semua data terkait?';
          break;
        case 'gedung':
          pesan = 'Apakah kamu yakin ingin menghapus gedung ini beserta semua ruangan di dalamnya?';
          break;
        case 'ruang':
          pesan = 'Apakah kamu yakin ingin menghapus ruang ini?';
          break;
      }

      if (confirm(pesan)) {
        this.submit();
      }
    });
  });
});
</script>
@endsection
