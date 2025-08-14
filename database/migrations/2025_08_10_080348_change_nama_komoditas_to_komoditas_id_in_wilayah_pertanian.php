<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('wilayah_pertanian', function (Blueprint $table) {
                if (Schema::hasColumn('wilayah_pertanian', 'nama_komoditas')) {
        $table->dropColumn('nama_komoditas');
    }

    if (!Schema::hasColumn('wilayah_pertanian', 'komoditas_id')) {
        $table->unsignedBigInteger('komoditas_id')->after('id');
        $table->foreign('komoditas_id')->references('id')->on('komoditas')->onDelete('cascade');
    }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wilayah_pertanian', function (Blueprint $table) {
            //
        });
    }
};
