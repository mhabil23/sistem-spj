<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

#[Fillable(['name', 'email', 'no_wa', 'avatar', 'password', 'role', 'status', 'seksi'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function spjs()
    {
        return $this->hasMany(Spj::class);
    }

    /**
     * Dapatkan daftar seluruh seksi yang tersedia secara dinamis.
     *
     * @return array<int, string>
     */
    public static function getAvailableSeksi(): array
    {
        $default = ['Sosial', 'Produksi', 'Distribusi', 'Neraca', 'IPDS'];
        $fromUsers = static::whereNotNull('seksi')
            ->where('seksi', '!=', '')
            ->distinct()
            ->pluck('seksi')
            ->toArray();

        $fromSpj = Spj::whereNotNull('seksi')
            ->where('seksi', '!=', '')
            ->distinct()
            ->pluck('seksi')
            ->toArray();

        $all = array_values(array_unique(array_filter(array_merge($default, $fromUsers, $fromSpj))));

        $clean = array_map(function ($s) {
            return preg_replace('/^Seksi\s+/i', '', trim($s));
        }, $all);

        $clean = array_values(array_unique(array_filter($clean)));
        sort($clean);

        return $clean;
    }

    public function getSeksiLabelAttribute(): string
    {
        $val = $this->seksi ?: $this->name;
        if (! $val) {
            return 'Staf Teknis';
        }

        $labels = [
            'Sosial' => 'Seksi Sosial',
            'Produksi' => 'Seksi Produksi',
            'Distribusi' => 'Seksi Distribusi',
            'Neraca' => 'Seksi Neraca',
            'IPDS' => 'Seksi IPDS',
            'Subbag Umum' => 'Subbagian Umum',
        ];

        if (isset($labels[$val])) {
            return $labels[$val];
        }

        if (str_starts_with($val, 'Seksi ') || str_starts_with($val, 'Subbag ') || str_starts_with($val, 'Subbagian ')) {
            return $val;
        }

        return 'Seksi '.$val;
    }

    /**
     * Send the password reset notification using custom BPS email template.
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Resolve the public URL for the user's avatar.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
            return Storage::url($this->avatar);
        }

        if (file_exists(public_path('images/default-avatar.svg'))) {
            return asset('images/default-avatar.svg');
        }

        if (file_exists(public_path('images/avatar-user.jpg'))) {
            return asset('images/avatar-user.jpg');
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->name ?? 'User').'&color=ffffff&background=1e4253';
    }
}
