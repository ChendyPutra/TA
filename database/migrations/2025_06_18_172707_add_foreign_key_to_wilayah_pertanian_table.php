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
        Schema::table('wilayah_pertanian', function (Blueprint $table) {
            // 1. Pastikan kolom 'kecamatan_id' bisa NULL
            //    Ini penting agar 'onDelete('set null')' bisa berfungsi.
            //    Jika kolom sudah ada dan tidak nullable, ini akan mengubahnya.
            $table->unsignedBigInteger('kecamatan_id')->nullable()->change();

            // 2. Tambahkan foreign key constraint
            //    Pastikan nama kolom yang dirujuk di tabel 'kecamatans' adalah 'kecamatan_id'
            //    (ini asumsi dari code Blade Anda sebelumnya `$w->kecamatan->nama_kecamatan`)
            $table->foreign('kecamatan_id')
                  ->references('kecamatan_id') // Kolom primary key di tabel 'kecamatans'
                  ->on('kecamatans')         // Nama tabel 'kecamatans'
                  ->onDelete('cascade');    // Saat kecamatan dihapus, kecamatan_id di sini jadi NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wilayah_pertanian', function (Blueprint $table) {
            // Hapus foreign key terlebih dahulu
            $table->dropForeign(['kecamatan_id']);

            // Ubah kembali kolom menjadi non-nullable jika Anda mau,
            // tapi biasanya tidak perlu di down() jika tujuan utamanya adalah mengatasi masalah null.
            // Atau Anda bisa biarkan saja, karena dropForeign sudah cukup untuk rollback.
            // $table->unsignedBigInteger('kecamatan_id')->nullable(false)->change();
        });
    }
};