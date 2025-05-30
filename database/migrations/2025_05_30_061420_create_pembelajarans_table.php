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
        Schema::create('pembelajaran', function (Blueprint $table) {
            $table->uuid('pembelajaran_id');
            $table->uuid('rombongan_belajar_id');
            $table->uuid('ptk_id');
			$table->string('nama_mata_pelajaran');
            $table->timestamps();
            $table->softDeletes();
            $table->primary('pembelajaran_id');
            $table->foreign('rombongan_belajar_id')->references('rombongan_belajar_id')->on('rombongan_belajar')->onDelete('CASCADE');
            $table->foreign('ptk_id')->references('ptk_id')->on('ptk')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelajaran');
    }
};
