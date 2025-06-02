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
        Schema::create('user_tes', function (Blueprint $table) {
            $table->uuid('user_tes_id');
            $table->uuid('sesi_latihan_id');
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->decimal('status', 1, 0)->default(0);
            $table->timestamps();
            $table->primary('user_tes_id');
            $table->foreign('sesi_latihan_id')->references('sesi_latihan_id')->on('sesi_latihan')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_tes');
    }
};
