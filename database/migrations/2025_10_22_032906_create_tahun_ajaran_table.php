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
        Schema::create('induk.tahun_ajaran', function (Blueprint $table) {
            $table->decimal('tahun_ajaran_id', 4, 0);
            $table->string('nama', 10);
            $table->timestamps();
            $table->primary('tahun_ajaran_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('induk.tahun_ajaran');
    }
};
