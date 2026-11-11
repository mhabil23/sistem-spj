<?php

namespace App\Http\Controllers\Teknis;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $queryBase = Spj::where('user_id', $userId);

        $totalSpj = (clone $queryBase)->count();
        $diprosesSpj = (clone $queryBase)->whereIn('status', ['diajukan', 'disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm', 'menunggu_otp_kpa', 'proses_ls'])->count();
        $dikembalikanSpj = (clone $queryBase)->whereIn('status', ['revisi_umum', 'revisi_ppk', 'revisi_ppspm', 'revisi_bendahara'])->count();
        $selesaiSpj = (clone $queryBase)->where('status', 'selesai')->count();

        $recentSpjs = (clone $queryBase)->latest()->take(5)->get();

        return view('teknis.dashboard', compact('totalSpj', 'diprosesSpj', 'dikembalikanSpj', 'selesaiSpj', 'recentSpjs'));
    }
}
