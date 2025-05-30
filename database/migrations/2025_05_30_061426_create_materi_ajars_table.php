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
        Schema::create('materi_ajar', function (Blueprint $table) {
            $table->uuid('materi_ajar_id');
            $table->uuid('pembelajaran_id');
            $table->string('judul', 100);
            $table->longText('deskripsi');
            $table->timestamps();
            $table->softDeletes();
            $table->primary('materi_ajar_id');
            $table->foreign('pembelajaran_id')->references('pembelajaran_id')->on('pembelajaran')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materi_ajar');
    }
};
