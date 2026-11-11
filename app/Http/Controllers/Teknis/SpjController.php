<?php

namespace App\Http\Controllers\Teknis;

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
        $userId = Auth::id();
        $query = Spj::where('user_id', $userId);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kegiatan', 'like', '%'.$request->search.'%')
                    ->orWhere('nomor_spj', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $spjs = $query->latest()->get();

        return view('teknis.spj.index', compact('spjs'));
    }

    public function create()
    {
        $nomorSpjOtomatis = Spj::generateNomorSpj();
        $spjData = SyaratDokumenSpj::getSpjData('teknis');

        return view('teknis.spj.create', compact('nomorSpjOtomatis', 'spjData'));
    }

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
            'seksi' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
            'no_wa' => 'nullable|string|max:25',
        ], [
            'nomor_spj.unique' => 'Nomor SPJ sudah terdaftar, silakan gunakan nomor lain.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai kegiatan tidak boleh sebelum tanggal mulai kegiatan.',
        ]);

        if ($request->filled('nama_belanja')) {
            $peruntukanBelanja = SyaratDokumenSpj::determinePeruntukanByKode((string) $request->nama_belanja);
            if ($peruntukanBelanja === 'umum') {
                return back()->withInput()->withErrors([
                    'nama_belanja' => 'Staf Teknis hanya diperbolehkan mengajukan Belanja Barang & Jasa (Akun 52). Belanja Pegawai (51) dan Belanja Modal (53) khusus untuk Subbagian Umum.',
                ]);
            }
        }

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
                        'checked_by' => $isChecked ? 'teknis' : null,
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
            'seksi' => $request->filled('seksi') ? $request->seksi : (Auth::user()->seksi ?: Auth::user()->name),
            'nilai' => $request->nilai,
            'keterangan' => $request->keterangan,
            'no_wa' => $request->no_wa,
            'status' => $request->status,
            'dokumen_file' => count($dokumenKelengkapan) > 0 ? json_encode($dokumenKelengkapan) : null,
            'diajukan_at' => $request->status === 'diajukan' ? now() : null,
        ]);

        if ($request->status === 'diajukan') {
            SpjHistory::create([
                'spj_id' => $spj->id,
                'user_id' => Auth::id(),
                'aksi' => 'Pengajuan Dokumen SPJ',
                'status_lama' => 'draft',
                'status_baru' => 'diajukan',
                'keterangan' => 'Dokumen SPJ diajukan oleh staf teknis untuk verifikasi kelengkapan berkas fisik.',
            ]);
            WhatsAppService::notifySubmitted($spj);
        }

        $msg = $request->status === 'diajukan'
            ? 'SPJ berhasil diajukan. Harap segera bawa dan serahkan berkas fisik SPJ ke Subbagian Umum untuk verifikasi.'
            : 'SPJ berhasil dibuat dan disimpan sebagai Draft.';

        return redirect()->route('teknis.spj.index')->with('success', $msg);
    }

    public function show(Spj $spj)
    {
        $userId = Auth::id();
        if ($spj->user_id !== $userId) {
            abort(403, 'Akses tidak diizinkan. SPJ ini bukan milik akun Anda.');
        }

        return view('teknis.spj.show', compact('spj'));
    }

    public function edit(Spj $spj)
    {
        $userId = Auth::id();
        if ($spj->user_id !== $userId) {
            abort(403, 'Akses tidak diizinkan. SPJ ini bukan milik akun Anda.');
        }

        if (! in_array($spj->status, ['draft', 'revisi_umum', 'revisi_ppk', 'revisi_ppspm', 'revisi_bendahara'])) {
            return redirect()->route('teknis.spj.index')->with('error', 'SPJ tidak dapat diubah karena sudah dalam proses.');
        }

        $nomorSpjOtomatis = $spj->nomor_spj ?: Spj::generateNomorSpj();
        $spjData = SyaratDokumenSpj::getSpjData('teknis');

        return view('teknis.spj.edit', compact('spj', 'nomorSpjOtomatis', 'spjData'));
    }

    public function update(Request $request, Spj $spj)
    {
        $userId = Auth::id();
        if ($spj->user_id !== $userId) {
            abort(403, 'Akses tidak diizinkan. SPJ ini bukan milik akun Anda.');
        }

        if (! in_array($spj->status, ['draft', 'revisi_umum', 'revisi_ppk', 'revisi_ppspm', 'revisi_bendahara'])) {
            return redirect()->route('teknis.spj.index')->with('error', 'SPJ tidak dapat diubah karena sudah dalam proses.');
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

        if ($request->filled('nama_belanja')) {
            $peruntukanBelanja = SyaratDokumenSpj::determinePeruntukanByKode((string) $request->nama_belanja);
            if ($peruntukanBelanja === 'umum') {
                return back()->withInput()->withErrors([
                    'nama_belanja' => 'Staf Teknis hanya diperbolehkan mengajukan Belanja Barang & Jasa (Akun 52). Belanja Pegawai (51) dan Belanja Modal (53) khusus untuk Subbagian Umum.',
                ]);
            }
        }

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
                    // Dokumen Bendahara atau PPK tidak boleh diubah centangnya oleh Teknis
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
                        'checked_by' => $isChecked ? 'teknis' : null,
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
            'seksi' => $request->filled('seksi') ? $request->seksi : (Auth::user()->seksi ?: Auth::user()->name),
            'nilai' => $request->nilai,
            'keterangan' => $request->keterangan,
            'no_wa' => $request->no_wa,
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
                    'keterangan' => 'Staf teknis telah memperbaiki berkas SPJ sesuai catatan revisi dan mengajukan ulang ke verifikator.',
                ]);
            } else {
                SpjHistory::create([
                    'spj_id' => $spj->id,
                    'user_id' => Auth::id(),
                    'aksi' => 'Pengajuan Dokumen SPJ',
                    'status_lama' => 'draft',
                    'status_baru' => 'diajukan',
                    'keterangan' => 'Dokumen SPJ diajukan oleh staf teknis untuk verifikasi kelengkapan berkas fisik.',
                ]);
            }

            WhatsAppService::notifySubmitted($spj);
        }

        $msg = $request->status === 'diajukan'
            ? ($isRevisi
                ? 'SPJ perbaikan berhasil diajukan kembali ke Subbagian Umum. Silakan serahkan berkas fisik yang telah diperbaiki.'
                : 'SPJ berhasil diajukan. Harap segera serahkan berkas fisik SPJ ke Subbagian Umum untuk verifikasi.')
            : 'Draft SPJ berhasil diperbarui.';

        return redirect()->route('teknis.spj.index')->with('success', $msg);
    }

    public function destroy(Spj $spj)
    {
        abort(403, 'Hanya Administrator yang memiliki hak akses untuk menghapus data SPJ.');
    }

    public function laporan(Request $request)
    {
        $userId = Auth::id();
        $query = Spj::where('user_id', $userId);

        // Filter status
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'diproses') {
                $query->whereIn('status', ['diproses', 'disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm', 'menunggu_otp_kpa', 'proses_ls']);
            } elseif ($status === 'dikembalikan') {
                $query->where(function ($q) {
                    $q->where('status', 'like', 'revisi_%')
                        ->orWhere('status', 'dikembalikan');
                });
            } else {
                $query->where('status', $status);
            }
        }

        // Filter tanggal mulai
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }

        // Filter tanggal selesai
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        // Ambil data laporan
        $spjs = $query
            ->orderBy('tanggal', 'desc')
            ->get();

        // Rekap
        $totalSpj = $spjs->count();

        $totalNilai = $spjs->sum('nilai');

        $totalDiajukan = $spjs->where('status', 'diajukan')->count();

        $totalDiproses = $spjs->whereIn('status', ['diproses', 'disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm', 'menunggu_otp_kpa'])->count();

        $totalSelesai = $spjs->where('status', 'selesai')->count();

        $totalDikembalikan = $spjs->filter(function ($item) {
            return str_starts_with((string) $item->status, 'revisi_') || $item->status === 'dikembalikan';
        })->count();

        return view('teknis.laporan-spj', compact(
            'spjs',
            'totalSpj',
            'totalNilai',
            'totalDiajukan',
            'totalDiproses',
            'totalSelesai',
            'totalDikembalikan'
        ));
    }

    /**
     * Cetak Lembar Rekapitulasi Checklist SPJ (Berkas Wajib Print) dalam format landscape resmi BPS (Tab Baru).
     */
    public function print($id)
    {
        $userId = Auth::id();
        $spj = Spj::with('user')
            ->where('user_id', $userId)
            ->findOrFail($id);

        if (request('format') === 'html') {
            return view('umum.spj.pdf-checklist', compact('spj'));
        }

        $pdf = Pdf::loadView('umum.spj.pdf-checklist', compact('spj'))->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan-Rekapitulasi-SPJ-'.$spj->id.'.pdf');
    }

    /**
     * Pratinjau & Cetak Laporan SPJ di Tab Baru dengan format landscape rapi.
     */
    public function printLaporan(Request $request)
    {
        $userId = Auth::id();
        $query = Spj::where('user_id', $userId);

        // Filter status
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'diproses') {
                $query->whereIn('status', ['diproses', 'disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm', 'menunggu_otp_kpa', 'proses_ls']);
            } elseif ($status === 'dikembalikan') {
                $query->where(function ($q) {
                    $q->where('status', 'like', 'revisi_%')
                        ->orWhere('status', 'dikembalikan');
                });
            } else {
                $query->where('status', $status);
            }
        }

        // Filter tanggal mulai
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }

        // Filter tanggal selesai
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        $spjs = $query
            ->orderBy('tanggal', 'desc')
            ->get();

        $totalSpj = $spjs->count();
        $totalNilai = $spjs->sum('nilai');
        $user = Auth::user();

        return view('teknis.laporan-print', compact(
            'spjs',
            'totalSpj',
            'totalNilai',
            'user'
        ));
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
