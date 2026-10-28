<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

#[Fillable(['name', 'email', 'no_wa', 'avatar', 'password', 'role', 'status'])]
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
