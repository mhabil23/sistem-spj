<?php

namespace App\Http\Controllers\Umum;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use App\Models\SpjHistory;
use App\Services\WhatsAppService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpjController extends Controller
{
    public function index(Request $request)
    {
        $query = Spj::with('user')->whereIn('status', ['diajukan', 'disetujui_umum', 'disetujui_ppk']);

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

        $spjs = $query->orderBy('created_at', 'desc')->get();

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
        $spj = Spj::with('user')->findOrFail($id);

        if (! in_array($spj->status, ['diajukan', 'disetujui_umum', 'disetujui_ppk'])) {
            return redirect()->route('umum.spj.index')->with('error', 'SPJ ini sudah tidak berada di antrean pemeriksaan Anda.');
        }

        $triggerWa = 'setuju_umum';

        if ($spj->status === 'diajukan') {
            $request->validate([
                'action' => 'required|in:terima_fisik,mulai_periksa,setujui,tolak,batal_fisik',
                'catatan_revisi' => 'required_if:action,tolak|nullable|string',
                'catatan_internal' => 'nullable|string',
            ]);

            if ($request->action === 'terima_fisik') {
                $spj->update([
                    'berkas_fisik_diterima_at' => now(),
                ]);

                return redirect()->route('umum.spj.show', $spj->id)
                    ->with('success', 'Berkas fisik SPJ berhasil dikonfirmasi diterima di meja Subbagian Umum.');
            }

            if ($request->action === 'batal_fisik') {
                $spj->update([
                    'berkas_fisik_diterima_at' => null,
                    'pemeriksaan_dimulai_at' => null,
                ]);

                return redirect()->route('umum.spj.show', $spj->id)
                    ->with('success', 'Status penerimaan fisik berhasil dibatalkan.');
            }

            if ($request->action === 'mulai_periksa') {
                $spj->update([
                    'berkas_fisik_diterima_at' => $spj->berkas_fisik_diterima_at ?: now(),
                    'pemeriksaan_dimulai_at' => now(),
                ]);

                return redirect()->route('umum.spj.show', $spj->id)
                    ->with('success', 'SPJ berhasil diterima ke dalam antrean pemeriksaan aktif.');
            }

            if ($request->action === 'setujui') {
                $spj->update([
                    'status' => 'disetujui_umum',
                    'catatan_revisi' => null,
                    'catatan_internal' => $request->catatan_internal,
                    'disetujui_umum_at' => now(),
                ]);
                SpjHistory::create([
                    'spj_id' => $spj->id,
                    'user_id' => Auth::id() ?? $spj->user_id,
                    'aksi' => 'Verifikasi Berkas Bagian Umum',
                    'status_lama' => 'diajukan',
                    'status_baru' => 'disetujui_umum',
                    'keterangan' => 'Kelengkapan berkas fisik diverifikasi Bagian Umum dan diteruskan ke PPK.',
                ]);
                WhatsAppService::notifyApproved($spj, 'Umum');
                $msg = 'SPJ diverifikasi Umum. Harap bawa dokumen fisik ke PPK untuk ditandatangani.';
                $triggerWa = 'setuju_umum';
            } else {
                $spj->update([
                    'status' => 'revisi_umum',
                    'catatan_revisi' => $request->catatan_revisi,
                    'berkas_fisik_diterima_at' => null,
                    'pemeriksaan_dimulai_at' => null,
                ]);
                SpjHistory::create([
                    'spj_id' => $spj->id,
                    'user_id' => Auth::id() ?? $spj->user_id,
                    'aksi' => 'Pengembalian Berkas (Revisi Bagian Umum)',
                    'status_lama' => 'diajukan',
                    'status_baru' => 'revisi_umum',
                    'keterangan' => 'Catatan revisi: '.($request->catatan_revisi ?: 'Perlu perbaikan kelengkapan berkas fisik.'),
                ]);
                WhatsAppService::notifyRevised($spj, 'Umum', $request->catatan_revisi);
                $msg = 'SPJ dikembalikan ke Teknis untuk direvisi.';
                $triggerWa = 'revisi_umum';
            }
        } elseif ($spj->status === 'disetujui_umum') {
            $request->validate([
                'action' => 'required|in:setujui_ppk,tolak_ppk',
                'catatan_revisi' => 'required_if:action,tolak_ppk|nullable|string',
            ]);

            if ($request->action === 'setujui_ppk') {
                $spj->update([
                    'status' => 'disetujui_ppk',
                    'catatan_revisi' => null,
                    'disetujui_ppk_at' => now(),
                ]);
                SpjHistory::create([
                    'spj_id' => $spj->id,
                    'user_id' => Auth::id() ?? $spj->user_id,
                    'aksi' => 'Persetujuan PPK',
                    'status_lama' => 'disetujui_umum',
                    'status_baru' => 'disetujui_ppk',
                    'keterangan' => 'Dokumen SPJ ditandatangani dan disetujui oleh Pejabat Pembuat Komitmen (PPK).',
                ]);
                WhatsAppService::notifyApproved($spj, 'PPK');
                $msg = 'SPJ telah ditandatangani PPK. Harap bawa dokumen fisik ke PPSPM untuk persetujuan selanjutnya.';
                $triggerWa = 'setuju_ppk';
            } else {
                $spj->update([
                    'status' => 'revisi_ppk',
                    'catatan_revisi' => $request->catatan_revisi,
                ]);
                SpjHistory::create([
                    'spj_id' => $spj->id,
                    'user_id' => Auth::id() ?? $spj->user_id,
                    'aksi' => 'Pengembalian Berkas (Revisi PPK)',
                    'status_lama' => 'disetujui_umum',
                    'status_baru' => 'revisi_ppk',
                    'keterangan' => 'Catatan revisi PPK: '.($request->catatan_revisi ?: 'Perlu perbaikan kelengkapan berkas fisik.'),
                ]);
                WhatsAppService::notifyRevised($spj, 'PPK', $request->catatan_revisi);
                $msg = 'SPJ dikembalikan ke Teknis karena revisi dari PPK.';
                $triggerWa = 'revisi_ppk';
            }
        } elseif ($spj->status === 'disetujui_ppk') {
            $request->validate([
                'action' => 'required|in:setujui_ppspm,tolak_ppspm',
                'catatan_revisi' => 'required_if:action,tolak_ppspm|nullable|string',
            ]);

            if ($request->action === 'setujui_ppspm') {
                $spj->update([
                    'status' => 'disetujui_ppspm',
                    'catatan_revisi' => null,
                    'disetujui_ppspm_at' => now(),
                ]);
                SpjHistory::create([
                    'spj_id' => $spj->id,
                    'user_id' => Auth::id() ?? $spj->user_id,
                    'aksi' => 'Penerbitan SPM oleh PPSPM',
                    'status_lama' => 'disetujui_ppk',
                    'status_baru' => 'disetujui_ppspm',
                    'keterangan' => 'Surat Perintah Membayar (SPM) diterbitkan oleh PPSPM. Seluruh proses verifikasi selesai.',
                ]);
                WhatsAppService::notifyApproved($spj, 'PPSPM');
                $msg = 'SPJ telah disetujui PPSPM dan diteruskan ke Bendahara.';
                $triggerWa = 'setuju_ppspm';
            } else {
                $spj->update([
                    'status' => 'revisi_ppspm',
                    'catatan_revisi' => $request->catatan_revisi,
                ]);
                SpjHistory::create([
                    'spj_id' => $spj->id,
                    'user_id' => Auth::id() ?? $spj->user_id,
                    'aksi' => 'Pengembalian Berkas (Revisi PPSPM)',
                    'status_lama' => 'disetujui_ppk',
                    'status_baru' => 'revisi_ppspm',
                    'keterangan' => 'Catatan revisi PPSPM: '.($request->catatan_revisi ?: 'Perlu perbaikan kelengkapan berkas fisik.'),
                ]);
                WhatsAppService::notifyRevised($spj, 'PPSPM', $request->catatan_revisi);
                $msg = 'SPJ dikembalikan ke Teknis karena revisi dari PPSPM.';
                $triggerWa = 'revisi_ppspm';
            }
        }

        return redirect()->route('umum.spj.show', $spj->id)
            ->with('success', $msg)
            ->with('trigger_wa', $triggerWa);
    }

    public function print($id)
    {
        $spj = Spj::with('user')->findOrFail($id);

        $pdf = Pdf::loadView('umum.spj.pdf-checklist', compact('spj'));

        return $pdf->stream('Checklist-Verifikasi-SPJ-'.$spj->id.'.pdf');
    }

    public function destroy($id)
    {
        $spj = Spj::findOrFail($id);
        $spj->delete();

        return redirect()->route('umum.spj.index')->with('success', 'SPJ berhasil dihapus.');
    }
}
