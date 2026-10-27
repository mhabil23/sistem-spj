<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spj extends Model
{
    protected $fillable = [
        'nomor_spj',
        'kegiatan',
        'tanggal',
        'nilai',
        'keterangan',
        'no_wa',
        'status',
        'user_id',
        'dokumen_file',
        'catatan_revisi',
        'catatan_internal',
        'catatan_ppk',
        'disposisi',
        'nomor_spm',
        'bukti_transfer',
        'catatan_ppspm',
        'diajukan_at',
        'berkas_fisik_diterima_at',
        'pemeriksaan_dimulai_at',
        'disetujui_umum_at',
        'disetujui_ppk_at',
        'disetujui_ppspm_at',
        'diselesaikan_at',
        'kel_daftar_penerima',
        'kel_bast',
        'kel_sk',
        'kel_kak',
        'kel_form_permintaan',
        'kel_spk',
        'kel_surat_tugas',
        'kel_kesesuaian_mrk',
        'kel_cms',
        'kel_cek_sbks',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function histories()
    {
        return $this->hasMany(SpjHistory::class)->latest('id');
    }

    protected $casts = [
        'tanggal' => 'date',
        'nilai' => 'decimal:2',
        'diajukan_at' => 'datetime',
        'berkas_fisik_diterima_at' => 'datetime',
        'pemeriksaan_dimulai_at' => 'datetime',
        'disetujui_umum_at' => 'datetime',
        'disetujui_ppk_at' => 'datetime',
        'disetujui_ppspm_at' => 'datetime',
        'diselesaikan_at' => 'datetime',
    ];

    /**
     * Bootstrap model events.
     */
    protected static function booted(): void
    {
        static::creating(function (Spj $spj): void {
            if (empty($spj->nomor_spj)) {
                $spj->nomor_spj = self::generateNomorSpj();
            }
        });
    }

    /**
     * Generate nomor SPJ otomatis dengan format: {URUT_3_DIGIT}{DD}{MM}{YYYY}
     *
     * Contoh: 00127102026 = SPJ ke-001, tanggal 27, bulan 10, tahun 2026
     * Nomor urut direset setiap hari (per tanggal).
     */
    public static function generateNomorSpj(): string
    {
        $today = now();
        $day = $today->format('d');
        $month = $today->format('m');
        $year = $today->format('Y');
        $dateSuffix = $day.$month.$year;

        // Cari SPJ terakhir yang dibuat hari ini dengan pola nomor yang sesuai
        $lastSpj = self::where('nomor_spj', 'like', "___$dateSuffix")
            ->orderByDesc('id')
            ->first();

        $nextNumber = 1;
        if ($lastSpj && preg_match('/^(\d{3})/', (string) $lastSpj->nomor_spj, $matches)) {
            $nextNumber = ((int) $matches[1]) + 1;
        }

        $nomor = str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT).$dateSuffix;

        while (self::where('nomor_spj', $nomor)->exists()) {
            $nextNumber++;
            $nomor = str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT).$dateSuffix;
        }

        return $nomor;
    }
}
