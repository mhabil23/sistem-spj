<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use App\Models\SpjHistory;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpjController extends Controller
{
    public function index(Request $request)
    {
        $baseQuery = Spj::with('user')->whereIn('status', ['disetujui_ppspm', 'menunggu_otp_kpa']);

        $totalAntrean = (clone $baseQuery)->count();
        $totalNilai = (clone $baseQuery)->sum('nilai');
        $siapDicairkanCount = (clone $baseQuery)->where('status', 'disetujui_ppspm')->count();
        $menungguOtpCount = (clone $baseQuery)->where('status', 'menunggu_otp_kpa')->count();

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
                    ->orWhereHas('user', function ($qu) use ($search) {
                        $qu->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        $spjs = $query->orderBy('created_at', 'desc')->get();

        return view('bendahara.spj.index', compact('spjs', 'totalAntrean', 'totalNilai', 'siapDicairkanCount', 'menungguOtpCount'));
    }

    public function show($id)
    {
        $spj = Spj::with('user')->findOrFail($id);

        return view('bendahara.spj.show', compact('spj'));
    }

    public function verify(Request $request, $id)
    {
        $spj = Spj::with('user')->findOrFail($id);

        if (! in_array($spj->status, ['disetujui_ppspm', 'menunggu_otp_kpa'])) {
            return redirect()->route('bendahara.spj.index')->with('error', 'SPJ ini tidak dalam antrean proses Bendahara.');
        }

        $request->validate([
            'action' => 'required|in:otp_kpa,setujui,tolak',
            'catatan_revisi' => 'required_if:action,tolak|nullable|string',
            'bukti_transfer' => 'nullable|string',
            'keterangan_otp' => 'nullable|string',
        ]);

        $statusLama = $spj->status;

        if ($request->action === 'otp_kpa') {
            $spj->update([
                'status' => 'menunggu_otp_kpa',
            ]);

            if (class_exists(SpjHistory::class)) {
                SpjHistory::create([
                    'spj_id' => $spj->id,
                    'user_id' => Auth::id(),
                    'aksi' => 'Menunggu OTP QLOLA KPA',
                    'status_lama' => $statusLama,
                    'status_baru' => 'menunggu_otp_kpa',
                    'keterangan' => $request->keterangan_otp ?: 'Transaksi telah diinput ke sistem BRI QLOLA oleh Bendahara dan sedang menunggu otorisasi OTP dari KPA.',
                ]);
            }

            WhatsAppService::notifyApproved($spj, 'menunggu_otp_kpa');
            $msg = 'Status SPJ berhasil diperbarui: Menunggu OTP QLOLA KPA.';
            $triggerWa = 'otp_kpa';
        } elseif ($request->action === 'setujui') {
            $spj->update([
                'status' => 'selesai',
                'catatan_revisi' => null,
                'bukti_transfer' => $request->bukti_transfer ?: 'Diproses via BRI QLOLA',
                'diselesaikan_at' => now(),
            ]);

            if (class_exists(SpjHistory::class)) {
                SpjHistory::create([
                    'spj_id' => $spj->id,
                    'user_id' => Auth::id(),
                    'aksi' => 'Proses Pembayaran Selesai',
                    'status_lama' => $statusLama,
                    'status_baru' => 'selesai',
                    'keterangan' => 'Proses pembayaran telah selesai, silahkan cek rekening masing-masing.',
                ]);
            }

            WhatsAppService::notifyApproved($spj, 'Selesai');
            $msg = 'Proses pembayaran telah selesai, silahkan cek rekening.';
            $triggerWa = 'cair';
        } else {
            $spj->update([
                'status' => 'revisi_bendahara',
                'catatan_revisi' => $request->catatan_revisi,
            ]);

            if (class_exists(SpjHistory::class)) {
                SpjHistory::create([
                    'spj_id' => $spj->id,
                    'user_id' => Auth::id(),
                    'aksi' => 'Revisi Bendahara',
                    'status_lama' => $statusLama,
                    'status_baru' => 'revisi_bendahara',
                    'keterangan' => $request->catatan_revisi,
                ]);
            }

            WhatsAppService::notifyRevised($spj, 'Bendahara', $request->catatan_revisi);
            $msg = 'SPJ dikembalikan ke Teknis untuk direvisi.';
            $triggerWa = 'revisi';
        }

        return redirect()->route('bendahara.spj.show', $spj->id)
            ->with('success', $msg)
            ->with('trigger_wa', $triggerWa);
    }

    public function bulkVerify(Request $request)
    {
        $request->validate([
            'spj_ids' => 'required|array',
            'spj_ids.*' => 'exists:spjs,id',
            'bulk_action' => 'nullable|in:setujui,otp_kpa',
            'action' => 'nullable|in:setujui,otp_kpa',
        ]);

        $spjs = Spj::whereIn('id', $request->spj_ids)
            ->whereIn('status', ['disetujui_ppspm', 'menunggu_otp_kpa'])
            ->get();

        $action = $request->input('bulk_action') ?? $request->input('action', 'setujui');

        foreach ($spjs as $spj) {
            $statusLama = $spj->status;
            if ($action === 'otp_kpa') {
                $spj->update([
                    'status' => 'menunggu_otp_kpa',
                ]);
                if (class_exists(SpjHistory::class)) {
                    SpjHistory::create([
                        'spj_id' => $spj->id,
                        'user_id' => Auth::id(),
                        'aksi' => 'Menunggu OTP QLOLA KPA',
                        'status_lama' => $statusLama,
                        'status_baru' => 'menunggu_otp_kpa',
                        'keterangan' => 'Transaksi diinput massal ke sistem BRI QLOLA. Menunggu OTP KPA.',
                    ]);
                }
            } else {
                $spj->update([
                    'status' => 'selesai',
                    'bukti_transfer' => 'Dicairkan secara massal via BRI QLOLA',
                    'diselesaikan_at' => now(),
                ]);
                if (class_exists(SpjHistory::class)) {
                    SpjHistory::create([
                        'spj_id' => $spj->id,
                        'user_id' => Auth::id(),
                        'aksi' => 'Proses Pembayaran Selesai',
                        'status_lama' => $statusLama,
                        'status_baru' => 'selesai',
                        'keterangan' => 'Proses pembayaran telah selesai secara massal, silakan cek rekening.',
                    ]);
                }
            }
        }

        $labelMsg = $action === 'otp_kpa' ? 'diset ke Menunggu OTP QLOLA KPA' : 'diselesaikan pembayarannya';

        return redirect()->route('bendahara.spj.index')->with('success', count($spjs).' SPJ berhasil '.$labelMsg.'.');
    }
}
