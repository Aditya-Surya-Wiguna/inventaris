<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangRusak;
use App\Models\BarangPindah;
use App\Models\Fakultas;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Set Locale & Zona Waktu
        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID.UTF-8');
        date_default_timezone_set('Asia/Jakarta');

        // Statistik Barang
        $totalBarang  = Barang::count();
        $barangBaik   = Barang::where('kondisi', 'B')->count();
        $rusakRingan  = Barang::where('kondisi', 'RR')->count();
        $rusakBerat   = Barang::where('kondisi', 'RB')->count();
        $barangPindah = BarangPindah::count();
        $barangRusak  = BarangRusak::count();

        // Grafik Kondisi Barang
        $chartLabels = ['Baik', 'Rusak Ringan', 'Rusak Berat'];
        $chartData   = [$barangBaik, $rusakRingan, $rusakBerat];

        //  Grafik Barang per Fakultas
        $fakultas = Fakultas::with('gedung.ruang.barang')->get();
        $fakultasLabels = $fakultas->pluck('nama_fakultas');
        $fakultasData = $fakultas->map(fn($f) =>
            $f->gedung->flatMap->ruang->flatMap->barang->count()
        );

        //  Barang Terbaru Masuk
        $barangTerbaru = Barang::latest()->take(5)->get();

        //  Notifikasi dengan format Indo
        $notifikasi = [];

        $waktu = Carbon::now()->translatedFormat('d F Y H:i'); 

        // Barang rusak berat → notifikasi merah
        if ($rusakBerat > 0) {
            $notifikasi[] = [
                'icon'  => 'bi-exclamation-octagon-fill text-danger',
                'pesan' => "Ada $rusakBerat barang rusak berat!",
                'waktu' => $waktu,
                'link'  => route('barang-rusak.index'),
            ];
        }

        // Barang rusak ringan → notifikasi kuning
        if ($rusakRingan > 0) {
            $notifikasi[] = [
                'icon'  => 'bi-tools text-warning',
                'pesan' => "Ada $rusakRingan barang rusak ringan.",
                'waktu' => $waktu,
                'link'  => route('barang-rusak.index'),
            ];
        }

        // Barang baru dipindahkan → notifikasi biru
        if ($barangPindah > 0) {
            $notifikasi[] = [
                'icon'  => 'bi-truck text-primary',
                'pesan' => "Terdapat $barangPindah data barang yang baru dipindahkan.",
                'waktu' => $waktu,
                'link'  => route('barang-pindah.index'),
            ];
        }

        // Barang baru masuk minggu ini → notifikasi hijau
        $barangBaruMingguIni = Barang::where('created_at', '>=', now()->subDays(7))->count();
        if ($barangBaruMingguIni > 0) {
            $notifikasi[] = [
                'icon'  => 'bi-box-seam text-success',
                'pesan' => "$barangBaruMingguIni barang baru ditambahkan minggu ini.",
                'waktu' => $waktu,
                'link'  => route('barang.index'),
            ];
        }

        $notifikasiCount = count($notifikasi);

        // Kirim ke View
        return view('dashboard.index', compact(
            'totalBarang', 'barangBaik', 'rusakRingan', 'rusakBerat',
            'barangPindah', 'barangRusak', 'chartLabels', 'chartData',
            'fakultasLabels', 'fakultasData', 'barangTerbaru',
            'notifikasi', 'notifikasiCount'
        ));
    }
}
