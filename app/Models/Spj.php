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
        'disetujui_umum_at',
        'disetujui_ppk_at',
        'disetujui_ppspm_at',
        'diselesaikan_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'tanggal' => 'date',
        'nilai' => 'decimal:2',
    ];
}
