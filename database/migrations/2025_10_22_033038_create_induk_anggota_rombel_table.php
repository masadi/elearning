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
        Schema::create('induk.anggota_rombel', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('rombongan_belajar_id');
			$table->uuid('peserta_didik_id');
            $table->timestamps();
            $table->foreign('rombongan_belajar_id')->references('id')->on('induk.rombongan_belajar')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('peserta_didik_id')->references('id')->on('induk.peserta_didik')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('induk.anggota_rombel');
    }
};
