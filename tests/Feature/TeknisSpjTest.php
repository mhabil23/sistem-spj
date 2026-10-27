<?php

namespace Tests\Feature;

use App\Models\Spj;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeknisSpjTest extends TestCase
{
    use RefreshDatabase;

    public function test_teknis_can_view_spj_create_page_with_nomor_spj_field(): void
    {
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $response = $this->actingAs($teknis)->get(route('teknis.spj.create'));

        $response->assertStatus(200);
        $response->assertSee('Nomor SPJ');
        $response->assertSee('name="nomor_spj"', false);
    }

    public function test_teknis_can_create_spj_with_nomor_spj(): void
    {
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $response = $this->actingAs($teknis)->post(route('teknis.spj.store'), [
            'nomor_spj' => 'SPJ-2026-TEST-001',
            'kegiatan' => 'Kegiatan Survei Sosial Ekonomi',
            'tanggal' => '2026-10-23',
            'nilai' => 1500000,
            'status' => 'draft',
            'no_wa' => '081234567890',
            'keterangan' => 'Catatan teknis uji coba',
        ]);

        $response->assertRedirect(route('teknis.spj.index'));
        $this->assertDatabaseHas('spjs', [
            'nomor_spj' => 'SPJ-2026-TEST-001',
            'kegiatan' => 'Kegiatan Survei Sosial Ekonomi',
            'nilai' => 1500000,
            'status' => 'draft',
        ]);
    }

    public function test_teknis_create_spj_auto_generates_nomor_spj_when_empty(): void
    {
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $response = $this->actingAs($teknis)->post(route('teknis.spj.store'), [
            'nomor_spj' => '',
            'kegiatan' => 'Kegiatan Survei Sosial Ekonomi',
            'tanggal' => '2026-10-23',
            'nilai' => 1500000,
            'status' => 'draft',
        ]);

        $response->assertRedirect(route('teknis.spj.index'));
        $this->assertDatabaseHas('spjs', [
            'kegiatan' => 'Kegiatan Survei Sosial Ekonomi',
            'nilai' => 1500000,
            'status' => 'draft',
        ]);

        $spj = Spj::where('kegiatan', 'Kegiatan Survei Sosial Ekonomi')->first();
        $this->assertNotNull($spj->nomor_spj);
        $this->assertMatchesRegularExpression('/^\d{3}\d{2}\d{2}\d{4}$/', $spj->nomor_spj);
    }

    public function test_teknis_cannot_use_duplicate_nomor_spj(): void
    {
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-2026-DUPLICATE',
            'kegiatan' => 'SPJ Pertama',
            'tanggal' => now(),
            'nilai' => 1000000,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($teknis)->post(route('teknis.spj.store'), [
            'nomor_spj' => 'SPJ-2026-DUPLICATE',
            'kegiatan' => 'SPJ Kedua',
            'tanggal' => '2026-10-23',
            'nilai' => 2000000,
            'status' => 'draft',
        ]);

        $response->assertSessionHasErrors('nomor_spj');
    }

    public function test_teknis_can_view_nomor_spj_in_index_and_show(): void
    {
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-2026-DISPLAY-001',
            'kegiatan' => 'Pengadaan ATK Kegiatan Susenas',
            'tanggal' => now(),
            'nilai' => 750000,
            'status' => 'draft',
        ]);

        $responseIndex = $this->actingAs($teknis)->get(route('teknis.spj.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('SPJ-2026-DISPLAY-001');

        $responseShow = $this->actingAs($teknis)->get(route('teknis.spj.show', $spj->id));
        $responseShow->assertStatus(200);
        $responseShow->assertSee('SPJ-2026-DISPLAY-001');
    }

    public function test_teknis_can_update_spj_with_nomor_spj(): void
    {
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-OLD-001',
            'kegiatan' => 'Kegiatan Lama',
            'tanggal' => now(),
            'nilai' => 500000,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($teknis)->put(route('teknis.spj.update', $spj->id), [
            'nomor_spj' => 'SPJ-NEW-001',
            'kegiatan' => 'Kegiatan Baru Diedit',
            'tanggal' => '2026-10-24',
            'nilai' => 600000,
            'status' => 'draft',
        ]);

        $response->assertRedirect(route('teknis.spj.index'));
        $this->assertDatabaseHas('spjs', [
            'id' => $spj->id,
            'nomor_spj' => 'SPJ-NEW-001',
            'kegiatan' => 'Kegiatan Baru Diedit',
        ]);
    }

    public function test_teknis_laporan_only_shows_own_spj_and_does_not_leak_other_users_spj(): void
    {
        $teknisA = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);
        $teknisB = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        Spj::create([
            'user_id' => $teknisA->id,
            'nomor_spj' => 'SPJ-USER-A-001',
            'kegiatan' => 'SPJ Khusus Teknis A',
            'tanggal' => now(),
            'nilai' => 1500000,
            'status' => 'diajukan',
        ]);

        Spj::create([
            'user_id' => $teknisB->id,
            'nomor_spj' => 'SPJ-USER-B-001',
            'kegiatan' => 'SPJ Rahasia Teknis B',
            'tanggal' => now(),
            'nilai' => 3000000,
            'status' => 'diajukan',
        ]);

        $response = $this->actingAs($teknisA)->get(route('teknis.laporan.spj'));

        $response->assertStatus(200);
        $response->assertSee('SPJ Khusus Teknis A');
        $response->assertDontSee('SPJ Rahasia Teknis B');

        $response->assertViewHas('totalSpj', 1);
        $response->assertViewHas('totalNilai', 1500000);
        $response->assertViewHas('totalDiajukan', 1);

        // Akses sebagai Teknis B
        $responseB = $this->actingAs($teknisB)->get(route('teknis.laporan.spj'));
        $responseB->assertStatus(200);
        $responseB->assertSee('SPJ Rahasia Teknis B');
        $responseB->assertDontSee('SPJ Khusus Teknis A');
        $responseB->assertViewHas('totalSpj', 1);
        $responseB->assertViewHas('totalNilai', 3000000);
    }

    public function test_teknis_laporan_filters_status_for_own_spj_only(): void
    {
        $teknisA = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);
        $teknisB = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        Spj::create([
            'user_id' => $teknisA->id,
            'nomor_spj' => 'SPJ-A-DRAFT',
            'kegiatan' => 'Draft Kegiatan A',
            'tanggal' => now(),
            'nilai' => 1000000,
            'status' => 'draft',
        ]);

        Spj::create([
            'user_id' => $teknisA->id,
            'nomor_spj' => 'SPJ-A-SELESAI',
            'kegiatan' => 'Selesai Kegiatan A',
            'tanggal' => now(),
            'nilai' => 2000000,
            'status' => 'selesai',
        ]);

        Spj::create([
            'user_id' => $teknisB->id,
            'nomor_spj' => 'SPJ-B-SELESAI',
            'kegiatan' => 'Selesai Kegiatan B Milik Orang Lain',
            'tanggal' => now(),
            'nilai' => 5000000,
            'status' => 'selesai',
        ]);

        $response = $this->actingAs($teknisA)->get(route('teknis.laporan.spj', ['status' => 'selesai']));

        $response->assertStatus(200);
        $response->assertSee('Selesai Kegiatan A');
        $response->assertDontSee('Draft Kegiatan A');
        $response->assertDontSee('Selesai Kegiatan B Milik Orang Lain');
        $response->assertViewHas('totalSpj', 1);
        $response->assertViewHas('totalNilai', 2000000);
    }
}
