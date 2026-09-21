<?php

namespace App\Http\Controllers\Teknis;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index()
    {
        // Ambil data SPJ yang statusnya sudah 'selesai' atau 'dikembalikan'
        $riwayatSpjs = Spj::where('user_id', Auth::id())
            ->whereIn('status', ['selesai', 'revisi_umum', 'revisi_ppk', 'revisi_bendahara'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('teknis.riwayat.index', compact('riwayatSpjs'));
    }
}
