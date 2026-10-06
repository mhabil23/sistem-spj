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
            $table->string('nomor_spm')->nullable()->after('nomor_spj');
            $table->text('catatan_ppspm')->nullable()->after('catatan_ppk');
            $table->timestamp('disetujui_ppk_at')->nullable()->after('disetujui_umum_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spjs', function (Blueprint $table) {
            $table->dropColumn(['nomor_spm', 'catatan_ppspm', 'disetujui_ppk_at']);
        });
    }
};
