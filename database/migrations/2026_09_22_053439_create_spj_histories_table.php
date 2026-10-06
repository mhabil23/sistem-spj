<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spj_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('spj_id')
                ->constrained('spjs')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('aksi');

            $table->string('status_lama')->nullable();

            $table->string('status_baru')->nullable();

            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spj_histories');
    }
};
