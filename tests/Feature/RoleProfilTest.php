<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleProfilTest extends TestCase
{
    use RefreshDatabase;

    public function test_teknis_cannot_change_email_without_current_password(): void
    {
        $user = User::factory()->create([
            'role' => 'teknis',
            'email' => 'teknis.lama@bps.go.id',
            'password' => 'password123',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($user)->put(route('teknis.profil.update'), [
            'name' => 'Teknis User',
            'email' => 'teknis.baru@bps.go.id',
            'current_password' => '',
        ]);

        $response->assertSessionHasErrors(['current_password']);
        $this->assertSame('teknis.lama@bps.go.id', $user->fresh()->email);
    }

    public function test_teknis_can_change_email_with_correct_current_password(): void
    {
        $user = User::factory()->create([
            'role' => 'teknis',
            'email' => 'teknis.lama@bps.go.id',
            'password' => 'password123',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($user)->put(route('teknis.profil.update'), [
            'name' => 'Teknis User',
            'email' => 'teknis.baru@bps.go.id',
            'current_password' => 'password123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertSame('teknis.baru@bps.go.id', $user->fresh()->email);
    }

    public function test_umum_cannot_change_email_without_current_password(): void
    {
        $user = User::factory()->create([
            'role' => 'umum',
            'email' => 'umum.lama@bps.go.id',
            'password' => 'password123',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($user)->put(route('umum.profil.update'), [
            'name' => 'Umum User',
            'email' => 'umum.baru@bps.go.id',
            'current_password' => '',
        ]);

        $response->assertSessionHasErrors(['current_password']);
        $this->assertSame('umum.lama@bps.go.id', $user->fresh()->email);
    }

    public function test_umum_can_change_email_with_correct_current_password(): void
    {
        $user = User::factory()->create([
            'role' => 'umum',
            'email' => 'umum.lama@bps.go.id',
            'password' => 'password123',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($user)->put(route('umum.profil.update'), [
            'name' => 'Umum User',
            'email' => 'umum.baru@bps.go.id',
            'current_password' => 'password123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertSame('umum.baru@bps.go.id', $user->fresh()->email);
    }

    public function test_admin_can_view_profile_page(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@bps.go.id',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.profil.index'));

        $response->assertStatus(200);
        $response->assertSee('admin@bps.go.id');
        $response->assertSee('Administrator Utama');
    }

    public function test_admin_cannot_change_email_without_current_password(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin.lama@bps.go.id',
            'password' => 'password123',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.profil.update'), [
            'name' => 'Admin User',
            'email' => 'admin.baru@bps.go.id',
            'current_password' => '',
        ]);

        $response->assertSessionHasErrors(['current_password']);
        $this->assertSame('admin.lama@bps.go.id', $admin->fresh()->email);
    }

    public function test_admin_can_change_email_with_correct_current_password(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin.lama@bps.go.id',
            'password' => 'password123',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.profil.update'), [
            'name' => 'Admin User',
            'email' => 'admin.baru@bps.go.id',
            'current_password' => 'password123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertSame('admin.baru@bps.go.id', $admin->fresh()->email);
    }

    public function test_role_cannot_change_email_to_already_taken_email(): void
    {
        User::factory()->create(['email' => 'taken@bps.go.id', 'status' => 'aktif']);

        $user = User::factory()->create([
            'role' => 'teknis',
            'email' => 'user@bps.go.id',
            'password' => 'password123',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($user)->put(route('teknis.profil.update'), [
            'name' => $user->name,
            'email' => 'taken@bps.go.id',
            'current_password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertSame('user@bps.go.id', $user->fresh()->email);
    }
}
