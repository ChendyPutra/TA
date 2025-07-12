<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wilayah_pertanian', function (Blueprint $table) {
            $table->id();
            $table->string('nama_komoditas');
            $table->unsignedBigInteger('kecamatan_id'); // Menggunakan kecamatan_id sebagai foreign key
            $table->string('warna');
            $table->text('polygon'); // GeoJSON
            $table->longText('polygon_kecamatan')->nullable(); // Simpan GeoJSON polygon kecamatan
            $table->double('luas_wilayah'); // dalam hektar
            $table->integer('jumlah_komoditas')->default(0);
            $table->timestamps();
           
        });
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wilayah_pertanian');
    }
};
