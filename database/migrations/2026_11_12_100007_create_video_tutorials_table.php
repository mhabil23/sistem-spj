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
        Schema::create('video_tutorials', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori')->default('semua'); // semua, teknis, umum, ppk, ppspm, bendahara
            $table->string('tipe_sumber')->default('link'); // link, upload
            $table->string('url_video')->nullable();
            $table->string('file_video')->nullable();
            $table->string('youtube_id', 50)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('durasi', 20)->nullable();
            $table->integer('urutan')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_tutorials');
    }
};
