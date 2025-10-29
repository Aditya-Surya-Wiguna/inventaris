@extends('layouts.main')
@section('title', 'Dashboard Gudang')

@section('content')
<h4 class="mb-4">📊 Dashboard Inventaris Gudang UIN Raden Fatah Palembang</h4>

<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="card bg-primary text-white shadow-sm text-center">
      <div class="card-body">
        <h2>{{ $totalBarang }}</h2>
        <p>Total Barang</p>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card bg-success text-white shadow-sm text-center">
      <div class="card-body">
        <h2>{{ $barangBaik }}</h2>
        <p>Barang Baik</p>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card bg-warning text-dark shadow-sm text-center">
      <div class="card-body">
        <h2>{{ $rusakRingan }}</h2>
        <p>Rusak Ringan</p>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card bg-danger text-white shadow-sm text-center">
      <div class="card-body">
        <h2>{{ $rusakBerat }}</h2>
        <p>Rusak Berat</p>
      </div>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-md-6">
    <div class="card shadow-sm">
      <div class="card-header bg-light">
        <strong>📈 Grafik Kondisi Barang</strong>
      </div>
      <div class="card-body">
        <canvas id="chartKondisi"></canvas>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card shadow-sm">
      <div class="card-header bg-light">
        <strong>📦 Statistik Aktivitas Barang</strong>
      </div>
      <div class="card-body">
        <p>Barang Rusak: <strong>{{ $barangRusak }}</strong></p>
        <p>Barang Pindah: <strong>{{ $barangPindah }}</strong></p>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('chartKondisi');
new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: @json($chartLabels),
    datasets: [{
      label: 'Jumlah Barang',
      data: @json($chartData),
      backgroundColor: ['#198754', '#ffc107', '#dc3545'],
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'bottom' }
    }
  }
});
</script>
@endpush
