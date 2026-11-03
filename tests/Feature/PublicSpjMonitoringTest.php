<?php

namespace Tests\Feature;

use App\Models\Spj;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSpjMonitoringTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Memastikan halaman publik dapat diakses tanpa login (guest)
     * dan menampilkan bagian Dashboard Monitoring & Status SPJ.
     */
    public function test_halaman_beranda_publik_dapat_diakses_tanpa_login(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard Monitoring &amp; Status SPJ', false);
        $response->assertSee('Cari Nama / Kegiatan');
        $response->assertSee('Tanggal SPJ');
        $response->assertSee('Jumlah / Nominal');
    }

    /**
     * Memastikan data SPJ yang aktif (bukan draft) terbaca di dashboard publik.
     */
    public function test_membaca_dan_menampilkan_seluruh_hasil_spj_publik(): void
    {
        $user = User::factory()->create(['name' => 'Budi Santoso', 'role' => 'teknis']);

        $spj1 = Spj::create([
            'nomor_spj' => '00128102026',
            'kegiatan' => 'Survei Angkatan Kerja Nasional (Sakernas)',
            'tanggal' => '2026-10-28',
            'nilai' => 8450000,
            'status' => 'disetujui_ppspm',
            'user_id' => $user->id,
            'diajukan_at' => now()->subDays(2),
        ]);

        $spj2 = Spj::create([
            'nomor_spj' => '00228102026',
            'kegiatan' => 'Survei Sosial Ekonomi Nasional (Susenas)',
            'tanggal' => '2026-10-29',
            'nilai' => 12300000,
            'status' => 'selesai',
            'user_id' => $user->id,
            'diajukan_at' => now()->subDay(),
        ]);

        // SPJ draft tidak boleh tampil di publik
        $spjDraft = Spj::create([
            'nomor_spj' => '00328102026',
            'kegiatan' => 'Draft Rahasia Internal Belum Diajukan',
            'tanggal' => '2026-10-30',
            'nilai' => 1000000,
            'status' => 'draft',
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('00128102026');
        $response->assertSee('Survei Angkatan Kerja Nasional (Sakernas)');
        $response->assertSee('00228102026');
        $response->assertSee('Survei Sosial Ekonomi Nasional (Susenas)');
        $response->assertDontSee('Draft Rahasia Internal Belum Diajukan');
    }

    /**
     * Memastikan pencarian berdasarkan Nama (kegiatan atau staf pengaju) berfungsi.
     */
    public function test_filter_pencarian_berdasarkan_nama(): void
    {
        $userA = User::factory()->create(['name' => 'Ahmad Dahlan', 'role' => 'teknis']);
        $userB = User::factory()->create(['name' => 'Siti Nurhaliza', 'role' => 'teknis']);

        Spj::create([
            'nomor_spj' => '10128102026',
            'kegiatan' => 'Pengadaan ATK dan Bahan Cetak Sensus',
            'tanggal' => '2026-10-28',
            'nilai' => 2500000,
            'status' => 'diajukan',
            'user_id' => $userA->id,
        ]);

        Spj::create([
            'nomor_spj' => '10228102026',
            'kegiatan' => 'Honor Tim Lapangan Survei Pertanian',
            'tanggal' => '2026-10-28',
            'nilai' => 6000000,
            'status' => 'selesai',
            'user_id' => $userB->id,
        ]);

        // Filter berdasarkan nama kegiatan
        $response1 = $this->get(route('home', ['cari_nama' => 'ATK']));
        $response1->assertStatus(200);
        $response1->assertSee('Pengadaan ATK dan Bahan Cetak Sensus');
        $response1->assertDontSee('Honor Tim Lapangan Survei Pertanian');

        // Filter berdasarkan nama pengaju
        $response2 = $this->get(route('home', ['cari_nama' => 'Siti Nurhaliza']));
        $response2->assertStatus(200);
        $response2->assertSee('Honor Tim Lapangan Survei Pertanian');
        $response2->assertDontSee('Pengadaan ATK dan Bahan Cetak Sensus');
    }

    /**
     * Memastikan filter pencarian berdasarkan Tanggal berfungsi.
     */
    public function test_filter_pencarian_berdasarkan_tanggal(): void
    {
        $user = User::factory()->create(['role' => 'teknis']);

        Spj::create([
            'nomor_spj' => '20128102026',
            'kegiatan' => 'Kegiatan Hari Pertama 25 Okt',
            'tanggal' => '2026-10-25',
            'nilai' => 3000000,
            'status' => 'diajukan',
            'user_id' => $user->id,
        ]);

        Spj::create([
            'nomor_spj' => '20228102026',
            'kegiatan' => 'Kegiatan Hari Kedua 30 Okt',
            'tanggal' => '2026-10-30',
            'nilai' => 4500000,
            'status' => 'diajukan',
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('home', ['cari_tanggal' => '2026-10-25']));
        $response->assertStatus(200);
        $response->assertSee('Kegiatan Hari Pertama 25 Okt');
        $response->assertDontSee('Kegiatan Hari Kedua 30 Okt');
    }

    /**
     * Memastikan filter pencarian berdasarkan Jumlah / Nominal (Rp) berfungsi.
     */
    public function test_filter_pencarian_berdasarkan_jumlah(): void
    {
        $user = User::factory()->create(['role' => 'teknis']);

        Spj::create([
            'nomor_spj' => '30128102026',
            'kegiatan' => 'SPJ Bernilai 4 Juta 750 Ribu',
            'tanggal' => '2026-10-28',
            'nilai' => 4750000,
            'status' => 'diajukan',
            'user_id' => $user->id,
        ]);

        Spj::create([
            'nomor_spj' => '30228102026',
            'kegiatan' => 'SPJ Bernilai 8 Juta 500 Ribu',
            'tanggal' => '2026-10-28',
            'nilai' => 8500000,
            'status' => 'diajukan',
            'user_id' => $user->id,
        ]);

        // Cari jumlah angka
        $response = $this->get(route('home', ['cari_jumlah' => '4750000']));
        $response->assertStatus(200);
        $response->assertSee('SPJ Bernilai 4 Juta 750 Ribu');
        $response->assertDontSee('SPJ Bernilai 8 Juta 500 Ribu');
    }

    /**
     * Memastikan filter status dan pengurutan terbaru berfungsi.
     */
    public function test_filter_status_dan_urutan_terbaru(): void
    {
        $user = User::factory()->create(['role' => 'teknis']);

        $spjLama = Spj::create([
            'nomor_spj' => '40128102026',
            'kegiatan' => 'SPJ Lama Status Selesai',
            'tanggal' => '2026-10-01',
            'nilai' => 2000000,
            'status' => 'selesai',
            'user_id' => $user->id,
            'created_at' => now()->subDays(10),
        ]);

        $spjBaru = Spj::create([
            'nomor_spj' => '40228102026',
            'kegiatan' => 'SPJ Baru Status Revisi',
            'tanggal' => '2026-10-28',
            'nilai' => 5000000,
            'status' => 'revisi_umum',
            'user_id' => $user->id,
            'created_at' => now(),
        ]);

        // Filter status selesai
        $responseSelesai = $this->get(route('home', ['status' => 'selesai']));
        $responseSelesai->assertStatus(200);
        $responseSelesai->assertSee('SPJ Lama Status Selesai');
        $responseSelesai->assertDontSee('SPJ Baru Status Revisi');

        // Filter status revisi
        $responseRevisi = $this->get(route('home', ['status' => 'revisi']));
        $responseRevisi->assertStatus(200);
        $responseRevisi->assertSee('SPJ Baru Status Revisi');
        $responseRevisi->assertDontSee('SPJ Lama Status Selesai');

        // Urutan nilai tertinggi
        $responseSortNilai = $this->get(route('home', ['sort' => 'nilai_tertinggi']));
        $responseSortNilai->assertStatus(200);
        $content = $responseSortNilai->getContent();
        $posisiBaru = strpos($content, 'SPJ Baru Status Revisi');
        $posisiLama = strpos($content, 'SPJ Lama Status Selesai');
        $this->assertTrue($posisiBaru < $posisiLama);
    }
}
