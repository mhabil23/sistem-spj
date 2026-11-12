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
            'judul' => 'Tutorial Testing 1',
            'kategori' => 'teknis',
            'tipe_sumber' => 'link',
            'url_video' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'youtube_id' => 'dQw4w9WgXcQ',
            'deskripsi' => 'Deskripsi testing tutorial.',
            'urutan' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.video-tutorial.index'));

        $response->assertStatus(200);
        $response->assertSee('Kelola Video Tutorial');
        $response->assertSee('Tutorial Testing 1');
    }

    public function test_non_admin_cannot_access_video_tutorial_management(): void
    {
        $teknis = User::factory()->create(['role' => 'teknis']);

        $response = $this->actingAs($teknis)->get(route('admin.video-tutorial.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_store_new_video_tutorial_with_youtube_link(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $payload = [
            'judul' => 'Panduan Baru Upload SPJ Teknis',
            'kategori' => 'teknis',
            'tipe_sumber' => 'link',
            'url_video' => 'https://youtu.be/abc12345678',
            'deskripsi' => 'Petunjuk lengkap cara input dokumen SPJ.',
            'durasi' => '05:30',
            'urutan' => 1,
            'is_active' => '1',
        ];

        $response = $this->actingAs($admin)->post(route('admin.video-tutorial.store'), $payload);
        $response->assertRedirect(route('admin.video-tutorial.index'));

        $this->assertDatabaseHas('video_tutorials', [
            'judul' => 'Panduan Baru Upload SPJ Teknis',
            'kategori' => 'teknis',
            'tipe_sumber' => 'link',
            'youtube_id' => 'abc12345678',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_store_new_video_tutorial_with_uploaded_file(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);

        $file = UploadedFile::fake()->create('panduan_video.mp4', 5000, 'video/mp4');

        $payload = [
            'judul' => 'Panduan Video Upload Langsung',
            'kategori' => 'umum',
            'tipe_sumber' => 'upload',
            'file_video' => $file,
            'deskripsi' => 'File video lokal MP4.',
            'durasi' => '02:15',
            'urutan' => 2,
            'is_active' => '1',
        ];

        $response = $this->actingAs($admin)->post(route('admin.video-tutorial.store'), $payload);
        $response->assertRedirect(route('admin.video-tutorial.index'));

        $video = VideoTutorial::where('judul', 'Panduan Video Upload Langsung')->first();
        $this->assertNotNull($video);
        $this->assertSame('upload', $video->tipe_sumber);
        $this->assertNotNull($video->file_video);
        Storage::disk('public')->assertExists($video->file_video);
    }

    public function test_admin_can_update_video_tutorial(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $video = VideoTutorial::create([
            'judul' => 'Judul Lama',
            'kategori' => 'bendahara',
            'tipe_sumber' => 'link',
            'url_video' => 'https://www.youtube.com/watch?v=old12345678',
            'youtube_id' => 'old12345678',
            'deskripsi' => 'Deskripsi lama.',
            'urutan' => 1,
            'is_active' => true,
        ]);

        $payload = [
            'judul' => 'Judul Baru Diperbarui',
            'kategori' => 'bendahara',
            'tipe_sumber' => 'link',
            'url_video' => 'https://www.youtube.com/watch?v=new87654321',
            'deskripsi' => 'Deskripsi baru yang telah diupdate.',
            'durasi' => '08:00',
            'urutan' => 3,
            'is_active' => '1',
        ];

        $response = $this->actingAs($admin)->put(route('admin.video-tutorial.update', $video->id), $payload);
        $response->assertRedirect(route('admin.video-tutorial.index'));

        $this->assertDatabaseHas('video_tutorials', [
            'id' => $video->id,
            'judul' => 'Judul Baru Diperbarui',
            'youtube_id' => 'new87654321',
            'urutan' => 3,
        ]);
    }

    public function test_admin_can_toggle_video_tutorial_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $video = VideoTutorial::create([
            'judul' => 'Video Toggle Test',
            'kategori' => 'ppk',
            'tipe_sumber' => 'link',
            'url_video' => 'https://www.youtube.com/watch?v=xyz12345678',
            'urutan' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.video-tutorial.toggle', $video->id));
        $response->assertRedirect(route('admin.video-tutorial.index'));

        $this->assertFalse($video->fresh()->is_active);

        $response2 = $this->actingAs($admin)->patch(route('admin.video-tutorial.toggle', $video->id));
        $response2->assertRedirect(route('admin.video-tutorial.index'));

        $this->assertTrue($video->fresh()->is_active);
    }

    public function test_admin_can_delete_video_tutorial(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);

        $file = UploadedFile::fake()->create('hapus_video.mp4', 1000, 'video/mp4');
        $filePath = $file->store('videos/tutorials', 'public');

        $video = VideoTutorial::create([
            'judul' => 'Video Mau Dihapus',
            'kategori' => 'ppspm',
            'tipe_sumber' => 'upload',
            'file_video' => $filePath,
            'urutan' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.video-tutorial.destroy', $video->id));
        $response->assertRedirect(route('admin.video-tutorial.index'));

        $this->assertDatabaseMissing('video_tutorials', ['id' => $video->id]);
        Storage::disk('public')->assertMissing($filePath);
    }

    public function test_home_page_displays_active_video_tutorials_in_modal(): void
    {
        VideoTutorial::create([
            'judul' => 'Video Muncul di Publik Beranda',
            'kategori' => 'teknis',
            'tipe_sumber' => 'link',
            'url_video' => 'https://www.youtube.com/watch?v=pub12345678',
            'youtube_id' => 'pub12345678',
            'deskripsi' => 'Deskripsi publik video tutorial.',
            'urutan' => 1,
            'is_active' => true,
        ]);

        VideoTutorial::create([
            'judul' => 'Video Nonaktif Tersembunyi',
            'kategori' => 'teknis',
            'tipe_sumber' => 'link',
            'url_video' => 'https://www.youtube.com/watch?v=hid12345678',
            'youtube_id' => 'hid12345678',
            'urutan' => 2,
            'is_active' => false,
        ]);

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('Video Muncul di Publik Beranda');
        $response->assertDontSee('Video Nonaktif Tersembunyi');
    }
}
