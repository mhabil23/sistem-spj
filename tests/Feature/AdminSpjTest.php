<?php

namespace Tests\Feature;

use App\Models\Spj;
use App\Models\SpjHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSpjTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_spj_show_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-ADMIN-SHOW-001',
            'kegiatan' => 'Pengadaan Laptop dan Perangkat IT',
            'tanggal' => now(),
            'nilai' => 25000000,
            'status' => 'disetujui_ppspm',
            'nomor_spm' => 'SPM-2026-001',
            'keterangan' => 'Pengadaan untuk staf teknis sensus',
        ]);

        SpjHistory::create([
            'spj_id' => $spj->id,
            'user_id' => $admin->id,
            'aksi' => 'Verifikasi Berkas',
            'status_lama' => 'disetujui_ppk',
            'status_baru' => 'disetujui_ppspm',
            'keterangan' => 'SPM diterbitkan',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.spj.show', $spj->id));

        $response->assertStatus(200);
        $response->assertSee('SPJ-ADMIN-SHOW-001');
        $response->assertSee('Pengadaan Laptop dan Perangkat IT');
        $response->assertSee('25.000.000');
        $response->assertSee('SPM-2026-001');
        $response->assertSee($teknis->name);
        $response->assertSee('Jejak Riwayat');
        $response->assertSee('Verifikasi Berkas');
    }

    public function test_admin_creating_spj_records_spj_history(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);

        $response = $this->actingAs($admin)->post(route('admin.spj.store'), [
            'nomor_spj' => 'SPJ-ADMIN-NEW-001',
            'kegiatan' => 'Sosialisasi Sensus Pertanian 2026',
            'tanggal' => '2026-10-27',
            'nilai' => 7500000,
            'status' => 'diajukan',
            'is_printed' => 1,
            'keterangan' => 'Dibuat oleh admin',
        ]);

        $response->assertRedirect(route('admin.spj.index'));

        $spj = Spj::where('nomor_spj', 'SPJ-ADMIN-NEW-001')->first();
        $this->assertNotNull($spj);

        $this->assertDatabaseHas('spj_histories', [
            'spj_id' => $spj->id,
            'user_id' => $admin->id,
            'aksi' => 'Pengajuan SPJ oleh Admin',
            'status_baru' => 'diajukan',
        ]);
    }

    public function test_admin_updating_spj_records_spj_history(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $admin->id,
            'nomor_spj' => 'SPJ-UPDATE-TEST-001',
            'kegiatan' => 'Kegiatan Sebelum Update',
            'tanggal' => now(),
            'nilai' => 4000000,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.spj.update', $spj->id), [
            'nomor_spj' => 'SPJ-UPDATE-TEST-001',
            'kegiatan' => 'Kegiatan Sesudah Update',
            'tanggal' => '2026-10-28',
            'nilai' => 5500000,
            'status' => 'diajukan',
            'is_printed' => 1,
        ]);

        $response->assertRedirect(route('admin.spj.index'));

        $this->assertDatabaseHas('spjs', [
            'id' => $spj->id,
            'kegiatan' => 'Kegiatan Sesudah Update',
            'nilai' => 5500000,
            'status' => 'diajukan',
        ]);

        $this->assertDatabaseHas('spj_histories', [
            'spj_id' => $spj->id,
            'user_id' => $admin->id,
            'aksi' => 'Pembaruan Data SPJ oleh Admin',
            'status_lama' => 'draft',
            'status_baru' => 'diajukan',
        ]);
    }

    public function test_admin_spj_index_and_dashboard_count_disetujui_ppspm_and_menunggu_otp_kpa_in_proses(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        // Create SPJ with disetujui_ppspm
        Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-PPSPM-001',
            'kegiatan' => 'Berkas di Tahap PPSPM',
            'tanggal' => now(),
            'nilai' => 3000000,
            'status' => 'disetujui_ppspm',
        ]);

        // Create SPJ with menunggu_otp_kpa
        Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-OTP-001',
            'kegiatan' => 'Berkas Menunggu OTP QLOLA KPA',
            'tanggal' => now(),
            'nilai' => 4500000,
            'status' => 'menunggu_otp_kpa',
        ]);

        // Create SPJ with diajukan
        Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-DIAJUKAN-001',
            'kegiatan' => 'Berkas Baru Diajukan',
            'tanggal' => now(),
            'nilai' => 2000000,
            'status' => 'diajukan',
        ]);

        // Test in Admin Spj Index
        $responseIndex = $this->actingAs($admin)->get(route('admin.spj.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertViewHas('menungguProses', 3);

        // Test in Admin Dashboard
        $responseDashboard = $this->actingAs($admin)->get(route('admin.dashboard'));
        $responseDashboard->assertStatus(200);
        $responseDashboard->assertViewHas('menungguProses', 3);
        $responseDashboard->assertViewHas('pengujianPpspm', 2);
    }
}
