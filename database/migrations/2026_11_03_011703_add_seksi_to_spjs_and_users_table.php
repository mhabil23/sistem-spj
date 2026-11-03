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
        Schema::table('users', function (Blueprint $table) {
            $table->string('seksi', 50)->nullable()->after('role');
        });

        Schema::table('spjs', function (Blueprint $table) {
            $table->string('seksi', 50)->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spjs', function (Blueprint $table) {
            $table->dropColumn('seksi');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('seksi');
        });
    }
};
