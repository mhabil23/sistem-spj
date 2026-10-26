<?php

namespace Tests\Feature;

use App\Models\Spj;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UmumLaporanTest extends TestCase
{
    use RefreshDatabase;

    protected User $userUmum;

    protected User $userTeknis;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userUmum = User::factory()->create([
            'name' => 'Petugas Umum BPS',
            'email' => 'umum.laporan@bps.go.id',
            'role' => 'umum',
        ]);

        $this->userTeknis = User::factory()->create([
            'name' => 'Staf Teknis BPS',
            'email' => 'teknis.laporan@bps.go.id',
            'role' => 'teknis',
        ]);
    }

    public function test_user_umum_can_view_laporan_index_page(): void
    {
        Spj::create([
            'nomor_spj' => 'SPJ/BPS/2026/001',
            'user_id' => $this->userTeknis->id,
            'kegiatan' => 'Pendataan Sensus Pertanian 2026',
            'tanggal' => Carbon::create(2026, 10, 15),
            'nilai' => 5000000,
            'status' => 'selesai',
            'diajukan_at' => Carbon::create(2026, 10, 16),
            'diselesaikan_at' => Carbon::create(2026, 10, 20),
        ]);

        $response = $this->actingAs($this->userUmum)->get(route('umum.laporan.index'));

        $response->assertStatus(200);
        $response->assertSee('Laporan Rekapitulasi SPJ');
        $response->assertSee('Pendataan Sensus Pertanian 2026');
        $response->assertSee('5.000.000');
    }

    public function test_user_umum_can_filter_laporan_by_month_and_year(): void
    {
        $spjOktober = Spj::create([
            'nomor_spj' => 'SPJ/BPS/2026/OKT',
            'user_id' => $this->userTeknis->id,
            'kegiatan' => 'Kegiatan Survei Harga Konsumen Oktober',
            'tanggal' => Carbon::create(2026, 10, 10),
            'nilai' => 4500000,
            'status' => 'disetujui_ppk',
        ]);

        $spjMei = Spj::create([
            'nomor_spj' => 'SPJ/BPS/2026/MEI',
            'user_id' => $this->userTeknis->id,
            'kegiatan' => 'Kegiatan Survei Angkatan Kerja Mei',
            'tanggal' => Carbon::create(2026, 5, 20),
            'nilai' => 3000000,
            'status' => 'selesai',
        ]);

        $response = $this->actingAs($this->userUmum)->get(route('umum.laporan.index', [
            'filter_type' => 'bulan',
            'bulan' => 10,
            'tahun' => 2026,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Bulan Oktober 2026');
        $response->assertSee('Kegiatan Survei Harga Konsumen Oktober');
        $response->assertDontSee('Kegiatan Survei Angkatan Kerja Mei');
    }

    public function test_user_umum_can_filter_laporan_by_date_range(): void
    {
        Spj::create([
            'nomor_spj' => 'SPJ/BPS/RANGE/01',
            'user_id' => $this->userTeknis->id,
            'kegiatan' => 'Survei Ubinan Padi Periode Awal',
            'tanggal' => Carbon::create(2026, 10, 12),
            'nilai' => 2500000,
            'status' => 'selesai',
        ]);

        Spj::create([
            'nomor_spj' => 'SPJ/BPS/RANGE/02',
            'user_id' => $this->userTeknis->id,
            'kegiatan' => 'Survei Industri Mikro Periode Akhir',
            'tanggal' => Carbon::create(2026, 10, 28),
            'nilai' => 6000000,
            'status' => 'diajukan',
        ]);

        $response = $this->actingAs($this->userUmum)->get(route('umum.laporan.index', [
            'filter_type' => 'tanggal',
            'tanggal_mulai' => '2026-10-10',
            'tanggal_selesai' => '2026-10-15',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Survei Ubinan Padi Periode Awal');
        $response->assertDontSee('Survei Industri Mikro Periode Akhir');
    }

    public function test_user_umum_can_filter_laporan_by_status(): void
    {
        Spj::create([
            'nomor_spj' => 'SPJ/BPS/SELESAI',
            'user_id' => $this->userTeknis->id,
            'kegiatan' => 'SPJ yang Sudah Tuntas Cair',
            'tanggal' => Carbon::create(2026, 10, 5),
            'nilai' => 8000000,
            'status' => 'selesai',
        ]);

        Spj::create([
            'nomor_spj' => 'SPJ/BPS/REVISI',
            'user_id' => $this->userTeknis->id,
            'kegiatan' => 'SPJ yang Masih Tertahan Revisi',
            'tanggal' => Carbon::create(2026, 10, 6),
            'nilai' => 2000000,
            'status' => 'revisi_umum',
            'catatan_revisi' => 'Kuitansi belum bermaterai',
        ]);

        $response = $this->actingAs($this->userUmum)->get(route('umum.laporan.index', [
            'status' => 'selesai',
        ]));

        $response->assertStatus(200);
        $response->assertSee('SPJ yang Sudah Tuntas Cair');
        $response->assertDontSee('SPJ yang Masih Tertahan Revisi');
    }

    public function test_user_umum_can_export_and_stream_pdf_report(): void
    {
        Spj::create([
            'nomor_spj' => 'SPJ/BPS/PRINT/001',
            'user_id' => $this->userTeknis->id,
            'kegiatan' => 'Pelatihan Petugas Sensus Daerah 2026',
            'tanggal' => Carbon::create(2026, 10, 15),
            'nilai' => 12500000,
            'status' => 'selesai',
            'nomor_spm' => 'SPM-2026-0042',
            'diselesaikan_at' => Carbon::create(2026, 10, 20),
        ]);

        $response = $this->actingAs($this->userUmum)->get(route('umum.laporan.print', [
            'filter_type' => 'bulan',
            'bulan' => 10,
            'tahun' => 2026,
        ]));

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
    }
}
