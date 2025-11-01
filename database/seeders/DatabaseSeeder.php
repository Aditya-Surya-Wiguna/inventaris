<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends seeder
{
    public function run(): void
    {
        // ================================
        // TABEL USER
        // ================================
        DB::table('user')->insert([
            [
                'id_user' => 1,
                'nama' => 'Admin Gudang',
                'username' => 'admin',
                'password' => '$2y$12$wz1FL7ApQFlpyRNJzeB69O7lZPY64E.XYppjOug0RQvh55u/QOWP6', // hash dari admin123
                'remember_token' => null,
                'created_at' => '2025-10-29 04:49:05',
                'updated_at' => '2025-10-29 04:49:05',
            ]
        ]);

        // ================================
        // TABEL FAKULTAS
        // ================================
        DB::table('fakultas')->insert([
            [
                'id_fakultas' => 6,
                'kode_fakultas' => 'SAINTEK',
                'nama_fakultas' => 'Sains dan Teknologi',
                'created_at' => '2025-10-31 05:55:24',
                'updated_at' => '2025-10-31 05:55:24',
            ]
        ]);

        // ================================
        // TABEL GEDUNG
        // ================================
        DB::table('gedung')->insert([
            [
                'id_gedung' => 10,
                'id_fakultas' => 6,
                'kode_gedung' => 'A',
                'created_at' => '2025-10-31 05:55:40',
                'updated_at' => '2025-10-31 05:55:40',
            ]
        ]);

        // ================================
        // TABEL RUANG
        // ================================
        DB::table('ruang')->insert([
            [
                'id_ruang' => 31,
                'id_gedung' => 10,
                'nama_ruang' => 'Lobi Lt. 1',
                'created_at' => '2025-10-31 06:23:29',
                'updated_at' => '2025-10-31 06:23:29',
            ],
            [
                'id_ruang' => 32,
                'id_gedung' => 10,
                'nama_ruang' => 'Lobi Lt. 2',
                'created_at' => '2025-10-31 06:23:29',
                'updated_at' => '2025-10-31 06:23:29',
            ],
        ]);

        // ================================
        // TABEL BARANG
        // ================================
        DB::table('barang')->insert([
            [
                'id_barang' => 23,
                'kode_barang' => 'BRG-000001',
                'nama_barang' => 'AC Central',
                'merek_tipe' => 'Panasonic',
                'tanggal_masuk' => '2025-10-31',
                'jumlah' => 1,
                'nomor_bmn' => '9780-2025001-128-246-7',
                'kondisi' => 'RR',
                'foto_surat' => 'surat/cnXgAeh6jCGwW4ieSpcgHwGrACTsDDsFrOjNGlMw.pdf',
                'foto_barang' => 'rusak/TiYagH1s3UpfPLvmPebrXNrhb2SYLAIVzrz96ZNj.png',
                'id_ruang' => 32,
                'kode_barcode' => null,
                'created_at' => '2025-10-31 07:11:37',
                'updated_at' => '2025-10-31 08:28:27',
            ]
        ]);

        // ================================
        // TABEL BARANG PINDAH
        // ================================
        DB::table('barang_pindah')->insert([
            [
                'id_pindah' => 9,
                'id_barang' => 23,
                'id_ruang_asal' => 31,
                'id_ruang_tujuan' => 32,
                'file_surat' => 'surat_pindah/Pb0UNJHWhjviZa4fzPllNOScIO7nesFcfyeGRNIL.png',
                'tanggal_pindah' => '2025-10-31',
                'created_at' => '2025-10-31 08:03:06',
                'updated_at' => '2025-10-31 08:03:06',
            ]
        ]);

        // ================================
        // TABEL BARANG RUSAK
        // ================================
        DB::table('barang_rusak')->insert([
            [
                'id_rusak' => 11,
                'id_barang' => 23,
                'kondisi_awal' => 'B',
                'kondisi_baru' => 'RR',
                'foto_bukti' => 'rusak/TiYagH1s3UpfPLvmPebrXNrhb2SYLAIVzrz96ZNj.png',
                'tanggal_catat' => '2025-10-31',
                'created_at' => '2025-10-31 08:28:27',
                'updated_at' => '2025-10-31 08:28:27',
            ]
        ]);

        // ================================
        // TABEL NOTIFIKASI
        // ================================
        DB::table('notifikasis')->insert([
            [
                'pesan' => '1 barang rusak tercatat',
                'icon' => 'bi-tools text-warning',
                'link' => '/barang-rusak',
                'status_baca' => 0,
                'created_at' => now(),
            ],
            [
                'pesan' => '1 barang telah dipindahkan',
                'icon' => 'bi-truck text-primary',
                'link' => '/barang-pindah',
                'status_baca' => 0,
                'created_at' => now(),
            ]
        ]);
    }
}
