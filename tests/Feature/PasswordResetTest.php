<?php

namespace Tests\Feature;

use App\Mail\TestSmtpMail;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_contains_forgot_password_link(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee(route('password.request'));
        $response->assertSee('Lupa kata sandi?');
    }

    public function test_forgot_password_page_can_be_rendered(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
        $response->assertSee('Reset Kata Sandi');
        $response->assertSee('name="email"', false);
        $response->assertSee('Kirim Link Reset Kata Sandi');
        $response->assertSee(route('login'));
    }

    public function test_reset_password_link_can_be_requested_with_valid_email(): void
    {
        Notification::fake();

        $admin = User::factory()->create([
            'email' => 'admin@bpskolakautara.go.id',
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $response = $this->post(route('password.email'), [
            'email' => 'admin@bpskolakautara.go.id',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        Notification::assertSentTo(
            $admin,
            ResetPasswordNotification::class,
            function ($notification) use ($admin) {
                $mailData = $notification->toMail($admin);
                $this->assertStringContainsString('Reset Kata Sandi Akun', $mailData->subject);

                return true;
            }
        );
    }

    public function test_reset_password_link_cannot_be_requested_with_unregistered_email(): void
    {
        Notification::fake();

        $response = $this->post(route('password.email'), [
            'email' => 'tidakada@example.com',
        ]);

        $response->assertSessionHasErrors('email');
        Notification::assertNothingSent();
    }

    public function test_reset_password_screen_can_be_rendered_with_token(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        $token = Password::broker()->createToken($admin);

        $response = $this->get(route('password.reset', [
            'token' => $token,
            'email' => 'admin@example.com',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Perbarui Kata Sandi');
        $response->assertSee('value="'.$token.'"', false);
        $response->assertSee('value="admin@example.com"', false);
        $response->assertSee('name="password"', false);
        $response->assertSee('name="password_confirmation"', false);
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('old-password-123'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $token = Password::broker()->createToken($admin);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'admin@example.com',
            'password' => 'new-secure-password-456',
            'password_confirmation' => 'new-secure-password-456',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');

        $this->assertTrue(Hash::check('new-secure-password-456', $admin->fresh()->password));

        // Test login with new password
        $loginResponse = $this->post(route('login'), [
            'email' => 'admin@example.com',
            'password' => 'new-secure-password-456',
        ]);

        $loginResponse->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_password_cannot_be_reset_with_invalid_token(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('original-password'),
        ]);

        $response = $this->post(route('password.update'), [
            'token' => 'invalid-token-123',
            'email' => 'admin@example.com',
            'password' => 'new-password-789',
            'password_confirmation' => 'new-password-789',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertTrue(Hash::check('original-password', $admin->fresh()->password));
    }

    public function test_mail_test_command_runs_successfully(): void
    {
        Mail::fake();

        User::factory()->create([
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        $this->artisan('mail:test admin@example.com')
            ->expectsOutputToContain('BERHASIL!')
            ->assertExitCode(0);

        Mail::assertSent(TestSmtpMail::class, function ($mail) {
            return $mail->hasTo('admin@example.com');
        });
    }
}
