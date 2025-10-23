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
        Schema::create('induk.semester', function (Blueprint $table) {
            $table->decimal('semester_id', 5, 0);
            $table->decimal('tahun_ajaran_id', 4, 0);
			$table->string('nama');
            $table->timestamps();
            $table->primary('semester_id');
			$table->foreign('tahun_ajaran_id')->references('tahun_ajaran_id')->on('induk.tahun_ajaran')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('induk.semester');
    }
};
