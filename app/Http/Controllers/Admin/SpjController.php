<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpjController extends Controller
{
    /**
     * Menampilkan daftar SPJ
     */
    public function index()
    {
        $spjs = Spj::with('user')->latest()->get();

        $totalSpj = $spjs->count();
        $totalNilaiSpj = $spjs->sum('nilai');
        $menungguProses = $spjs->filter(fn ($s) => in_array($s->status, ['diajukan', 'disetujui_umum', 'disetujui_ppk', 'diproses']))->count();
        $selesai = $spjs->filter(fn ($s) => in_array($s->status, ['disetujui_ppspm', 'selesai']))->count();
        $dikembalikan = $spjs->filter(fn ($s) => str_contains($s->status, 'revisi') || $s->status === 'dikembalikan')->count();

        $totalNilaiSelesai = $spjs->filter(fn ($s) => in_array($s->status, ['disetujui_ppspm', 'selesai']))->sum('nilai');

        return view('admin.spj', compact(
            'spjs',
            'totalSpj',
            'totalNilaiSpj',
            'totalNilaiSelesai',
            'menungguProses',
            'selesai',
            'dikembalikan'
        ));
    }

    /**
     * Menampilkan form tambah SPJ
     */
    public function create()
    {
        $nomorSpjOtomatis = Spj::generateNomorSpj();

        return view('admin.spj-create', compact('nomorSpjOtomatis'));
    }

    /**
     * Menampilkan detail SPJ
     */
    public function show(Spj $spj)
    {
        return view('admin.spj-show', compact('spj'));
    }

    /**
     * Menampilkan form edit SPJ
     */
    public function edit(Spj $spj)
    {
        $nomorSpjOtomatis = $spj->nomor_spj ?: Spj::generateNomorSpj();

        return view('admin.spj-edit', compact('spj', 'nomorSpjOtomatis'));
    }

    /**
     * Menyimpan SPJ baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomor_spj' => 'nullable|string|max:100|unique:spjs,nomor_spj',
            'kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'nilai' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'no_wa' => 'nullable|string|max:25',
            'status' => 'required|in:draft,diajukan,diproses,selesai,dikembalikan',
        ], [
            'nomor_spj.unique' => 'Nomor SPJ sudah terdaftar, silakan gunakan nomor lain.',
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

        $nomorSpj = $request->filled('nomor_spj') ? $request->nomor_spj : Spj::generateNomorSpj();

        Spj::create([
            'user_id' => Auth::id() ?? 1,
            'nomor_spj' => $nomorSpj,
            'kegiatan' => $request->kegiatan,
            'tanggal' => $request->tanggal,
            'nilai' => $request->nilai,
            'keterangan' => $request->keterangan,
            'no_wa' => $request->no_wa,
            'status' => $request->status,
            'dokumen_file' => count($dokumenKelengkapan) > 0 ? json_encode($dokumenKelengkapan) : null,
            'diajukan_at' => $request->status === 'diajukan' ? now() : null,
        ]);

        return redirect()
            ->route('admin.spj.index')
            ->with('success', 'SPJ berhasil ditambahkan.');
    }

    /**
     * Memperbarui SPJ
     */
    public function update(Request $request, Spj $spj)
    {
        $validated = $request->validate([
            'nomor_spj' => 'nullable|string|max:100|unique:spjs,nomor_spj,'.$spj->id,
            'kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'nilai' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'no_wa' => 'nullable|string|max:25',
            'status' => 'required|in:draft,diajukan,diproses,selesai,dikembalikan',
        ], [
            'nomor_spj.unique' => 'Nomor SPJ sudah terdaftar, silakan gunakan nomor lain.',
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

        $validated['nomor_spj'] = $request->filled('nomor_spj') ? $request->nomor_spj : ($spj->nomor_spj ?: Spj::generateNomorSpj());
        $validated['dokumen_file'] = count($dokumenKelengkapan) > 0 ? json_encode($dokumenKelengkapan) : null;
        if ($request->status === 'diajukan' && $spj->status !== 'diajukan') {
            $validated['diajukan_at'] = now();
        }

        $spj->update($validated);

        return redirect()
            ->route('admin.spj.index')
            ->with('success', 'Data SPJ berhasil diperbarui.');
    }

    /**
     * Menghapus SPJ
     */
    public function destroy(Spj $spj)
    {
        $spj->delete();

        return redirect()
            ->route('admin.spj.index')
            ->with('success', 'Data SPJ berhasil dihapus.');
    }

    public function laporan(Request $request)
    {
        $query = Spj::query();

        // Filter nama SPJ / kegiatan
        $search = $request->search ?? $request->nama_spj;
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('kegiatan', 'like', "%{$search}%")
                    ->orWhere('nomor_spj', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'diproses') {
                $query->whereIn('status', ['diajukan', 'disetujui_umum', 'disetujui_ppk']);
            } elseif ($status === 'selesai') {
                $query->whereIn('status', ['disetujui_ppspm', 'selesai']);
            } elseif ($status === 'dikembalikan') {
                $query->where('status', 'like', 'revisi_%');
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

        $totalDiproses = $spjs->whereIn('status', ['diajukan', 'disetujui_umum', 'disetujui_ppk'])->count();

        $totalSelesai = $spjs->whereIn('status', ['disetujui_ppspm', 'selesai'])->count();

        $totalDikembalikan = $spjs->filter(function ($item) {
            return str_starts_with((string) $item->status, 'revisi_');
        })->count();

        return view('admin.laporan-spj', compact(
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
