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
        $baseQuery = Spj::with('user')->whereIn('status', ['disetujui_ppspm', 'menunggu_otp_kpa', 'proses_ls']);

        $totalAntrean = (clone $baseQuery)->count();
        $totalNilai = (clone $baseQuery)->sum('nilai');
        $siapDicairkanCount = (clone $baseQuery)->where('status', 'disetujui_ppspm')->count();
        $menungguOtpCount = (clone $baseQuery)->where('status', 'menunggu_otp_kpa')->count();
        $prosesLsCount = (clone $baseQuery)->where('status', 'proses_ls')->count();

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

        return view('bendahara.spj.index', compact('spjs', 'totalAntrean', 'totalNilai', 'siapDicairkanCount', 'menungguOtpCount', 'prosesLsCount'));
    }

    public function show($id)
    {
        $spj = Spj::with('user')->findOrFail($id);

        $grouped = $spj->grouped_dokumen;
        $berkasTeknis = $grouped['teknis'] ?? [];
        $berkasBendahara = $grouped['bendahara'] ?? [];

        return view('bendahara.spj.show', compact('spj', 'berkasTeknis', 'berkasBendahara'));
    }

    public function updateChecklist(Request $request, $id)
    {
        return app(BerkasController::class)->update($request, $id);
    }

    public function verify(Request $request, $id)
    {
        $spj = Spj::with('user')->findOrFail($id);

        if (! in_array($spj->status, ['disetujui_ppspm', 'menunggu_otp_kpa', 'proses_ls'])) {
            return redirect()->route('bendahara.spj.index')->with('error', 'SPJ ini tidak dalam antrean proses Bendahara.');
        }

        $request->validate([
            'action' => 'required|in:otp_kpa,bayar_ls,setujui,tolak',
            'catatan_revisi' => 'required_if:action,tolak|nullable|string',
            'bukti_transfer' => 'nullable|string',
            'nomor_sp2d_ls' => 'nullable|string',
            'keterangan_otp' => 'nullable|string',
            'catatan_internal' => 'nullable|string',
        ]);

        // Simpan checklist berkas jika dikirim bersamaan
        if ($request->has('berkas_bendahara') || $request->has('berkas_virtual')) {
            app(BerkasController::class)->updateChecklistData($spj, $request);
        }

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
        } elseif ($request->action === 'bayar_ls') {
            $buktiRef = $request->nomor_sp2d_ls ?: $request->bukti_transfer;
            $spj->update([
                'status' => 'proses_ls',
                'bukti_transfer' => $buktiRef ?: $spj->bukti_transfer,
            ]);

            $isHonor = $spj->isHonor();
            $aksiHistory = $isHonor
                ? 'Menunggu Kliring Bank LS (Maks. 3 Hari Kerja)'
                : 'Menunggu Transfer Bank Langsung (LS)';
            $keteranganHistory = $request->catatan_internal ?: ($isHonor
                ? 'Pembayaran honorarium diproses via LS Bank. Menunggu proses transfer & kliring antar-bank (maksimal 3 hari kerja).'
                : 'Pembayaran diproses via mekanisme LS. Menunggu penerbitan SP2D dan transfer langsung dari bank ke rekening rekanan/pihak ketiga.');

            if (class_exists(SpjHistory::class)) {
                SpjHistory::create([
                    'spj_id' => $spj->id,
                    'user_id' => Auth::id(),
                    'aksi' => $aksiHistory,
                    'status_lama' => $statusLama,
                    'status_baru' => 'proses_ls',
                    'keterangan' => $keteranganHistory,
                ]);
            }

            WhatsAppService::notifyApproved($spj, 'bayar_ls');
            $msg = 'Status SPJ berhasil diperbarui: '.$aksiHistory.'.';
            $triggerWa = 'bayar_ls';
        } elseif ($request->action === 'setujui') {
            $buktiFinal = $request->bukti_transfer ?: ($request->nomor_sp2d_ls ?: ($spj->bukti_transfer ?: 'Diproses via Perbankan'));

            $spj->update([
                'status' => 'selesai',
                'catatan_revisi' => null,
                'bukti_transfer' => $buktiFinal,
                'diselesaikan_at' => now(),
            ]);

            if (class_exists(SpjHistory::class)) {
                SpjHistory::create([
                    'spj_id' => $spj->id,
                    'user_id' => Auth::id(),
                    'aksi' => 'Proses Pembayaran Selesai',
                    'status_lama' => $statusLama,
                    'status_baru' => 'selesai',
                    'keterangan' => 'Proses pembayaran telah selesai dan dana telah dicairkan.',
                ]);
            }

            WhatsAppService::notifyApproved($spj, 'Selesai');
            $msg = 'Proses pembayaran telah selesai, silahkan cek rekening masing-masing.';
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
            'bulk_action' => 'nullable|in:setujui,otp_kpa,bayar_ls',
            'action' => 'nullable|in:setujui,otp_kpa,bayar_ls',
        ]);

        $spjs = Spj::whereIn('id', $request->spj_ids)
            ->whereIn('status', ['disetujui_ppspm', 'menunggu_otp_kpa', 'proses_ls'])
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
            } elseif ($action === 'bayar_ls') {
                $spj->update([
                    'status' => 'proses_ls',
                ]);
                $isHonor = $spj->isHonor();
                $aksiHistory = $isHonor
                    ? 'Menunggu Kliring Bank LS (Maks. 3 Hari Kerja)'
                    : 'Menunggu Transfer Bank Langsung (LS)';
                if (class_exists(SpjHistory::class)) {
                    SpjHistory::create([
                        'spj_id' => $spj->id,
                        'user_id' => Auth::id(),
                        'aksi' => $aksiHistory,
                        'status_lama' => $statusLama,
                        'status_baru' => 'proses_ls',
                        'keterangan' => 'Pembayaran diajukan via mekanisme LS Bank secara massal.',
                    ]);
                }
            } else {
                $spj->update([
                    'status' => 'selesai',
                    'bukti_transfer' => 'Dicairkan secara massal via Perbankan',
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

        $labelMsg = 'diselesaikan pembayarannya';
        if ($action === 'otp_kpa') {
            $labelMsg = 'diset ke Menunggu OTP QLOLA KPA';
        } elseif ($action === 'bayar_ls') {
            $labelMsg = 'diajukan ke Pembayaran LS Bank';
        }

        return redirect()->route('bendahara.spj.index')->with('success', count($spjs).' SPJ berhasil '.$labelMsg.'.');
    }
}
