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
        Schema::table('induk.peserta_didik', function (Blueprint $table) {
            $table->string('nopes')->nullable();
            $table->string('nomor_seri_rapor')->nullable();
            $table->string('nomor_seri_ijazah')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('induk.peserta_didik', function (Blueprint $table) {
            $table->dropColumn(['nopes', 'nomor_seri_rapor', 'nomor_seri_ijazah']);
        });
    }
};
