<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AutoLogoutIdleUser
{
    /**
     * Waktu maksimal tidak ada aktivitas sebelum otomatis logout (1 jam = 3600 detik).
     */
    public const MAX_IDLE_SECONDS = 3600;

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $lastActivity = $request->session()->get('last_active_at');

            // Cek apakah tidak ada aktivitas selama lebih dari batas maksimal (1 jam)
            if ($lastActivity && (time() - $lastActivity > self::MAX_IDLE_SECONDS)) {
                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('warning', 'Sesi Anda telah berakhir');
            }

            // Perbarui waktu aktivitas terakhir
            $request->session()->put('last_active_at', time());
        }

        return $next($request);
    }
}
