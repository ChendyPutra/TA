<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WilayahPertanian extends Model
{
    use HasFactory;

    protected $table = 'wilayah_pertanian';

    protected $fillable = [
        'nama_komoditas',
        'luas_wilayah',
        'kecamatan_id',  // Foreign key untuk kecamatan
        'warna',
        'polygon',
        'polygon_kecamatan',
        'jumlah_komoditas',
    ];
     // Relasi ke model Kecamatan
     public function kecamatan()
     {
         return $this->belongsTo(Kecamatan::class, 'kecamatan_id');  // Menggunakan 'kecamatan_id' untuk relasi
     }
}