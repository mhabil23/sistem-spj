<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class VideoTutorial extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'kategori',
        'tipe_sumber',
        'url_video',
        'file_video',
        'youtube_id',
        'deskripsi',
        'durasi',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];

    /**
     * Ekstrak YouTube ID dari berbagai format link (youtube.com, youtu.be, shorts, embed).
     */
    public static function extractYouTubeId(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|youtube\.com\/shorts\/)([^"&?\/ ]{11})/i';

        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Dapatkan URL embed yang aman untuk diputar di iframe / HTML5 player.
     */
    public function getEmbedUrlAttribute(): ?string
    {
        if ($this->tipe_sumber === 'upload' && $this->file_video) {
            return Storage::url($this->file_video);
        }

        if ($this->youtube_id) {
            return "https://www.youtube-nocookie.com/embed/{$this->youtube_id}?rel=0&modestbranding=1";
        }

        if (! empty($this->url_video)) {
            // Google Drive preview link
            if (preg_match('/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/i', $this->url_video, $m)) {
                return "https://drive.google.com/file/d/{$m[1]}/preview";
            }

            return $this->url_video;
        }

        return null;
    }

    /**
     * Dapatkan URL thumbnail gambar untuk kartu tutorial.
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->youtube_id) {
            return "https://img.youtube.com/vi/{$this->youtube_id}/hqdefault.jpg";
        }

        return null;
    }

    /**
     * Label visual untuk kategori peruntukan role.
     */
    public function getKategoriLabelAttribute(): string
    {
        $labels = [
            'semua' => 'Semua Pengguna',
            'teknis' => 'Staf Teknis',
            'umum' => 'Subbagian Umum',
            'ppk' => 'PPK',
            'ppspm' => 'PPSPM',
            'bendahara' => 'Bendahara',
        ];

        return $labels[$this->kategori] ?? ucfirst($this->kategori);
    }

    /**
     * Badge CSS class untuk kategori.
     */
    public function getKategoriBadgeClassAttribute(): string
    {
        return match ($this->kategori) {
            'teknis' => 'badge-teknis',
            'umum' => 'badge-umum',
            'ppk' => 'badge-ppk',
            'ppspm' => 'badge-ppspm',
            'bendahara' => 'badge-bendahara',
            default => 'badge-semua',
        };
    }

    /**
     * Event boot model untuk auto extract youtube id.
     */
    protected static function booted(): void
    {
        static::saving(function (VideoTutorial $video): void {
            if ($video->tipe_sumber === 'link' && ! empty($video->url_video)) {
                $ytId = self::extractYouTubeId($video->url_video);
                if ($ytId) {
                    $video->youtube_id = $ytId;
                }
            }
        });
    }
}
