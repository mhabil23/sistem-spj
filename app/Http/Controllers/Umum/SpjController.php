<?php

namespace App\Http\Controllers\Umum;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use Illuminate\Http\Request;

class SpjController extends Controller
{
    public function index(Request $request)
    {
        $query = Spj::with('user')->where('status', 'diajukan');

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

        return view('umum.spj.index', compact('spjs'));
    }

    public function show($id)
    {
        $spj = Spj::with('user')->findOrFail($id);
        
        // Umum hanya boleh melihat SPJ yang statusnya tidak draft
        if ($spj->status == 'draft') {
            abort(404);
        }

        return view('umum.spj.show', compact('spj'));
    }

    public function verify(Request $request, $id)
    {
        $spj = Spj::findOrFail($id);

        if ($spj->status !== 'diajukan') {
            return redirect()->route('umum.spj.index')->with('error', 'SPJ ini sudah tidak berada di antrean pemeriksaan Umum.');
        }

        $request->validate([
            'action' => 'required|in:setujui,tolak',
            'catatan_revisi' => 'required_if:action,tolak|nullable|string',
            'catatan_internal' => 'nullable|string'
        ]);

        if ($request->action === 'setujui') {
            $spj->update([
                'status' => 'disetujui_umum',
                'catatan_revisi' => null,
                'catatan_internal' => $request->catatan_internal,
                'disetujui_umum_at' => now(),
            ]);
            $msg = 'SPJ berhasil disetujui dan diteruskan ke PPK.';
        } else {
            $spj->update([
                'status' => 'revisi_umum',
                'catatan_revisi' => $request->catatan_revisi
            ]);
            $msg = 'SPJ dikembalikan ke Teknis untuk direvisi.';
        }

        return redirect()->route('umum.spj.index')->with('success', $msg);
    }

    public function print($id)
    {
        $spj = Spj::with('user')->findOrFail($id);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('umum.spj.pdf-checklist', compact('spj'));
        
        return $pdf->stream('Checklist-Verifikasi-'.$spj->nomor_spj.'.pdf');
    }
}
