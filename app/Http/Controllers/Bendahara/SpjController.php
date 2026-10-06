<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use Illuminate\Http\Request;

class SpjController extends Controller
{
    public function index(Request $request)
    {
        $query = Spj::with('user')->where('status', 'disetujui_ppspm');

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

        return view('bendahara.spj.index', compact('spjs'));
    }

    public function show($id)
    {
        $spj = Spj::with('user')->findOrFail($id);
        
        return view('bendahara.spj.show', compact('spj'));
    }

    public function verify(Request $request, $id)
    {
        $spj = Spj::findOrFail($id);

        if ($spj->status !== 'disetujui_ppspm') {
            return redirect()->route('bendahara.spj.index')->with('error', 'SPJ ini tidak dalam antrean Anda.');
        }

        $request->validate([
            'action' => 'required|in:setujui,tolak',
            'catatan_revisi' => 'required_if:action,tolak|nullable|string',
            'bukti_transfer' => 'required_if:action,setujui|nullable|string',
        ]);

        if ($request->action === 'setujui') {
            $spj->update([
                'status' => 'selesai',
                'catatan_revisi' => null,
                'bukti_transfer' => $request->bukti_transfer,
                'diselesaikan_at' => now(),
            ]);
            $msg = 'SPJ berhasil dicairkan dan dinyatakan selesai.';
        } else {
            $spj->update([
                'status' => 'revisi_bendahara',
                'catatan_revisi' => $request->catatan_revisi
            ]);
            $msg = 'SPJ dikembalikan ke Teknis untuk direvisi.';
        }

        return redirect()->route('bendahara.spj.index')->with('success', $msg);
    }

    public function bulkVerify(Request $request)
    {
        $request->validate([
            'spj_ids' => 'required|array',
            'spj_ids.*' => 'exists:spjs,id'
        ]);

        $spjs = Spj::whereIn('id', $request->spj_ids)
            ->where('status', 'disetujui_ppspm')
            ->get();

        foreach ($spjs as $spj) {
            $spj->update([
                'status' => 'selesai',
                'bukti_transfer' => 'Dicairkan secara massal',
                'diselesaikan_at' => now(),
            ]);
        }

        return redirect()->route('bendahara.spj.index')->with('success', count($spjs) . ' SPJ berhasil disetujui secara massal.');
    }
}


