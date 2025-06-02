<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_jawaban', function (Blueprint $table) {
            $table->uuid('user_jawaban_id');
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->uuid('tes_id');
            $table->uuid('jawaban_id');
            $table->timestamps();
            $table->primary('user_jawaban_id');
            $table->foreign('tes_id')->references('tes_id')->on('tes_formatif')->cascadeOnDelete();
            $table->foreign('jawaban_id')->references('jawaban_id')->on('jawaban')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_jawaban');
    }
};
