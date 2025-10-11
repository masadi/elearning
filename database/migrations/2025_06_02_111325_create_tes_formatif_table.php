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
        Schema::create('tes_formatif', function (Blueprint $table) {
            $table->uuid('tes_id');
            $table->uuid('pembelajaran_id')->nullable();
            $table->longText('deskripsi')->nullable();
            $table->timestamps();
            $table->primary('tes_id');
            $table->foreign('pembelajaran_id')->references('pembelajaran_id')->on('pembelajaran')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tes_formatif');
    }
};
