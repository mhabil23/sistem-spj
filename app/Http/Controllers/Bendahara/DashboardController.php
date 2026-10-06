<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Spj;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAntrean = Spj::where('status', 'disetujui_ppspm')->count();
        $totalSelesai = Spj::whereIn('status', ['selesai'])->count();
        $totalRevisi = Spj::where('status', 'revisi_bendahara')->count();
        
        $nilaiAntrean = Spj::where('status', 'disetujui_ppspm')->sum('nilai');
        $nilaiSelesai = Spj::whereIn('status', ['selesai'])->sum('nilai');

        $antreanSpjs = Spj::with('user')
            ->where('status', 'disetujui_ppspm')
            ->orderBy('updated_at', 'asc')
            ->take(5)
            ->get();

        return view('bendahara.dashboard', compact('totalAntrean', 'totalSelesai', 'totalRevisi', 'nilaiAntrean', 'nilaiSelesai', 'antreanSpjs'));
    }
}


