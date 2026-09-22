<?php

namespace App\Http\Controllers\Ppspm;

use App\Http\Controllers\Controller;
use App\Models\Spj;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAntrean = Spj::where('status', 'disetujui_ppk')->count();
        $totalSelesai = Spj::whereIn('status', ['disetujui_ppspm', 'selesai', 'revisi_bendahara'])->count();
        $totalRevisi = Spj::where('status', 'revisi_ppspm')->count();
        
        $nilaiAntrean = Spj::where('status', 'disetujui_ppk')->sum('nilai');
        $nilaiSelesai = Spj::whereIn('status', ['disetujui_ppspm', 'selesai', 'revisi_bendahara'])->sum('nilai');

        $antreanSpjs = Spj::with('user')
            ->where('status', 'disetujui_ppk')
            ->orderBy('updated_at', 'asc')
            ->take(5)
            ->get();

        return view('ppspm.dashboard', compact('totalAntrean', 'totalSelesai', 'totalRevisi', 'nilaiAntrean', 'nilaiSelesai', 'antreanSpjs'));
    }
}

