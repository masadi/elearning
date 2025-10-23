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
        Schema::create('induk.peserta_didik', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('sekolah_id');
			$table->string('nama');
			$table->string('nipd');
			$table->string('nisn')->nullable();
			$table->string('jenis_kelamin');
			$table->string('tempat_lahir');
			$table->date('tanggal_lahir');
			$table->integer('agama_id');
			$table->string('alamat')->nullable();
			$table->string('telepon')->nullable();
			$table->string('nama_ayah')->nullable();
			$table->string('nama_ibu')->nullable();
			$table->integer('kerja_ayah')->nullable();
			$table->integer('kerja_ibu')->nullable();
            $table->integer('agama_ayah')->nullable();
			$table->integer('agama_ibu')->nullable();
			$table->string('nama_wali')->nullable();
			$table->integer('kerja_wali')->nullable();
            $table->integer('agama_wali')->nullable();
            $table->string('alamat_wali')->nullable();
			$table->string('sekolah_asal')->nullable();
            $table->date('diterima')->nullable();
			$table->string('surat_pindah')->nullable();
			$table->string('nipd_asal')->nullable();
            $table->string('program_pilihan')->nullable();
            $table->string('nilai_un')->nullable();
            $table->string('avatar')->nullable();
            $table->timestamps();
            $table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('induk.peserta_didik');
    }
};
