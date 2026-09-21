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
        Schema::create('spjs', function (Blueprint $table) {
            $table->id();

            $table->string('nomor_spj')->unique();
            $table->string('kegiatan');
            $table->date('tanggal');
            $table->decimal('nilai', 15, 2);
            $table->text('keterangan')->nullable();

            $table->enum('status', [
                'draft',
                'diajukan',
                'diproses',
                'selesai',
                'dikembalikan'
            ])->default('draft');

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spjs');
    }
};
