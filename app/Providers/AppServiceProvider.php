<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Foundation\AliasLoader;
use App\Models\BarangRusak;
use App\Models\BarangPindah;
use Carbon\Carbon;

// Tambahkan ini
use Milon\Barcode\Facades\DNS1DFacade;
use Milon\Barcode\Facades\DNS2DFacade;

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
        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID.UTF-8');
        date_default_timezone_set('Asia/Jakarta');

        /*
        |--------------------------------------------------------------------------
        | 🧩 Daftarkan Alias Barcode (Laravel 12 tidak lagi punya config/app.php)
        |--------------------------------------------------------------------------
        */
        AliasLoader::getInstance()->alias('DNS1D', DNS1DFacade::class);
        AliasLoader::getInstance()->alias('DNS2D', DNS2DFacade::class);

        /*
        |--------------------------------------------------------------------------
        | 🔔 Variabel Notifikasi Global
        |--------------------------------------------------------------------------
        */
        View::composer('*', function ($view) {
            $barangRusak = BarangRusak::count();
            $barangPindah = BarangPindah::count();

            $notifikasi = [];

            if ($barangRusak > 0) {
                $notifikasi[] = [
                    'icon'  => 'bi-tools text-warning',
                    'pesan' => "$barangRusak barang rusak tercatat.",
                    'waktu' => Carbon::now()->translatedFormat('d F Y H:i'),
                    'link'  => route('barang-rusak.index'),
                ];
            }

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
