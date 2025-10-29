<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangRusak extends Model
{
    protected $table = 'barang_rusak';
    protected $primaryKey = 'id_rusak';
    protected $fillable = [
        'id_barang','kondisi_awal','kondisi_baru','foto_bukti','tanggal_catat'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }
}
