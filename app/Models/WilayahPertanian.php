<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WilayahPertanian extends Model
{
    use HasFactory;

    protected $table = 'wilayah_pertanian';

   protected $fillable = [
    'komoditas_id',
    'kecamatan_id',
    'warna',
    'polygon',
    'luas_wilayah',
    'jumlah_komoditas',
    'bidang_id',
];

     // Relasi ke model Kecamatan
     public function kecamatan()
     {
         return $this->belongsTo(Kecamatan::class, 'kecamatan_id');  // Menggunakan 'kecamatan_id' untuk relasi
     }

     public function bidang()
{
    return $this->belongsTo(Bidang::class);
}
public function komoditas()
{
    return $this->belongsTo(Komoditas::class, 'komoditas_id');
}

}