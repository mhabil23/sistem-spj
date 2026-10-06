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
            $table->string('bukti_transfer')->nullable()->after('nomor_spm');
            $table->timestamp('disetujui_ppspm_at')->nullable()->after('disetujui_ppk_at');
            $table->timestamp('diselesaikan_at')->nullable()->after('disetujui_ppspm_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spjs', function (Blueprint $table) {
            $table->dropColumn(['bukti_transfer', 'disetujui_ppspm_at', 'diselesaikan_at']);
        });
    }
};
