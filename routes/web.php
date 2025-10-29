<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\BarangRusakController;
use App\Http\Controllers\BarangPindahController;

// ===============================
// DASHBOARD
// ===============================
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// ===============================
// BARANG
// ===============================
Route::prefix('barang')->name('barang.')->group(function () {
    Route::get('/', [BarangController::class, 'index'])->name('index');
    Route::get('/tambah', [BarangController::class, 'create'])->name('create');
    Route::post('/simpan', [BarangController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [BarangController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [BarangController::class, 'update'])->name('update');
    Route::delete('/hapus/{id}', [BarangController::class, 'destroy'])->name('destroy');
    Route::get('/show/{id}', [BarangController::class, 'show'])->name('show');
    Route::get('/barcode/{id}', [BarangController::class, 'barcode'])->name('barcode');

    // 🧾 Cetak Semua Barcode (termasuk hasil filter)
    Route::get('/cetak/barcode', [BarangController::class, 'cetakBarcode'])->name('cetak.barcode');
});

// ===============================
// LOKASI
// ===============================
Route::resource('lokasi', LokasiController::class)->only(['index', 'create', 'store', 'destroy']);

// ===============================
// BARANG RUSAK & PINDAH
// ===============================
Route::resource('barang-rusak', BarangRusakController::class)->only(['index', 'create', 'store']);
Route::resource('barang-pindah', BarangPindahController::class)->only(['index', 'create', 'store']);

// ===============================
// AJAX untuk lokasi dinamis
// ===============================
Route::get('/get-gedung/{id_fakultas}', [LokasiController::class, 'getGedung']);
Route::get('/get-ruang/{id_gedung}', [LokasiController::class, 'getRuang']);

// ===============================
// AJAX untuk filter di halaman barang
// ===============================
Route::get('/ajax/gedung/{id_fakultas}', [BarangController::class, 'getGedung']);
Route::get('/ajax/ruang/{id_gedung}', [BarangController::class, 'getRuang']);
