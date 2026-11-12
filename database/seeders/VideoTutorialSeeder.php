<?php

namespace Database\Seeders;

use App\Models\VideoTutorial;
use Illuminate\Database\Seeder;

class VideoTutorialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bersihkan data dummy sebelumnya agar hanya menyisakan maksimal 1 video tutorial utama
        VideoTutorial::truncate();

        VideoTutorial::create([
            'judul' => 'Video Tutorial Alur SPJ Digital 5 Pintu BPS Kolaka Utara',
            'kategori' => 'semua',
            'tipe_sumber' => 'link',
            'url_video' => null,
            'file_video' => null,
            'youtube_id' => null,
            'deskripsi' => 'Panduan lengkap tata cara pengajuan, verifikasi checklist dokumen, pengujian komitmen, hingga pencairan dana SPJ.',
            'durasi' => null,
            'urutan' => 1,
            'is_active' => true,
        ]);
    }
}
