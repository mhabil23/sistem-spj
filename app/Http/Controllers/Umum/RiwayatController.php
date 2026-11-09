<?php

namespace App\Http\Controllers\Umum;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $baseQuery = Spj::with(['user', 'histories'])
            ->where('status', '!=', 'draft');

        // Statistik ringkasan seluruh berkas riwayat pemeriksaan
        $allSpjs = (clone $baseQuery)->get();
        $stats = [
            'total' => $allSpjs->count(),
            'revisi' => $allSpjs->filter(fn ($s) => str_contains((string) $s->status, 'revisi'))->count(),
            'perbaikan' => $allSpjs->filter(fn ($s) => $s->status === 'diajukan' && $s->was_revisi)->count(),
            'diproses' => $allSpjs->filter(fn ($s) => in_array($s->status, ['disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm', 'menunggu_otp_kpa', 'proses_ls']) || ($s->status === 'diajukan' && ! $s->was_revisi))->count(),
            'selesai' => $allSpjs->filter(fn ($s) => $s->status === 'selesai')->count(),
        ];

        $query = clone $baseQuery;

        // Filter status berkas
        if ($request->filled('status') && $request->status !== 'semua') {
            $status = $request->status;
            if ($status === 'revisi') {
                $query->where('status', 'like', 'revisi_%');
            } elseif ($status === 'perbaikan') {
                $query->where('status', 'diajukan')->where(function ($q) {
                    $q->whereNotNull('catatan_revisi')
                        ->orWhereHas('histories', function ($hq) {
                            $hq->where('status_baru', 'like', 'revisi_%')
                                ->orWhere('status_lama', 'like', 'revisi_%')
                                ->orWhere('status_baru', 'dikembalikan');
                        });
                });
            } elseif ($status === 'diajukan') {
                $query->where('status', 'diajukan');
            } elseif ($status === 'proses') {
                $query->whereIn('status', ['disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm', 'menunggu_otp_kpa', 'proses_ls']);
            } elseif ($status === 'selesai') {
                $query->where('status', 'selesai');
            } else {
                $query->where('status', $status);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kegiatan', 'like', "%{$search}%")
                    ->orWhere('nomor_spj', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($qu) use ($search) {
                        $qu->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        $riwayatSpjs = $query->orderBy('updated_at', 'desc')->get();

        return view('umum.riwayat.index', compact('riwayatSpjs', 'stats'));
    }

    public function destroy($id)
    {
        abort(403, 'Hanya Administrator yang memiliki hak akses untuk menghapus data SPJ.');
    }
}
