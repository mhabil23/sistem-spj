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
        Schema::create('syarat_dokumen_spjs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_belanja');
            $table->string('jenis_belanja');
            $table->string('nama_dokumen');
            $table->string('assignee', 50)->default('SM'); // 'SM', 'Bendahara', 'PPK'
            $table->integer('urutan')->default(0);
            $table->timestamps();

            $table->index(['nama_belanja', 'jenis_belanja']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syarat_dokumen_spjs');
    }
};
