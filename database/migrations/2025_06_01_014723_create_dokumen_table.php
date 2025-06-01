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
        Schema::create('dokumen', function (Blueprint $table) {
            $table->uuid('dokumen_id');
            $table->string('nama');
            $table->string('table_name');
            $table->uuid('table_id');
            $table->string('extension', 5)->nullable();
            $table->string('path');
            $table->timestamps();
            $table->primary('dokumen_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen');
    }
};
