<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    /**
     * Tampilkan formulir permintaan reset kata sandi (lupa password).
     */
    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Kirim email berisi tautan reset kata sandi ke alamat email pengguna.
     */
    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
        ]);

        $normalizedEmail = strtolower(trim((string) $request->email));

        $user = User::where('email', $normalizedEmail)->first();
        if (! $user) {
            return back()->withInput()->withErrors([
                'email' => 'Alamat email "'.$normalizedEmail.'" tidak terdaftar dalam sistem.',
            ]);
        }

        try {
            $status = Password::broker()->sendResetLink(['email' => $normalizedEmail]);
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->withErrors([
                'email' => 'Gagal mengirim email reset kata sandi: '.$e->getMessage().'. Pastikan konfigurasi SMTP pada file .env sudah diatur dengan benar.',
            ]);
        }

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Tautan untuk mengatur ulang kata sandi telah berhasil dikirim ke '.$normalizedEmail.'. Silakan periksa kotak masuk (Inbox) atau folder Spam email Anda.');
        }

        return back()->withInput()->withErrors([
            'email' => __($status),
        ]);
    }

    /**
     * Tampilkan formulir pembuatan kata sandi baru.
     */
    public function showResetPassword(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    /**
     * Proses pembaruan kata sandi akun pengguna.
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(6)],
        ], [
            'token.required' => 'Token reset kata sandi tidak valid atau hilang.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $normalizedEmail = strtolower(trim((string) $request->email));

        $status = Password::broker()->reset(
            [
                'email' => $normalizedEmail,
                'password' => $request->password,
                'password_confirmation' => $request->password_confirmation,
                'token' => $request->token,
            ],
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Kata sandi akun Anda berhasil diperbarui! Silakan masuk dengan kata sandi baru Anda.');
        }

        return back()->withInput($request->only('email'))->withErrors([
            'email' => __($status),
        ]);
    }
}
