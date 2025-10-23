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
        Schema::create('induk.rombongan_belajar', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('sekolah_id');
            $table->decimal('semester_id', 5, 0);
            $table->string('nama');
            $table->unsignedTinyInteger('tingkat');
            $table->timestamps();
            $table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('semester_id')->references('semester_id')->on('induk.semester')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('induk.rombongan_belajar');
    }
};
