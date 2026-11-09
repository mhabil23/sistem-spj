<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SyaratDokumenSpj;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SyaratDokumenController extends Controller
{
    /**
     * Tampilkan daftar dokumen persyaratan per jenis belanja SPJ.
     */
    public function index(Request $request): View
    {
        $query = SyaratDokumenSpj::query();

        if ($request->filled('nama_belanja')) {
            $query->where('nama_belanja', $request->nama_belanja);
        }

        if ($request->filled('jenis_belanja')) {
            $query->where('jenis_belanja', $request->jenis_belanja);
        }

        if ($request->filled('assignee')) {
            $query->where('assignee', $request->assignee);
        }

        if ($request->filled('search')) {
            $search = '%'.$request->search.'%';
            $query->where(function ($q) use ($search) {
                $q->where('nama_dokumen', 'like', $search)
                    ->orWhere('nama_belanja', 'like', $search)
                    ->orWhere('jenis_belanja', 'like', $search);
            });
        }

        $syaratDokumens = $query->orderBy('nama_belanja')
            ->orderBy('jenis_belanja')
            ->orderBy('urutan')
            ->orderBy('id')
            ->paginate(25)
            ->withQueryString();

        $daftarNamaBelanja = SyaratDokumenSpj::distinct()
            ->orderBy('nama_belanja')
            ->pluck('nama_belanja');

        $daftarJenisBelanja = SyaratDokumenSpj::distinct()
            ->when($request->filled('nama_belanja'), function ($q) use ($request) {
                $q->where('nama_belanja', $request->nama_belanja);
            })
            ->orderBy('jenis_belanja')
            ->pluck('jenis_belanja');

        $totalDokumen = SyaratDokumenSpj::count();
        $totalSm = SyaratDokumenSpj::where('assignee', 'SM')->count();
        $totalBendahara = SyaratDokumenSpj::where('assignee', 'Bendahara')->count();

        return view('admin.syarat-dokumen.index', compact(
            'syaratDokumens',
            'daftarNamaBelanja',
            'daftarJenisBelanja',
            'totalDokumen',
            'totalSm',
            'totalBendahara'
        ));
    }

    /**
     * Simpan dokumen persyaratan baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_belanja' => 'required|string|max:255',
            'jenis_belanja' => 'required|string|max:255',
            'nama_dokumen' => 'required|string|max:255',
            'assignee' => 'required|in:SM,Bendahara,PPK',
            'peruntukan' => 'nullable|in:semua,teknis,umum',
            'urutan' => 'nullable|integer|min:0',
        ], [
            'nama_belanja.required' => 'Nama Belanja wajib diisi atau dipilih.',
            'jenis_belanja.required' => 'Jenis Belanja wajib diisi atau dipilih.',
            'nama_dokumen.required' => 'Nama Dokumen wajib diisi.',
            'assignee.required' => 'Pihak yang menyiapkan wajib dipilih.',
            'assignee.in' => 'Pihak yang menyiapkan harus SM, Bendahara, atau PPK.',
        ]);

        if (empty($validated['peruntukan'])) {
            $validated['peruntukan'] = 'semua';
        }

        if (empty($validated['urutan'])) {
            $maxUrutan = SyaratDokumenSpj::where('nama_belanja', $validated['nama_belanja'])
                ->where('jenis_belanja', $validated['jenis_belanja'])
                ->max('urutan') ?? 0;
            $validated['urutan'] = $maxUrutan + 1;
        }

        SyaratDokumenSpj::create($validated);

        return redirect()->route('admin.syarat-dokumen.index', [
            'nama_belanja' => $validated['nama_belanja'],
            'jenis_belanja' => $validated['jenis_belanja'],
        ])->with('success', 'Dokumen persyaratan SPJ berhasil ditambahkan!');
    }

    /**
     * Perbarui data dokumen persyaratan yang sudah ada.
     */
    public function update(Request $request, SyaratDokumenSpj $syaratDokumen): RedirectResponse
    {
        $validated = $request->validate([
            'nama_belanja' => 'required|string|max:255',
            'jenis_belanja' => 'required|string|max:255',
            'nama_dokumen' => 'required|string|max:255',
            'assignee' => 'required|in:SM,Bendahara,PPK',
            'peruntukan' => 'nullable|in:semua,teknis,umum',
            'urutan' => 'nullable|integer|min:0',
        ], [
            'nama_belanja.required' => 'Nama Belanja wajib diisi atau dipilih.',
            'jenis_belanja.required' => 'Jenis Belanja wajib diisi atau dipilih.',
            'nama_dokumen.required' => 'Nama Dokumen wajib diisi.',
            'assignee.required' => 'Pihak yang menyiapkan wajib dipilih.',
        ]);

        if (empty($validated['peruntukan'])) {
            $validated['peruntukan'] = $syaratDokumen->peruntukan ?: 'semua';
        }

        if (empty($validated['urutan'])) {
            $validated['urutan'] = $syaratDokumen->urutan ?: 1;
        }

        $syaratDokumen->update($validated);

        return back()->with('success', 'Dokumen persyaratan SPJ berhasil diperbarui!');
    }

    /**
     * Hapus dokumen persyaratan dari sistem.
     */
    public function destroy(SyaratDokumenSpj $syaratDokumen): RedirectResponse
    {
        $namaDokumen = $syaratDokumen->nama_dokumen;
        $syaratDokumen->delete();

        return back()->with('success', "Dokumen \"{$namaDokumen}\" berhasil dihapus!");
    }
}
