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

    public function test_admin_can_create_spj_for_umum(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $createResponse = $this->actingAs($admin)->get('/admin/spj/create');
        $createResponse->assertStatus(200);

        $storeResponse = $this->actingAs($admin)->post('/admin/spj', [
            'peruntukan' => 'umum',
            'nama_belanja' => 'Belanja Gaji PNS (5111)',
            'jenis_belanja' => 'Belanja Gaji PNS',
            'kegiatan' => 'Belanja Gaji PNS Bulan November 2026',
            'tanggal' => '2026-11-04',
            'nilai' => '15.000.000',
            'status' => 'draft',
            'nama_dokumen' => ['0' => 'KAK', '1' => 'FP (dari BOS)'],
            'assignee_dokumen' => ['0' => 'SM', '1' => 'SM'],
            'berkas' => ['0' => '1', '1' => '1'],
        ]);
        $storeResponse->assertRedirect(route('admin.spj.index'));

        $this->assertDatabaseHas('spjs', [
            'kegiatan' => 'Belanja Gaji PNS Bulan November 2026',
            'seksi' => 'Subbag Umum',
            'nilai' => 15000000,
        ]);
    }
}
