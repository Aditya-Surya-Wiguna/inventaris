<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\BarangRusak;
use App\Models\BarangPindah;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 🌍 Pengaturan Locale & Zona Waktu
        |--------------------------------------------------------------------------
        */
        // Set locale Carbon & sistem ke Bahasa Indonesia
        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID.UTF-8');
        date_default_timezone_set('Asia/Jakarta');

        /*
        |--------------------------------------------------------------------------
        | 🔔 Variabel Notifikasi Global
        |--------------------------------------------------------------------------
        */
        View::composer('*', function ($view) {
            $barangRusak = BarangRusak::count();
            $barangPindah = BarangPindah::count();

            $notifikasi = [];

            // Barang rusak tercatat
            if ($barangRusak > 0) {
                $notifikasi[] = [
                    'icon'  => 'bi-tools text-warning',
                    'pesan' => "$barangRusak barang rusak tercatat.",
                    'waktu' => Carbon::now()->translatedFormat('d F Y H:i'), // 🟢 Format Bahasa Indonesia
                    'link'  => route('barang-rusak.index'),
                ];
            }

            // Barang yang dipindahkan
            if ($barangPindah > 0) {
                $notifikasi[] = [
                    'icon'  => 'bi-truck text-primary',
                    'pesan' => "$barangPindah barang telah dipindahkan.",
                    'waktu' => Carbon::now()->translatedFormat('d F Y H:i'),
                    'link'  => route('barang-pindah.index'),
                ];
            }

            $view->with([
                'notifikasi' => $notifikasi,
                'notifikasiCount' => count($notifikasi),
            ]);
        });
    }
}
