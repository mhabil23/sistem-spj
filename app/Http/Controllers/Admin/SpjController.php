<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use Illuminate\Http\Request;

class SpjController extends Controller
{
    /**
     * Menampilkan daftar SPJ
     */
    public function index()
    {
        $spjs = Spj::latest()->get();

        return view('admin.spj', compact('spjs'));
    }

    /**
     * Menampilkan form tambah SPJ
     */
    public function create()
    {
        return view('admin.spj-create');
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
        return view('admin.spj-edit', compact('spj'));
    }

    /**
     * Menyimpan SPJ baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomor_spj' => 'required|string|max:100|unique:spjs,nomor_spj',
            'kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'nilai' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:draft,diajukan,diproses,selesai,dikembalikan',
        ]);

        Spj::create([
            'nomor_spj' => $request->nomor_spj,
            'kegiatan' => $request->kegiatan,
            'tanggal' => $request->tanggal,
            'nilai' => $request->nilai,
            'keterangan' => $request->keterangan,
            'status' => $request->status,
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
            'nomor_spj' => 'required|string|max:255|unique:spjs,nomor_spj,' . $spj->id,
            'kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'nilai' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:draft,diajukan,diproses,selesai,dikembalikan',
        ]);

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

        $totalDiproses = $spjs->where('status', 'diproses')->count();

        $totalSelesai = $spjs->where('status', 'selesai')->count();

        $totalDikembalikan = $spjs->where('status', 'dikembalikan')->count();

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
