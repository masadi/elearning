<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\SesiLatihan;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('materi_sesi', function (Blueprint $table) {
            $table->uuid('materi_sesi_id');
            $table->uuid('sesi_latihan_id');
            $table->string('judul', 100);
            $table->longText('deskripsi')->nullable();
            $table->timestamps();
            $table->primary('materi_sesi_id');
            $table->foreign('sesi_latihan_id')->references('sesi_latihan_id')->on('sesi_latihan')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materi_sesi');
    }
};
