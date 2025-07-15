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
        Schema::table('admins', function (Blueprint $table) {
            $table->enum('role', ['superadmin', 'memberadmin'])->default('memberadmin');
            $table->unsignedBigInteger('bidang_id')->nullable()->after('role');

            $table->foreign('bidang_id')->references('id')->on('bidangs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropForeign(['bidang_id']);
            $table->dropColumn(['role', 'bidang_id']);
        });
    }
};
