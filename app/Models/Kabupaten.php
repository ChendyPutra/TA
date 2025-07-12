<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
     protected $table = 'kabupaten';
    protected $primaryKey = 'kabupaten_id';

    protected $fillable = [
        'nama_kabupaten',
        'luas_kabupaten',
        'warna_kabupaten',
        'polygon_kabupaten',
    ];
}
