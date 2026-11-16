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
            if (! Schema::hasColumn('spjs', 'nama_belanja')) {
                $table->string('nama_belanja')->nullable()->after('seksi');
            }
            if (! Schema::hasColumn('spjs', 'jenis_belanja')) {
                $table->string('jenis_belanja')->nullable()->after('nama_belanja');
            }
            if (! Schema::hasColumn('spjs', 'peruntukan')) {
                $table->string('peruntukan')->nullable()->after('jenis_belanja');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spjs', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('spjs', 'nama_belanja')) {
                $columnsToDrop[] = 'nama_belanja';
            }
            if (Schema::hasColumn('spjs', 'jenis_belanja')) {
                $columnsToDrop[] = 'jenis_belanja';
            }
            if (Schema::hasColumn('spjs', 'peruntukan')) {
                $columnsToDrop[] = 'peruntukan';
            }
            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
