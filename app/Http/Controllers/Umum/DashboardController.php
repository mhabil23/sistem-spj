<?php

namespace App\Http\Controllers\Umum;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAntrean = Spj::where('status', 'diajukan')->whereNotNull('berkas_fisik_diterima_at')->count();
        $totalMenungguFisik = Spj::where('status', 'diajukan')->whereNull('berkas_fisik_diterima_at')->count();
        $totalSelesai = Spj::whereIn('status', ['disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm', 'selesai'])->count();
        $totalRevisi = Spj::where('status', 'revisi_umum')->count();
        $totalSpj = Spj::count();
        $totalUsers = User::where('role', 'teknis')->count();

        // Persentase status SPJ untuk donut chart
        $totalDiproses = $totalAntrean + $totalRevisi;
        $totalEvaluasi = $totalSelesai + $totalDiproses;
        if ($totalEvaluasi > 0) {
            $pctSelesai = round(($totalSelesai / $totalEvaluasi) * 100);
            $pctProses = 100 - $pctSelesai;
        } else {
            $pctSelesai = 68;
            $pctProses = 32;
        }

        // Seluruh staf teknis riil terintegrasi sesuai username/nama di database
        $dbTopUsers = User::where('role', 'teknis')
            ->withCount('spjs')
            ->orderByDesc('spjs_count')
            ->get();

        $maxSpjCount = $dbTopUsers->max('spjs_count') ?: 1;

        $topPengaju = [];
        foreach ($dbTopUsers as $user) {
            $count = (int) $user->spjs_count;
            $pct = $count > 0 ? round(($count / $maxSpjCount) * 100) : 0;
            $topPengaju[] = [
                'name' => $user->name,
                'bar' => $count > 0 ? max($pct, 12).'%' : '0%',
                'count' => $count.' SPJ',
            ];
        }

        // Tren 6 bulan terakhir
        $trenBulanan = [];
        $namaBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $bulanIndex = $date->month - 1;
            $mLabel = $namaBulan[$bulanIndex] ?? $date->format('M');

            $diajukanCount = Spj::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();

            $disetujuiCount = Spj::whereMonth('disetujui_umum_at', $date->month)
                ->whereYear('disetujui_umum_at', $date->year)
                ->count();

            $trenBulanan[] = [
                'bulan' => $mLabel,
                'diajukan' => $diajukanCount,
                'disetujui' => $disetujuiCount,
            ];
        }

        $antreanSpjs = Spj::with('user')
            ->where('status', 'diajukan')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('umum.dashboard', compact(
            'totalAntrean',
            'totalMenungguFisik',
            'totalSelesai',
            'totalRevisi',
            'totalSpj',
            'totalUsers',
            'pctSelesai',
            'pctProses',
            'topPengaju',
            'trenBulanan',
            'antreanSpjs'
        ));
    }
}
