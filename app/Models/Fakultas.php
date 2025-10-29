<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    protected $table = 'fakultas';
    protected $primaryKey = 'id_fakultas';

    protected $fillable = [
        'kode_fakultas',
        'nama_fakultas',
    ];

    public function gedung()
    {
        return $this->hasMany(Gedung::class, 'id_fakultas', 'id_fakultas');
    }
}
