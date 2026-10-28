<?php

use App\Models\Spj;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $spjs = Spj::whereNull('nomor_spj')->orWhere('nomor_spj', '')->orderBy('id')->get();
        foreach ($spjs as $spj) {
            $spj->nomor_spj = Spj::generateNomorSpj($spj->tanggal ?? $spj->created_at ?? now());
            $spj->saveQuietly();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse
    }
};
