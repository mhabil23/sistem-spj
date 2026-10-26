<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('admin.profil.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'no_wa' => ['nullable', 'string', 'max:25'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'current_password' => ['nullable', 'string'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar pada akun lain. Silakan gunakan alamat email lain.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            'avatar.max' => 'Ukuran foto profil maksimal 2 MB.',
        ]);

        $newEmail = strtolower(trim($request->email));
        $isEmailChanged = $newEmail !== strtolower(trim($user->email));
        $isPasswordChanged = $request->filled('password');

        // Verifikasi kata sandi saat ini jika mengubah email atau mengubah password
        if ($isEmailChanged || $isPasswordChanged) {
            if (! $request->filled('current_password')) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'current_password' => 'Konfirmasi kata sandi saat ini wajib diisi untuk mengesahkan perubahan '
                            .($isEmailChanged && $isPasswordChanged
                                ? 'alamat email dan kata sandi baru'
                                : ($isEmailChanged ? 'alamat email login' : 'kata sandi baru')).'.',
                    ]);
            }

            if (! Hash::check($request->current_password, $user->password)) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'current_password' => 'Kata sandi saat ini yang Anda masukkan tidak sesuai.',
                    ]);
            }
        }

        $user->name = $request->name;
        $user->email = $newEmail;
        $user->no_wa = $request->no_wa;

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        } elseif ($request->boolean('remove_avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = null;
        }

        if ($isPasswordChanged) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        if ($isEmailChanged && $isPasswordChanged) {
            $message = 'Profil berhasil diperbarui. Alamat email login dan kata sandi baru Anda telah aktif. Gunakan kredensial baru ini untuk login berikutnya.';
        } elseif ($isEmailChanged) {
            $message = 'Profil berhasil diperbarui. Alamat email login Anda telah diubah menjadi '.$user->email.'. Gunakan email ini untuk login berikutnya.';
        } elseif ($isPasswordChanged) {
            $message = 'Profil dan kata sandi baru Anda berhasil diperbarui.';
        } else {
            $message = 'Data profil berhasil diperbarui.';
        }

        return back()->with('success', $message);
    }
}
