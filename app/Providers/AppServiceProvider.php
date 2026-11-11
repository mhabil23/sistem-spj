<?php

namespace App\Providers;

use App\Models\Spj;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.admin');

        // Atur locale dan timezone Carbon ke bahasa Indonesia / WITA
        Carbon::setLocale(config('app.locale', 'id'));
        date_default_timezone_set(config('app.timezone', 'Asia/Makassar'));

        // View Composer untuk Badge Notifikasi Sidebar Subbag Umum
        View::composer('components.umum.sidebar', function ($view) {
            if (Auth::check()) {
                // Menghitung SPJ yang diajukan (antrean verifikasi berkas masuk oleh Subbag Umum)
                $badgeKotakMasukUmum = Spj::where('status', 'diajukan')->count();

                // SPJ mandiri Subbag Umum yang perlu revisi / perbaikan
                $badgeRevisiSayaUmum = Spj::where('user_id', Auth::id())
                    ->where(function ($q) {
                        $q->where('status', 'dikembalikan')
                            ->orWhere('status', 'like', 'revisi_%');
                    })->count();

                // SPJ mandiri Subbag Umum yang sedang dalam antrean pengajuan
                $badgeDiajukanSayaUmum = Spj::where('user_id', Auth::id())
                    ->where('status', 'diajukan')
                    ->count();

                $view->with([
                    'badgeKotakMasukUmum' => $badgeKotakMasukUmum,
                    'badgeRevisiSayaUmum' => $badgeRevisiSayaUmum,
                    'badgeDiajukanSayaUmum' => $badgeDiajukanSayaUmum,
                ]);
            }
        });

        // View Composer untuk Badge Notifikasi Sidebar Staf Teknis
        View::composer('components.teknis.sidebar', function ($view) {
            if (Auth::check()) {
                $userId = Auth::id();

                // SPJ Staf Teknis yang perlu revisi / perbaikan berkas (urgent)
                $badgeRevisiTeknis = Spj::where('user_id', $userId)
                    ->where(function ($q) {
                        $q->where('status', 'dikembalikan')
                            ->orWhere('status', 'like', 'revisi_%');
                    })->count();

                // SPJ Staf Teknis yang sedang berstatus 'diajukan'
                $badgeDiajukanTeknis = Spj::where('user_id', $userId)
                    ->where('status', 'diajukan')
                    ->count();

                $view->with([
                    'badgeRevisiTeknis' => $badgeRevisiTeknis,
                    'badgeDiajukanTeknis' => $badgeDiajukanTeknis,
                ]);
            }
        });
    }
}
