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
        Schema::create('sesi_latihan', function (Blueprint $table) {
            $table->uuid('sesi_latihan_id');
            $table->uuid('pelatihan_id');
            $table->string('judul', 50);
            $table->decimal('urut', 2, 0);
            $table->longText('deskripsi')->nullable();
            $table->timestamps();
            $table->primary('sesi_latihan_id');
            $table->foreign('pelatihan_id')->references('pelatihan_id')->on('pelatihan')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesi_latihan');
    }
};
