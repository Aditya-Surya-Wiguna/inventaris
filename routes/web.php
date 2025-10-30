<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\BarangRusakController;
use App\Http\Controllers\BarangPindahController;

// ==========================================================
// 🏠 DASHBOARD
// ==========================================================
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// ==========================================================
// 📦 DATA BARANG
// ==========================================================
Route::prefix('barang')->name('barang.')->group(function () {
    // CRUD Barang
    Route::get('/', [BarangController::class, 'index'])->name('index');
    Route::get('/tambah', [BarangController::class, 'create'])->name('create');
    Route::post('/simpan', [BarangController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [BarangController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [BarangController::class, 'update'])->name('update');
    Route::delete('/hapus/{id}', [BarangController::class, 'destroy'])->name('destroy');
    Route::get('/show/{id}', [BarangController::class, 'show'])->name('show');

    // Barcode per barang
    Route::get('/barcode/{id}', [BarangController::class, 'barcode'])->name('barcode');

    // Cetak Semua Barcode (berdasarkan hasil filter)
    Route::get('/cetak/barcode', [BarangController::class, 'cetakBarcode'])->name('cetak.barcode');

    // Cetak Data Barang (versi tabel PDF sederhana)
    Route::get('/cetak/pdf', [BarangController::class, 'cetakPdf'])->name('cetak.pdf');

    // Cetak Laporan Resmi (format DBR)
    Route::get('/cetak/laporan', [BarangController::class, 'cetakLaporan'])->name('cetak.laporan');
});

// ==========================================================
// 🏛️ DATA LOKASI (Fakultas, Gedung, Ruangan)
// ==========================================================
Route::resource('lokasi', LokasiController::class)
    ->only(['index', 'create', 'store', 'destroy']);

// ==========================================================
// ⚙️ BARANG RUSAK & BARANG PINDAH
// ==========================================================
Route::resource('barang-rusak', BarangRusakController::class)
    ->only(['index', 'create', 'store']);

Route::resource('barang-pindah', BarangPindahController::class)
    ->only(['index', 'create', 'store']);

// ==========================================================
// 🔄 AJAX - Dynamic Dropdown Lokasi
// ==========================================================
Route::get('/get-gedung/{id_fakultas}', [LokasiController::class, 'getGedung']);
Route::get('/get-ruang/{id_gedung}', [LokasiController::class, 'getRuang']);

// ==========================================================
// 🔄 AJAX - Filter Dinamis di Halaman Barang
// ==========================================================
Route::get('/ajax/gedung/{id_fakultas}', [BarangController::class, 'getGedung']);
Route::get('/ajax/ruang/{id_gedung}', [BarangController::class, 'getRuang']);
