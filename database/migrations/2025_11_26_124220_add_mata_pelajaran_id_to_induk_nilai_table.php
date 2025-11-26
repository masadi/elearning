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
        Schema::table('induk.nilai', function (Blueprint $table) {
            $table->bigInteger('mata_pelajaran_id')->nullable();
            $table->foreign('mata_pelajaran_id')->references('id')->on('induk.mata_pelajaran')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('induk.nilai', function (Blueprint $table) {
            $table->dropForeign(['mata_pelajaran_id']);
            $table->dropColumn('mata_pelajaran_id');
        });
    }
};
