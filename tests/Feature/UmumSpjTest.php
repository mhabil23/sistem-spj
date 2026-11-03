<?php

namespace Tests\Feature;

use App\Models\Spj;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UmumSpjTest extends TestCase
{
    use RefreshDatabase;

    protected User $userUmum;

    protected User $userTeknis;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userUmum = User::factory()->create([
            'name' => 'Petugas Umum BPS',
            'email' => 'umum.test@bps.go.id',
            'role' => 'umum',
        ]);

        $this->userTeknis = User::factory()->create([
            'name' => 'Staf Teknis BPS',
            'email' => 'teknis.test@bps.go.id',
            'role' => 'teknis',
            'no_wa' => '081234567890',
        ]);
    }

    public function test_user_umum_can_view_spj_detail_and_checklist(): void
    {
        $spj = Spj::create([
            'user_id' => $this->userTeknis->id,
            'kegiatan' => 'Penyusunan Publikasi Statistik Daerah 2026',
            'tanggal' => now(),
            'nilai' => 3500000,
            'status' => 'diajukan',
            'no_wa' => '081234567890',
            'diajukan_at' => now(),
            'dokumen_file' => json_encode([
                ['nama' => 'KAK', 'disiapkan' => true],
                ['nama' => 'Form Permintaan', 'disiapkan' => true],
                ['nama' => 'Kuitansi Asli', 'disiapkan' => true],
            ]),
        ]);

        $response = $this->actingAs($this->userUmum)->get(route('umum.spj.show', $spj->id));

        $response->assertStatus(200);
        $response->assertSee('Penyusunan Publikasi Statistik Daerah 2026');
        $response->assertSee('Rp 3.500.000');
        $response->assertSee('Menunggu Berkas Fisik Disetor');
    }

    public function test_user_umum_can_confirm_physical_document_received(): void
    {
        $spj = Spj::create([
            'user_id' => $this->userTeknis->id,
            'kegiatan' => 'Sosialisasi Sensus Pertanian Lanjutan',
            'tanggal' => now(),
            'nilai' => 1500000,
            'status' => 'diajukan',
            'diajukan_at' => now(),
        ]);

        $response = $this->actingAs($this->userUmum)->post(route('umum.spj.verify', $spj->id), [
            'action' => 'terima_fisik',
        ]);

        $response->assertRedirect(route('umum.spj.show', $spj->id));
        $response->assertSessionHas('success');

        $spj->refresh();
        $this->assertNotNull($spj->berkas_fisik_diterima_at);
        $this->assertNull($spj->pemeriksaan_dimulai_at);
    }

    public function test_user_umum_can_start_inspection_after_physical_document_received(): void
    {
        $spj = Spj::create([
            'user_id' => $this->userTeknis->id,
            'kegiatan' => 'Pemeriksaan Lapangan Survei Biaya Hidup',
            'tanggal' => now(),
            'nilai' => 2200000,
            'status' => 'diajukan',
            'diajukan_at' => now(),
            'berkas_fisik_diterima_at' => now(),
        ]);

        $response = $this->actingAs($this->userUmum)->post(route('umum.spj.verify', $spj->id), [
            'action' => 'mulai_periksa',
        ]);

        $response->assertRedirect(route('umum.spj.show', $spj->id));
        $response->assertSessionHas('success');

        $spj->refresh();
        $this->assertNotNull($spj->pemeriksaan_dimulai_at);
    }

    public function test_user_umum_can_approve_spj_to_ppk(): void
    {
        $spj = Spj::create([
            'user_id' => $this->userTeknis->id,
            'kegiatan' => 'Rapat Evaluasi Sensus Pertanian',
            'tanggal' => now(),
            'nilai' => 2000000,
            'status' => 'diajukan',
            'diajukan_at' => now(),
            'berkas_fisik_diterima_at' => now(),
            'pemeriksaan_dimulai_at' => now(),
        ]);

        $response = $this->actingAs($this->userUmum)->post(route('umum.spj.verify', $spj->id), [
            'action' => 'setujui',
            'catatan_internal' => 'Kuitansi dan BAST lengkap sesuai SOP.',
        ]);

        $response->assertRedirect(route('umum.spj.show', $spj->id));
        $response->assertSessionHas('success');
        $response->assertSessionHas('trigger_wa', 'setuju_umum');

        $spj->refresh();
        $this->assertEquals('disetujui_umum', $spj->status);
        $this->assertEquals('Kuitansi dan BAST lengkap sesuai SOP.', $spj->catatan_internal);
        $this->assertNotNull($spj->disetujui_umum_at);
    }

    public function test_user_umum_can_return_spj_for_revision(): void
    {
        $spj = Spj::create([
            'user_id' => $this->userTeknis->id,
            'kegiatan' => 'Pengadaan Bahan Komputer',
            'tanggal' => now(),
            'nilai' => 1200000,
            'status' => 'diajukan',
            'diajukan_at' => now(),
            'berkas_fisik_diterima_at' => now(),
            'pemeriksaan_dimulai_at' => now(),
        ]);

        $response = $this->actingAs($this->userUmum)->post(route('umum.spj.verify', $spj->id), [
            'action' => 'tolak',
            'catatan_revisi' => 'Nota belum memiliki cap basah toko.',
        ]);

        $response->assertRedirect(route('umum.spj.show', $spj->id));
        $response->assertSessionHas('success');
        $response->assertSessionHas('trigger_wa', 'revisi_umum');

        $spj->refresh();
        $this->assertEquals('revisi_umum', $spj->status);
        $this->assertEquals('Nota belum memiliki cap basah toko.', $spj->catatan_revisi);
        // Memastikan reset fisik agar teknis menyetor ulang fisik jika revisi
        $this->assertNull($spj->berkas_fisik_diterima_at);
        $this->assertNull($spj->pemeriksaan_dimulai_at);
    }

    public function test_user_umum_can_print_pdf_checklist(): void
    {
        $spj = Spj::create([
            'user_id' => $this->userTeknis->id,
            'kegiatan' => 'Pelatihan Petugas Susenas 2026',
            'tanggal' => now(),
            'nilai' => 4800000,
            'status' => 'disetujui_umum',
            'diajukan_at' => now(),
            'disetujui_umum_at' => now(),
        ]);

        $response = $this->actingAs($this->userUmum)->get(route('umum.spj.print', $spj->id));

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
    }

    public function test_user_umum_cannot_delete_spj(): void
    {
        $spj = Spj::create([
            'user_id' => $this->userTeknis->id,
            'kegiatan' => 'Kegiatan SPJ Umum Non Delete',
            'tanggal' => now(),
            'nilai' => 2500000,
            'status' => 'disetujui_umum',
            'diajukan_at' => now(),
        ]);

        $responseRiwayat = $this->actingAs($this->userUmum)->delete(route('umum.riwayat.destroy', $spj->id));
        $responseRiwayat->assertStatus(403);

        $responseSpj = $this->actingAs($this->userUmum)->delete(route('umum.spj.destroy', $spj->id));
        $responseSpj->assertStatus(403);

        $this->assertDatabaseHas('spjs', ['id' => $spj->id]);
    }

    public function test_user_umum_riwayat_does_not_display_delete_button(): void
    {
        $spj = Spj::create([
            'user_id' => $this->userTeknis->id,
            'kegiatan' => 'Kegiatan Riwayat Tanpa Tombol Hapus',
            'tanggal' => now(),
            'nilai' => 3100000,
            'status' => 'disetujui_umum',
            'diajukan_at' => now(),
        ]);

        $response = $this->actingAs($this->userUmum)->get(route('umum.riwayat.index'));

        $response->assertStatus(200);
        $response->assertSee('Kegiatan Riwayat Tanpa Tombol Hapus');
        $response->assertDontSee('btn-table-delete');
        $response->assertDontSee('Hapus Permanen');
    }

    public function test_umum_dashboard_displays_all_teknis_users_with_real_counts(): void
    {
        $teknis2 = User::factory()->create([
            'role' => 'teknis',
            'name' => 'Budi Teknis',
            'status' => 'aktif',
        ]);

        Spj::create([
            'user_id' => $this->userTeknis->id,
            'kegiatan' => 'Kegiatan Sensus Susenas',
            'tanggal' => now(),
            'nilai' => 1500000,
            'status' => 'diajukan',
        ]);

        Spj::create([
            'user_id' => $this->userTeknis->id,
            'kegiatan' => 'Survei Ubinan Padi',
            'tanggal' => now(),
            'nilai' => 1200000,
            'status' => 'selesai',
        ]);

        Spj::create([
            'user_id' => $teknis2->id,
            'kegiatan' => 'Kegiatan Sakernas',
            'tanggal' => now(),
            'nilai' => 2000000,
            'status' => 'selesai',
        ]);

        $response = $this->actingAs($this->userUmum)->get(route('umum.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Top Pengaju Teknis');
        $response->assertSee($this->userTeknis->name);
        $response->assertSee('Budi Teknis');

        // Pastikan tidak ada lagi daftar tim dummy lama
        $response->assertDontSee('Tim Sakernas');
        $response->assertDontSee('Tim Susenas');
        $response->assertDontSee('Tim SBH');
        $response->assertDontSee('Tim Pengolahan');

        // Data topPengaju terisi user teknis riil sesuai urutan jumlah SPJ terbanyak
        $topPengaju = $response->viewData('topPengaju');
        $this->assertCount(2, $topPengaju);
        $this->assertEquals($this->userTeknis->name, $topPengaju[0]['name']);
        $this->assertEquals('2 SPJ', $topPengaju[0]['count']);
        $this->assertEquals('Budi Teknis', $topPengaju[1]['name']);
        $this->assertEquals('1 SPJ', $topPengaju[1]['count']);
    }
}
