@extends('layouts.main')
@section('title', 'Barang Rusak')

@section('content')
<h4 class="mb-4">⚠️ Data Barang Rusak</h4>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="d-flex justify-content-between mb-3">
  <a href="{{ route('barang-rusak.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Tambah Data Rusak</a>
</div>

<div class="card shadow-sm">
  <div class="card-body table-responsive">
    <table class="table table-bordered">
      <thead class="table-warning text-center">
        <tr>
          <th>No</th>
          <th>Nama Barang</th>
          <th>Kode Barang</th>
          <th>Kondisi Awal</th>
          <th>Kondisi Baru</th>
          <th>Tanggal</th>
          <th>Foto Bukti</th>
        </tr>
      </thead>
      <tbody>
        @forelse($barangRusak as $r)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $r->barang->nama_barang }}</td>
          <td>{{ $r->barang->kode_barang }}</td>
          <td class="text-center">{{ $r->kondisi_awal }}</td>
          <td class="text-center">{{ $r->kondisi_baru }}</td>
          <td>{{ $r->tanggal_catat }}</td>
          <td class="text-center">
            @if($r->foto_bukti)
              <img src="{{ asset('storage/'.$r->foto_bukti) }}" width="70" data-bs-toggle="modal" data-bs-target="#img{{ $r->id_rusak }}">
              <div class="modal fade" id="img{{ $r->id_rusak }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                  <img src="{{ asset('storage/'.$r->foto_bukti) }}" class="img-fluid rounded">
                </div>
              </div>
            @else
              <span class="text-muted">-</span>
            @endif
          </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center text-muted">Belum ada data rusak</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
