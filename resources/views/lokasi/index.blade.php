@extends('layouts.main')
@section('title', 'Lokasi Barang')

@section('content')
<h4 class="mb-4">📍 Lokasi Barang</h4>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<div class="d-flex justify-content-between mb-3">
  <a href="{{ route('lokasi.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Lokasi</a>
</div>

<div class="accordion" id="accordionLokasi">
  @forelse($fakultas as $f)
  <div class="accordion-item">
    <h2 class="accordion-header" id="heading{{ $f->id_fakultas }}">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $f->id_fakultas }}">
        🏛️ {{ $f->nama_fakultas }} ({{ $f->kode_fakultas }})
      </button>
    </h2>
    <div id="collapse{{ $f->id_fakultas }}" class="accordion-collapse collapse">
      <div class="accordion-body">
        <form action="{{ route('lokasi.destroy', $f->id_fakultas) }}" method="POST" class="text-end mb-2">
          @csrf @method('DELETE')
          <input type="hidden" name="tipe" value="fakultas">
          <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Hapus Fakultas</button>
        </form>

        @foreach($f->gedung as $g)
        <div class="card mb-3 border-info">
          <div class="card-body">
            <h6>🏢 Gedung {{ $g->kode_gedung }}</h6>
            <form action="{{ route('lokasi.destroy', $g->id_gedung) }}" method="POST" class="text-end mb-2">
              @csrf @method('DELETE')
              <input type="hidden" name="tipe" value="gedung">
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Hapus Gedung</button>
            </form>

            <ul class="list-group list-group-flush">
              @forelse($g->ruang as $r)
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>🚪 {{ $r->nama_ruang }}</span>
                <form action="{{ route('lokasi.destroy', $r->id_ruang) }}" method="POST" class="d-inline">
                  @csrf @method('DELETE')
                  <input type="hidden" name="tipe" value="ruang">
                  <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-circle"></i></button>
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
  <p class="text-muted">Belum ada data lokasi.</p>
  @endforelse
</div>
@endsection
