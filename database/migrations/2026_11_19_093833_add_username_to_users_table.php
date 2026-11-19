<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
        });

        // Generate username awal otomatis untuk pengguna non-admin yang sudah ada di database
        $users = DB::table('users')->where('role', '!=', 'admin')->get();
        $usedUsernames = [];

        foreach ($users as $user) {
            $candidate = null;
            if (! empty($user->seksi)) {
                $cleanSeksi = preg_replace('/^seksi\s+/i', '', trim($user->seksi));
                $candidate = Str::slug($cleanSeksi, '_');
            }
            if (empty($candidate)) {
                $candidate = Str::slug($user->role, '_');
            }
            if (empty($candidate) && ! empty($user->name)) {
                $candidate = Str::slug($user->name, '_');
            }
            if (empty($candidate)) {
                $candidate = 'user';
            }

            $base = strtolower($candidate);
            $finalUsername = $base;
            $counter = 1;
            while (in_array($finalUsername, $usedUsernames) || DB::table('users')->where('username', $finalUsername)->exists()) {
                $finalUsername = $base.$counter;
                $counter++;
            }

            $usedUsernames[] = $finalUsername;
            DB::table('users')->where('id', $user->id)->update(['username' => $finalUsername]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
        });
    }
};
