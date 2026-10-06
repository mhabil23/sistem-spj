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
            $table->boolean('kel_daftar_penerima')->default(false);
            $table->boolean('kel_bast')->default(false);
            $table->boolean('kel_sk')->default(false);
            $table->boolean('kel_kak')->default(false);
            $table->boolean('kel_form_permintaan')->default(false);
            $table->boolean('kel_spk')->default(false);
            $table->boolean('kel_surat_tugas')->default(false);
            $table->boolean('kel_kesesuaian_mrk')->default(false);
            $table->boolean('kel_cms')->default(false);
            $table->boolean('kel_cek_sbks')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spjs', function (Blueprint $table) {
            $table->dropColumn([
                'kel_daftar_penerima',
                'kel_bast',
                'kel_sk',
                'kel_kak',
                'kel_form_permintaan',
                'kel_spk',
                'kel_surat_tugas',
                'kel_kesesuaian_mrk',
                'kel_cms',
                'kel_cek_sbks',
            ]);
        });
    }
};
