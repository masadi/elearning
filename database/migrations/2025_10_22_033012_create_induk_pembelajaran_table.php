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
        Schema::create('induk.pembelajaran', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('rombongan_belajar_id');
            $table->bigInteger('mata_pelajaran_id');
            $table->bigInteger('kelompok_id');
            $table->tinyInteger('nomor_urut');
            $table->timestamps();
            $table->foreign('rombongan_belajar_id')->references('id')->on('induk.rombongan_belajar')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('mata_pelajaran_id')->references('id')->on('induk.mata_pelajaran')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('induk.pembelajaran');
    }
};
