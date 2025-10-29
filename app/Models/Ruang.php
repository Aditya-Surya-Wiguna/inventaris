<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruang extends Model
{
    protected $table = 'ruang';
    protected $primaryKey = 'id_ruang';

    protected $fillable = [
        'id_gedung',
        'nama_ruang',
    ];

    public function gedung()
    {
        return $this->belongsTo(Gedung::class, 'id_gedung', 'id_gedung');
    }

    public function barang()
    {
        return $this->hasMany(Barang::class, 'id_ruang', 'id_ruang');
    }
}
