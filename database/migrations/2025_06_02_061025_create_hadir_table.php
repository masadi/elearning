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
        Schema::create('hadir', function (Blueprint $table) {
            $table->uuid('hadir_id');
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->uuid('sesi_latihan_id');
            $table->decimal('hadir', 1, 0);
            $table->timestamps();
            $table->primary('hadir_id');
            $table->foreign('sesi_latihan_id')->references('sesi_latihan_id')->on('sesi_latihan')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hadir');
    }
};
