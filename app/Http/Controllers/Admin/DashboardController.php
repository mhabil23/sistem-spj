<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // ==============================
        // STATISTIK SPJ
        // ==============================

        $totalSpj = Spj::count();

        $menungguProses = Spj::whereIn('status', [
            'diajukan',
            'diproses'
        ])->count();

        $selesai = Spj::where('status', 'selesai')->count();

        $dikembalikan = Spj::where('status', 'dikembalikan')->count();


        // ==============================
        // STATISTIK PENGGUNA
        // ==============================

        $totalPengguna = User::count();

        $penggunaAktif = User::where('status', 'aktif')->count();


        // ==============================
        // SPJ TERBARU
        // ==============================

        $spjTerbaru = Spj::latest()
            ->take(5)
            ->get();


        // ==============================
        // MONITORING ALUR SPJ
        // ==============================

        // Tahap Pengajuan - Teknis
        $pengajuan = Spj::where('status', 'diajukan')->count();

        // Tahap Pemeriksaan - Umum/PPSPM
        $pemeriksaan = Spj::where('status', 'diproses')->count();

        // Tahap Persetujuan - PPK
        $persetujuan = Spj::where('status', 'selesai')->count();

        // Tahap Pembayaran - Bendahara
        $pembayaran = 0;


        // ==============================
        // KIRIM DATA KE DASHBOARD
        // ==============================

        return view('admin.dashboard', compact(
            'totalSpj',
            'menungguProses',
            'selesai',
            'dikembalikan',

            'totalPengguna',
            'penggunaAktif',

            'spjTerbaru',

            'pengajuan',
            'pemeriksaan',
            'persetujuan',
            'pembayaran'
        ));
    }
}
