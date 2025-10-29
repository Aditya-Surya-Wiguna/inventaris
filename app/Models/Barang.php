<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'merek_tipe',
        'tanggal_masuk',
        'jumlah',
        'nomor_bmn',
        'kondisi',
        'foto_surat',
        'foto_barang',
        'id_ruang',
        'kode_barcode',
    ];

    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'id_ruang', 'id_ruang');
    }

    public function rusak()
    {
        return $this->hasMany(BarangRusak::class, 'id_barang', 'id_barang');
    }

    public function pindah()
    {
        return $this->hasMany(BarangPindah::class, 'id_barang', 'id_barang');
    }
}
