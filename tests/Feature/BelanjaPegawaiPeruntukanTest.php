<?php

namespace Tests\Feature;

use App\Models\SyaratDokumenSpj;
use App\Models\User;
use Database\Seeders\SyaratDokumenSpjSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BelanjaPegawaiPeruntukanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SyaratDokumenSpjSeeder::class);
    }

    public function test_teknis_cannot_see_belanja_pegawai_kode_51(): void
    {
        $teknis = User::factory()->create([
            'role' => 'teknis',
            'status' => 'aktif',
            'seksi' => 'Sosial',
        ]);

        $response = $this->actingAs($teknis)->get('/teknis/spj/create');
        $response->assertStatus(200);

        // Memastikan belanja umum seperti belanja bahan ada
        $response->assertSee('Belanja Bahan (521211)');

        // Memastikan seluruh 7 Belanja Pegawai (51xx) tidak dapat dilihat oleh staf teknis
        $response->assertDontSee('Belanja Gaji PNS (5111)');
        $response->assertDontSee('Belanja Gaji PPPK (5116)');
        $response->assertDontSee('Belanja Tunjangan Kinerja PNS (512411)');
        $response->assertDontSee('Belanja Tunjangan Kinerja PPPK (512414)');
        $response->assertDontSee('Belanja Uang Makan PNS (511129)');
        $response->assertDontSee('Belanja Uang Makan PPPK (511628)');
        $response->assertDontSee('Belanja Lembur (512211)');
    }

    public function test_umum_can_see_belanja_pegawai_kode_51(): void
    {
        $umum = User::factory()->create([
            'role' => 'umum',
            'status' => 'aktif',
            'seksi' => 'Subbag Umum',
        ]);

        $response = $this->actingAs($umum)->get('/umum/spj/create');
        $response->assertStatus(200);

        // Memastikan Belanja Pegawai (51xx) tersedia untuk Subbagian Umum
        $response->assertSee('Belanja Gaji PNS (5111)');
        $response->assertSee('Belanja Gaji PPPK (5116)');
        $response->assertSee('Belanja Tunjangan Kinerja PNS (512411)');
        $response->assertSee('Belanja Tunjangan Kinerja PPPK (512414)');
        $response->assertSee('Belanja Uang Makan PNS (511129)');
        $response->assertSee('Belanja Uang Makan PPPK (511628)');
        $response->assertSee('Belanja Lembur (512211)');
    }

    public function test_syarat_dokumen_spj_model_filters_peruntukan_correctly(): void
    {
        $teknisData = SyaratDokumenSpj::getSpjData('teknis');
        $this->assertArrayNotHasKey('Belanja Gaji PNS (5111)', $teknisData);
        $this->assertArrayNotHasKey('Belanja Lembur (512211)', $teknisData);
        $this->assertArrayHasKey('Belanja Bahan (521211)', $teknisData);

        $umumData = SyaratDokumenSpj::getSpjData('umum');
        $this->assertArrayHasKey('Belanja Gaji PNS (5111)', $umumData);
        $this->assertArrayHasKey('Belanja Lembur (512211)', $umumData);
        $this->assertArrayHasKey('Belanja Bahan (521211)', $umumData);
    }

    public function test_admin_can_submit_spj_for_both_teknis_and_umum(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        // Submit untuk Teknis
        $responseTeknis = $this->actingAs($admin)->post('/admin/spj', [
            'peruntukan' => 'teknis',
            'seksi' => 'Distribusi',
            'nama_belanja' => 'Belanja Bahan (521211)',
            'jenis_belanja' => 'Belanja Konsumsi',
            'kegiatan' => 'Rapat Koordinasi Survei Harga',
            'tanggal' => now()->format('Y-m-d'),
            'nilai' => '3.000.000',
            'status' => 'draft',
            'nama_dokumen' => ['0' => 'KAK'],
            'assignee_dokumen' => ['0' => 'SM'],
            'berkas' => ['0' => '1'],
        ]);
        $responseTeknis->assertRedirect(route('admin.spj.index'));

        $this->assertDatabaseHas('spjs', [
            'kegiatan' => 'Rapat Koordinasi Survei Harga',
            'seksi' => 'Distribusi',
            'nilai' => 3000000,
        ]);

        // Submit untuk Umum dengan Belanja Pegawai (51xx)
        $responseUmum = $this->actingAs($admin)->post('/admin/spj', [
            'peruntukan' => 'umum',
            'nama_belanja' => 'Belanja Lembur (512211)',
            'jenis_belanja' => 'Belanja Lembur',
            'kegiatan' => 'Lembur Penyusunan LAKIP BPS Kolut',
            'tanggal' => now()->format('Y-m-d'),
            'nilai' => '4.250.000',
            'status' => 'diajukan',
            'is_printed' => '1',
            'nama_dokumen' => ['0' => 'KAK', '1' => 'Surat Perintah Lembur'],
            'assignee_dokumen' => ['0' => 'SM', '1' => 'SM'],
            'berkas' => ['0' => '1', '1' => '1'],
        ]);
        $responseUmum->assertRedirect(route('admin.spj.index'));

        $this->assertDatabaseHas('spjs', [
            'kegiatan' => 'Lembur Penyusunan LAKIP BPS Kolut',
            'seksi' => 'Subbag Umum',
            'nilai' => 4250000,
            'status' => 'diajukan',
        ]);
    }
}
