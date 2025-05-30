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
        Schema::create('ptk', function (Blueprint $table) {
            $table->uuid('ptk_id');
            $table->uuid('sekolah_id');
			$table->string('nama');
			$table->string('nuptk', 16)->nullable();
			$table->string('email');
			$table->string('jenis_kelamin', 1);
			$table->string('tempat_lahir', 50);
			$table->date('tanggal_lahir');
			$table->string('nik', 16)->nullable();
            $table->string('avatar')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->primary('ptk_id');
            $table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ptk');
    }
};
