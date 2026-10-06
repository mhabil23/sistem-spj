<?php

namespace App\Http\Controllers\Umum;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use Illuminate\Http\Request;

class SpjController extends Controller
{
    public function index(Request $request)
    {
        $query = Spj::with('user')->whereIn('status', ['diajukan', 'disetujui_umum', 'disetujui_ppk']);

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

        if (!in_array($spj->status, ['diajukan', 'disetujui_umum', 'disetujui_ppk'])) {
            return redirect()->route('umum.spj.index')->with('error', 'SPJ ini sudah tidak berada di antrean pemeriksaan Anda.');
        }

        if ($spj->status === 'diajukan') {
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
                $msg = 'SPJ diverifikasi Umum. Harap bawa dokumen fisik ke PPK untuk ditandatangani.';
            } else {
                $spj->update([
                    'status' => 'revisi_umum',
                    'catatan_revisi' => $request->catatan_revisi
                ]);
                $msg = 'SPJ dikembalikan ke Teknis untuk direvisi.';
            }
        } elseif ($spj->status === 'disetujui_umum') {
            $request->validate([
                'action' => 'required|in:setujui_ppk,tolak_ppk',
                'catatan_revisi' => 'required_if:action,tolak_ppk|nullable|string'
            ]);

            if ($request->action === 'setujui_ppk') {
                $spj->update([
                    'status' => 'disetujui_ppk',
                    'catatan_revisi' => null,
                    'disetujui_ppk_at' => now(),
                ]);
                $msg = 'SPJ telah ditandatangani PPK. Harap bawa dokumen fisik ke PPSPM untuk persetujuan selanjutnya.';
            } else {
                $spj->update([
                    'status' => 'revisi_ppk',
                    'catatan_revisi' => $request->catatan_revisi
                ]);
                $msg = 'SPJ dikembalikan ke Teknis karena revisi dari PPK.';
            }
        } elseif ($spj->status === 'disetujui_ppk') {
            $request->validate([
                'action' => 'required|in:setujui_ppspm,tolak_ppspm',
                'catatan_revisi' => 'required_if:action,tolak_ppspm|nullable|string'
            ]);

            if ($request->action === 'setujui_ppspm') {
                $spj->update([
                    'status' => 'disetujui_ppspm',
                    'catatan_revisi' => null,
                    // We don't have disetujui_ppspm_at column in migration, just update status
                ]);
                $msg = 'SPJ telah disetujui PPSPM dan diteruskan ke Bendahara.';
            } else {
                $spj->update([
                    'status' => 'revisi_ppspm',
                    'catatan_revisi' => $request->catatan_revisi
                ]);
                $msg = 'SPJ dikembalikan ke Teknis karena revisi dari PPSPM.';
            }
        }

        return redirect()->route('umum.spj.index')->with('success', $msg);
    }

    public function print($id)
    {
        $spj = Spj::with('user')->findOrFail($id);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('umum.spj.pdf-checklist', compact('spj'));
        
        return $pdf->stream('Checklist-Verifikasi-'.$spj->nomor_spj.'.pdf');
    }

    public function destroy($id)
    {
        $spj = Spj::findOrFail($id);
        $spj->delete();

        return redirect()->route('umum.spj.index')->with('success', 'SPJ berhasil dihapus.');
    }
}
