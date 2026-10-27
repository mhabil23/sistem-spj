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
     * Generate nomor SPJ otomatis dengan format BPS: SPJ/BPS7408/{TAHUN}/{URUT_4_DIGIT}
     */
    public static function generateNomorSpj(): string
    {
        $year = date('Y');
        $prefix = "SPJ/BPS7408/{$year}/";

        $lastSpj = self::where('nomor_spj', 'like', "{$prefix}%")
            ->orderByDesc('id')
            ->first();

        $nextNumber = 1;
        if ($lastSpj && preg_match('/\/(\d+)$/', (string) $lastSpj->nomor_spj, $matches)) {
            $nextNumber = ((int) $matches[1]) + 1;
        } else {
            $countThisYear = self::whereYear('created_at', $year)->count();
            $nextNumber = max($countThisYear + 1, 1);
        }

        $nomor = $prefix.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);

        while (self::where('nomor_spj', $nomor)->exists()) {
            $nextNumber++;
            $nomor = $prefix.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
        }

        return $nomor;
    }
}
