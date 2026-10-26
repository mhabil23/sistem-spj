<?php

namespace Tests\Feature;

use App\Models\Spj;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLaporanTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_laporan_spj(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-LAP-001',
            'kegiatan' => 'Kegiatan Sensus Pertanian Kolaka',
            'tanggal' => now(),
            'nilai' => 15000000,
            'status' => 'disetujui_ppspm',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.laporan.spj'));

        $response->assertStatus(200);
        $response->assertSee('Laporan &amp; Rekapitulasi SPJ', false);
        $response->assertSee('bi bi-file-earmark-bar-graph-fill', false);
        $response->assertSee('bi bi-printer-fill', false);
        $response->assertSee('Cetak Laporan Fisik');
        $response->assertSee('Kegiatan Sensus Pertanian Kolaka');
        $response->assertSee('15.000.000');
        // Ensure print elements exist untouched
        $response->assertSee('cetak-table', false);
        $response->assertSee('print-only-logo', false);
        $response->assertSee('ttd-container', false);
        $response->assertSee('Hidayatullah');
    }

    public function test_admin_can_filter_laporan_by_date_and_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-LAP-002',
            'kegiatan' => 'Rapat Koordinasi BPS',
            'tanggal' => '2026-10-10',
            'nilai' => 2500000,
            'status' => 'diajukan',
        ]);

        Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-LAP-003',
            'kegiatan' => 'Pelatihan Petugas',
            'tanggal' => '2026-10-15',
            'nilai' => 3000000,
            'status' => 'selesai',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.laporan.spj', [
            'status' => 'diajukan',
            'tanggal_mulai' => '2026-10-01',
            'tanggal_selesai' => '2026-10-12',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Rapat Koordinasi BPS');
        $response->assertDontSee('Pelatihan Petugas');
    }

    public function test_admin_can_filter_laporan_by_nama_spj(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-SEARCH-001',
            'kegiatan' => 'Sewa Kendaraan Dinas Survei',
            'tanggal' => now(),
            'nilai' => 1800000,
            'status' => 'diajukan',
        ]);

        Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-SEARCH-002',
            'kegiatan' => 'Konsumsi Rapat Evaluasi',
            'tanggal' => now(),
            'nilai' => 750000,
            'status' => 'diajukan',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.laporan.spj', [
            'search' => 'Kendaraan Dinas',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Sewa Kendaraan Dinas Survei');
        $response->assertSee('Pencarian SPJ:');
        $response->assertDontSee('Konsumsi Rapat Evaluasi');
    }
}
