<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $role = Auth::user()->role;
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

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email atau kata sandi yang Anda masukkan tidak sesuai.',
                'errors' => [
                    'email' => ['Email atau kata sandi yang Anda masukkan tidak sesuai.'],
                ],
            ], 422);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
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
