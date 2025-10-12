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
        Schema::create('sekolah', function (Blueprint $table) {
            $table->uuid('sekolah_id');
            $table->foreignId('user_id')->nullable()->index();
            $table->string('nama', 100);
            $table->string('npsn', 10);
            $table->string('alamat', 100)->nullable();
			$table->string('desa_kelurahan', 100)->nullable();
			$table->string('kecamatan', 50)->nullable();
            $table->string('kabupaten', 50)->nullable();
			$table->string('provinsi', 50)->nullable();
			$table->string('kodepos', 7)->nullable();
            $table->string('telpon', 20)->nullable();
			$table->string('fax', 20)->nullable();
			$table->string('email', 50)->nullable();
			$table->string('website', 100)->nullable();
            $table->string('logo')->nullable();
            $table->unsignedTinyInteger('is_default')->default(0)->comment('1=aktif,0=nonaktif');
            $table->softDeletes();
            $table->timestamps();
            $table->primary('sekolah_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sekolah');
    }
};
