<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\BarangRusakController;
use App\Http\Controllers\BarangPindahController;
use App\Http\Controllers\AuthController;

/* ROUTE LOGIN */
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

/* ROUTE YANG DILINDUNGI (HANYA BISA DIAKSES SETELAH LOGIN)*/
Route::middleware('auth')->group(function () {

    //  DASHBOARD
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    //  DATA BARANG
    Route::prefix('barang')->name('barang.')->group(function () {
        // CRUD Barang
        Route::get('/', [BarangController::class, 'index'])->name('index');
        Route::get('/tambah', [BarangController::class, 'create'])->name('create');
        Route::post('/simpan', [BarangController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [BarangController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [BarangController::class, 'update'])->name('update');
        Route::delete('/hapus/{id}', [BarangController::class, 'destroy'])->name('destroy');
        Route::get('/show/{id}', [BarangController::class, 'show'])->name('show');

        // Tambah Banyak Barang Sekaligus
        Route::get('/tambah-banyak', [BarangController::class, 'createMultiple'])->name('createMultiple');
        Route::post('/simpan-banyak', [BarangController::class, 'storeMultiple'])->name('storeMultiple');

        // Cetak Barcode dan Laporan
        Route::get('/barcode/{id}', [BarangController::class, 'barcode'])->name('barcode');
        Route::get('/cetak/barcode', [BarangController::class, 'cetakBarcode'])->name('cetak.barcode');
        Route::get('/cetak/pdf', [BarangController::class, 'cetakPdf'])->name('cetak.pdf');
        Route::get('/cetak/laporan', [BarangController::class, 'cetakLaporan'])->name('cetak.laporan');
    });

 
    //  BARANG RUSAK
    Route::resource('barang-rusak', BarangRusakController::class)->only(['index', 'create', 'store']);

    //  Cetak Barang Rusak (PDF dengan filter)
    Route::get('/barang-rusak/cetak', [BarangRusakController::class, 'cetak'])->name('barang-rusak.cetak');

    //  BARANG PINDAH
    Route::resource('barang-pindah', BarangPindahController::class)->only(['index', 'create', 'store']);

    //  Cetak Barang Pindah (PDF dengan filter)
    Route::get('/barang-pindah/cetak', [BarangPindahController::class, 'cetak'])->name('barang-pindah.cetak');

    //  DATA LOKASI (Fakultas, Gedung, Ruangan)
    Route::resource('lokasi', LokasiController::class)->only(['index', 'create', 'store', 'destroy']);

    //  AJAX - Dropdown Dinamis Lokasi
    Route::get('/get-gedung/{id_fakultas}', [LokasiController::class, 'getGedung']);
    Route::get('/get-ruang/{id_gedung}', [LokasiController::class, 'getRuang']);

    //  AJAX - Filter Dinamis untuk Barang
    Route::get('/ajax/gedung/{id_fakultas}', [BarangController::class, 'getGedung']);
    Route::get('/ajax/ruang/{id_gedung}', [BarangController::class, 'getRuang']);
});
