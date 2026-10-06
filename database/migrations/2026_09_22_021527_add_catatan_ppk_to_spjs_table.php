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
            $table->text('catatan_ppk')->nullable()->after('catatan_internal');
            $table->timestamp('disetujui_umum_at')->nullable()->after('diajukan_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spjs', function (Blueprint $table) {
            $table->dropColumn(['catatan_ppk', 'disetujui_umum_at']);
        });
    }
};
