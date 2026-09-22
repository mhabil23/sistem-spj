<?php

namespace App\Http\Controllers\Ppk;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use Illuminate\Http\Request;

class SpjController extends Controller
{
    public function index(Request $request)
    {
        $query = Spj::with('user')->where('status', 'disetujui_umum');

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

        $spjs = $query->orderBy('updated_at', 'asc')->get();

        return view('ppk.spj.index', compact('spjs'));
    }

    public function show($id)
    {
        $spj = Spj::with('user')->findOrFail($id);
        
        return view('ppk.spj.show', compact('spj'));
    }

    public function verify(Request $request, $id)
    {
        $spj = Spj::findOrFail($id);

        if ($spj->status !== 'disetujui_umum') {
            return redirect()->route('ppk.spj.index')->with('error', 'SPJ ini tidak dalam antrean Anda.');
        }

        $request->validate([
            'action' => 'required|in:setujui,tolak',
            'catatan_revisi' => 'required_if:action,tolak|nullable|string',
            'catatan_ppk' => 'nullable|string',
            'disposisi' => 'nullable|string'
        ]);

        if ($request->action === 'setujui') {
            $spj->update([
                'status' => 'disetujui_ppk',
                'catatan_revisi' => null,
                'catatan_ppk' => $request->catatan_ppk,
                'disposisi' => $request->disposisi,
                'disetujui_ppk_at' => now(),
            ]);
            $msg = 'SPJ berhasil disetujui dan diteruskan ke PPSPM.';
        } else {
            $spj->update([
                'status' => 'revisi_ppk',
                'catatan_revisi' => $request->catatan_revisi
            ]);
            $msg = 'SPJ dikembalikan ke Teknis untuk direvisi.';
        }

        return redirect()->route('ppk.spj.index')->with('success', $msg);
    }

    public function bulkVerify(Request $request)
    {
        $request->validate([
            'spj_ids' => 'required|array',
            'spj_ids.*' => 'exists:spjs,id'
        ]);

        $spjs = Spj::whereIn('id', $request->spj_ids)
            ->where('status', 'disetujui_umum')
            ->get();

        foreach ($spjs as $spj) {
            $spj->update([
                'status' => 'disetujui_ppk',
                'disposisi' => 'Disetujui secara massal',
                'disetujui_ppk_at' => now(),
            ]);
        }

        return redirect()->route('ppk.spj.index')->with('success', count($spjs) . ' SPJ berhasil disetujui secara massal.');
    }
}
