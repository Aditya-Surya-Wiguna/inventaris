@extends('layouts.main')
@section('title', 'Barcode Barang')

@section('content')
<h4 class="mb-4">🔖 Barcode Barang</h4>

<div class="card text-center shadow-sm p-4">
  <h5>{{ $barang->nama_barang }}</h5>
  <p>{{ $barang->kode_barang }}</p>
  <div class="d-flex justify-content-center mb-3">{!! $qr !!}</div>
  <button onclick="window.print()" class="btn btn-outline-dark"><i class="bi bi-printer"></i> Cetak Barcode</button>
</div>
@endsection
