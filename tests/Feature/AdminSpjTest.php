<?php

namespace Tests\Feature;

use App\Models\Spj;
use App\Models\SpjHistory;
use App\Models\SyaratDokumenSpj;
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

    public function test_admin_can_create_spj_with_peruntukan(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);

        $response = $this->actingAs($admin)->get('/admin/spj/create');
        $response->assertStatus(200);
        $response->assertSee('Peruntukan Pengajuan SPJ');
        $response->assertSee('SPJ Tim Teknis');
        $response->assertSee('SPJ Subbagian Umum');

        $postResponse = $this->actingAs($admin)->post('/admin/spj', [
            'peruntukan' => 'teknis',
            'seksi' => 'Sosial',
            'nama_belanja' => 'Belanja Bahan (521211)',
            'jenis_belanja' => 'Belanja Konsumsi',
            'kegiatan' => 'Sosialisasi Sensus Pertanian 2026',
            'tanggal' => now()->format('Y-m-d'),
            'nilai' => '2.500.000',
            'status' => 'draft',
            'nama_dokumen' => ['0' => 'KAK', '1' => 'FP (dari BOS)'],
            'assignee_dokumen' => ['0' => 'SM', '1' => 'SM'],
            'berkas' => ['0' => '1', '1' => '1'],
        ]);
        $postResponse->assertRedirect(route('admin.spj.index'));

        $this->assertDatabaseHas('spjs', [
            'kegiatan' => 'Sosialisasi Sensus Pertanian 2026',
            'seksi' => 'Sosial',
            'nilai' => 2500000,
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

    public function test_admin_can_update_spj_switching_belanja_from_52_to_51(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
        $userUmum = User::factory()->create(['role' => 'umum', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $admin->id,
            'nomor_spj' => 'SPJ-EDIT-52-TO-51',
            'nama_belanja' => 'Belanja Bahan (521211)',
            'jenis_belanja' => 'Belanja Konsumsi',
            'peruntukan' => 'teknis',
            'kegiatan' => 'Konsumsi Rapat Koordinasi',
            'tanggal' => now(),
            'nilai' => 1500000,
            'seksi' => 'Sosial',
            'status' => 'draft',
            'dokumen_file' => json_encode([
                ['nama' => 'KAK', 'disiapkan' => true, 'assignee' => 'SM'],
            ]),
        ]);

        $response = $this->actingAs($admin)->put(route('admin.spj.update', $spj->id), [
            'nomor_spj' => 'SPJ-EDIT-52-TO-51',
            'peruntukan' => 'umum',
            'nama_belanja' => 'Belanja Gaji Pokok PNS (511111)',
            'jenis_belanja' => 'Gaji Induk PNS',
            'kegiatan' => 'Pembayaran Gaji Induk Pegawai',
            'tanggal' => now()->format('Y-m-d'),
            'nilai' => 75000000,
            'status' => 'diajukan',
            'is_printed' => 1,
            'nama_dokumen' => ['0' => 'Daftar Gaji Induk', '1' => 'SPTJM Pembayaran Gaji'],
            'assignee_dokumen' => ['0' => 'SM', '1' => 'SM'],
            'berkas' => ['0' => '1', '1' => '1'],
        ]);

        $response->assertRedirect(route('admin.spj.index'));

        $spj->refresh();
        $this->assertEquals('Belanja Gaji Pokok PNS (511111)', $spj->nama_belanja);
        $this->assertEquals('Gaji Induk PNS', $spj->jenis_belanja);
        $this->assertEquals('umum', $spj->peruntukan);
        $this->assertEquals('Subbag Umum', $spj->seksi);
        $this->assertEquals('Pembayaran Gaji Induk Pegawai', $spj->kegiatan);
        $this->assertEquals(75000000, $spj->nilai);

        $decodedDocs = json_decode($spj->dokumen_file, true);
        $this->assertCount(2, $decodedDocs);
        $this->assertEquals('Daftar Gaji Induk', $decodedDocs[0]['nama']);
        $this->assertTrue($decodedDocs[0]['disiapkan']);
    }

    public function test_admin_can_manage_syarat_dokumen_and_store_multiple_documents(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);

        $getIndex = $this->actingAs($admin)->get(route('admin.syarat-dokumen.index'));
        $getIndex->assertStatus(200);

        $postBatch = $this->actingAs($admin)->post(route('admin.syarat-dokumen.store'), [
            'nama_belanja' => 'Belanja Uji Coba Multi (529999)',
            'jenis_belanja' => 'Uji Coba Multi Dokumen',
            'dokumen' => [
                ['nama_dokumen' => 'Surat Pengantar Multi A', 'assignee' => 'SM', 'peruntukan' => 'semua'],
                ['nama_dokumen' => 'Bukti Transfer Multi B', 'assignee' => 'Bendahara', 'peruntukan' => 'semua'],
            ],
        ]);

        $postBatch->assertRedirect(route('admin.syarat-dokumen.index', [
            'nama_belanja' => 'Belanja Uji Coba Multi (529999)',
            'jenis_belanja' => 'Uji Coba Multi Dokumen',
        ]));

        $this->assertDatabaseHas('syarat_dokumen_spjs', [
            'nama_belanja' => 'Belanja Uji Coba Multi (529999)',
            'jenis_belanja' => 'Uji Coba Multi Dokumen',
            'nama_dokumen' => 'Surat Pengantar Multi A',
            'assignee' => 'SM',
        ]);

        $this->assertDatabaseHas('syarat_dokumen_spjs', [
            'nama_belanja' => 'Belanja Uji Coba Multi (529999)',
            'jenis_belanja' => 'Uji Coba Multi Dokumen',
            'nama_dokumen' => 'Bukti Transfer Multi B',
            'assignee' => 'Bendahara',
        ]);
    }

    public function test_admin_can_update_peruntukan_for_entire_group_except_bendahara(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);

        $docTeknis1 = SyaratDokumenSpj::create([
            'nama_belanja' => 'Honor output kegiatan (512213)',
            'jenis_belanja' => 'Honor Pengajar',
            'nama_dokumen' => 'Surat Tugas Pengajar',
            'assignee' => 'SM',
            'peruntukan' => 'semua',
            'urutan' => 1,
        ]);

        $docTeknis2 = SyaratDokumenSpj::create([
            'nama_belanja' => 'Honor output kegiatan (512213)',
            'jenis_belanja' => 'Honor Pengajar',
            'nama_dokumen' => 'Daftar Hadir Mengajar',
            'assignee' => 'SM',
            'peruntukan' => 'semua',
            'urutan' => 2,
        ]);

        $docBendahara = SyaratDokumenSpj::create([
            'nama_belanja' => 'Honor output kegiatan (512213)',
            'jenis_belanja' => 'Honor Pengajar',
            'nama_dokumen' => 'Bukti Potong PPh 21',
            'assignee' => 'Bendahara',
            'peruntukan' => 'semua',
            'urutan' => 3,
        ]);

        $response = $this->actingAs($admin)->postJson(route('admin.syarat-dokumen.update-peruntukan-group'), [
            'nama_belanja' => 'Honor output kegiatan (512213)',
            'jenis_belanja' => 'Honor Pengajar',
            'peruntukan' => 'umum',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'peruntukan' => 'umum',
            'updated_count' => 2,
        ]);

        $docTeknis1->refresh();
        $docTeknis2->refresh();
        $docBendahara->refresh();

        // Non-Bendahara docs follow the new peruntukan
        $this->assertEquals('umum', $docTeknis1->peruntukan);
        $this->assertEquals('umum', $docTeknis2->peruntukan);

        // Bendahara docs remain 'semua' (safe for both)
        $this->assertEquals('semua', $docBendahara->peruntukan);
        $this->assertEquals('Bendahara', $docBendahara->assignee);
    }

    public function test_admin_syarat_dokumen_index_renders_both_cards_and_excel_table(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);

        SyaratDokumenSpj::create([
            'nama_belanja' => 'Belanja Bahan (521211)',
            'jenis_belanja' => 'Belanja Konsumsi',
            'nama_dokumen' => 'Undangan Rapat Konsumsi',
            'assignee' => 'SM',
            'peruntukan' => 'semua',
            'urutan' => 1,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.syarat-dokumen.index'));
        $response->assertStatus(200);

        // Card View elements
        $response->assertSee('containerViewCards');
        $response->assertSee('Tampilan Kartu');

        // Excel Table View elements
        $response->assertSee('containerViewTable');
        $response->assertSee('Tampilan Tabel Excel');
        $response->assertSee('excel-doc-table');
        $response->assertSee('Belanja Bahan (521211)');
        $response->assertSee('Belanja Konsumsi');
        $response->assertSee('Undangan Rapat Konsumsi');
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

    public function test_admin_can_delete_spj(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-ADMIN-DEL-001',
            'kegiatan' => 'Kegiatan yang Boleh Dihapus Admin',
            'tanggal' => now(),
            'nilai' => 1000000,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.spj.destroy', $spj->id));

        $response->assertRedirect(route('admin.spj.index'));
        $response->assertSessionHas('success', 'Data SPJ berhasil dihapus.');
        $this->assertDatabaseMissing('spjs', ['id' => $spj->id]);
    }

    public function test_admin_spj_index_displays_delete_button(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-ADMIN-BTN-001',
            'kegiatan' => 'Kegiatan Tampil Tombol Hapus Admin',
            'tanggal' => now(),
            'nilai' => 1000000,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.spj.index'));

        $response->assertStatus(200);
        $response->assertSee(route('admin.spj.destroy', $spj->id));
        $response->assertSee('Hapus Semua SPJ');
    }

    public function test_admin_can_destroy_all_spjs(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-ALL-001',
            'kegiatan' => 'Kegiatan Semua 1',
            'tanggal' => now(),
            'nilai' => 1000000,
            'status' => 'draft',
        ]);
        Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-ALL-002',
            'kegiatan' => 'Kegiatan Semua 2',
            'tanggal' => now(),
            'nilai' => 2000000,
            'status' => 'draft',
        ]);

        $this->assertGreaterThanOrEqual(2, Spj::count());

        $response = $this->actingAs($admin)->delete(route('admin.spj.destroy-all'));

        $response->assertRedirect(route('admin.spj.index'));
        $response->assertSessionHas('success');
        $this->assertEquals(0, Spj::count());
    }

    public function test_admin_can_bulk_destroy_selected_spjs(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj1 = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-BULK-001',
            'kegiatan' => 'Kegiatan Bulk 1',
            'tanggal' => now(),
            'nilai' => 1000000,
            'status' => 'draft',
        ]);
        $spj2 = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-BULK-002',
            'kegiatan' => 'Kegiatan Bulk 2',
            'tanggal' => now(),
            'nilai' => 2000000,
            'status' => 'draft',
        ]);
        $spj3 = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-BULK-003',
            'kegiatan' => 'Kegiatan Bulk 3 (Tetap Ada)',
            'tanggal' => now(),
            'nilai' => 3000000,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.spj.bulk-destroy'), [
            'ids' => [$spj1->id, $spj2->id],
        ]);

        $response->assertRedirect(route('admin.spj.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('spjs', ['id' => $spj1->id]);
        $this->assertDatabaseMissing('spjs', ['id' => $spj2->id]);
        $this->assertDatabaseHas('spjs', ['id' => $spj3->id]);
    }
}
