<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $baseQuery = Spj::with('user')
            ->whereIn('status', ['revisi_bendahara', 'disetujui_ppspm', 'menunggu_otp_kpa', 'selesai']);

        $totalRiwayat = (clone $baseQuery)->count();
        $totalDanaCair = (clone $baseQuery)->where('status', 'selesai')->sum('nilai');
        $totalSelesai = (clone $baseQuery)->where('status', 'selesai')->count();
        $totalRevisi = (clone $baseQuery)->where('status', 'revisi_bendahara')->count();

        $query = clone $baseQuery;

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kegiatan', 'like', "%{$search}%")
                    ->orWhere('nomor_spj', 'like', "%{$search}%")
                    ->orWhere('nomor_spm', 'like', "%{$search}%")
                    ->orWhere('bukti_transfer', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($qu) use ($search) {
                        $qu->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        $riwayatSpjs = $query->orderBy('updated_at', 'desc')->get();

        return view('bendahara.riwayat.index', compact(
            'riwayatSpjs',
            'totalRiwayat',
            'totalDanaCair',
            'totalSelesai',
            'totalRevisi'
        ));
    }

    public function destroy($id)
    {
        $spj = Spj::findOrFail($id);

        if (in_array($spj->status, ['disetujui_ppspm', 'menunggu_otp_kpa'])) {
            return back()->with('error', 'SPJ yang masih dalam proses tidak dapat dihapus.');
        }

        $spj->delete();

        return back()->with('success', 'Riwayat SPJ berhasil dihapus selamanya.');
    }

    public function exportCsv()
    {
        $spjs = Spj::with('user')->whereIn('status', ['disetujui_ppspm', 'selesai', 'revisi_bendahara'])->orderBy('updated_at', 'desc')->get();

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=riwayat_spj_ppspm.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($spjs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal Update', 'Kegiatan', 'Pengaju', 'Nilai (Rp)', 'Status', 'Nomor SPM', 'Catatan/Disposisi']);

            foreach ($spjs as $spj) {
                $statusText = $spj->status == 'revisi_bendahara' ? 'Dikembalikan' : 'Disetujui';
                fputcsv($file, [
                    $spj->updated_at->format('Y-m-d H:i'),
                    $spj->kegiatan,
                    $spj->user->name ?? 'Teknis',
                    $spj->nilai,
                    $statusText,
                    $spj->nomor_spm ?? '-',
                    $spj->bukti_transfer ?? $spj->catatan_revisi,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $spjs = Spj::with('user')->whereIn('status', ['disetujui_ppspm', 'selesai', 'revisi_bendahara'])->orderBy('updated_at', 'desc')->get();
        $pdf = Pdf::loadview('bendahara.riwayat.pdf', compact('spjs'));

        return $pdf->download('riwayat_spj_ppspm.pdf');
    }
}
