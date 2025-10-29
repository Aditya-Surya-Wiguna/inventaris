<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangPindah extends Model
{
    protected $table = 'barang_pindah';
    protected $primaryKey = 'id_pindah';
    protected $fillable = [
        'id_barang','id_ruang_asal','id_ruang_tujuan','file_surat','tanggal_pindah'
    ];

    // Barang yang dipindah
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    // Ruang asal
    public function asal()
    {
        return $this->belongsTo(Ruang::class, 'id_ruang_asal', 'id_ruang');
    }

    // Ruang tujuan
    public function tujuan()
    {
        return $this->belongsTo(Ruang::class, 'id_ruang_tujuan', 'id_ruang');
    }
}
