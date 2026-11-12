<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VideoTutorial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminVideoTutorialTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_video_tutorial_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        VideoTutorial::create([
            'judul' => 'Tutorial Utama SPJ',
            'kategori' => 'semua',
            'tipe_sumber' => 'link',
            'url_video' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'youtube_id' => 'dQw4w9WgXcQ',
            'deskripsi' => 'Deskripsi testing tutorial utama.',
            'urutan' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.video-tutorial.index'));

        $response->assertStatus(200);
        $response->assertSee('Kelola Video Tutorial Panduan');
        $response->assertSee('Tutorial Utama SPJ');
    }

    public function test_non_admin_cannot_access_video_tutorial_management(): void
    {
        $teknis = User::factory()->create(['role' => 'teknis']);

        $response = $this->actingAs($teknis)->get(route('admin.video-tutorial.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_store_single_video_tutorial_with_youtube_link(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $payload = [
            'judul' => 'Panduan Tunggal SPJ YouTube',
            'tipe_sumber' => 'link',
            'url_video' => 'https://youtu.be/abc12345678',
            'deskripsi' => 'Petunjuk lengkap satu video panduan SPJ.',
            'durasi' => '05:30',
            'is_active' => '1',
        ];

        $response = $this->actingAs($admin)->post(route('admin.video-tutorial.store'), $payload);
        $response->assertRedirect(route('admin.video-tutorial.index'));

        $this->assertDatabaseHas('video_tutorials', [
            'judul' => 'Panduan Tunggal SPJ YouTube',
            'tipe_sumber' => 'link',
            'youtube_id' => 'abc12345678',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_upload_and_replace_single_video_tutorial_file(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);

        $file = UploadedFile::fake()->create('panduan_video.mp4', 5000, 'video/mp4');

        $payload = [
            'judul' => 'Panduan Video Upload Berkas MP4',
            'tipe_sumber' => 'upload',
            'file_video' => $file,
            'deskripsi' => 'File video lokal MP4.',
            'durasi' => '02:15',
            'is_active' => '1',
        ];

        $response = $this->actingAs($admin)->post(route('admin.video-tutorial.store'), $payload);
        $response->assertRedirect(route('admin.video-tutorial.index'));

        $video = VideoTutorial::where('judul', 'Panduan Video Upload Berkas MP4')->first();
        $this->assertNotNull($video);
        $this->assertSame('upload', $video->tipe_sumber);
        $this->assertNotNull($video->file_video);
        Storage::disk('public')->assertExists($video->file_video);
    }

    public function test_admin_can_delete_single_video_tutorial(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);

        $file = UploadedFile::fake()->create('hapus_video.mp4', 1000, 'video/mp4');
        $filePath = $file->store('videos/tutorials', 'public');

        $video = VideoTutorial::create([
            'judul' => 'Video Mau Dihapus',
            'kategori' => 'semua',
            'tipe_sumber' => 'upload',
            'file_video' => $filePath,
            'urutan' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.video-tutorial.destroy'));
        $response->assertRedirect(route('admin.video-tutorial.index'));

        $this->assertDatabaseMissing('video_tutorials', ['id' => $video->id]);
        Storage::disk('public')->assertMissing($filePath);
    }

    public function test_home_page_displays_active_single_video_tutorial(): void
    {
        VideoTutorial::create([
            'judul' => 'Video Tunggal Resmi SPJ',
            'kategori' => 'semua',
            'tipe_sumber' => 'link',
            'url_video' => 'https://www.youtube.com/watch?v=pub12345678',
            'youtube_id' => 'pub12345678',
            'deskripsi' => 'Deskripsi publik video tutorial tunggal.',
            'urutan' => 1,
            'is_active' => true,
        ]);

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('Video Tunggal Resmi SPJ');
        $response->assertSee('Deskripsi publik video tutorial tunggal.');
    }

    public function test_home_page_shows_friendly_placeholder_when_no_video_uploaded(): void
    {
        VideoTutorial::truncate();

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('Video Tutorial Belum Diunggah');
    }
}
