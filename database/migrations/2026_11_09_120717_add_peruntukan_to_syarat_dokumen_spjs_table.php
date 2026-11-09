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
        Schema::table('syarat_dokumen_spjs', function (Blueprint $table) {
            $table->string('peruntukan', 20)->default('semua')->after('assignee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('syarat_dokumen_spjs', function (Blueprint $table) {
            $table->dropColumn('peruntukan');
        });
    }
};
