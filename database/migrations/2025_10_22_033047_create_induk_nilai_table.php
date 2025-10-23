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
        Schema::create('induk.nilai', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('anggota_rombel_id');
            $table->uuid('pembelajaran_id');
            $table->unsignedInteger('nilai');
            $table->timestamps();
            $table->foreign('anggota_rombel_id')->references('id')->on('induk.anggota_rombel')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('pembelajaran_id')->references('id')->on('induk.pembelajaran')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('induk.nilai');
    }
};
