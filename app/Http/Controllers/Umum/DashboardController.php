<?php

namespace App\Http\Controllers\Umum;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAntrean = Spj::where('status', 'diajukan')->count();
        $totalSelesai = Spj::whereIn('status', ['disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm', 'selesai'])->count();
        $totalRevisi = Spj::where('status', 'revisi_umum')->count();

        $antreanSpjs = Spj::with('user')
            ->where('status', 'diajukan')
            ->orderBy('updated_at', 'asc') // First in first out
            ->take(5)
            ->get();

        return view('umum.dashboard', compact('totalAntrean', 'totalSelesai', 'totalRevisi', 'antreanSpjs'));
    }
}
