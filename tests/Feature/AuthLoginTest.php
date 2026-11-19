<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_can_login_with_username(): void
    {
        $user = User::factory()->create([
            'name' => 'Staf Teknis',
            'username' => 'stafteknis',
            'email' => 'staf@bps.go.id',
            'password' => Hash::make('password123'),
            'role' => 'teknis',
            'status' => 'aktif',
        ]);

        $response = $this->post('/login', [
            'login' => 'stafteknis',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('teknis.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_non_admin_can_login_with_username_case_insensitive(): void
    {
        $user = User::factory()->create([
            'name' => 'Staf Teknis',
            'username' => 'stafteknis',
            'email' => 'staf@bps.go.id',
            'password' => Hash::make('password123'),
            'role' => 'teknis',
            'status' => 'aktif',
        ]);

        $response = $this->post('/login', [
            'login' => 'StafTeknis',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('teknis.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_non_admin_can_login_with_email(): void
    {
        $user = User::factory()->create([
            'name' => 'Petugas Umum',
            'username' => 'petugasumum',
            'email' => 'umum@bps.go.id',
            'password' => Hash::make('password123'),
            'role' => 'umum',
            'status' => 'aktif',
        ]);

        $response = $this->post('/login', [
            'email' => 'umum@bps.go.id',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('umum.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_can_login_with_email(): void
    {
        $admin = User::factory()->create([
            'name' => 'Administrator',
            'username' => null,
            'email' => 'admin@bps.go.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@bps.go.id',
            'password' => 'admin123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_admin_cannot_login_with_username(): void
    {
        $admin = User::factory()->create([
            'name' => 'Administrator',
            'username' => 'superadmin',
            'email' => 'admin@bps.go.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $response = $this->post('/login', [
            'login' => 'superadmin',
            'password' => 'admin123',
        ]);

        $response->assertSessionHasErrors([
            'login' => 'Akun Administrator wajib masuk menggunakan alamat email resmi.',
        ]);
        $this->assertGuest();
    }

    public function test_inactive_user_cannot_login(): void
    {
        User::factory()->create([
            'username' => 'nonaktifuser',
            'email' => 'nonaktif@bps.go.id',
            'password' => Hash::make('password123'),
            'role' => 'teknis',
            'status' => 'non-aktif',
        ]);

        $response = $this->post('/login', [
            'login' => 'nonaktifuser',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'login' => 'Akun Anda sedang dinonaktifkan. Silakan hubungi Administrator.',
        ]);
        $this->assertGuest();
    }
}
