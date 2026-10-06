<?php

namespace App\Http\Controllers\Ppspm;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $query = Spj::with('user')
            ->whereIn('status', ['revisi_ppspm', 'disetujui_ppspm', 'revisi_bendahara', 'selesai']);

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

        return view('ppspm.riwayat.index', compact('riwayatSpjs'));
    }

    public function destroy($id)
    {
        $spj = Spj::findOrFail($id);
        
        if ($spj->status === 'disetujui_ppspm') {
            return back()->with('error', 'SPJ yang masih dalam antrean tidak dapat dihapus.');
        }
        
        $spj->delete();
        
        return back()->with('success', 'Riwayat SPJ berhasil dihapus selamanya.');
    }

    public function exportCsv()
    {
        $spjs = Spj::with('user')->whereIn('status', ['disetujui_ppspm', 'selesai', 'revisi_ppspm'])->orderBy('updated_at', 'desc')->get();
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=riwayat_spj_ppspm.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];
        
        $callback = function() use($spjs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal Update', 'Nomor SPJ', 'Pengaju', 'Kegiatan', 'Nilai (Rp)', 'Status', 'Nomor SPM', 'Catatan/Disposisi']);
            
            foreach ($spjs as $spj) {
                $statusText = $spj->status == 'revisi_ppspm' ? 'Dikembalikan' : 'Disetujui';
                fputcsv($file, [
                    $spj->updated_at->format('Y-m-d H:i'),
                    $spj->nomor_spj,
                    $spj->user->name ?? 'Teknis',
                    $spj->kegiatan,
                    $spj->nilai,
                    $statusText,
                    $spj->nomor_spm ?? '-',
                    $spj->catatan_ppspm ?? $spj->catatan_revisi
                ]);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $spjs = Spj::with('user')->whereIn('status', ['disetujui_ppspm', 'selesai', 'revisi_ppspm'])->orderBy('updated_at', 'desc')->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('ppspm.riwayat.pdf', compact('spjs'));
        return $pdf->download('riwayat_spj_ppspm.pdf');
    }
}
