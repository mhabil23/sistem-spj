<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AdminTemplateBerkasTest extends TestCase
{
    use RefreshDatabase;

    protected string $templatesPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->templatesPath = public_path('files/templates');
    }

    protected function tearDown(): void
    {
        // Bersihkan berkas uji jika ada
        if (File::exists($this->templatesPath)) {
            File::cleanDirectory($this->templatesPath);
        }
        parent::tearDown();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.template-berkas.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_template_berkas(): void
    {
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $response = $this->actingAs($teknis)->get(route('admin.template-berkas.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_view_template_berkas_page_and_upload_form(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);

        $response = $this->actingAs($admin)->get(route('admin.template-berkas.index'));

        $response->assertStatus(200);
        $response->assertSee('Kelola Template Berkas SPJ');
        $response->assertSee('Formulir Unggah Template Berkas Baru');
        $response->assertSee('Nama Berkas Template');
        $response->assertSee('Kategori Dokumen');
        $response->assertSee(route('admin.template-berkas.upload'));
    }

    public function test_admin_can_upload_template_file_to_public_directory(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);

        $file = UploadedFile::fake()->create('format-kak-2026.docx', 120, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');

        $response = $this->actingAs($admin)->post(route('admin.template-berkas.upload'), [
            'nama' => 'Format Kerangka Acuan Kerja (KAK) 2026',
            'kategori' => 'Administrasi Utama',
            'kode_dokumen' => 'KAK-01',
            'kode_akun' => '521211, 521811',
            'pic' => 'Seksi Teknis / SM',
            'keterangan' => 'Format KAK standar kegiatan sensus/survei BPS Kolaka Utara',
            'berkas_template' => $file,
        ]);

        $response->assertRedirect(route('admin.template-berkas.index'));
        $response->assertSessionHas('success');

        // Pastikan manifest JSON terisi
        $manifestPath = $this->templatesPath.'/manifest.json';
        $this->assertFileExists($manifestPath);

        $manifestData = json_decode(File::get($manifestPath), true);
        $this->assertIsArray($manifestData);
        $this->assertCount(1, $manifestData);
        $this->assertEquals('Format Kerangka Acuan Kerja (KAK) 2026', $manifestData[0]['nama']);

        // Pastikan file fisik tersimpan di direktori publik
        $savedFile = $this->templatesPath.'/'.$manifestData[0]['file_name'];
        $this->assertFileExists($savedFile);

        // Verifikasi tampil di halaman index
        $indexResponse = $this->actingAs($admin)->get(route('admin.template-berkas.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Format Kerangka Acuan Kerja (KAK) 2026');
        $indexResponse->assertSee('KAK-01');
        $indexResponse->assertSee('DOCX');
    }

    public function test_admin_can_delete_uploaded_template(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);

        $file = UploadedFile::fake()->create('format-kuitansi.xlsx', 80, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $this->actingAs($admin)->post(route('admin.template-berkas.upload'), [
            'nama' => 'Format Kuitansi Pembayaran BPS',
            'kategori' => 'Administrasi Utama',
            'kode_dokumen' => 'KWT-01',
            'berkas_template' => $file,
        ]);

        $manifestPath = $this->templatesPath.'/manifest.json';
        $manifestData = json_decode(File::get($manifestPath), true);
        $templateId = $manifestData[0]['id'];
        $savedFile = $this->templatesPath.'/'.$manifestData[0]['file_name'];
        $this->assertFileExists($savedFile);

        // Hapus template
        $deleteResponse = $this->actingAs($admin)->delete(route('admin.template-berkas.destroy', $templateId));
        $deleteResponse->assertRedirect(route('admin.template-berkas.index'));
        $deleteResponse->assertSessionHas('success');

        // Pastikan file fisik terhapus dan manifest kosong
        $this->assertFileDoesNotExist($savedFile);
        $updatedManifest = json_decode(File::get($manifestPath), true);
        $this->assertCount(0, $updatedManifest);
    }
}
