<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            // store winners per position, e.g. {"President": {"name":"Alice","votes":120}, ...}
            $table->json('winners')->nullable()->after('status');
        });
    }


    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropColumn('winners');
        });
    }
};
