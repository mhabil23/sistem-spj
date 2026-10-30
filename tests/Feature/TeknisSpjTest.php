<?php

namespace Tests\Feature;

use App\Models\Spj;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

    public function test_teknis_submitting_spj_is_directed_to_bring_physical_files_to_umum(): void
    {
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $response = $this->actingAs($teknis)->post(route('teknis.spj.store'), [
            'kegiatan' => 'Pencairan Survei Biaya Hidup',
            'tanggal' => '2026-10-28',
            'nilai' => 2800000,
            'status' => 'diajukan',
            'is_printed' => 1,
        ]);

        $response->assertRedirect(route('teknis.spj.index'));
        $response->assertSessionHas('success', 'SPJ berhasil diajukan. Harap segera bawa dan serahkan berkas fisik SPJ ke Subbagian Umum untuk verifikasi.');
    }

    public function test_teknis_show_page_renders_bawa_berkas_fisik_banner_when_status_diajukan(): void
    {
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-FISIK-001',
            'kegiatan' => 'Belanja Pemeliharaan Gedung',
            'tanggal' => now(),
            'nilai' => 1200000,
            'status' => 'diajukan',
        ]);

        $this->assertSame('Diajukan (Bawa Berkas Fisik ke Umum)', $spj->status_label);

        $response = $this->actingAs($teknis)->get(route('teknis.spj.show', $spj->id));

        $response->assertStatus(200);
        $response->assertSee('Langkah Selanjutnya: Bawa Berkas Fisik ke Subbagian Umum');
        $response->assertSee('harap membawa dan menyerahkan dokumen fisik SPJ beserta seluruh lampirannya ke meja Subbagian Umum', false);
        $response->assertSee('Diajukan (Bawa Berkas Fisik ke Umum)');
    }

    public function test_teknis_status_and_show_page_synchronized_when_berkas_fisik_diterima(): void
    {
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-FISIK-002',
            'kegiatan' => 'Belanja Pemeliharaan Jaringan',
            'tanggal' => now(),
            'nilai' => 1500000,
            'status' => 'diajukan',
            'berkas_fisik_diterima_at' => now(),
        ]);

        $this->assertSame('Fisik Diterima (Siap Cek)', $spj->status_label);

        $response = $this->actingAs($teknis)->get(route('teknis.spj.show', $spj->id));
        $response->assertStatus(200);
        $response->assertSee('Berkas Fisik Telah Diterima Subbagian Umum');
        $response->assertSee('Status: Fisik Diterima (Siap Cek)');

        $indexResponse = $this->actingAs($teknis)->get(route('teknis.spj.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Fisik Diterima (Siap Cek)');
    }

    public function test_teknis_status_and_show_page_synchronized_when_pemeriksaan_dimulai(): void
    {
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-FISIK-003',
            'kegiatan' => 'Belanja ATK Kantor',
            'tanggal' => now(),
            'nilai' => 2500000,
            'status' => 'diajukan',
            'berkas_fisik_diterima_at' => now(),
            'pemeriksaan_dimulai_at' => now(),
        ]);

        $this->assertSame('Sedang Diperiksa', $spj->status_label);

        $response = $this->actingAs($teknis)->get(route('teknis.spj.show', $spj->id));
        $response->assertStatus(200);
        $response->assertSee('Sedang Diperiksa oleh Subbagian Umum');
        $response->assertSee('Status: Sedang Diperiksa');

        $indexResponse = $this->actingAs($teknis)->get(route('teknis.spj.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Sedang Diperiksa');
        $indexResponse->assertSee('dash-badge-amber', false);
    }

    public function test_teknis_create_page_contains_print_validation_modal(): void
    {
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $response = $this->actingAs($teknis)->get(route('teknis.spj.create'));

        $response->assertStatus(200);
        $response->assertSee('id="printValidationModal"', false);
        $response->assertSee('Lembar Checklist Belum Dicetak!');
        $response->assertSee('Cetak Lembar Checklist Sekarang');
        $response->assertSee('openPrintValidationModal()', false);
    }

    public function test_teknis_dashboard_and_show_page_render_detailed_sop_5_pintu_stepper(): void
    {
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-STEPPER-001',
            'kegiatan' => 'Belanja ATK Survei BPS',
            'tanggal' => now(),
            'nilai' => 2500000,
            'status' => 'diajukan',
            'berkas_fisik_diterima_at' => now(),
            'pemeriksaan_dimulai_at' => now(),
        ]);

        // Dashboard
        $dashResponse = $this->actingAs($teknis)->get(route('teknis.dashboard'));
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('teknis-stepper-card', false);
        $dashResponse->assertSee('1. Pengajuan Teknis');
        $dashResponse->assertSee('2. Verifikasi Umum');
        $dashResponse->assertSee('3. Tanda Tangan PPK');
        $dashResponse->assertSee('4. Persetujuan PPSPM');
        $dashResponse->assertSee('5. Pencairan Dana');
        $dashResponse->assertSee('Sedang Diperiksa');

        // Show page
        $showResponse = $this->actingAs($teknis)->get(route('teknis.spj.show', $spj->id));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('SOP 5 Pintu BPS');
        $showResponse->assertSee('1. Pengajuan Teknis');
        $showResponse->assertSee('2. Verifikasi Umum');
        $showResponse->assertSee('3. Tanda Tangan PPK');
        $showResponse->assertSee('4. Persetujuan PPSPM');
        $showResponse->assertSee('5. Pencairan Dana');
    }

    public function test_uploading_document_file_preserves_checklist_and_both_are_displayed_in_show(): void
    {
        Storage::fake('public');

        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $checklist = [
            ['nama' => 'KAK', 'disiapkan' => true],
            ['nama' => 'Faktur Pajak', 'disiapkan' => true],
            ['nama' => 'PPh 22', 'disiapkan' => false],
        ];

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-DOC-001',
            'kegiatan' => 'Belanja ATK Kantor',
            'tanggal' => now(),
            'nilai' => 1500000,
            'status' => 'diajukan',
            'dokumen_file' => json_encode($checklist),
        ]);

        $file = UploadedFile::fake()->image('bukti_scan.jpg');

        $uploadResponse = $this->actingAs($teknis)->post(route('teknis.dokumen.upload', $spj->id), [
            'dokumen_file' => $file,
        ]);

        $uploadResponse->assertSessionHas('success');

        $spj->refresh();

        // Checklist in dokumen_file must NOT be overwritten
        $this->assertNotNull($spj->dokumen_file);
        $this->assertEquals($checklist, json_decode($spj->dokumen_file, true));

        // Uploaded file must be in file_lampiran
        $this->assertNotNull($spj->file_lampiran);
        $this->assertStringStartsWith('dokumen_spjs/', $spj->file_lampiran);
        $this->assertStringEndsWith('.jpg', $spj->file_lampiran);

        // Show page must display both
        $showResponse = $this->actingAs($teknis)->get(route('teknis.spj.show', $spj->id));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Lampiran Berkas Digital');
        $showResponse->assertSee('File Terunggah');
        $showResponse->assertSee('Checklist Kelengkapan Dokumen Fisik');
        $showResponse->assertSee('KAK');
        $showResponse->assertSee('Faktur Pajak');
        $showResponse->assertSee('PPh 22');
        $showResponse->assertDontSee('Data dokumen tidak valid atau kosong.');
    }
}
