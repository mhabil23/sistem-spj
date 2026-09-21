<?php

namespace App\Http\Controllers\Teknis;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id() ?? 1; // Fallback jika tidak ada auth saat ini

        $totalSpj = Spj::where('user_id', $userId)->count();
        $diprosesSpj = Spj::where('user_id', $userId)->whereIn('status', ['diajukan', 'disetujui_umum', 'disetujui_ppk'])->count();
        $dikembalikanSpj = Spj::where('user_id', $userId)->whereIn('status', ['revisi_umum', 'revisi_ppk', 'revisi_bendahara'])->count();
        $selesaiSpj = Spj::where('user_id', $userId)->where('status', 'selesai')->count();

        $recentSpjs = Spj::where('user_id', $userId)->latest()->take(5)->get();

        return view('teknis.dashboard', compact('totalSpj', 'diprosesSpj', 'dikembalikanSpj', 'selesaiSpj', 'recentSpjs'));
    }
}
