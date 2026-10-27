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
        $this->assertStringStartsWith('SPJ/BPS7408/', $spj->nomor_spj);
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
}
