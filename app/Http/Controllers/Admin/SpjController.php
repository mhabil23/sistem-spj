<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use App\Models\SpjHistory;
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
        $menungguProses = $spjs->filter(fn ($s) => in_array($s->status, [
            'diajukan',
            'disetujui_umum',
            'disetujui_ppk',
            'disetujui_ppspm',
            'menunggu_otp_kpa',
            'diproses',
        ]))->count();
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
     * Menampilkan form tambah SPJ (Dinonaktifkan untuk Admin)
     */
    public function create()
    {
        return redirect()->route('admin.spj.index')->with('info', 'Administrator tidak memiliki kewenangan mengajukan SPJ. Pengajuan SPJ dilakukan oleh Tim Teknis atau Subbagian Umum.');
    }

    /**
     * Menampilkan detail SPJ
     */
    public function show(Spj $spj)
    {
        $spj->load(['user', 'histories.user']);

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
     * Menyimpan SPJ baru (Dinonaktifkan untuk Admin)
     */
    public function store(Request $request)
    {
        return redirect()->route('admin.spj.index')->with('info', 'Administrator tidak memiliki kewenangan mengajukan SPJ. Pengajuan SPJ dilakukan oleh Tim Teknis atau Subbagian Umum.');
    }

    /**
     * Memperbarui SPJ
     */
    public function update(Request $request, Spj $spj)
    {
        $this->normalizeNilai($request);

        $validated = $request->validate([
            'nomor_spj' => 'nullable|string|max:100|unique:spjs,nomor_spj,'.$spj->id,
            'kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal',
            'nilai' => 'required|numeric|min:0',
            'seksi' => 'nullable|string|in:Sosial,Produksi,Distribusi,Neraca,IPDS',
            'keterangan' => 'nullable|string',
            'no_wa' => 'nullable|string|max:25',
            'status' => 'required|in:draft,diajukan,diproses,selesai,dikembalikan',
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
                    // Dokumen Bendahara atau PPK tidak boleh diubah centangnya oleh form umum/teknis
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

        $validated['nomor_spj'] = $request->filled('nomor_spj') ? $request->nomor_spj : ($spj->nomor_spj ?: Spj::generateNomorSpj());
        $validated['tanggal_selesai'] = $request->tanggal_selesai ?: null;
        $validated['dokumen_file'] = count($dokumenKelengkapan) > 0 ? json_encode($dokumenKelengkapan) : null;
        if ($request->status === 'diajukan' && $spj->status !== 'diajukan') {
            $validated['diajukan_at'] = now();
        }

        $statusLama = $spj->status;
        $spj->update($validated);

        SpjHistory::create([
            'spj_id' => $spj->id,
            'user_id' => Auth::id() ?? 1,
            'aksi' => 'Pembaruan Data SPJ oleh Admin',
            'status_lama' => $statusLama,
            'status_baru' => $spj->status,
            'keterangan' => $statusLama !== $spj->status
                ? "Data dan status SPJ diperbarui oleh Admin dari '{$statusLama}' menjadi '{$spj->status}'."
                : 'Data SPJ diperbarui oleh Admin.',
        ]);

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
                $query->whereIn('status', [
                    'diajukan',
                    'disetujui_umum',
                    'disetujui_ppk',
                    'disetujui_ppspm',
                    'menunggu_otp_kpa',
                    'diproses',
                ]);
            } elseif ($status === 'selesai') {
                $query->whereIn('status', ['disetujui_ppspm', 'selesai']);
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

        $totalDiproses = $spjs->whereIn('status', [
            'diajukan',
            'disetujui_umum',
            'disetujui_ppk',
            'disetujui_ppspm',
            'menunggu_otp_kpa',
            'diproses',
        ])->count();

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
