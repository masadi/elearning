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
        Schema::create('galeris', function (Blueprint $table) {
            $table->id();
            $table->uuid('sekolah_id')->nullable();
            $table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onDelete('CASCADE');
            $table->string('nama');
            $table->string('slug')->unique();
            $table->string('foto_id_gdrive');
            $table->string('folder_id_gdrive');
            $table->date('tanggal');
            $table->string('lokasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galeris');
    }
};
