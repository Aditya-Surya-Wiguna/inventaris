@extends('layouts.main')
@section('title', 'Dashboard Gudang')

@section('content')
<h5 class="mb-3 fw-bold text-primary animate__animated animate__fadeInDown">
  📊 DASHBOARD INVENTARIS UIN RADEN FATAH PALEMBANG
</h5>

<style>

  .card-modern {
    border: none;
    border-radius: 14px;
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 14px rgba(0,0,0,0.06);
    transition: all 0.3s ease;
  }
  .card-modern:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
  }

  .stat-card {
    color: #fff;
    border: none;
    border-radius: 14px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
    height: 100%;
  }
  .stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 18px rgba(0,0,0,0.25);
  }
  .stat-body {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 100%;
    padding: 1rem 1.2rem;
  }
  .stat-left {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 0.4rem;
  }
  .stat-icon {
    font-size: 2rem;
    opacity: 0.9;
  }
  .stat-title {
    font-size: 0.9rem;
    font-weight: 600;
    letter-spacing: 0.3px;
  }
  .stat-value {
    font-size: 2rem;
    font-weight: 900;
    text-align: right;
  }

  .card-header {
    background: linear-gradient(90deg, #f0f6ff, #e9f1ff);
    border-bottom: 1px solid #e0e0e0;
    border-radius: 14px 14px 0 0;
    font-weight: 700;
    color: #0d47a1;
    font-size: 0.95rem;
  }

  .table thead th {
    font-weight: 800;
    color: #000;
    font-size: 0.85rem;
    background: linear-gradient(90deg, #eaf2ff, #ffffff);
    border-bottom: 2px solid #dee2e6;
  }
  .table-hover tbody tr:hover {
    background: #f6faff !important;
  }
  .table-sm td {
    vertical-align: middle;
    font-size: 0.85rem;
  }

  .badge {
    font-size: 0.95rem !important;
    padding: 6px 12px;
    font-weight: 600;
    border-radius: 8px;
  }

  canvas {
    max-height: 180px !important;
  }
</style>

{{-- ====== STATISTIK UTAMA ====== --}}
<div class="row g-3 mb-4 animate__animated animate__fadeInUp">
  @php
    $cards = [
      ['bg' => 'linear-gradient(145deg, #0d6efd, #004aad)', 'icon' => 'bi-box-seam', 'title' => 'Total Barang', 'value' => $totalBarang],
      ['bg' => 'linear-gradient(145deg, #198754, #0d683f)', 'icon' => 'bi-check-circle', 'title' => 'Barang Baik', 'value' => $barangBaik],
      ['bg' => 'linear-gradient(145deg, #ffc107, #c59000)', 'icon' => 'bi-tools', 'title' => 'Rusak Ringan', 'value' => $rusakRingan],
      ['bg' => 'linear-gradient(145deg, #dc3545, #a71d2a)', 'icon' => 'bi-x-circle', 'title' => 'Rusak Berat', 'value' => $rusakBerat],
    ];
  @endphp

  @foreach ($cards as $card)
  <div class="col-6 col-md-3">
    <div class="card stat-card" style="background: {{ $card['bg'] }}">
      <div class="stat-body">
        <div class="stat-left">
          <i class="bi {{ $card['icon'] }} stat-icon"></i>
          <p class="stat-title mb-0">{{ $card['title'] }}</p>
        </div>
        <div class="stat-value">{{ $card['value'] }}</div>
      </div>
    </div>
  </div>
  @endforeach
</div>

{{-- ====== GRAFIK ====== --}}
<div class="row g-3 mb-4 animate__animated animate__fadeInUp">
  <div class="col-md-6">
    <div class="card card-modern">
      <div class="card-header">📈 <span class="fw-bold">Grafik Kondisi Barang</span></div>
      <div class="card-body text-center">
        <canvas id="chartKondisi"></canvas>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card card-modern">
      <div class="card-header">🏛️ <span class="fw-bold">Barang per Fakultas</span></div>
      <div class="card-body text-center">
        <canvas id="chartFakultas"></canvas>
      </div>
    </div>
  </div>
</div>

{{-- ====== TABEL BARANG TERBARU ====== --}}
<div class="card card-modern animate__animated animate__fadeInUp">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span class="fw-bold">🕒 Barang Terbaru Masuk</span>
    <a href="{{ route('barang.index') }}" class="btn btn-sm btn-outline-primary fw-semibold">Lihat Semua</a>
  </div>
  <div class="card-body p-2">
    <table class="table table-sm table-hover mb-0 align-middle text-center">
      <thead>
        <tr>
          <th>No.</th>
          <th>Nama Barang</th>
          <th>Merek/Tipe</th>
          <th>Tanggal Masuk</th>
          <th>Kondisi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($barangTerbaru as $i => $b)
        <tr>
          <td>{{ $i + 1 }}</td>
          <td class="fw-semibold">{{ $b->nama_barang }}</td>
          <td>{{ $b->merek_tipe ?? '-' }}</td>
          <td>{{ $b->tanggal_masuk ? \Carbon\Carbon::parse($b->tanggal_masuk)->translatedFormat('d F Y') : '-' }}</td>
          <td>
            @if($b->kondisi == 'B')
              <span class="badge bg-success shadow-sm">Baik</span>
            @elseif($b->kondisi == 'RR')
              <span class="badge bg-warning text-dark shadow-sm">Ringan</span>
            @else
              <span class="badge bg-danger shadow-sm">Berat</span>
            @endif
          </td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center text-muted py-3">Belum ada data</td></tr>
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
    plugins: {
      legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11, weight: '600' } } }
    }
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
      y: { beginAtZero: true, ticks: { font: { size: 11 } } },
      x: { ticks: { font: { size: 11 } } }
    },
    plugins: { legend: { display: false } }
  }
});
</script>
@endpush
