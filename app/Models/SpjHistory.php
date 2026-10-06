<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpjHistory extends Model
{
    protected $fillable = [
        'spj_id',
        'user_id',
        'aksi',
        'status_lama',
        'status_baru',
        'keterangan',
    ];

    public function spj()
    {
        return $this->belongsTo(Spj::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
