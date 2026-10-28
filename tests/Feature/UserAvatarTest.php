<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserAvatarTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_without_avatar_receives_default_whatsapp_base_profile(): void
    {
        $user = User::factory()->create(['avatar' => null]);

        $this->assertStringContainsString('images/default-avatar.svg', $user->avatar_url);
        $this->assertFileExists(public_path('images/default-avatar.svg'));
    }

    public function test_user_with_custom_avatar_receives_stored_avatar_url(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('custom_foto.jpg');
        $path = $file->store('avatars', 'public');

        $user = User::factory()->create(['avatar' => $path]);

        $this->assertSame(Storage::disk('public')->url($path), $user->avatar_url);
    }
}
