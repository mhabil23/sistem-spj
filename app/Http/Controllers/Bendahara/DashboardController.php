<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Spj;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAntrean = Spj::whereIn('status', ['disetujui_ppspm', 'menunggu_otp_kpa', 'proses_ls'])->count();
        $nilaiAntrean = Spj::whereIn('status', ['disetujui_ppspm', 'menunggu_otp_kpa', 'proses_ls'])->sum('nilai');

        $totalMenungguOtp = Spj::where('status', 'menunggu_otp_kpa')->count();
        $nilaiMenungguOtp = Spj::where('status', 'menunggu_otp_kpa')->sum('nilai');

        $totalProsesLs = Spj::where('status', 'proses_ls')->count();
        $nilaiProsesLs = Spj::where('status', 'proses_ls')->sum('nilai');

        $siapDicairkanCount = Spj::where('status', 'disetujui_ppspm')->count();
        $nilaiSiapDicairkan = Spj::where('status', 'disetujui_ppspm')->sum('nilai');

        $totalSelesai = Spj::where('status', 'selesai')->count();
        $nilaiSelesai = Spj::where('status', 'selesai')->sum('nilai');

        $totalRevisi = Spj::where('status', 'revisi_bendahara')->count();

        $antreanSpjs = Spj::with('user')
            ->whereIn('status', ['disetujui_ppspm', 'menunggu_otp_kpa', 'proses_ls'])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('bendahara.dashboard', compact(
            'totalAntrean',
            'nilaiAntrean',
            'totalMenungguOtp',
            'nilaiMenungguOtp',
            'totalProsesLs',
            'nilaiProsesLs',
            'siapDicairkanCount',
            'nilaiSiapDicairkan',
            'totalSelesai',
            'nilaiSelesai',
            'totalRevisi',
            'antreanSpjs'
        ));
    }
}
