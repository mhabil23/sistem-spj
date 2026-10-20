<?php

namespace App\Http\Controllers\Teknis;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data SPJ yang statusnya sudah 'selesai' atau 'dikembalikan'
        $query = Spj::where('user_id', Auth::id())
            ->where('status', '!=', 'draft');

        if ($request->filled('search')) {
            $query->where('kegiatan', 'like', '%'.$request->search.'%');
        }

        $riwayatSpjs = $query->orderBy('updated_at', 'desc')->get();

        return view('teknis.riwayat.index', compact('riwayatSpjs'));
    }
}
