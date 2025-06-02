<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dokumen_tugas', function (Blueprint $table) {
            $table->uuid('dokumen_tugas_id');
            $table->uuid('sesi_latihan_id');
            $table->foreignIdFor(User::class)->nullable()->constrained()->nullOnDelete();
            $table->string('nama');
            $table->string('extension', 5)->nullable();
            $table->string('path');
            $table->timestamps();
            $table->primary('dokumen_tugas_id');
            $table->foreign('sesi_latihan_id')->references('sesi_latihan_id')->on('sesi_latihan')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_tugas');
    }
};
