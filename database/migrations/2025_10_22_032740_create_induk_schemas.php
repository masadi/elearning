<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::select("CREATE SCHEMA induk");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::select("DROP SCHEMA ref CASCADE");
    }
};
