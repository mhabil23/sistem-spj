<?php

namespace Tests\Feature;

use App\Models\Spj;
use App\Models\SpjHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_admin_dashboard(): void
    {
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $response = $this->actingAs($teknis)->get(route('admin.dashboard'));
        $response->assertForbidden();
    }

    public function test_admin_can_access_dashboard_with_all_metrics(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);
        $umum = User::factory()->create(['role' => 'umum', 'status' => 'aktif']);
        $ppk = User::factory()->create(['role' => 'ppk', 'status' => 'aktif']);
        $ppspm = User::factory()->create(['role' => 'ppspm', 'status' => 'aktif']);

        // Create sample SPJs across multiple stages
        $spj1 = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-DASH-001',
            'kegiatan' => 'Kegiatan Sensus Pertanian 2026',
            'tanggal' => now(),
            'nilai' => 12500000,
            'status' => 'disetujui_ppspm',
        ]);

        $spj2 = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-DASH-002',
            'kegiatan' => 'Rapat Koordinasi Evaluasi SPJ',
            'tanggal' => now(),
            'nilai' => 3500000,
            'status' => 'diajukan',
        ]);

        $spj3 = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-DASH-003',
            'kegiatan' => 'Pelatihan Survei Biaya Hidup',
            'tanggal' => now(),
            'nilai' => 8000000,
            'status' => 'revisi_umum',
        ]);

        // Create history log
        SpjHistory::create([
            'spj_id' => $spj1->id,
            'user_id' => $ppspm->id,
            'aksi' => 'Pengesahan SPM Selesai',
            'status_lama' => 'disetujui_ppk',
            'status_baru' => 'disetujui_ppspm',
            'keterangan' => 'SPM telah disahkan',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);

        // Header & Branding
        $response->assertSee('Dashboard Administrator');
        $response->assertSee('Badan Pusat Statistik', false);
        $response->assertSee('BPS Kolaka Utara');

        // Executive KPI Cards & Interactive Links
        $response->assertSee('Total Berkas SPJ');
        $response->assertSee('Sedang Diproses');
        $response->assertSee('SPJ Disetujui (Selesai)');
        $response->assertSee('Perlu Revisi');

        // Pipeline 4 Tahapan
        $response->assertSee('Monitoring Alur 4 Tahapan SPJ');
        $response->assertSee('Pengajuan Awal');
        $response->assertSee('Verifikasi Berkas');
        $response->assertSee('Persetujuan PPK');
        $response->assertSee('Pengesahan SPM');

        // Table & Activities
        $response->assertSee('Kegiatan Sensus Pertanian 2026');
        $response->assertSee('12.500.000');
        $response->assertSee('Rapat Koordinasi Evaluasi SPJ');
        $response->assertSee('Aktivitas Terkini');

        // User stats
        $response->assertSee('Pengguna Sistem');
        $response->assertSee('Total Akun Terdaftar');
        $response->assertSee('Akun Berstatus Aktif');
    }

    public function test_admin_dashboard_renders_cleanly_when_empty(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Belum ada berkas SPJ yang terdaftar dalam sistem.');
        $response->assertSee('Belum ada aktivitas tercatat.');
    }
}
