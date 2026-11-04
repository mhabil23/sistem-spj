<?php

namespace Tests\Feature;

use App\Models\Spj;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeknisPrivacyTest extends TestCase
{
    use RefreshDatabase;

    public function test_teknis_user_only_sees_their_own_spj_in_index_and_dashboard(): void
    {
        $userA = User::factory()->create(['name' => 'Teknis A', 'role' => 'teknis']);
        $userB = User::factory()->create(['name' => 'Teknis B', 'role' => 'teknis']);

        $spjA = Spj::create([
            'user_id' => $userA->id,
            'nomor_spj' => 'SPJ-A-001',
            'kegiatan' => 'Kegiatan SPJ Khusus Milik A',
            'tanggal' => '2026-11-01',
            'nilai' => 1000000,
            'status' => 'diajukan',
        ]);

        $spjB = Spj::create([
            'user_id' => $userB->id,
            'nomor_spj' => 'SPJ-B-002',
            'kegiatan' => 'Kegiatan SPJ Rahasia Milik B',
            'tanggal' => '2026-11-02',
            'nilai' => 2000000,
            'status' => 'diajukan',
        ]);

        // Login sebagai User A
        $response = $this->actingAs($userA)->get(route('teknis.spj.index'));
        $response->assertStatus(200);
        $response->assertSee('Kegiatan SPJ Khusus Milik A');
        $response->assertDontSee('Kegiatan SPJ Rahasia Milik B');

        // Dashboard User A
        $dashResponse = $this->actingAs($userA)->get(route('teknis.dashboard'));
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Kegiatan SPJ Khusus Milik A');
        $dashResponse->assertDontSee('Kegiatan SPJ Rahasia Milik B');
    }

    public function test_teknis_user_cannot_access_or_print_other_teknis_spj(): void
    {
        $userA = User::factory()->create(['name' => 'Teknis A', 'role' => 'teknis']);
        $userB = User::factory()->create(['name' => 'Teknis B', 'role' => 'teknis']);

        $spjB = Spj::create([
            'user_id' => $userB->id,
            'nomor_spj' => 'SPJ-B-003',
            'kegiatan' => 'Privasi Dokumen B',
            'tanggal' => '2026-11-03',
            'nilai' => 1500000,
            'status' => 'draft',
        ]);

        // User A mencoba melihat detail SPJ milik B
        $this->actingAs($userA)
            ->get(route('teknis.spj.show', $spjB->id))
            ->assertStatus(403);

        // User A mencoba mengedit SPJ milik B
        $this->actingAs($userA)
            ->get(route('teknis.spj.edit', $spjB->id))
            ->assertStatus(403);

        // User A mencoba mencetak SPJ milik B
        $this->actingAs($userA)
            ->get(route('teknis.spj.print', $spjB->id))
            ->assertStatus(404);
    }

    public function test_home_monitoring_respects_privacy_for_logged_in_teknis_user(): void
    {
        $userA = User::factory()->create(['name' => 'Teknis A', 'role' => 'teknis']);
        $userB = User::factory()->create(['name' => 'Teknis B', 'role' => 'teknis']);

        Spj::create([
            'user_id' => $userA->id,
            'nomor_spj' => 'SPJ-PUB-A',
            'kegiatan' => 'Kegiatan Publik A',
            'tanggal' => '2026-11-01',
            'nilai' => 1000000,
            'status' => 'diajukan',
        ]);

        Spj::create([
            'user_id' => $userB->id,
            'nomor_spj' => 'SPJ-PUB-B',
            'kegiatan' => 'Kegiatan Publik B',
            'tanggal' => '2026-11-02',
            'nilai' => 2000000,
            'status' => 'diajukan',
        ]);

        // Sebagai User A di beranda publik
        $response = $this->actingAs($userA)->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('SPJ-PUB-A');
        $response->assertDontSee('SPJ-PUB-B');
    }
}
