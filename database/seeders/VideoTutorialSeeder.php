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
        $tutorials = [
            [
                'judul' => 'Alur Penyelenggaraan SOP SPJ 5 Pintu BPS Kolaka Utara',
                'kategori' => 'semua',
                'tipe_sumber' => 'link',
                'url_video' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'youtube_id' => 'dQw4w9WgXcQ',
                'deskripsi' => 'Gambaran umum proses dan tahapan tata kelola pertanggungjawaban belanja APBN dari hulu ke hilir.',
                'durasi' => '04:15',
                'urutan' => 1,
                'is_active' => true,
            ],
            [
                'judul' => 'Panduan Pengajuan SPJ Digital & Checklist Berkas (Staf Teknis)',
                'kategori' => 'teknis',
                'tipe_sumber' => 'link',
                'url_video' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'youtube_id' => 'dQw4w9WgXcQ',
                'deskripsi' => 'Tata cara pembuatan pengajuan SPJ, pemilihan kategori belanja akun 52, checklist dokumen, dan pelacakan status.',
                'durasi' => '06:40',
                'urutan' => 2,
                'is_active' => true,
            ],
            [
                'judul' => 'Verifikasi Checklist Dokumen & Penerimaan Fisik (Subbag Umum)',
                'kategori' => 'umum',
                'tipe_sumber' => 'link',
                'url_video' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'youtube_id' => 'dQw4w9WgXcQ',
                'deskripsi' => 'Panduan verifikator Subbagian Umum dalam memeriksa fisik berkas, menandai checklist, dan meneruskan berkas ke PPK.',
                'durasi' => '05:25',
                'urutan' => 3,
                'is_active' => true,
            ],
            [
                'judul' => 'Pengujian Komitmen Anggaran & Penerbitan Persetujuan (PPK)',
                'kategori' => 'ppk',
                'tipe_sumber' => 'link',
                'url_video' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'youtube_id' => 'dQw4w9WgXcQ',
                'deskripsi' => 'Pengujian kelayakan komitmen anggaran DIPA/POK, pemberian catatan revisi, dan pengesahan persetujuan PPK.',
                'durasi' => '04:50',
                'urutan' => 4,
                'is_active' => true,
            ],
            [
                'judul' => 'Validasi Dokumen Tagihan & Penerbitan SPM Resmi (PPSPM)',
                'kategori' => 'ppspm',
                'tipe_sumber' => 'link',
                'url_video' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'youtube_id' => 'dQw4w9WgXcQ',
                'deskripsi' => 'Validasi menyeluruh dokumen pengajuan tagihan dan proses input nomor SPM sebelum diteruskan ke Bendahara.',
                'durasi' => '03:45',
                'urutan' => 5,
                'is_active' => true,
            ],
            [
                'judul' => 'Eksekusi Transaksi BRI QLOLA & Pembayaran LS (Bendahara)',
                'kategori' => 'bendahara',
                'tipe_sumber' => 'link',
                'url_video' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'youtube_id' => 'dQw4w9WgXcQ',
                'deskripsi' => 'Input transaksi transfer di portal BRI QLOLA sebagai Maker, checklist perpajakan, hingga otorisasi OTP oleh KPA.',
                'durasi' => '07:10',
                'urutan' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($tutorials as $item) {
            VideoTutorial::updateOrCreate(
                ['judul' => $item['judul']],
                $item
            );
        }
    }
}
