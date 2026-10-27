<?php

namespace Tests\Feature;

use App\Models\Spj;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlurIntegrasiSpjTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Menguji alur lengkap end-to-end terintegrasi:
     * Teknis (Ajukan SPJ + Auto Nomor) -> Umum (Terima Berkas, Periksa, Setuju)
     * -> PPK (Setuju) -> PPSPM (Setuju) -> Bendahara (OTP QLOLA KPA -> Pencairan Selesai).
     */
    public function test_alur_lengkap_terintegrasi_dari_teknis_ke_bendahara_sampai_selesai(): void
    {
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif', 'name' => 'Staf Teknis BPS']);
        $umum = User::factory()->create(['role' => 'umum', 'status' => 'aktif', 'name' => 'Verifikator Umum']);
        $bendahara = User::factory()->create(['role' => 'bendahara', 'status' => 'aktif', 'name' => 'Bendahara Pengeluaran']);

        // 1. TAHAP TEKNIS: Membuat dan mengajukan SPJ baru tanpa mengisi nomor SPJ (Otomatis)
        $responseTeknis = $this->actingAs($teknis)->post(route('teknis.spj.store'), [
            'nomor_spj' => '', // Dikosongkan agar digenerate otomatis
            'kegiatan' => 'Kegiatan Survei Biaya Hidup (SBH) 2026',
            'tanggal' => '2026-10-27',
            'nilai' => 3500000,
            'status' => 'diajukan',
            'is_printed' => '1',
            'no_wa' => '081234567890',
            'keterangan' => 'Honor petugas lapangan dan transport lokal',
            'nama_dokumen' => ['KAK', 'Form Permintaan', 'Kuitansi Riil'],
            'berkas' => ['0' => '1', '1' => '1', '2' => '1'],
        ]);

        $responseTeknis->assertRedirect(route('teknis.spj.index'));

        $spj = Spj::where('kegiatan', 'Kegiatan Survei Biaya Hidup (SBH) 2026')->first();
        $this->assertNotNull($spj);
        $this->assertSame('diajukan', $spj->status);
        $this->assertNotNull($spj->nomor_spj);
        $this->assertStringStartsWith('SPJ/BPS7408/', $spj->nomor_spj);
        $this->assertNotNull($spj->diajukan_at);

        // 2. TAHAP UMUM: Masuk ke antrean Umum, terima berkas fisik, mulai periksa, dan setujui
        $responseUmumIndex = $this->actingAs($umum)->get(route('umum.spj.index'));
        $responseUmumIndex->assertStatus(200);
        $responseUmumIndex->assertSee($spj->nomor_spj);
        $responseUmumIndex->assertSee('Kegiatan Survei Biaya Hidup (SBH) 2026');

        // Umum: Konfirmasi Berkas Fisik Diterima
        $this->actingAs($umum)->post(route('umum.spj.verify', $spj->id), [
            'action' => 'terima_fisik',
        ]);
        $spj->refresh();
        $this->assertNotNull($spj->berkas_fisik_diterima_at);

        // Umum: Mulai Pemeriksaan Aktif
        $this->actingAs($umum)->post(route('umum.spj.verify', $spj->id), [
            'action' => 'mulai_periksa',
        ]);
        $spj->refresh();
        $this->assertNotNull($spj->pemeriksaan_dimulai_at);

        // Umum: Setujui Kelengkapan Administrasi
        $this->actingAs($umum)->post(route('umum.spj.verify', $spj->id), [
            'action' => 'setujui',
            'catatan_internal' => 'Berkas lengkap dan sesuai SOP.',
        ]);
        $spj->refresh();
        $this->assertSame('disetujui_umum', $spj->status);
        $this->assertNotNull($spj->disetujui_umum_at);

        // 3. TAHAP PPK: Dokumen ditandatangani PPK
        $this->actingAs($umum)->post(route('umum.spj.verify', $spj->id), [
            'action' => 'setujui_ppk',
        ]);
        $spj->refresh();
        $this->assertSame('disetujui_ppk', $spj->status);
        $this->assertNotNull($spj->disetujui_ppk_at);

        // 4. TAHAP PPSPM: Pengujian tagihan dan penerbitan SPM
        $this->actingAs($umum)->post(route('umum.spj.verify', $spj->id), [
            'action' => 'setujui_ppspm',
        ]);
        $spj->refresh();
        $this->assertSame('disetujui_ppspm', $spj->status);
        $this->assertNotNull($spj->disetujui_ppspm_at);

        // 5. TAHAP BENDAHARA: SPJ otomatis masuk antrean Bendahara Pengeluaran
        $responseBendaharaIndex = $this->actingAs($bendahara)->get(route('bendahara.spj.index'));
        $responseBendaharaIndex->assertStatus(200);
        $responseBendaharaIndex->assertSee($spj->nomor_spj);
        $responseBendaharaIndex->assertSee('Kegiatan Survei Biaya Hidup (SBH) 2026');

        // Bendahara: Input ke BRI QLOLA dan update status Menunggu OTP KPA
        $this->actingAs($bendahara)->post(route('bendahara.spj.verify', $spj->id), [
            'action' => 'otp_kpa',
            'keterangan_otp' => 'Maker telah input transaksi, menunggu approval OTP KPA.',
        ]);
        $spj->refresh();
        $this->assertSame('menunggu_otp_kpa', $spj->status);

        // Bendahara: Setelah KPA menyetujui, Bendahara menyelesaikan pencairan dana
        $this->actingAs($bendahara)->post(route('bendahara.spj.verify', $spj->id), [
            'action' => 'setujui',
            'bukti_transfer' => 'REF-QLOLA-20261027-089',
        ]);
        $spj->refresh();
        $this->assertSame('selesai', $spj->status);
        $this->assertSame('REF-QLOLA-20261027-089', $spj->bukti_transfer);
        $this->assertNotNull($spj->diselesaikan_at);

        // 6. VERIFIKASI AKHIR: Teknis & Riwayat Bendahara
        $responseTeknisShow = $this->actingAs($teknis)->get(route('teknis.spj.show', $spj->id));
        $responseTeknisShow->assertStatus(200);
        $responseTeknisShow->assertSee('Proses Pembayaran Telah Selesai');
        $responseTeknisShow->assertSee('REF-QLOLA-20261027-089');

        $responseBendaharaRiwayat = $this->actingAs($bendahara)->get(route('bendahara.riwayat.index'));
        $responseBendaharaRiwayat->assertStatus(200);
        $responseBendaharaRiwayat->assertSee($spj->nomor_spj);
        $responseBendaharaRiwayat->assertSee('REF-QLOLA-20261027-089');
    }

    /**
     * Menguji alur revisi dari Bendahara kembali ke Teknis, lalu diajukan ulang ke Umum.
     */
    public function test_alur_revisi_bendahara_ke_teknis_dan_diajukan_ulang(): void
    {
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);
        $bendahara = User::factory()->create(['role' => 'bendahara', 'status' => 'aktif']);

        // Buat SPJ yang sudah sampai di meja Bendahara
        $spj = Spj::create([
            'user_id' => $teknis->id,
            'kegiatan' => 'Rapat Koordinasi Evaluasi',
            'tanggal' => now(),
            'nilai' => 1200000,
            'status' => 'disetujui_ppspm',
            'diajukan_at' => now(),
        ]);

        // Bendahara mengembalikan berkas karena ada revisi
        $response = $this->actingAs($bendahara)->post(route('bendahara.spj.verify', $spj->id), [
            'action' => 'tolak',
            'catatan_revisi' => 'Nomor rekening penerima tidak sesuai dengan SK penugasan.',
        ]);

        $response->assertRedirect(route('bendahara.spj.show', $spj->id));
        $spj->refresh();
        $this->assertSame('revisi_bendahara', $spj->status);

        // Teknis membuka halaman edit dan memperbaiki SPJ
        $responseEdit = $this->actingAs($teknis)->get(route('teknis.spj.edit', $spj->id));
        $responseEdit->assertStatus(200);

        // Teknis mengajukan ulang dokumen yang telah diperbaiki
        $responseUpdate = $this->actingAs($teknis)->put(route('teknis.spj.update', $spj->id), [
            'nomor_spj' => $spj->nomor_spj,
            'kegiatan' => 'Rapat Koordinasi Evaluasi (Rekening Diperbarui)',
            'tanggal' => '2026-10-27',
            'nilai' => 1200000,
            'status' => 'diajukan',
            'is_printed' => '1',
        ]);

        $responseUpdate->assertRedirect(route('teknis.spj.index'));
        $spj->refresh();
        $this->assertSame('diajukan', $spj->status);
        $this->assertSame('Rapat Koordinasi Evaluasi (Rekening Diperbarui)', $spj->kegiatan);
    }
}
