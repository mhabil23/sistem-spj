<?php

namespace App\Http\Controllers\Umum;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use App\Models\SpjHistory;
use App\Models\SyaratDokumenSpj;
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

    /**
     * Tampilkan form pembuatan pengajuan SPJ untuk Subbagian Umum.
     */
    public function create()
    {
        $nomorSpjOtomatis = Spj::generateNomorSpj();
        $spjData = SyaratDokumenSpj::getSpjData('umum');

        return view('umum.spj.create', compact('nomorSpjOtomatis', 'spjData'));
    }

    /**
     * Simpan pengajuan SPJ baru oleh Subbagian Umum.
     */
    public function store(Request $request)
    {
        $this->normalizeNilai($request);

        $request->validate([
            'nomor_spj' => 'nullable|string|max:100|unique:spjs,nomor_spj',
            'kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal',
            'status' => 'required|in:draft,diajukan',
            'nilai' => 'required|numeric|min:0',
            'seksi' => 'nullable|string|max:50',
            'keterangan' => 'nullable|string',
            'no_wa' => 'nullable|string|max:25',
        ], [
            'nomor_spj.unique' => 'Nomor SPJ sudah terdaftar, silakan gunakan nomor lain.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai kegiatan tidak boleh sebelum tanggal mulai kegiatan.',
        ]);

        if ($request->status === 'diajukan' && ! $request->boolean('is_printed')) {
            return back()->withInput()->withErrors([
                'status' => 'SPJ tidak dapat diajukan jika belum menekan Print sampai tercetak. Silakan cetak lembar checklist berkas terlebih dahulu.',
            ]);
        }

        $dokumenKelengkapan = [];
        if ($request->has('nama_dokumen') && is_array($request->input('nama_dokumen'))) {
            foreach ($request->input('nama_dokumen') as $index => $namaDokumen) {
                $assignee = $request->input("assignee_dokumen.{$index}");
                $isBendahara = Spj::isBendaharaDoc(['nama' => $namaDokumen, 'assignee' => $assignee]);
                $isPpk = Spj::isPpkDoc(['nama' => $namaDokumen, 'assignee' => $assignee]);

                if ($isBendahara || $isPpk) {
                    $dokumenKelengkapan[] = [
                        'nama' => $namaDokumen,
                        'disiapkan' => false,
                        'assignee' => $assignee ?: ($isBendahara ? 'Bendahara' : 'PPK'),
                    ];
                } else {
                    $isChecked = $request->has("berkas.{$index}");
                    $dokumenKelengkapan[] = [
                        'nama' => $namaDokumen,
                        'disiapkan' => $isChecked,
                        'assignee' => $assignee ?: 'SM',
                        'checked_by' => $isChecked ? 'umum' : null,
                    ];
                }
            }
        }

        $nomorSpj = $request->filled('nomor_spj') ? $request->nomor_spj : Spj::generateNomorSpj();

        $spj = Spj::create([
            'user_id' => Auth::id(),
            'nomor_spj' => $nomorSpj,
            'kegiatan' => $request->kegiatan,
            'tanggal' => $request->tanggal,
            'tanggal_selesai' => $request->tanggal_selesai ?: null,
            'seksi' => $request->seksi ?: (Auth::user()->seksi ?? 'Subbag Umum'),
            'nilai' => $request->nilai,
            'keterangan' => $request->keterangan,
            'no_wa' => $request->no_wa ?: Auth::user()->no_wa,
            'status' => $request->status,
            'dokumen_file' => count($dokumenKelengkapan) > 0 ? json_encode($dokumenKelengkapan) : null,
            'diajukan_at' => $request->status === 'diajukan' ? now() : null,
        ]);

        SpjHistory::create([
            'spj_id' => $spj->id,
            'user_id' => Auth::id(),
            'aksi' => $request->status === 'diajukan' ? 'Pengajuan SPJ oleh Subbag Umum' : 'Draf SPJ dibuat oleh Subbag Umum',
            'status_lama' => null,
            'status_baru' => $request->status,
            'keterangan' => $request->status === 'diajukan'
                ? 'SPJ dibuat dan diajukan oleh Subbagian Umum ke alur verifikasi.'
                : 'SPJ disimpan sebagai draft oleh Subbagian Umum.',
        ]);

        if ($request->status === 'diajukan') {
            WhatsAppService::notifySubmitted($spj);
        }

        $msg = $request->status === 'diajukan'
            ? 'SPJ berhasil diajukan oleh Subbagian Umum.'
            : 'Draft SPJ berhasil disimpan.';

        return redirect()->route('umum.spj-saya.index')->with('success', $msg);
    }

    /**
     * Tampilkan daftar SPJ yang diajukan sendiri oleh akun Subbagian Umum.
     */
    public function mySpj(Request $request)
    {
        $userId = Auth::id();
        $baseQuery = Spj::with(['user', 'histories'])->where('user_id', $userId);

        // Hitung statistik SPJ Saya
        $allMySpjs = (clone $baseQuery)->get();
        $stats = [
            'total' => $allMySpjs->count(),
            'draft' => $allMySpjs->where('status', 'draft')->count(),
            'diajukan' => $allMySpjs->where('status', 'diajukan')->count(),
            'diproses' => $allMySpjs->filter(fn ($s) => in_array($s->status, ['disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm', 'menunggu_otp_kpa', 'proses_ls']) || ($s->status === 'diajukan' && ! $s->was_revisi))->count(),
            'revisi' => $allMySpjs->filter(fn ($s) => str_contains((string) $s->status, 'revisi'))->count(),
            'selesai' => $allMySpjs->where('status', 'selesai')->count(),
            'totalNilai' => (float) $allMySpjs->sum('nilai'),
        ];

        $query = clone $baseQuery;

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kegiatan', 'like', "%{$search}%")
                    ->orWhere('nomor_spj', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'semua') {
            $status = $request->status;
            if ($status === 'revisi') {
                $query->where('status', 'like', 'revisi_%');
            } elseif ($status === 'proses') {
                $query->whereIn('status', ['disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm', 'menunggu_otp_kpa', 'proses_ls']);
            } else {
                $query->where('status', $status);
            }
        }

        $spjs = $query->orderBy('updated_at', 'desc')->get();

        return view('umum.spj-saya.index', compact('spjs', 'stats'));
    }

    /**
     * Tampilkan formulir edit untuk SPJ yang diajukan sendiri oleh Subbagian Umum (draft / revisi).
     */
    public function editMySpj($id)
    {
        $userId = Auth::id();
        $spj = Spj::where('user_id', $userId)->findOrFail($id);

        if (! in_array($spj->status, ['draft', 'revisi_umum', 'revisi_ppk', 'revisi_ppspm', 'revisi_bendahara'])) {
            return redirect()->route('umum.spj-saya.index')
                ->with('error', 'SPJ tidak dapat diubah karena sudah masuk dalam tahapan alur verifikasi resmi.');
        }

        $nomorSpjOtomatis = $spj->nomor_spj ?: Spj::generateNomorSpj();
        $spjData = SyaratDokumenSpj::getSpjData('umum');

        return view('umum.spj-saya.edit', compact('spj', 'nomorSpjOtomatis', 'spjData'));
    }

    /**
     * Perbarui data SPJ yang diajukan sendiri oleh Subbagian Umum.
     */
    public function updateMySpj(Request $request, $id)
    {
        $userId = Auth::id();
        $spj = Spj::where('user_id', $userId)->findOrFail($id);

        if (! in_array($spj->status, ['draft', 'revisi_umum', 'revisi_ppk', 'revisi_ppspm', 'revisi_bendahara'])) {
            return redirect()->route('umum.spj-saya.index')
                ->with('error', 'SPJ tidak dapat diubah karena sudah masuk dalam proses verifikasi.');
        }

        $this->normalizeNilai($request);

        $request->validate([
            'nomor_spj' => 'nullable|string|max:100|unique:spjs,nomor_spj,'.$spj->id,
            'kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal',
            'status' => 'required|in:draft,diajukan',
            'nilai' => 'required|numeric|min:0',
            'seksi' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
            'no_wa' => 'nullable|string|max:25',
        ], [
            'nomor_spj.unique' => 'Nomor SPJ sudah terdaftar, silakan gunakan nomor lain.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai kegiatan tidak boleh sebelum tanggal mulai kegiatan.',
        ]);

        if ($request->status === 'diajukan' && ! $request->boolean('is_printed')) {
            return back()->withInput()->withErrors([
                'status' => 'SPJ tidak dapat diajukan jika belum menekan Print sampai tercetak. Silakan cetak lembar checklist berkas terlebih dahulu.',
            ]);
        }

        $dokumenKelengkapan = $spj->dokumen_file ? json_decode($spj->dokumen_file, true) : [];
        if ($request->has('nama_dokumen') && is_array($request->input('nama_dokumen'))) {
            $existingMap = [];
            if ($spj->dokumen_file && is_array(json_decode($spj->dokumen_file, true))) {
                foreach (json_decode($spj->dokumen_file, true) as $ex) {
                    if (isset($ex['nama'])) {
                        $existingMap[$ex['nama']] = $ex;
                    }
                }
            }

            $dokumenKelengkapan = [];
            foreach ($request->input('nama_dokumen') as $index => $namaDokumen) {
                $assignee = $request->input("assignee_dokumen.{$index}");
                $isBendahara = Spj::isBendaharaDoc(['nama' => $namaDokumen, 'assignee' => $assignee]);
                $isPpk = Spj::isPpkDoc(['nama' => $namaDokumen, 'assignee' => $assignee]);

                if ($isBendahara || $isPpk) {
                    $existingItem = $existingMap[$namaDokumen] ?? null;
                    $dokumenKelengkapan[] = [
                        'nama' => $namaDokumen,
                        'disiapkan' => ! empty($existingItem['disiapkan']),
                        'assignee' => $assignee ?: ($existingItem['assignee'] ?? ($isBendahara ? 'Bendahara' : 'PPK')),
                        'checked_by' => $existingItem['checked_by'] ?? null,
                        'checked_at' => $existingItem['checked_at'] ?? null,
                    ];
                } else {
                    $isChecked = $request->has("berkas.{$index}");
                    $dokumenKelengkapan[] = [
                        'nama' => $namaDokumen,
                        'disiapkan' => $isChecked,
                        'assignee' => $assignee ?: 'SM',
                        'checked_by' => $isChecked ? 'umum' : null,
                    ];
                }
            }
        }

        $nomorSpj = $request->filled('nomor_spj') ? $request->nomor_spj : ($spj->nomor_spj ?: Spj::generateNomorSpj());

        $statusLama = $spj->status;
        $wasDraftOrRevisi = $spj->status !== 'diajukan';
        $isRevisi = str_starts_with((string) $statusLama, 'revisi_') || $statusLama === 'dikembalikan';

        $spj->update([
            'nomor_spj' => $nomorSpj,
            'kegiatan' => $request->kegiatan,
            'tanggal' => $request->tanggal,
            'tanggal_selesai' => $request->tanggal_selesai ?: null,
            'seksi' => $request->filled('seksi') ? $request->seksi : ($spj->seksi ?: (Auth::user()->seksi ?? 'Subbag Umum')),
            'nilai' => $request->nilai,
            'keterangan' => $request->keterangan,
            'no_wa' => $request->no_wa ?: Auth::user()->no_wa,
            'status' => $request->status,
            'dokumen_file' => count($dokumenKelengkapan) > 0 ? json_encode($dokumenKelengkapan) : null,
            'diajukan_at' => $request->status === 'diajukan' && $spj->status !== 'diajukan' ? now() : $spj->diajukan_at,
            'berkas_fisik_diterima_at' => $isRevisi && $request->status === 'diajukan' ? null : $spj->berkas_fisik_diterima_at,
            'pemeriksaan_dimulai_at' => $isRevisi && $request->status === 'diajukan' ? null : $spj->pemeriksaan_dimulai_at,
        ]);

        if ($request->status === 'diajukan' && $wasDraftOrRevisi) {
            if ($isRevisi) {
                SpjHistory::create([
                    'spj_id' => $spj->id,
                    'user_id' => Auth::id(),
                    'aksi' => 'Pengajuan Ulang (Perbaikan Berkas Selesai)',
                    'status_lama' => $statusLama,
                    'status_baru' => 'diajukan',
                    'keterangan' => 'Subbagian Umum telah memperbaiki berkas SPJ sesuai catatan revisi dan mengajukan ulang ke alur verifikasi.',
                ]);
            } else {
                SpjHistory::create([
                    'spj_id' => $spj->id,
                    'user_id' => Auth::id(),
                    'aksi' => 'Pengajuan SPJ oleh Subbag Umum',
                    'status_lama' => 'draft',
                    'status_baru' => 'diajukan',
                    'keterangan' => 'Dokumen SPJ diajukan oleh Subbagian Umum untuk verifikasi kelengkapan berkas fisik.',
                ]);
            }

            WhatsAppService::notifySubmitted($spj);
        }

        $msg = $request->status === 'diajukan'
            ? ($isRevisi
                ? 'SPJ perbaikan berhasil diajukan ulang ke alur verifikasi.'
                : 'SPJ berhasil diajukan ke alur verifikasi.')
            : 'Draft SPJ berhasil diperbarui.';

        return redirect()->route('umum.spj-saya.index')->with('success', $msg);
    }

    /**
     * Hapus draf SPJ milik Subbagian Umum sendiri.
     */
    public function destroyMySpj($id)
    {
        $userId = Auth::id();
        $spj = Spj::where('user_id', $userId)->findOrFail($id);

        if ($spj->status !== 'draft') {
            abort(403, 'Hanya SPJ berstatus draf yang dapat dihapus.');
        }

        $spj->delete();

        return redirect()->route('umum.spj-saya.index')->with('success', 'Draf SPJ berhasil dihapus.');
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
        abort(403, 'Hanya Administrator yang memiliki hak akses untuk menghapus data SPJ.');
    }

    /**
     * Normalisasi format nilai input (rupiah dengan titik/koma) menjadi angka numerik bersih.
     */
    protected function normalizeNilai(Request $request): void
    {
        if ($request->has('nilai') && $request->nilai !== null) {
            $raw = (string) $request->input('nilai');
            $clean = trim(preg_replace('/[^\d,\.]/', '', $raw));
            if ($clean === '') {
                return;
            }

            if (str_contains($clean, ',') && str_contains($clean, '.')) {
                $clean = str_replace('.', '', $clean);
                $clean = str_replace(',', '.', $clean);
            } elseif (str_contains($clean, '.')) {
                $parts = explode('.', $clean);
                if (count($parts) > 2 || (count($parts) === 2 && strlen($parts[1]) === 3)) {
                    $clean = str_replace('.', '', $clean);
                }
            } elseif (str_contains($clean, ',')) {
                $clean = str_replace(',', '.', $clean);
            }

            $request->merge(['nilai' => $clean]);
        }
    }
}
