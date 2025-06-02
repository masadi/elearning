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
        Schema::create('jawaban', function (Blueprint $table) {
            $table->uuid('jawaban_id');
            $table->uuid('tes_id');
            $table->string('opsi', 1);
            $table->longText('deskripsi')->nullable();
            $table->decimal('benar', 1, 0);
            $table->timestamps();
            $table->primary('jawaban_id');
            $table->foreign('tes_id')->references('tes_id')->on('tes_formatif')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban');
    }
};
