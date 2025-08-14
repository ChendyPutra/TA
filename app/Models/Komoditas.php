<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komoditas extends Model
{
    use HasFactory;

    protected $table = 'komoditas'; // Nama tabel
    protected $fillable = ['nama'];

    // Relasi ke WilayahPertanian
    public function wilayah()
    {
        return $this->hasMany(WilayahPertanian::class, 'komoditas_id');
    }
}
