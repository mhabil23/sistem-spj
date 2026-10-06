<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpjHistory;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $query = SpjHistory::with([
            'spj',
            'user'
        ]);

        // Pencarian
        if ($request->filled('search')) {

            $search = $request->search;

            $query->whereHas('spj', function ($q) use ($search) {

                $q->where('nomor_spj', 'like', "%{$search}%")
                    ->orWhere('kegiatan', 'like', "%{$search}%");
            });
        }

        // Data riwayat
        $riwayat = $query
            ->latest()
            ->get();

        // Statistik
        $totalRiwayat = SpjHistory::count();

        $diajukan = SpjHistory::whereHas('spj', function ($q) {
            $q->where('status', 'diajukan');
        })->count();

        $diproses = SpjHistory::whereHas('spj', function ($q) {
            $q->where('status', 'diproses');
        })->count();

        $selesai = SpjHistory::whereHas('spj', function ($q) {
            $q->where('status', 'selesai');
        })->count();

        $dikembalikan = SpjHistory::whereHas('spj', function ($q) {
            $q->where('status', 'dikembalikan');
        })->count();

        return view('admin.riwayat', compact(
            'riwayat',
            'totalRiwayat',
            'diajukan',
            'diproses',
            'selesai',
            'dikembalikan'
        ));
    }
}
