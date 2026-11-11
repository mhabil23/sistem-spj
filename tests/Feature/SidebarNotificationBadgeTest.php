<?php

namespace Tests\Feature;

use App\Models\Spj;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SidebarNotificationBadgeTest extends TestCase
{
    use RefreshDatabase;

    public function test_umum_sidebar_shows_badge_for_pending_diajukan_spjs(): void
    {
        $umum = User::factory()->create([
            'role' => 'umum',
            'status' => 'aktif',
            'seksi' => 'Subbag Umum',
        ]);

        $teknis = User::factory()->create([
            'role' => 'teknis',
            'status' => 'aktif',
            'seksi' => 'Sosial',
        ]);

        // Belum ada SPJ diajukan -> badge tidak muncul
        $response = $this->actingAs($umum)->get(route('umum.dashboard'));
        $response->assertStatus(200);
        $response->assertDontSee('SPJ baru diajukan & menunggu verifikasi berkas');

        // Buat 2 SPJ yang diajukan oleh staf teknis
        Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-TEST-001',
            'kegiatan' => 'Kegiatan Uji Coba 1',
            'tanggal' => now(),
            'nilai' => 1000000,
            'status' => 'diajukan',
        ]);
        Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-TEST-002',
            'kegiatan' => 'Kegiatan Uji Coba 2',
            'tanggal' => now(),
            'nilai' => 1500000,
            'status' => 'diajukan',
        ]);

        // Buat 1 SPJ selesai (tidak boleh dihitung)
        Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-TEST-003',
            'kegiatan' => 'Kegiatan Selesai',
            'tanggal' => now(),
            'nilai' => 2000000,
            'status' => 'selesai',
        ]);

        $responseWithBadge = $this->actingAs($umum)->get(route('umum.dashboard'));
        $responseWithBadge->assertStatus(200);
        $responseWithBadge->assertSee('2 SPJ baru diajukan dan menunggu verifikasi berkas');
    }

    public function test_teknis_sidebar_shows_badge_for_own_diajukan_spjs_and_revisi(): void
    {
        $teknis = User::factory()->create([
            'role' => 'teknis',
            'status' => 'aktif',
            'seksi' => 'Produksi',
        ]);

        // Belum ada SPJ
        $response = $this->actingAs($teknis)->get(route('teknis.dashboard'));
        $response->assertStatus(200);
        $response->assertDontSee('SPJ Anda sedang dalam antrean pengajuan');

        // Buat 3 SPJ diajukan milik teknis
        Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-TEK-001',
            'kegiatan' => 'Kegiatan Teknis 1',
            'tanggal' => now(),
            'nilai' => 1000000,
            'status' => 'diajukan',
        ]);
        Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-TEK-002',
            'kegiatan' => 'Kegiatan Teknis 2',
            'tanggal' => now(),
            'nilai' => 1200000,
            'status' => 'diajukan',
        ]);
        Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-TEK-003',
            'kegiatan' => 'Kegiatan Teknis 3',
            'tanggal' => now(),
            'nilai' => 1300000,
            'status' => 'diajukan',
        ]);

        $responseWithDiajukan = $this->actingAs($teknis)->get(route('teknis.dashboard'));
        $responseWithDiajukan->assertStatus(200);
        $responseWithDiajukan->assertSee('3 SPJ Anda sedang dalam antrean pengajuan');

        // Jika ada 1 SPJ yang dikembalikan / perlu revisi, badge revisi prioritas muncul
        Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-TEK-004',
            'kegiatan' => 'Kegiatan Teknis Revisi',
            'tanggal' => now(),
            'nilai' => 900000,
            'status' => 'dikembalikan',
        ]);

        $responseWithRevisi = $this->actingAs($teknis)->get(route('teknis.dashboard'));
        $responseWithRevisi->assertStatus(200);
        $responseWithRevisi->assertSee('SPJ perlu perbaikan berkas');
    }
}
