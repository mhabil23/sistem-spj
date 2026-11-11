<?php

namespace Tests\Feature;

use App\Models\Spj;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BendaharaKewajibanTest extends TestCase
{
    use RefreshDatabase;

    public function test_bendahara_cannot_choose_otp_kpa_if_obligations_not_checked(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-BND-KWJ-001',
            'kegiatan' => 'Belanja Pemeliharaan Server',
            'tanggal' => now(),
            'nilai' => 5000000,
            'status' => 'disetujui_ppspm',
            'dokumen_file' => json_encode([
                ['nama' => 'Nota Pembelian', 'disiapkan' => true],
                ['nama' => 'Faktur Pajak', 'disiapkan' => false, 'assignee' => 'Bendahara'],
                ['nama' => 'SSP PPN', 'disiapkan' => false, 'assignee' => 'Bendahara'],
            ]),
        ]);

        $response = $this->actingAs($bendahara)->post(route('bendahara.spj.verify', $spj->id), [
            'action' => 'otp_kpa',
            'keterangan_otp' => 'Coba set OTP tanpa centang kewajiban',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $spj->refresh();
        $this->assertSame('disetujui_ppspm', $spj->status);
        $this->assertFalse($spj->areBendaharaObligationsCompleted());
    }

    public function test_bendahara_cannot_choose_bayar_ls_if_obligations_not_checked(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-BND-KWJ-002',
            'kegiatan' => 'Honor Mitra Survei Sensus',
            'tanggal' => now(),
            'nilai' => 8000000,
            'status' => 'disetujui_ppspm',
            'dokumen_file' => json_encode([
                ['nama' => 'Daftar Penerima Honor', 'disiapkan' => true],
                ['nama' => 'SSP PPh Pasal 21', 'disiapkan' => false, 'assignee' => 'Bendahara'],
            ]),
        ]);

        $response = $this->actingAs($bendahara)->post(route('bendahara.spj.verify', $spj->id), [
            'action' => 'bayar_ls',
            'nomor_sp2d_ls' => 'SP2D-LS-2026-TEST',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $spj->refresh();
        $this->assertSame('disetujui_ppspm', $spj->status);
        $this->assertFalse($spj->areBendaharaObligationsCompleted());
    }

    public function test_bendahara_can_choose_otp_kpa_when_obligations_are_checked(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-BND-KWJ-003',
            'kegiatan' => 'Pengadaan ATK Rutin Kantor',
            'tanggal' => now(),
            'nilai' => 2500000,
            'status' => 'disetujui_ppspm',
            'dokumen_file' => json_encode([
                ['nama' => 'Nota Pembelian', 'disiapkan' => true],
                ['nama' => 'Faktur Pajak', 'disiapkan' => true, 'assignee' => 'Bendahara'],
                ['nama' => 'SSP PPN', 'disiapkan' => true, 'assignee' => 'Bendahara'],
            ]),
        ]);

        $response = $this->actingAs($bendahara)->post(route('bendahara.spj.verify', $spj->id), [
            'action' => 'otp_kpa',
            'keterangan_otp' => 'Batch QLOLA #01 siap approval KPA',
        ]);

        $response->assertRedirect(route('bendahara.spj.show', $spj->id));
        $response->assertSessionHas('success');

        $spj->refresh();
        $this->assertSame('menunggu_otp_kpa', $spj->status);
        $this->assertTrue($spj->areBendaharaObligationsCompleted());
    }

    public function test_bendahara_can_choose_bayar_ls_when_obligations_are_checked(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-BND-KWJ-004',
            'kegiatan' => 'Honor Tim Evaluasi',
            'tanggal' => now(),
            'nilai' => 4500000,
            'status' => 'disetujui_ppspm',
            'dokumen_file' => json_encode([
                ['nama' => 'Daftar Hadir', 'disiapkan' => true],
                ['nama' => 'SSP PPh Pasal 21', 'disiapkan' => true, 'assignee' => 'Bendahara'],
            ]),
        ]);

        $response = $this->actingAs($bendahara)->post(route('bendahara.spj.verify', $spj->id), [
            'action' => 'bayar_ls',
            'nomor_sp2d_ls' => 'SP2D-LS-2026-BPS-004',
        ]);

        $response->assertRedirect(route('bendahara.spj.show', $spj->id));
        $response->assertSessionHas('success');

        $spj->refresh();
        $this->assertSame('proses_ls', $spj->status);
        $this->assertTrue($spj->areBendaharaObligationsCompleted());
    }

    public function test_bendahara_can_submit_obligations_checklist_and_otp_kpa_in_same_request(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-BND-KWJ-005',
            'kegiatan' => 'Pengadaan Lisensi Software Statistik',
            'tanggal' => now(),
            'nilai' => 3000000,
            'status' => 'disetujui_ppspm',
            'dokumen_file' => json_encode([
                ['nama' => 'Invoice Rekanan', 'disiapkan' => true],
                ['nama' => 'Faktur Pajak', 'disiapkan' => false, 'assignee' => 'Bendahara'],
            ]),
        ]);

        $response = $this->actingAs($bendahara)->post(route('bendahara.spj.verify', $spj->id), [
            'action' => 'otp_kpa',
            'berkas_bendahara' => [
                1 => '1',
            ],
            'keterangan_otp' => 'Centang dan proses OTP sekaligus',
        ]);

        $response->assertRedirect(route('bendahara.spj.show', $spj->id));
        $response->assertSessionHas('success');

        $spj->refresh();
        $this->assertSame('menunggu_otp_kpa', $spj->status);
        $this->assertTrue($spj->areBendaharaObligationsCompleted());
    }

    public function test_bendahara_bulk_verify_blocked_if_any_spj_has_incomplete_obligations(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj1 = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-BULK-OK',
            'kegiatan' => 'Kegiatan Lengkap',
            'tanggal' => now(),
            'nilai' => 1000000,
            'status' => 'disetujui_ppspm',
            'dokumen_file' => json_encode([
                ['nama' => 'SSP PPh Pasal 21', 'disiapkan' => true, 'assignee' => 'Bendahara'],
            ]),
        ]);

        $spj2 = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-BULK-BELUM',
            'kegiatan' => 'Kegiatan Belum Lengkap',
            'tanggal' => now(),
            'nilai' => 2000000,
            'status' => 'disetujui_ppspm',
            'dokumen_file' => json_encode([
                ['nama' => 'SSP PPh Pasal 21', 'disiapkan' => false, 'assignee' => 'Bendahara'],
            ]),
        ]);

        $response = $this->actingAs($bendahara)->post(route('bendahara.spj.bulk-verify'), [
            'action' => 'otp_kpa',
            'spj_ids' => [$spj1->id, $spj2->id],
        ]);

        $response->assertRedirect(route('bendahara.spj.index'));
        $response->assertSessionHas('error');

        $this->assertSame('disetujui_ppspm', $spj1->fresh()->status);
        $this->assertSame('disetujui_ppspm', $spj2->fresh()->status);
    }

    public function test_bendahara_view_renders_warning_and_locked_buttons_when_obligations_incomplete(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-BND-VIEW-001',
            'kegiatan' => 'Belanja Operasional',
            'tanggal' => now(),
            'nilai' => 1500000,
            'status' => 'disetujui_ppspm',
            'dokumen_file' => json_encode([
                ['nama' => 'Kwitansi', 'disiapkan' => true],
                ['nama' => 'Faktur Pajak', 'disiapkan' => false, 'assignee' => 'Bendahara'],
            ]),
        ]);

        $responseShow = $this->actingAs($bendahara)->get(route('bendahara.spj.show', $spj->id));
        $responseShow->assertStatus(200);
        $responseShow->assertSee('Kewajiban Berkas Fisik &amp; Perpajakan Bendahara Belum Lengkap', false);
        $responseShow->assertSee('Terkunci: Menunggu OTP QLOLA KPA');
        $responseShow->assertSee('Terkunci: Pembayaran LS Diajukan ke Bank');

        $responseBerkas = $this->actingAs($bendahara)->get(route('bendahara.berkas.show', $spj->id));
        $responseBerkas->assertStatus(200);
        $responseBerkas->assertSee('Kewajiban Berkas Fisik &amp; Perpajakan Bendahara Belum Lengkap', false);
        $responseBerkas->assertSee('Terkunci: Menunggu OTP QLOLA KPA');
        $responseBerkas->assertSee('Terkunci: Pembayaran LS Diajukan ke Bank');
    }

    public function test_bendahara_view_renders_success_and_active_buttons_when_obligations_complete(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $spj = Spj::create([
            'user_id' => $teknis->id,
            'nomor_spj' => 'SPJ-BND-VIEW-002',
            'kegiatan' => 'Belanja Operasional Selesai Berkas',
            'tanggal' => now(),
            'nilai' => 1500000,
            'status' => 'disetujui_ppspm',
            'dokumen_file' => json_encode([
                ['nama' => 'Kwitansi', 'disiapkan' => true],
                ['nama' => 'Faktur Pajak', 'disiapkan' => true, 'assignee' => 'Bendahara'],
            ]),
        ]);

        $responseShow = $this->actingAs($bendahara)->get(route('bendahara.spj.show', $spj->id));
        $responseShow->assertStatus(200);
        $responseShow->assertSee('Seluruh kewajiban berkas fisik &amp; perpajakan Bendahara telah lengkap dicentang', false);
        $responseShow->assertSee('Set Status: Menunggu OTP QLOLA KPA');
        $responseShow->assertSee('Set Status: Pembayaran LS Diajukan ke Bank');
    }
}
