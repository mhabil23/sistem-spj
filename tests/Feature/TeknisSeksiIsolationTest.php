<?php

namespace Tests\Feature;

use App\Models\Spj;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeknisSeksiIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_teknis_ipds_only_sees_their_own_seksi_spj(): void
    {
        $userIpds = User::factory()->create([
            'name' => 'IPDS',
            'role' => 'teknis',
            'seksi' => 'IPDS',
            'status' => 'aktif',
        ]);

        $userNeraca = User::factory()->create([
            'name' => 'NERACA',
            'role' => 'teknis',
            'seksi' => 'Neraca',
            'status' => 'aktif',
        ]);

        $userSosial = User::factory()->create([
            'name' => 'SOSIAL',
            'role' => 'teknis',
            'seksi' => 'Sosial',
            'status' => 'aktif',
        ]);

        // SPJ IPDS
        Spj::create([
            'user_id' => $userIpds->id,
            'nomor_spj' => 'SPJ-IPDS-001',
            'kegiatan' => 'Kegiatan Khusus IPDS',
            'seksi' => 'Seksi IPDS',
            'tanggal' => now(),
            'nilai' => 1000000,
            'status' => 'diajukan',
        ]);

        // SPJ Neraca
        Spj::create([
            'user_id' => $userNeraca->id,
            'nomor_spj' => 'SPJ-NERACA-001',
            'kegiatan' => 'Kegiatan Khusus Neraca',
            'seksi' => 'Seksi Neraca',
            'tanggal' => now(),
            'nilai' => 2000000,
            'status' => 'diajukan',
        ]);

        // SPJ Sosial
        Spj::create([
            'user_id' => $userSosial->id,
            'nomor_spj' => 'SPJ-SOSIAL-001',
            'kegiatan' => 'Kegiatan Khusus Sosial',
            'seksi' => 'Seksi Sosial',
            'tanggal' => now(),
            'nilai' => 3000000,
            'status' => 'diajukan',
        ]);

        // Login sebagai IPDS
        $response = $this->actingAs($userIpds)->get(route('teknis.spj.index'));
        $response->assertStatus(200);
        $response->assertSee('Kegiatan Khusus IPDS');
        $response->assertDontSee('Kegiatan Khusus Neraca');
        $response->assertDontSee('Kegiatan Khusus Sosial');

        // Login sebagai Neraca
        $responseNeraca = $this->actingAs($userNeraca)->get(route('teknis.spj.index'));
        $responseNeraca->assertStatus(200);
        $responseNeraca->assertSee('Kegiatan Khusus Neraca');
        $responseNeraca->assertDontSee('Kegiatan Khusus IPDS');
        $responseNeraca->assertDontSee('Kegiatan Khusus Sosial');
    }

    public function test_teknis_cannot_access_other_teknis_spj_detail(): void
    {
        $userIpds = User::factory()->create([
            'name' => 'IPDS',
            'role' => 'teknis',
            'seksi' => 'IPDS',
            'status' => 'aktif',
        ]);

        $userNeraca = User::factory()->create([
            'name' => 'NERACA',
            'role' => 'teknis',
            'seksi' => 'Neraca',
            'status' => 'aktif',
        ]);

        $spjNeraca = Spj::create([
            'user_id' => $userNeraca->id,
            'nomor_spj' => 'SPJ-NERACA-999',
            'kegiatan' => 'SPJ Neraca Rahasia',
            'seksi' => 'Seksi Neraca',
            'tanggal' => now(),
            'nilai' => 5000000,
            'status' => 'diajukan',
        ]);

        // IPDS mencoba membuka SPJ milik Neraca -> 403 Forbidden
        $response = $this->actingAs($userIpds)->get(route('teknis.spj.show', $spjNeraca->id));
        $response->assertStatus(403);
    }
}
