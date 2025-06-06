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
        Schema::table('sesi_latihan', function (Blueprint $table) {
            $table->unsignedInteger('bobot_hadir')->nullable();
            $table->unsignedInteger('bobot_materi')->nullable();
            $table->unsignedInteger('bobot_tugas')->nullable();
            $table->unsignedInteger('bobot_tes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sesi_latihan', function (Blueprint $table) {
            $table->dropColumn(['bobot_hadir', 'bobot_materi', 'bobot_tugas', 'bobot_tes']);
        });
    }
};
