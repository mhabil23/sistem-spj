<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use App\Models\SpjHistory;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // ==============================
        // STATISTIK SPJ
        // ==============================
        $totalSpj = Spj::count();
        $totalNilaiSpj = Spj::sum('nilai');

        // SPJ Selesai (Disetujui PPSPM / Selesai)
        $selesai = Spj::whereIn('status', ['disetujui_ppspm', 'selesai'])->count();
        $totalNilaiSelesai = Spj::whereIn('status', ['disetujui_ppspm', 'selesai'])->sum('nilai');

        // Sedang Berjalan dalam Alur Verifikasi
        $menungguProses = Spj::whereIn('status', [
            'diajukan',
            'disetujui_umum',
            'disetujui_ppk',
            'diproses',
        ])->count();

        // SPJ yang butuh perbaikan / dikembalikan
        $dikembalikan = Spj::where(function ($q) {
            $q->where('status', 'like', 'revisi_%')
                ->orWhere('status', 'dikembalikan');
        })->count();

        // ==============================
        // STATISTIK PENGGUNA
        // ==============================
        $totalPengguna = User::count();
        $penggunaAktif = User::where('status', 'aktif')->count();
        $penggunaPerRole = User::selectRaw('role, count(*) as count')
            ->groupBy('role')
            ->pluck('count', 'role');

        // ==============================
        // SPJ TERBARU
        // ==============================
        $spjTerbaru = Spj::with('user')
            ->latest()
            ->take(6)
            ->get();

        // ==============================
        // AKTIVITAS TERBARU (TIMELINE LOG)
        // ==============================
        if (class_exists(SpjHistory::class) && SpjHistory::count() > 0) {
            $aktivitasTerbaru = SpjHistory::with(['spj.user', 'user'])
                ->latest('id')
                ->take(5)
                ->get();
        } else {
            $aktivitasTerbaru = Spj::with('user')
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();
        }

        // ==============================
        // MONITORING ALUR SPJ (4 TAHAPAN)
        // ==============================
        // 1. Pengajuan oleh Teknis
        $pengajuan = Spj::where('status', 'diajukan')->count();

        // 2. Verifikasi oleh Bagian Umum
        $verifikasiUmum = Spj::where('status', 'disetujui_umum')->count();

        // 3. Persetujuan oleh PPK
        $persetujuanPpk = Spj::where('status', 'disetujui_ppk')->count();

        // 4. Pengesahan Akhir oleh PPSPM (Selesai)
        $pengujianPpspm = Spj::whereIn('status', ['disetujui_ppspm', 'selesai'])->count();

        // Backwards compatibility
        $pemeriksaan = $verifikasiUmum;
        $persetujuan = $persetujuanPpk;
        $pembayaran = $pengujianPpspm;

        // Persentase status
        $persentaseSelesai = $totalSpj > 0 ? round(($selesai / $totalSpj) * 100) : 0;
        $persentaseProses = $totalSpj > 0 ? round(($menungguProses / $totalSpj) * 100) : 0;
        $persentaseRevisi = $totalSpj > 0 ? round(($dikembalikan / $totalSpj) * 100) : 0;

        return view('admin.dashboard', compact(
            'totalSpj',
            'totalNilaiSpj',
            'totalNilaiSelesai',
            'menungguProses',
            'selesai',
            'dikembalikan',
            'totalPengguna',
            'penggunaAktif',
            'penggunaPerRole',
            'spjTerbaru',
            'aktivitasTerbaru',
            'pengajuan',
            'verifikasiUmum',
            'persetujuanPpk',
            'pengujianPpspm',
            'pemeriksaan',
            'persetujuan',
            'pembayaran',
            'persentaseSelesai',
            'persentaseProses',
            'persentaseRevisi'
        ));
    }
}
