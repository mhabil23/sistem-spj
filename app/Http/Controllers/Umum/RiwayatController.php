<?php

namespace App\Http\Controllers\Umum;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $query = Spj::with('user')
            ->whereIn('status', ['revisi_umum', 'disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm', 'revisi_ppk', 'revisi_ppspm', 'revisi_bendahara', 'selesai']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kegiatan', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($qu) use ($search) {
                        $qu->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        $riwayatSpjs = $query->orderBy('updated_at', 'desc')->get();

        return view('umum.riwayat.index', compact('riwayatSpjs'));
    }

    public function destroy($id)
    {
        abort(403, 'Hanya Administrator yang memiliki hak akses untuk menghapus data SPJ.');
    }
}
