<?php

namespace Tests\Feature;

use App\Models\Spj;
use App\Models\SpjHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRiwayatTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_riwayat_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-20261020-001',
            'kegiatan' => 'Pengadaan Alat Tulis Kantor BPS',
            'tanggal' => now(),
            'nilai' => 5000000,
            'status' => 'disetujui_ppspm',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.riwayat'));

        $response->assertStatus(200);
        $response->assertSee('Riwayat Proses &amp; Jejak Audit SPJ', false);
        $response->assertSee('bi bi-clock-history', false);
        $response->assertSee('bi bi-journal-check', false);
        $response->assertSee('bi bi-hourglass-split', false);
        $response->assertSee('bi bi-check2-all', false);
        $response->assertSee('SPJ-20261020-001');
        $response->assertSee('Pengadaan Alat Tulis Kantor BPS');
    }

    public function test_admin_can_filter_riwayat_by_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-20261020-002',
            'kegiatan' => 'Sensus Lapangan',
            'tanggal' => now(),
            'nilai' => 1200000,
            'status' => 'diajukan',
        ]);

        SpjHistory::create([
            'spj_id' => $spj->id,
            'user_id' => $teknis->id,
            'aksi' => 'Pengajuan Dokumen SPJ',
            'status_lama' => 'draft',
            'status_baru' => 'diajukan',
            'keterangan' => 'Pengajuan awal berkas SPJ',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.riwayat', ['status' => 'diajukan']));

        $response->assertStatus(200);
        $response->assertSee('Sensus Lapangan');
    }
}
