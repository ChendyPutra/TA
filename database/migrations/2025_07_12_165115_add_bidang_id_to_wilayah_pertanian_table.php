<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('wilayah_pertanian', function (Blueprint $table) {
        $table->foreignId('bidang_id')->nullable()->constrained('bidangs')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('wilayah_pertanian', function (Blueprint $table) {
        $table->dropForeign(['bidang_id']);
        $table->dropColumn('bidang_id');
    });
}
};
