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
        Schema::create('kecamatans', function (Blueprint $table) {
            $table->id('kecamatan_id');
            $table->string('nama_kecamatan');
            $table->string('warna');
            $table->text('polygon_kecamatan'); // Simpan GeoJSON polygon kecamatan
            $table->double('luas_kecamatan'); // dalam hektar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kecamatans');
    }
};
