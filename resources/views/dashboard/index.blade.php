@extends('layouts.main')
@section('title', 'Dashboard Gudang')

@section('content')
<h5 class="mb-3">📊 Dashboard Inventaris Gudang UIN Raden Fatah Palembang</h5>

<style>
  /* === Dashboard Styling Compact === */
  .card-body {
    padding: 0.75rem !important;
  }
  .stat-icon {
    font-size: 1.8rem; /* icon lebih kecil */
    margin-bottom: 4px;
  }
  .stat-value {
    font-size: 1.3rem;
    font-weight: 600;
  }
  .stat-title {
    font-size: 0.85rem;
    margin: 0;
  }
  canvas {
    max-height: 160px !important; /* grafik kecil */
  }
  .table-sm th, .table-sm td {
    padding: 4px 8px !important;
    font-size: 0.8rem;
  }
  .card-header {
    padding: 6px 10px !important;
    font-size: 0.85rem;
  }
</style>

{{-- ====== STATISTIK UTAMA ====== --}}
<div class="row g-2 mb-3">
  @php
    $cards = [
      ['bg' => 'primary', 'icon' => 'bi-box-seam', 'title' => 'Total Barang', 'value' => $totalBarang],
      ['bg' => 'success', 'icon' => 'bi-check-circle', 'title' => 'Barang Baik', 'value' => $barangBaik],
      ['bg' => 'warning', 'icon' => 'bi-tools', 'title' => 'Rusak Ringan', 'value' => $rusakRingan],
      ['bg' => 'danger', 'icon' => 'bi-x-circle', 'title' => 'Rusak Berat', 'value' => $rusakBerat],
    ];
  @endphp

  @foreach ($cards as $card)
  <div class="col-3">
    <div class="card text-white bg-{{ $card['bg'] }} text-center shadow-sm">
      <div class="card-body p-2">
        <i class="bi {{ $card['icon'] }} stat-icon"></i>
        <div class="stat-value">{{ $card['value'] }}</div>
        <p class="stat-title">{{ $card['title'] }}</p>
      </div>
    </div>
  </div>
  @endforeach
</div>

{{-- ====== GRAFIK ====== --}}
<div class="row g-2 mb-3">
  <div class="col-md-6">
    <div class="card shadow-sm">
      <div class="card-header bg-light">
        📈 Grafik Kondisi Barang
      </div>
      <div class="card-body text-center">
        <canvas id="chartKondisi"></canvas>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card shadow-sm">
      <div class="card-header bg-light">
        🏛️ Barang per Fakultas
      </div>
      <div class="card-body text-center">
        <canvas id="chartFakultas"></canvas>
      </div>
    </div>
  </div>
</div>

{{-- ====== TABEL BARANG TERBARU ====== --}}
<div class="card shadow-sm">
  <div class="card-header bg-light d-flex justify-content-between align-items-center">
    <strong>🕒 Barang Terbaru Masuk</strong>
    <a href="{{ route('barang.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
  </div>
  <div class="card-body p-2">
    <table class="table table-sm table-hover mb-0 align-middle">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Nama Barang</th>
          <th>Merek/Tipe</th>
          <th>Tanggal</th>
          <th>Kondisi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($barangTerbaru as $i => $b)
        <tr>
          <td>{{ $i + 1 }}</td>
          <td>{{ $b->nama_barang }}</td>
          <td>{{ $b->merek_tipe ?? '-' }}</td>
          <td>{{ $b->tanggal_masuk ?? '-' }}</td>
          <td>
            @if($b->kondisi == 'B')
              <span class="badge bg-success">Baik</span>
            @elseif($b->kondisi == 'RR')
              <span class="badge bg-warning text-dark">Ringan</span>
            @else
              <span class="badge bg-danger">Berat</span>
            @endif
          </td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center text-muted">Belum ada data</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('chartKondisi'), {
  type: 'doughnut',
  data: {
    labels: @json($chartLabels),
    datasets: [{
      data: @json($chartData),
      backgroundColor: ['#198754', '#ffc107', '#dc3545'],
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } } }
  }
});

new Chart(document.getElementById('chartFakultas'), {
  type: 'bar',
  data: {
    labels: @json($fakultasLabels),
    datasets: [{
      label: 'Jumlah Barang',
      data: @json($fakultasData),
      backgroundColor: '#0d6efd'
    }]
  },
  options: {
    responsive: true,
    scales: {
      y: { beginAtZero: true, ticks: { font: { size: 10 } } },
      x: { ticks: { font: { size: 10 } } }
    },
    plugins: { legend: { display: false } }
  }
});
</script>
@endpush
