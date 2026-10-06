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
            $query->where(function($q) use ($search) {
                $q->where('nomor_spj', 'like', "%{$search}%")
                  ->orWhere('kegiatan', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
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
        $spj = Spj::findOrFail($id);
        
        // Pastikan hanya bisa menghapus jika status bukan diajukan (sudah ada di riwayat)
        if ($spj->status === 'diajukan') {
            return back()->with('error', 'SPJ yang masih dalam antrean tidak dapat dihapus.');
        }
        
        $spj->delete();
        
        return back()->with('success', 'Riwayat SPJ berhasil dihapus selamanya.');
    }
}
