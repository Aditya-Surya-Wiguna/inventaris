<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gedung extends Model
{
    protected $table = 'gedung';
    protected $primaryKey = 'id_gedung';

    protected $fillable = [
        'id_fakultas',
        'kode_gedung',
    ];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'id_fakultas', 'id_fakultas');
    }

    public function ruang()
    {
        return $this->hasMany(Ruang::class, 'id_gedung', 'id_gedung');
    }
}
