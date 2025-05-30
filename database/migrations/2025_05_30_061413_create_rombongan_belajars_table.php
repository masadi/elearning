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
        Schema::create('rombongan_belajar', function (Blueprint $table) {
            $table->uuid('rombongan_belajar_id');
            $table->uuid('sekolah_id');
            $table->uuid('ptk_id');
            $table->string('semester_id', 5);
			$table->string('nama');
            $table->decimal('tingkat', 1, 0);
            $table->timestamps();
            $table->softDeletes();
            $table->primary('rombongan_belajar_id');
            $table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onDelete('CASCADE');
            $table->foreign('ptk_id')->references('ptk_id')->on('ptk')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rombongan_belajar');
    }
};
