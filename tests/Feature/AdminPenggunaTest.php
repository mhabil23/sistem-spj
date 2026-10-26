<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPenggunaTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_pengguna_index(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'aktif',
            'no_wa' => '08123456789',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.pengguna.index'));

        $response->assertStatus(200);
        $response->assertSee('Manajemen Pengguna & Hak Akses', false);
        $response->assertSee('bi bi-people-fill', false);
        $response->assertSee('bi bi-person-plus-fill', false);
        $response->assertSee('bi bi-whatsapp', false);
    }

    public function test_admin_can_view_pengguna_create(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);

        $response = $this->actingAs($admin)->get(route('admin.pengguna.create'));

        $response->assertStatus(200);
        $response->assertSee('Tambah Pengguna Baru');
        $response->assertSee('bi bi-person-plus-fill', false);
    }

    public function test_admin_can_view_pengguna_edit(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
        $targetUser = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $response = $this->actingAs($admin)->get(route('admin.pengguna.edit', $targetUser->id));

        $response->assertStatus(200);
        $response->assertSee('Edit Akun Pengguna');
        $response->assertSee('bi bi-person-gear', false);
    }

    public function test_admin_can_store_new_pengguna(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);

        $response = $this->actingAs($admin)->post(route('admin.pengguna.store'), [
            'name' => 'Budi Santoso',
            'email' => 'budi@bps.go.id',
            'no_wa' => '081298765432',
            'password' => 'rahasia123',
            'role' => 'teknis',
            'status' => 'aktif',
        ]);

        $response->assertRedirect(route('admin.pengguna.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'budi@bps.go.id',
            'role' => 'teknis',
            'no_wa' => '081298765432',
        ]);
    }

    public function test_admin_can_update_pengguna(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
        $targetUser = User::factory()->create(['name' => 'Lama', 'role' => 'teknis']);

        $response = $this->actingAs($admin)->put(route('admin.pengguna.update', $targetUser->id), [
            'name' => 'Baru',
            'email' => $targetUser->email,
            'no_wa' => '08999999999',
            'role' => 'umum',
            'status' => 'aktif',
        ]);

        $response->assertRedirect(route('admin.pengguna.index'));
        $this->assertDatabaseHas('users', [
            'id' => $targetUser->id,
            'name' => 'Baru',
            'role' => 'umum',
            'no_wa' => '08999999999',
        ]);
    }

    public function test_admin_can_delete_pengguna(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
        $targetUser = User::factory()->create(['role' => 'teknis']);

        $response = $this->actingAs($admin)->delete(route('admin.pengguna.destroy', $targetUser->id));

        $response->assertRedirect(route('admin.pengguna.index'));
        $this->assertDatabaseMissing('users', [
            'id' => $targetUser->id,
        ]);
    }
}
