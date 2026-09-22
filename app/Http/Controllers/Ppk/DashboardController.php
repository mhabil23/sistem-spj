<?php

namespace App\Http\Controllers\Ppk;

use App\Http\Controllers\Controller;
use App\Models\Spj;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAntrean = Spj::where('status', 'disetujui_umum')->count();
        $totalSelesai = Spj::whereIn('status', ['disetujui_ppk', 'disetujui_ppspm', 'selesai'])->count();
        $totalRevisi = Spj::where('status', 'revisi_ppk')->count();
        
        $nilaiAntrean = Spj::where('status', 'disetujui_umum')->sum('nilai');
        $nilaiSelesai = Spj::whereIn('status', ['disetujui_ppk', 'disetujui_ppspm', 'selesai'])->sum('nilai');

        $antreanSpjs = Spj::with('user')
            ->where('status', 'disetujui_umum')
            ->orderBy('updated_at', 'asc')
            ->take(5)
            ->get();

        return view('ppk.dashboard', compact('totalAntrean', 'totalSelesai', 'totalRevisi', 'nilaiAntrean', 'nilaiSelesai', 'antreanSpjs'));
    }
}
