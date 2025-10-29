<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangRusak;
use App\Models\BarangPindah;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang = Barang::count();
        $barangBaik  = Barang::where('kondisi', 'B')->count();
        $rusakRingan = Barang::where('kondisi', 'RR')->count();
        $rusakBerat  = Barang::where('kondisi', 'RB')->count();
        $barangPindah = BarangPindah::count();
        $barangRusak = BarangRusak::count();

        // Data grafik kondisi barang
        $chartLabels = ['Baik', 'Rusak Ringan', 'Rusak Berat'];
        $chartData = [$barangBaik, $rusakRingan, $rusakBerat];

        return view('dashboard.index', compact(
            'totalBarang',
            'barangBaik',
            'rusakRingan',
            'rusakBerat',
            'barangPindah',
            'barangRusak',
            'chartLabels',
            'chartData'
        ));
    }
}
