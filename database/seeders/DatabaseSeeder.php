<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Fakultas;
use App\Models\Gedung;
use App\Models\Ruang;
use App\Models\Barang;
use App\Models\BarangRusak;
use App\Models\BarangPindah;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =====================================================
        // ADMIN GUDANG (tabel user)
        // =====================================================
        User::create([
            'nama' => 'Admin Gudang',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
        ]);

        // =====================================================
        // DATA FAKULTAS, GEDUNG, DAN RUANG
        // =====================================================
        $daftarFakultas = [
            ['kode_fakultas' => 'FSH', 'nama_fakultas' => 'Fakultas Syariah dan Hukum'],
            ['kode_fakultas' => 'FEBI', 'nama_fakultas' => 'Fakultas Ekonomi dan Bisnis Islam'],
            ['kode_fakultas' => 'FU', 'nama_fakultas' => 'Fakultas Ushuluddin'],
            ['kode_fakultas' => 'FITK', 'nama_fakultas' => 'Fakultas Ilmu Tarbiyah dan Keguruan'],
        ];

        foreach ($daftarFakultas as $fak) {
            $fakultas = Fakultas::create($fak);

            foreach (['A', 'B'] as $kodeGedung) {
                $gedung = Gedung::create([
                    'id_fakultas' => $fakultas->id_fakultas,
                    'kode_gedung' => $kodeGedung,
                ]);

                for ($i = 1; $i <= 3; $i++) {
                    Ruang::create([
                        'id_gedung' => $gedung->id_gedung,
                        'nama_ruang' => "Ruang $kodeGedung-10$i",
                    ]);
                }
            }
        }

        // =====================================================
        // DATA BARANG (15 data dummy)
        // =====================================================
        $ruangList = Ruang::all();
        $faker = \Faker\Factory::create('id_ID');
        $kode = 1;

        foreach (range(1, 15) as $i) {
            $ruang = $ruangList->random();
            $kodeBarang = 'BRG-' . str_pad($kode++, 6, '0', STR_PAD_LEFT);

            Barang::create([
                'kode_barang'   => $kodeBarang,
                'nama_barang'   => $faker->word() . ' ' . $faker->randomElement(['Printer', 'Laptop', 'Monitor', 'Meja', 'Kursi']),
                'merek_tipe'    => $faker->randomElement(['Canon LBP2900', 'HP LaserJet 1020', 'Lenovo ThinkCentre', 'LG 24MP59G', 'IKEA Classic']),
                'tanggal_masuk' => $faker->dateTimeBetween('-2 years', 'now'),
                'jumlah'        => $faker->numberBetween(1, 10),
                'nomor_bmn'     => $faker->optional()->numerify('BMN-####'),
                'kondisi'       => $faker->randomElement(['B', 'B', 'RR', 'B', 'B']),
                'foto_surat'    => null,
                'foto_barang'   => null,
                'id_ruang'      => $ruang->id_ruang,
                'kode_barcode'  => "http://localhost:8000/barang/$kodeBarang",
            ]);
        }

        // =====================================================
        // DATA BARANG RUSAK & PINDAH (dummy)
        // =====================================================
        $daftarBarang = Barang::all();

        // ---- Barang Rusak (5 data) ----
        foreach ($daftarBarang->take(5) as $barang) {
            BarangRusak::create([
                'id_barang'     => $barang->id_barang,
                'kondisi_awal'  => 'B',
                'kondisi_baru'  => 'RR',
                'foto_bukti'    => null,
                'tanggal_catat' => Carbon::now()->subDays(rand(5, 100)),
            ]);

            $barang->update(['kondisi' => 'RR']);
        }

        // ---- Barang Pindah (5 data) ----
        foreach ($daftarBarang->skip(5)->take(5) as $barang) {
            $asal = $barang->id_ruang;
            $tujuan = $ruangList->where('id_ruang', '!=', $asal)->random()->id_ruang;

            BarangPindah::create([
                'id_barang'       => $barang->id_barang,
                'id_ruang_asal'   => $asal,
                'id_ruang_tujuan' => $tujuan,
                'file_surat'      => null,
                'tanggal_pindah'  => Carbon::now()->subDays(rand(1, 30)),
            ]);

            $barang->update(['id_ruang' => $tujuan]);
        }
    }
}
