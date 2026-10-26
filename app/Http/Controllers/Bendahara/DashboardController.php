<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Spj;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAntrean = Spj::whereIn('status', ['disetujui_ppspm', 'menunggu_otp_kpa'])->count();
        $nilaiAntrean = Spj::whereIn('status', ['disetujui_ppspm', 'menunggu_otp_kpa'])->sum('nilai');

        $totalMenungguOtp = Spj::where('status', 'menunggu_otp_kpa')->count();
        $nilaiMenungguOtp = Spj::where('status', 'menunggu_otp_kpa')->sum('nilai');

        $siapDicairkanCount = Spj::where('status', 'disetujui_ppspm')->count();
        $nilaiSiapDicairkan = Spj::where('status', 'disetujui_ppspm')->sum('nilai');

        $totalSelesai = Spj::where('status', 'selesai')->count();
        $nilaiSelesai = Spj::where('status', 'selesai')->sum('nilai');

        $totalRevisi = Spj::where('status', 'revisi_bendahara')->count();

        $antreanSpjs = Spj::with('user')
            ->whereIn('status', ['disetujui_ppspm', 'menunggu_otp_kpa'])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('bendahara.dashboard', compact(
            'totalAntrean',
            'nilaiAntrean',
            'totalMenungguOtp',
            'nilaiMenungguOtp',
            'siapDicairkanCount',
            'nilaiSiapDicairkan',
            'totalSelesai',
            'nilaiSelesai',
            'totalRevisi',
            'antreanSpjs'
        ));
    }
}
