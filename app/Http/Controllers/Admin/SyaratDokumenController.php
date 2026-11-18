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

        if ($request->filled('peruntukan')) {
            $query->where('peruntukan', $request->peruntukan);
        }

        if ($request->filled('search')) {
            $search = '%'.$request->search.'%';
            $query->where(function ($q) use ($search) {
                $q->where('nama_dokumen', 'like', $search)
                    ->orWhere('nama_belanja', 'like', $search)
                    ->orWhere('jenis_belanja', 'like', $search);
            });
        }

        $allSyaratDokumens = $query->orderBy('nama_belanja')
            ->orderBy('jenis_belanja')
            ->orderBy('urutan')
            ->orderBy('id')
            ->get();

        // Kelompokkan dokumen per Kategori Akun & Jenis Belanja agar tabel tidak berulang/doble
        $groupedDokumens = $allSyaratDokumens->groupBy(function ($item) {
            return $item->nama_belanja.'|||'.$item->jenis_belanja;
        });

        // Struktur hierarkis untuk Tampilan Tabel Excel (Nama Belanja -> Jenis Belanja -> Dokumen)
        $groupedByNamaBelanja = $allSyaratDokumens->groupBy('nama_belanja')->map(function ($itemsInNama) {
            return $itemsInNama->groupBy('jenis_belanja');
        });

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
        $totalJenisBelanja = SyaratDokumenSpj::distinct('jenis_belanja')->count('jenis_belanja');

        return view('admin.syarat-dokumen.index', compact(
            'groupedDokumens',
            'groupedByNamaBelanja',
            'daftarNamaBelanja',
            'daftarJenisBelanja',
            'totalDokumen',
            'totalSm',
            'totalBendahara',
            'totalJenisBelanja'
        ));
    }

    /**
     * Simpan dokumen persyaratan baru (Mendukung batch input dengan tombol +).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_belanja' => 'required|string|max:255',
            'jenis_belanja' => 'required|string|max:255',
            'peruntukan' => 'nullable|in:semua,teknis,umum',
        ], [
            'nama_belanja.required' => 'Nama Belanja wajib diisi atau dipilih.',
            'jenis_belanja.required' => 'Jenis Belanja wajib diisi atau dipilih.',
        ]);

        $namaBelanja = trim($request->nama_belanja);
        $jenisBelanja = trim($request->jenis_belanja);
        $peruntukan = $request->peruntukan ?: SyaratDokumenSpj::determinePeruntukanByKode($namaBelanja);

        // Jika input batch dokumen (menggunakan tombol +)
        if ($request->has('dokumen') && is_array($request->dokumen)) {
            $dokumenList = $request->input('dokumen', []);
            $maxUrutan = SyaratDokumenSpj::where('nama_belanja', $namaBelanja)
                ->where('jenis_belanja', $jenisBelanja)
                ->max('urutan') ?? 0;

            $insertedCount = 0;
            foreach ($dokumenList as $docItem) {
                $namaDok = trim($docItem['nama_dokumen'] ?? '');
                if (empty($namaDok)) {
                    continue;
                }

                $assignee = in_array($docItem['assignee'] ?? '', ['SM', 'Bendahara', 'PPK']) ? $docItem['assignee'] : 'SM';
                $urutan = ! empty($docItem['urutan']) ? (int) $docItem['urutan'] : (++$maxUrutan);

                SyaratDokumenSpj::create([
                    'nama_belanja' => $namaBelanja,
                    'jenis_belanja' => $jenisBelanja,
                    'nama_dokumen' => $namaDok,
                    'assignee' => $assignee,
                    'peruntukan' => $peruntukan,
                    'urutan' => $urutan,
                ]);
                $insertedCount++;
            }

            if ($insertedCount > 0) {
                return redirect()->route('admin.syarat-dokumen.index', [
                    'nama_belanja' => $namaBelanja,
                    'jenis_belanja' => $jenisBelanja,
                ])->with('success', "{$insertedCount} dokumen persyaratan berhasil ditambahkan untuk '{$jenisBelanja}'!");
            }
        }

        // Fallback untuk single dokumen jika dikirim format lama
        $validated = $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'assignee' => 'required|in:SM,Bendahara,PPK',
            'urutan' => 'nullable|integer|min:0',
        ], [
            'nama_dokumen.required' => 'Nama Dokumen wajib diisi.',
            'assignee.required' => 'Pihak yang menyiapkan wajib dipilih.',
            'assignee.in' => 'Pihak yang menyiapkan harus SM, Bendahara, atau PPK.',
        ]);

        if (empty($validated['urutan'])) {
            $maxUrutan = SyaratDokumenSpj::where('nama_belanja', $namaBelanja)
                ->where('jenis_belanja', $jenisBelanja)
                ->max('urutan') ?? 0;
            $validated['urutan'] = $maxUrutan + 1;
        }

        SyaratDokumenSpj::create([
            'nama_belanja' => $namaBelanja,
            'jenis_belanja' => $jenisBelanja,
            'nama_dokumen' => $validated['nama_dokumen'],
            'assignee' => $validated['assignee'],
            'peruntukan' => $peruntukan,
            'urutan' => $validated['urutan'],
        ]);

        return redirect()->route('admin.syarat-dokumen.index', [
            'nama_belanja' => $namaBelanja,
            'jenis_belanja' => $jenisBelanja,
        ])->with('success', 'Dokumen persyaratan SPJ berhasil ditambahkan!');
    }

    /**
     * Perbarui peruntukan untuk semua dokumen dalam satu jenis belanja sekaligus (kecuali berkas bendahara).
     */
    public function updatePeruntukanGroup(Request $request)
    {
        $validated = $request->validate([
            'nama_belanja' => 'required|string|max:255',
            'jenis_belanja' => 'required|string|max:255',
            'peruntukan' => 'required|in:semua,teknis,umum',
        ], [
            'nama_belanja.required' => 'Nama belanja wajib disertakan.',
            'jenis_belanja.required' => 'Jenis belanja wajib disertakan.',
            'peruntukan.required' => 'Peruntukan wajib dipilih.',
            'peruntukan.in' => 'Peruntukan harus semua, teknis, atau umum.',
        ]);

        $namaBelanja = trim($validated['nama_belanja']);
        $jenisBelanja = trim($validated['jenis_belanja']);
        $peruntukan = $validated['peruntukan'];

        // Update semua dokumen dalam kelompok belanja ini KECUALI berkas yang disiapkan oleh Bendahara
        $updatedCount = SyaratDokumenSpj::where('nama_belanja', $namaBelanja)
            ->where('jenis_belanja', $jenisBelanja)
            ->where('assignee', '!=', 'Bendahara')
            ->update([
                'peruntukan' => $peruntukan,
            ]);

        // Pastikan berkas bendahara tetap berstatus 'semua' agar bisa diakses dan diverifikasi oleh kedua alur
        SyaratDokumenSpj::where('nama_belanja', $namaBelanja)
            ->where('jenis_belanja', $jenisBelanja)
            ->where('assignee', 'Bendahara')
            ->update([
                'peruntukan' => 'semua',
            ]);

        $labelPeruntukan = match ($peruntukan) {
            'umum' => 'Khusus Subbag Umum',
            'teknis' => 'Khusus Staf Teknis',
            default => 'Dua-duanya (Teknis & Umum)',
        };

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'peruntukan' => $peruntukan,
                'label' => $labelPeruntukan,
                'updated_count' => $updatedCount,
                'message' => "Peruntukan untuk '{$jenisBelanja}' berhasil diubah menjadi: {$labelPeruntukan} ({$updatedCount} berkas disesuaikan, berkas Bendahara tetap aman).",
            ]);
        }

        return back()->with('success', "Peruntukan untuk '{$jenisBelanja}' berhasil diperbarui menjadi: {$labelPeruntukan}!");
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
            $validated['peruntukan'] = $syaratDokumen->peruntukan ?: SyaratDokumenSpj::determinePeruntukanByKode($validated['nama_belanja']);
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
