<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $loginInput = trim((string) ($request->input('login') ?? $request->input('email') ?? ''));
        $password = (string) $request->input('password');

        if (empty($loginInput) || empty($password)) {
            $msg = 'Username / email dan kata sandi wajib diisi.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $msg,
                    'errors' => ['email' => [$msg], 'login' => [$msg]],
                ], 422);
            }

            return back()->withErrors(['email' => $msg, 'login' => $msg])->onlyInput('login', 'email');
        }

        $isEmail = (bool) filter_var($loginInput, FILTER_VALIDATE_EMAIL);

        if ($isEmail) {
            $user = User::where('email', $loginInput)->first();
        } else {
            // Pencarian username (case-insensitive)
            $user = User::where('username', $loginInput)
                ->orWhereRaw('LOWER(username) = ?', [strtolower($loginInput)])
                ->first();
        }

        // Semua user kecuali admin bisa login menggunakan username.
        // Jika akun adalah admin tetapi mencoba login dengan username (bukan email), tolak dengan pesan khusus.
        if ($user && $user->role === 'admin' && ! $isEmail) {
            $msg = 'Akun Administrator wajib masuk menggunakan alamat email resmi.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $msg,
                    'errors' => ['email' => [$msg], 'login' => [$msg]],
                ], 422);
            }

            return back()->withErrors(['email' => $msg, 'login' => $msg])->onlyInput('login', 'email');
        }

        // Verifikasi password dan login
        if ($user && Hash::check($password, $user->password)) {
            if ($user->status !== 'aktif') {
                $msg = 'Akun Anda sedang dinonaktifkan. Silakan hubungi Administrator.';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $msg,
                        'errors' => ['email' => [$msg], 'login' => [$msg]],
                    ], 422);
                }

                return back()->withErrors(['email' => $msg, 'login' => $msg])->onlyInput('login', 'email');
            }

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            $role = $user->role;
            $redirectUrl = match ($role) {
                'admin' => route('admin.dashboard'),
                'teknis' => route('teknis.dashboard'),
                'umum' => route('umum.dashboard'),
                'ppk' => route('ppk.dashboard'),
                'ppspm' => route('ppspm.dashboard'),
                'bendahara' => route('bendahara.dashboard'),
                default => url('/'),
            };

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login berhasil. Mengalihkan ke dashboard...',
                    'redirect_url' => $redirectUrl,
                ]);
            }

            return redirect()->intended($redirectUrl);
        }

        $errorMsg = 'Username / email atau kata sandi yang Anda masukkan tidak sesuai.';
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'error',
                'message' => $errorMsg,
                'errors' => [
                    'email' => [$errorMsg],
                    'login' => [$errorMsg],
                ],
            ], 422);
        }

        return back()->withErrors([
            'email' => $errorMsg,
            'login' => $errorMsg,
        ])->onlyInput('login', 'email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function logoutIdle(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('warning', 'Sesi Anda telah berakhir');
    }

    public function pingSession(Request $request)
    {
        if (Auth::check()) {
            $request->session()->put('last_active_at', time());

            return response()->json([
                'status' => 'active',
                'time' => time(),
            ]);
        }

        return response()->json([
            'status' => 'unauthenticated',
        ], 401);
    }
}
