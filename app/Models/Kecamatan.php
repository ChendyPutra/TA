<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'kecamatans';
    protected $primaryKey = 'kecamatan_id'; // tambahkan ini

    protected $fillable = [
        'nama_kecamatan',
        'luas_kecamatan',
        'warna',
        'polygon_kecamatan',
    ];

    public function wilayahPertanian()
    {
        return $this->hasMany(WilayahPertanian::class, 'kecamatan_id', 'kecamatan_id');
    }
}
