<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Pengguna Teknis',
            'username' => 'teknis',
            'email' => 'teknis@example.com',
            'role' => 'teknis',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Pengguna Admin',
            'username' => null, // Admin login menggunakan email resmi
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Pengguna Umum',
            'username' => 'umum',
            'email' => 'umum@example.com',
            'role' => 'umum',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Pengguna PPK',
            'username' => 'ppk',
            'email' => 'ppk@example.com',
            'role' => 'ppk',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Pengguna PPSPM',
            'username' => 'ppspm',
            'email' => 'ppspm@example.com',
            'role' => 'ppspm',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Pengguna Bendahara',
            'username' => 'bendahara',
            'email' => 'bendahara@example.com',
            'role' => 'bendahara',
            'password' => bcrypt('password'),
        ]);

        $this->call(SyaratDokumenSpjSeeder::class);
    }
}
