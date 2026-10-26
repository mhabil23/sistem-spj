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
        Schema::table('spjs', function (Blueprint $table) {
            $table->timestamp('berkas_fisik_diterima_at')->nullable()->after('diajukan_at');
            $table->timestamp('pemeriksaan_dimulai_at')->nullable()->after('berkas_fisik_diterima_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spjs', function (Blueprint $table) {
            $table->dropColumn(['berkas_fisik_diterima_at', 'pemeriksaan_dimulai_at']);
        });
    }
};
