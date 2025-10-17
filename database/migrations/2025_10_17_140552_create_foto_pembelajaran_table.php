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
        Schema::create('foto_pembelajaran', function (Blueprint $table) {
            $table->id();
            $table->uuid('pembelajaran_id')->nullable();
            $table->string('foto');
            $table->unsignedInteger('urut');
            $table->timestamps();
            $table->foreign('pembelajaran_id')->references('pembelajaran_id')->on('pembelajaran')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_pembelajaran');
    }
};
