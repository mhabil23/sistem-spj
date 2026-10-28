<?php

namespace Tests\Feature;

use App\Models\Spj;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BendaharaSpjTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_bendahara_spj_index(): void
    {
        $response = $this->get(route('bendahara.spj.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_non_bendahara_cannot_access_bendahara_queue(): void
    {
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $response = $this->actingAs($teknis)->get(route('bendahara.spj.index'));
        $response->assertForbidden();
    }

    public function test_bendahara_can_view_spj_queue_including_menunggu_otp_kpa(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-BND-001',
            'kegiatan' => 'Honor Petugas Lapangan',
            'tanggal' => now(),
            'nilai' => 5000000,
            'status' => 'disetujui_ppspm',
        ]);

        Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-BND-002',
            'kegiatan' => 'Biaya Transport Survei',
            'tanggal' => now(),
            'nilai' => 2500000,
            'status' => 'menunggu_otp_kpa',
        ]);

        $response = $this->actingAs($bendahara)->get(route('bendahara.spj.index'));

        $response->assertStatus(200);
        $response->assertSee('Honor Petugas Lapangan');
        $response->assertSee('Biaya Transport Survei');
        $response->assertSee('Menunggu OTP QLOLA KPA');
    }

    public function test_bendahara_views_auto_generated_nomor_spj_when_spj_created_without_nomor_spj(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'kegiatan' => 'Rapat Koordinasi BPS',
            'tanggal' => now(),
            'nilai' => 1500000,
            'status' => 'disetujui_ppspm',
        ]);

        $this->assertNotEmpty($spj->nomor_spj);
        $this->assertMatchesRegularExpression('/^\d{3}\d{8}$/', $spj->nomor_spj);

        // Akses halaman detail SPJ di Bendahara
        $responseShow = $this->actingAs($bendahara)->get(route('bendahara.spj.show', $spj->id));
        $responseShow->assertStatus(200);
        $responseShow->assertSee($spj->nomor_spj);

        // Akses halaman verifikasi berkas di Bendahara
        $responseBerkas = $this->actingAs($bendahara)->get(route('bendahara.berkas.show', $spj->id));
        $responseBerkas->assertStatus(200);
        $responseBerkas->assertSee($spj->nomor_spj);
    }

    public function test_bendahara_can_choose_menunggu_otp_qlola_kpa(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-BND-003',
            'kegiatan' => 'Pengadaan ATK Pelatihan',
            'tanggal' => now(),
            'nilai' => 4200000,
            'status' => 'disetujui_ppspm',
            'no_wa' => '081234567890',
        ]);

        $response = $this->actingAs($bendahara)->post(route('bendahara.spj.verify', $spj->id), [
            'action' => 'otp_kpa',
            'catatan_internal' => 'Maker telah input QLOLA, menunggu KPA approval.',
        ]);

        $response->assertRedirect(route('bendahara.spj.show', $spj->id));
        $response->assertSessionHas('success', 'Status SPJ berhasil diperbarui: Menunggu OTP QLOLA KPA.');

        $spj->refresh();
        $this->assertSame('menunggu_otp_kpa', $spj->status);

        $this->assertDatabaseHas('spj_histories', [
            'spj_id' => $spj->id,
            'user_id' => $bendahara->id,
            'aksi' => 'Menunggu OTP QLOLA KPA',
            'status_baru' => 'menunggu_otp_kpa',
        ]);
    }

    public function test_bendahara_can_choose_proses_pembayaran_telah_selesai(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-BND-004',
            'kegiatan' => 'Konsumsi Rapat Evaluasi',
            'tanggal' => now(),
            'nilai' => 1750000,
            'status' => 'menunggu_otp_kpa',
            'no_wa' => '081234567890',
        ]);

        $response = $this->actingAs($bendahara)->post(route('bendahara.spj.verify', $spj->id), [
            'action' => 'setujui',
            'bukti_transfer' => 'REF-QLOLA-20261026-001',
            'catatan_internal' => 'Sudah diapprove KPA dan dana berhasil ditransfer.',
        ]);

        $response->assertRedirect(route('bendahara.spj.show', $spj->id));
        $response->assertSessionHas('success', 'Proses pembayaran telah selesai, silahkan cek rekening masing-masing.');

        $spj->refresh();
        $this->assertSame('selesai', $spj->status);
        $this->assertSame('REF-QLOLA-20261026-001', $spj->bukti_transfer);
        $this->assertNotNull($spj->diselesaikan_at);

        $this->assertDatabaseHas('spj_histories', [
            'spj_id' => $spj->id,
            'user_id' => $bendahara->id,
            'aksi' => 'Proses Pembayaran Selesai',
            'status_baru' => 'selesai',
        ]);
    }

    public function test_bendahara_can_access_berkas_index_and_show(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-BND-DOK-01',
            'kegiatan' => 'Honor Petugas Pemetaan Wilayah',
            'tanggal' => now(),
            'nilai' => 3000000,
            'status' => 'disetujui_ppspm',
            'dokumen_file' => json_encode([
                ['nama' => 'Daftar Hadir', 'disiapkan' => true],
                ['nama' => 'Surat Tugas', 'disiapkan' => true],
                ['nama' => 'SSP PPh Pasal 21', 'disiapkan' => false],
            ]),
        ]);

        $responseIndex = $this->actingAs($bendahara)->get(route('bendahara.berkas.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Verifikasi Kelengkapan Berkas SPJ');
        $responseIndex->assertSee('SPJ-BND-DOK-01');

        $responseShow = $this->actingAs($bendahara)->get(route('bendahara.berkas.show', $spj->id));
        $responseShow->assertStatus(200);
        $responseShow->assertSee('Daftar Hadir');
        $responseShow->assertSee('SSP PPh Pasal 21');
        $responseShow->assertSee('Berkas yang Sudah Dicentang di Awal');
        $responseShow->assertSee('Berkas &amp; Syarat Khusus Bagian Bendahara', false);
    }

    public function test_bendahara_can_update_berkas_checklist(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-BND-CHK-01',
            'kegiatan' => 'Pengadaan ATK Rutin Kantor',
            'tanggal' => now(),
            'nilai' => 2500000,
            'status' => 'disetujui_ppspm',
            'dokumen_file' => json_encode([
                ['nama' => 'Nota Pembelian / Kwitansi', 'disiapkan' => true],
                ['nama' => 'Faktur Pajak', 'disiapkan' => false],
                ['nama' => 'PPh 22 (jika diatas 2 juta)', 'disiapkan' => false],
            ]),
        ]);

        $response = $this->actingAs($bendahara)->post(route('bendahara.berkas.update', $spj->id), [
            'berkas_bendahara' => [
                1 => '1',
                2 => '1',
            ],
            'catatan_internal' => 'Faktur pajak dan bukti setor PPh 22 telah diverifikasi lengkap.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Kelengkapan berkas Bendahara berhasil diperbarui & disimpan.');

        $spj->refresh();
        $dokumenList = $spj->dokumen_list;

        $fakturPajak = collect($dokumenList)->firstWhere('nama', 'Faktur Pajak');
        $this->assertTrue($fakturPajak['disiapkan']);

        $pph22 = collect($dokumenList)->firstWhere('nama', 'PPh 22 (jika diatas 2 juta)');
        $this->assertTrue($pph22['disiapkan']);

        $this->assertDatabaseHas('spj_histories', [
            'spj_id' => $spj->id,
            'user_id' => $bendahara->id,
            'aksi' => 'Verifikasi Berkas Bendahara',
        ]);
    }

    public function test_bendahara_can_choose_bayar_ls_for_honor_with_3_days_clearing_status(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-HONOR-001',
            'kegiatan' => 'Honor Petugas Mitra Lapangan BPS',
            'tanggal' => now(),
            'nilai' => 6000000,
            'status' => 'disetujui_ppspm',
            'no_wa' => '081234567890',
        ]);

        $response = $this->actingAs($bendahara)->post(route('bendahara.spj.verify', $spj->id), [
            'action' => 'bayar_ls',
            'catatan_internal' => 'SPM LS telah dikirim ke KPPN / Bank mitra.',
        ]);

        $response->assertRedirect(route('bendahara.spj.show', $spj->id));
        $response->assertSessionHas('success');

        $spj->refresh();
        $this->assertSame('proses_ls', $spj->status);
        $this->assertTrue($spj->isHonor());
        $this->assertStringContainsString('3 Hari Kerja', $spj->status_label);
        $this->assertStringContainsString('3 hari kerja', $spj->keterangan_ls);

        $this->assertDatabaseHas('spj_histories', [
            'spj_id' => $spj->id,
            'user_id' => $bendahara->id,
            'aksi' => 'Menunggu Kliring Bank LS (Maks. 3 Hari Kerja)',
            'status_baru' => 'proses_ls',
        ]);
    }

    public function test_bendahara_can_choose_bayar_ls_for_non_honor(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-NONHONOR-001',
            'kegiatan' => 'Belanja Pemeliharaan Gedung Kantor',
            'tanggal' => now(),
            'nilai' => 8500000,
            'status' => 'disetujui_ppspm',
            'no_wa' => '081234567890',
        ]);

        $response = $this->actingAs($bendahara)->post(route('bendahara.spj.verify', $spj->id), [
            'action' => 'bayar_ls',
            'catatan_internal' => 'Pembayaran LS pihak ketiga.',
        ]);

        $response->assertRedirect(route('bendahara.spj.show', $spj->id));
        $response->assertSessionHas('success');

        $spj->refresh();
        $this->assertSame('proses_ls', $spj->status);
        $this->assertFalse($spj->isHonor());
        $this->assertStringContainsString('Transfer Bank Langsung (LS)', $spj->status_label);
    }

    public function test_bendahara_can_complete_payment_from_proses_ls(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-LS-SELESAI-01',
            'kegiatan' => 'Honor Tim Evaluasi',
            'tanggal' => now(),
            'nilai' => 3200000,
            'status' => 'proses_ls',
            'no_wa' => '081234567890',
        ]);

        $response = $this->actingAs($bendahara)->post(route('bendahara.spj.verify', $spj->id), [
            'action' => 'setujui',
            'bukti_transfer' => 'SP2D-2026-9998',
            'catatan_internal' => 'Dana telah masuk ke rekening seluruh penerima.',
        ]);

        $response->assertRedirect(route('bendahara.spj.show', $spj->id));
        $response->assertSessionHas('success', 'Proses pembayaran telah selesai, silahkan cek rekening masing-masing.');

        $spj->refresh();
        $this->assertSame('selesai', $spj->status);
        $this->assertSame('SP2D-2026-9998', $spj->bukti_transfer);
        $this->assertNotNull($spj->diselesaikan_at);
    }

    public function test_bendahara_can_return_spj_for_revision(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-BND-005',
            'kegiatan' => 'Perjalanan Dinas Luar Kota',
            'tanggal' => now(),
            'nilai' => 3100000,
            'status' => 'disetujui_ppspm',
            'no_wa' => '081234567890',
        ]);

        $response = $this->actingAs($bendahara)->post(route('bendahara.spj.verify', $spj->id), [
            'action' => 'tolak',
            'catatan_revisi' => 'Nomor rekening penerima tidak sesuai dengan SK penugasan.',
        ]);

        $response->assertRedirect(route('bendahara.spj.show', $spj->id));
        $response->assertSessionHas('success', 'SPJ dikembalikan ke Teknis untuk direvisi.');

        $spj->refresh();
        $this->assertSame('revisi_bendahara', $spj->status);
        $this->assertSame('Nomor rekening penerima tidak sesuai dengan SK penugasan.', $spj->catatan_revisi);

        $this->assertDatabaseHas('spj_histories', [
            'spj_id' => $spj->id,
            'user_id' => $bendahara->id,
            'aksi' => 'Revisi Bendahara',
            'status_baru' => 'revisi_bendahara',
        ]);
    }

    public function test_bendahara_can_bulk_verify_to_otp_kpa(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj1 = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-BULK-001',
            'kegiatan' => 'Kegiatan Bulk 1',
            'tanggal' => now(),
            'nilai' => 1000000,
            'status' => 'disetujui_ppspm',
        ]);

        $spj2 = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-BULK-002',
            'kegiatan' => 'Kegiatan Bulk 2',
            'tanggal' => now(),
            'nilai' => 2000000,
            'status' => 'disetujui_ppspm',
        ]);

        $response = $this->actingAs($bendahara)->post(route('bendahara.spj.bulk-verify'), [
            'action' => 'otp_kpa',
            'spj_ids' => [$spj1->id, $spj2->id],
        ]);

        $response->assertRedirect(route('bendahara.spj.index'));

        $this->assertSame('menunggu_otp_kpa', $spj1->fresh()->status);
        $this->assertSame('menunggu_otp_kpa', $spj2->fresh()->status);
    }

    public function test_bendahara_can_view_riwayat_index_with_statistics(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-RIW-001',
            'nomor_spm' => 'SPM-2026-001',
            'kegiatan' => 'Kegiatan Riwayat Selesai',
            'tanggal' => now(),
            'nilai' => 3500000,
            'status' => 'selesai',
            'bukti_transfer' => 'REF-QLOLA-12345',
            'diajukan_at' => now()->subHours(2),
        ]);

        Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-RIW-002',
            'kegiatan' => 'Kegiatan Riwayat Revisi',
            'tanggal' => now(),
            'nilai' => 1200000,
            'status' => 'revisi_bendahara',
            'catatan_revisi' => 'Kwitansi belum ditandatangani',
            'diajukan_at' => now()->subHours(5),
        ]);

        $response = $this->actingAs($bendahara)->get(route('bendahara.riwayat.index'));

        $response->assertStatus(200);
        $response->assertSee('Riwayat &amp; Arsip Keputusan SPJ', false);
        $response->assertSee('Total Arsip Terdata');
        $response->assertSee('Total Dana Berhasil Cair');
        $response->assertSee('Rp 3.500.000');
        $response->assertSee('Kegiatan Riwayat Selesai');
        $response->assertSee('REF-QLOLA-12345');
        $response->assertSee('Kwitansi belum ditandatangani');
        $response->assertSee('bendahara/spj.css');
    }

    public function test_bendahara_can_view_profile_page(): void
    {
        $bendahara = User::factory()->create([
            'role' => 'bendahara',
            'name' => 'Bendahara Utama',
            'email' => 'bendahara@bps.go.id',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($bendahara)->get(route('bendahara.profil.index'));

        $response->assertStatus(200);
        $response->assertSee('Pengaturan Profil &amp; Akun', false);
        $response->assertSee('bendahara@bps.go.id');
        $response->assertSee('Bendahara Pengeluaran');
    }

    public function test_bendahara_can_update_name_and_wa_without_password(): void
    {
        $bendahara = User::factory()->create([
            'role' => 'bendahara',
            'name' => 'Nama Lama',
            'email' => 'bendahara@bps.go.id',
            'no_wa' => '08111111111',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($bendahara)->put(route('bendahara.profil.update'), [
            'name' => 'Nama Baru',
            'email' => 'bendahara@bps.go.id',
            'no_wa' => '08222222222',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Data profil berhasil diperbarui.');

        $bendahara->refresh();
        $this->assertSame('Nama Baru', $bendahara->name);
        $this->assertSame('08222222222', $bendahara->no_wa);
    }

    public function test_bendahara_cannot_change_email_without_current_password(): void
    {
        $bendahara = User::factory()->create([
            'role' => 'bendahara',
            'email' => 'lama@bps.go.id',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($bendahara)->put(route('bendahara.profil.update'), [
            'name' => $bendahara->name,
            'email' => 'baru@bps.go.id',
            'current_password' => '',
        ]);

        $response->assertSessionHasErrors(['current_password', 'email']);
        $this->assertSame('lama@bps.go.id', $bendahara->fresh()->email);
    }

    public function test_bendahara_cannot_change_email_with_wrong_current_password(): void
    {
        $bendahara = User::factory()->create([
            'role' => 'bendahara',
            'email' => 'lama@bps.go.id',
            'password' => 'secret123',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($bendahara)->put(route('bendahara.profil.update'), [
            'name' => $bendahara->name,
            'email' => 'baru@bps.go.id',
            'current_password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['current_password', 'email']);
        $this->assertSame('lama@bps.go.id', $bendahara->fresh()->email);
    }

    public function test_bendahara_cannot_change_email_to_already_used_email(): void
    {
        User::factory()->create(['email' => 'existing@bps.go.id', 'status' => 'aktif']);
        $bendahara = User::factory()->create([
            'role' => 'bendahara',
            'email' => 'lama@bps.go.id',
            'password' => 'secret123',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($bendahara)->put(route('bendahara.profil.update'), [
            'name' => $bendahara->name,
            'email' => 'existing@bps.go.id',
            'current_password' => 'secret123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertSame('lama@bps.go.id', $bendahara->fresh()->email);
    }

    public function test_bendahara_can_change_email_with_correct_current_password(): void
    {
        $bendahara = User::factory()->create([
            'role' => 'bendahara',
            'email' => 'lama@bps.go.id',
            'password' => 'secret123',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($bendahara)->put(route('bendahara.profil.update'), [
            'name' => $bendahara->name,
            'email' => 'baru@bps.go.id',
            'current_password' => 'secret123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertSame('baru@bps.go.id', $bendahara->fresh()->email);
    }

    public function test_bendahara_show_renders_reconfirmation_modal_for_disbursement_completion(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-BND-CONFIRM-1',
            'kegiatan' => 'Pencairan Survei Pertanian',
            'tanggal' => now(),
            'nilai' => 3500000,
            'status' => 'menunggu_otp_kpa',
        ]);

        $response = $this->actingAs($bendahara)->get(route('bendahara.spj.show', $spj->id));

        $response->assertStatus(200);
        $response->assertSee('modalKonfirmasiCair');
        $response->assertSee('Konfirmasi Ulang Pembayaran');
        $response->assertSee('Ya, Selesaikan &amp; Cairkan Sekarang', false);
        $response->assertSee('Periksa Kembali');
        $response->assertSee('bukaModalKonfirmasiCair', false);
    }

    public function test_bendahara_berkas_show_renders_reconfirmation_modal_for_disbursement_completion(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-BND-CONFIRM-2',
            'kegiatan' => 'Pencairan Honor Pengolahan Data',
            'tanggal' => now(),
            'nilai' => 4500000,
            'status' => 'proses_ls',
            'keterangan_ls' => 'Menunggu kliring 3 hari kerja bank.',
        ]);

        $response = $this->actingAs($bendahara)->get(route('bendahara.berkas.show', $spj->id));

        $response->assertStatus(200);
        $response->assertSee('modalKonfirmasiCair');
        $response->assertSee('Konfirmasi Ulang Pembayaran');
        $response->assertSee('Ya, Selesaikan &amp; Cairkan Sekarang', false);
        $response->assertSee('Periksa Kembali');
        $response->assertSee('bukaModalKonfirmasiCair', false);
    }
}
