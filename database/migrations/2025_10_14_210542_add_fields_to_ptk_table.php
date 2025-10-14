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
        Schema::table('ptk', function (Blueprint $table) {
            if (Schema::hasColumn('ptk', 'email')) {
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('ptk', 'jenis_kelamin')) {
                $table->dropColumn('jenis_kelamin');
            }
            if (Schema::hasColumn('ptk', 'tempat_lahir')) {
                $table->dropColumn('tempat_lahir');
            }
            if (Schema::hasColumn('ptk', 'tanggal_lahir')) {
                $table->dropColumn('tanggal_lahir');
            }
            $table->string('jabatan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ptk', function (Blueprint $table) {
            $table->dropColumn('jabatan');
        });
    }
};
