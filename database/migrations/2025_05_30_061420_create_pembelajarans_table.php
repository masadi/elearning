<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\RombonganBelajar;
use App\Models\Ptk;
use App\Models\MataPelajaran;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pembelajaran', function (Blueprint $table) {
            $table->uuid('pembelajaran_id');
            $table->uuid('sekolah_id')->nullable();
            $table->foreignIdFor(MataPelajaran::class);
			$table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->unsignedTinyInteger('status')->default(1)->comment('1=aktif,0=nonaktif');
            $table->timestamps();
            $table->softDeletes();
            $table->primary('pembelajaran_id');
            $table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onDelete('CASCADE');
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
