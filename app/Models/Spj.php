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
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nilai' => 'decimal:2',
    ];
}
