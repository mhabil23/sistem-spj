<?php

namespace App\Http\Controllers\Teknis;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SpjController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id() ?? 1;
        $query = Spj::where('user_id', $userId);

        if ($request->filled('search')) {
            $query->where('kegiatan', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $spjs = $query->latest()->get();

        return view('teknis.spj.index', compact('spjs'));
    }

    public function create()
    {
        return view('teknis.spj.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'status' => 'required|in:draft,diajukan',
            'nilai' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'no_wa' => 'nullable|string|max:25',
        ]);

        if ($request->status === 'diajukan' && ! $request->boolean('is_printed')) {
            return back()->withInput()->withErrors([
                'status' => 'SPJ tidak dapat diajukan jika belum menekan Print sampai tercetak. Silakan cetak lembar checklist berkas terlebih dahulu.',
            ]);
        }

        $dokumenKelengkapan = [];
        if ($request->has('nama_dokumen') && is_array($request->input('nama_dokumen'))) {
            foreach ($request->input('nama_dokumen') as $index => $namaDokumen) {
                $isChecked = $request->has("berkas.{$index}");
                $dokumenKelengkapan[] = [
                    'nama' => $namaDokumen,
                    'disiapkan' => $isChecked,
                ];
            }
        }

        $spj = Spj::create([
            'user_id' => Auth::id() ?? 1,
            'kegiatan' => $request->kegiatan,
            'tanggal' => $request->tanggal,
            'nilai' => $request->nilai,
            'keterangan' => $request->keterangan,
            'no_wa' => $request->no_wa,
            'status' => $request->status,
            'dokumen_file' => count($dokumenKelengkapan) > 0 ? json_encode($dokumenKelengkapan) : null,
            'diajukan_at' => $request->status === 'diajukan' ? now() : null,
        ]);

        if ($request->status === 'diajukan') {
            WhatsAppService::notifySubmitted($spj);
        }

        $msg = $request->status === 'diajukan' ? 'SPJ berhasil diajukan ke Umum.' : 'SPJ berhasil dibuat dan disimpan sebagai Draft.';

        return redirect()->route('teknis.spj.index')->with('success', $msg);
    }

    public function show(Spj $spj)
    {
        $userId = Auth::id() ?? 1;
        if ($spj->user_id !== $userId) {
            abort(403, 'Akses tidak diizinkan.');
        }

        return view('teknis.spj.show', compact('spj'));
    }

    public function edit(Spj $spj)
    {
        $userId = Auth::id() ?? 1;
        if ($spj->user_id !== $userId) {
            abort(403, 'Akses tidak diizinkan.');
        }

        if (! in_array($spj->status, ['draft', 'revisi_umum', 'revisi_ppk', 'revisi_ppspm', 'revisi_bendahara'])) {
            return redirect()->route('teknis.spj.index')->with('error', 'SPJ tidak dapat diubah karena sudah dalam proses.');
        }

        return view('teknis.spj.edit', compact('spj'));
    }

    public function update(Request $request, Spj $spj)
    {
        $userId = Auth::id() ?? 1;
        if ($spj->user_id !== $userId) {
            abort(403, 'Akses tidak diizinkan.');
        }

        if (! in_array($spj->status, ['draft', 'revisi_umum', 'revisi_ppk', 'revisi_ppspm', 'revisi_bendahara'])) {
            return redirect()->route('teknis.spj.index')->with('error', 'SPJ tidak dapat diubah karena sudah dalam proses.');
        }

        $request->validate([
            'kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'status' => 'required|in:draft,diajukan',
            'nilai' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'no_wa' => 'nullable|string|max:25',
        ]);

        if ($request->status === 'diajukan' && ! $request->boolean('is_printed')) {
            return back()->withInput()->withErrors([
                'status' => 'SPJ tidak dapat diajukan jika belum menekan Print sampai tercetak. Silakan cetak lembar checklist berkas terlebih dahulu.',
            ]);
        }

        $dokumenKelengkapan = $spj->dokumen_file ? json_decode($spj->dokumen_file, true) : [];
        if ($request->has('nama_dokumen') && is_array($request->input('nama_dokumen'))) {
            $dokumenKelengkapan = [];
            foreach ($request->input('nama_dokumen') as $index => $namaDokumen) {
                $isChecked = $request->has("berkas.{$index}");
                $dokumenKelengkapan[] = [
                    'nama' => $namaDokumen,
                    'disiapkan' => $isChecked,
                ];
            }
        }

        $wasDraftOrRevisi = $spj->status !== 'diajukan';
        $spj->update([
            'kegiatan' => $request->kegiatan,
            'tanggal' => $request->tanggal,
            'nilai' => $request->nilai,
            'keterangan' => $request->keterangan,
            'no_wa' => $request->no_wa,
            'status' => $request->status,
            'dokumen_file' => count($dokumenKelengkapan) > 0 ? json_encode($dokumenKelengkapan) : null,
            'diajukan_at' => $request->status === 'diajukan' && $spj->status !== 'diajukan' ? now() : $spj->diajukan_at,
        ]);

        if ($request->status === 'diajukan' && $wasDraftOrRevisi) {
            WhatsAppService::notifySubmitted($spj);
        }

        $msg = $request->status === 'diajukan' ? 'SPJ berhasil diajukan.' : 'Draft SPJ berhasil diperbarui.';

        return redirect()->route('teknis.spj.index')->with('success', $msg);
    }

    public function destroy(Spj $spj)
    {
        $userId = Auth::id() ?? 1;
        if ($spj->user_id !== $userId) {
            abort(403, 'Akses tidak diizinkan.');
        }

        if ($spj->dokumen_file && ! str_starts_with($spj->dokumen_file, '[')) {
            Storage::disk('public')->delete($spj->dokumen_file);
        }

        $spj->delete();

        return redirect()->route('teknis.spj.index')->with('success', 'Dokumen SPJ berhasil dihapus.');
    }

    public function laporan(Request $request)
    {
        $query = Spj::query();

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
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

        $totalDiproses = $spjs->whereIn('status', ['diproses', 'revisi_umum', 'revisi_ppk', 'revisi_ppspm', 'revisi_bendahara'])->count();

        $totalSelesai = $spjs->where('status', 'selesai')->count();

        $totalDikembalikan = $spjs->where('status', 'dikembalikan')->count();

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
}
