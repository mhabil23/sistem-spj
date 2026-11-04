<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UmumSpjCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_umum_user_can_access_create_spj_page(): void
    {
        $umum = User::factory()->create(['role' => 'umum']);

        $response = $this->actingAs($umum)->get(route('umum.spj.create'));

        $response->assertStatus(200);
        $response->assertSee('Formulir Pengajuan SPJ Baru');
        $response->assertSee('Subbag Umum');
    }

    public function test_umum_user_can_store_spj(): void
    {
        $umum = User::factory()->create(['role' => 'umum', 'seksi' => 'Subbag Umum']);

        $payload = [
            'nomor_spj' => 'SPJ-UMUM-2026-001',
            'kegiatan' => 'Pengadaan Perlengkapan Kantor Subbag Umum',
            'tanggal' => '2026-11-04',
            'nilai' => 2500000,
            'seksi' => 'Subbag Umum',
            'status' => 'diajukan',
            'is_printed' => '1',
            'nama_dokumen' => ['KAK', 'BAST'],
            'assignee_dokumen' => ['SM', 'SM'],
            'berkas' => [0 => '1', 1 => '1'],
        ];

        $response = $this->actingAs($umum)->post(route('umum.spj.store'), $payload);

        $response->assertRedirect(route('umum.spj.index'));
        $this->assertDatabaseHas('spjs', [
            'nomor_spj' => 'SPJ-UMUM-2026-001',
            'user_id' => $umum->id,
            'seksi' => 'Subbag Umum',
            'status' => 'diajukan',
        ]);
    }

    public function test_admin_cannot_access_create_spj_or_store_spj(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Rute pengajuan SPJ oleh admin ditiadakan (404 Not Found)
        $createResponse = $this->actingAs($admin)->get('/admin/spj/create');
        $createResponse->assertStatus(404);

        $storeResponse = $this->actingAs($admin)->post('/admin/spj', [
            'kegiatan' => 'Coba Input Admin',
            'tanggal' => '2026-11-04',
            'nilai' => 100000,
            'status' => 'draft',
        ]);
        $storeResponse->assertStatus(405);
        $this->assertDatabaseMissing('spjs', [
            'kegiatan' => 'Coba Input Admin',
        ]);
    }
}
