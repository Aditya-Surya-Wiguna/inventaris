<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ===========================================
        // USER (Admin Gudang)
        // ===========================================
        Schema::create('user', function (Blueprint $t) {
            $t->id('id_user');
            $t->string('nama')->default('Admin Gudang');
            $t->string('username', 50)->unique();
            $t->string('password');
            $t->rememberToken();
            $t->timestamps();
        });

        // ===========================================
        // FAKULTAS
        // ===========================================
        Schema::create('fakultas', function (Blueprint $t) {
            $t->id('id_fakultas');
            $t->string('kode_fakultas', 20)->unique();
            $t->string('nama_fakultas');
            $t->timestamps();
        });

        // ===========================================
        // GEDUNG
        // ===========================================
        Schema::create('gedung', function (Blueprint $t) {
            $t->id('id_gedung');
            $t->unsignedBigInteger('id_fakultas');
            $t->enum('kode_gedung', ['A', 'B']);
            $t->timestamps();
            $t->unique(['id_fakultas', 'kode_gedung']);

            // Foreign key manual (harus cocok kolom)
            $t->foreign('id_fakultas')
              ->references('id_fakultas')
              ->on('fakultas')
              ->cascadeOnDelete();
        });

        // ===========================================
        // RUANG
        // ===========================================
        Schema::create('ruang', function (Blueprint $t) {
            $t->id('id_ruang');
            $t->unsignedBigInteger('id_gedung');
            $t->string('nama_ruang');
            $t->timestamps();
            $t->unique(['id_gedung', 'nama_ruang']);

            $t->foreign('id_gedung')
              ->references('id_gedung')
              ->on('gedung')
              ->cascadeOnDelete();
        });

        // ===========================================
        // DATA BARANG
        // ===========================================
        Schema::create('barang', function (Blueprint $t) {
            $t->id('id_barang');
            $t->string('kode_barang', 30)->unique()->index();
            $t->string('nama_barang', 120);
            $t->string('merek_tipe', 120)->nullable();
            $t->date('tanggal_masuk')->nullable();
            $t->unsignedInteger('jumlah')->default(1);
            $t->string('nomor_bmn', 100)->nullable();
            $t->enum('kondisi', ['B', 'RR', 'RB'])->default('B');
            $t->string('foto_surat')->nullable();
            $t->string('foto_barang')->nullable();
            $t->unsignedBigInteger('id_ruang')->nullable();
            $t->string('kode_barcode')->nullable();
            $t->timestamps();

            $t->foreign('id_ruang')
              ->references('id_ruang')
              ->on('ruang')
              ->nullOnDelete();
        });

        // ===========================================
        // BARANG RUSAK
        // ===========================================
        Schema::create('barang_rusak', function (Blueprint $t) {
            $t->id('id_rusak');
            $t->unsignedBigInteger('id_barang');
            $t->enum('kondisi_awal', ['B', 'RR', 'RB']);
            $t->enum('kondisi_baru', ['RR', 'RB']);
            $t->string('foto_bukti')->nullable();
            $t->date('tanggal_catat');
            $t->timestamps();

            $t->foreign('id_barang')
              ->references('id_barang')
              ->on('barang')
              ->cascadeOnDelete();
        });

        // ===========================================
        // BARANG PINDAH
        // ===========================================
        Schema::create('barang_pindah', function (Blueprint $t) {
            $t->id('id_pindah');
            $t->unsignedBigInteger('id_barang');
            $t->unsignedBigInteger('id_ruang_asal')->nullable();
            $t->unsignedBigInteger('id_ruang_tujuan');
            $t->string('file_surat')->nullable();
            $t->date('tanggal_pindah');
            $t->timestamps();

            $t->foreign('id_barang')
              ->references('id_barang')
              ->on('barang')
              ->cascadeOnDelete();

            $t->foreign('id_ruang_asal')
              ->references('id_ruang')
              ->on('ruang')
              ->nullOnDelete();

            $t->foreign('id_ruang_tujuan')
              ->references('id_ruang')
              ->on('ruang')
              ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_pindah');
        Schema::dropIfExists('barang_rusak');
        Schema::dropIfExists('barang');
        Schema::dropIfExists('ruang');
        Schema::dropIfExists('gedung');
        Schema::dropIfExists('fakultas');
        Schema::dropIfExists('user');
    }
};
