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
        Schema::table('induk.pembelajaran', function (Blueprint $table) {
            $table->foreign('kelompok_id')->references('id')->on('induk.kelompok')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('induk.pembelajaran', function (Blueprint $table) {
            $table->dropForeign(['kelompok_id']);
        });
    }
};
