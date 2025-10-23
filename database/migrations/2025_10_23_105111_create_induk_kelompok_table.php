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
        Schema::create('induk.kelompok', function (Blueprint $table) {
            $table->id();
            $table->uuid('sekolah_id');
            $table->string('nama');
            $table->timestamps();
            $table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('induk.kelompok');
    }
};
