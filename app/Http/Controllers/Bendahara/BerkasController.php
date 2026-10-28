<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use App\Models\SpjHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BerkasController extends Controller
{
    /**
     * Tampilkan daftar SPJ untuk verifikasi kelengkapan berkas fisik & perpajakan Bendahara.
     */
    public function index(Request $request)
    {
        $baseQuery = Spj::with('user')->whereIn('status', [
            'disetujui_ppspm',
            'menunggu_otp_kpa',
            'proses_ls',
            'selesai',
            'revisi_bendahara',
        ]);

        // Filter status SPJ
        if ($request->filled('status') && $request->status !== 'semua') {
            if ($request->status === 'proses_bank') {
                $baseQuery->whereIn('status', ['menunggu_otp_kpa', 'proses_ls']);
            } else {
                $baseQuery->where('status', $request->status);
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $baseQuery->where(function ($q) use ($search) {
                $q->where('kegiatan', 'like', "%{$search}%")
                    ->orWhere('nomor_spj', 'like', "%{$search}%")
                    ->orWhere('nomor_spm', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($qu) use ($search) {
                        $qu->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $allSpjs = (clone $baseQuery)->orderByDesc('created_at')->get();

        // Hitung statistik kelengkapan berkas
        $totalSpj = $allSpjs->count();
        $lengkapCount = 0;
        $perluLengkapCount = 0;
        $siapCairCount = 0;
        $prosesBankCount = 0;
        $selesaiCount = 0;

        foreach ($allSpjs as $s) {
            if ($s->status === 'disetujui_ppspm') {
                $siapCairCount++;
            } elseif (in_array($s->status, ['menunggu_otp_kpa', 'proses_ls'])) {
                $prosesBankCount++;
            } elseif ($s->status === 'selesai') {
                $selesaiCount++;
            }

            $grouped = $s->grouped_dokumen;
            $bendaharaDocs = $grouped['bendahara'] ?? [];
            $allChecked = true;

            if (empty($bendaharaDocs)) {
                $allChecked = false;
            } else {
                foreach ($bendaharaDocs as $bDoc) {
                    if (empty($bDoc['disiapkan'])) {
                        $allChecked = false;
                        break;
                    }
                }
            }

            $s->is_bendahara_complete = $allChecked;

            if ($allChecked) {
                $lengkapCount++;
            } else {
                $perluLengkapCount++;
            }
        }

        // Filter berdasarkan status kelengkapan berkas jika diminta
        if ($request->filled('kelengkapan')) {
            if ($request->kelengkapan === 'lengkap') {
                $allSpjs = $allSpjs->filter(fn ($s) => $s->is_bendahara_complete);
            } elseif ($request->kelengkapan === 'perlu_lengkap') {
                $allSpjs = $allSpjs->filter(fn ($s) => ! $s->is_bendahara_complete);
            }
        }

        $spjs = $allSpjs;

        $stats = [
            'total' => $totalSpj,
            'siap_cair' => $siapCairCount,
            'proses_bank' => $prosesBankCount,
            'lengkap' => $lengkapCount,
            'perlu_lengkap' => $perluLengkapCount,
            'selesai' => $selesaiCount,
        ];

        return view('bendahara.berkas.index', compact('spjs', 'stats'));
    }

    /**
     * Tampilkan detail berkas SPJ yang dicentang di awal oleh Teknis dan bagian Bendahara.
     */
    public function show($id)
    {
        $spj = Spj::with(['user', 'histories.user'])->findOrFail($id);

        $grouped = $spj->grouped_dokumen;
        $berkasTeknis = $grouped['teknis'] ?? [];
        $berkasBendahara = $grouped['bendahara'] ?? [];
        $berkasPpk = $grouped['ppk'] ?? [];

        // Hitung jumlah checklist
        $teknisSiap = count(array_filter($berkasTeknis, fn ($d) => ! empty($d['disiapkan'])));
        $teknisTotal = count($berkasTeknis);

        $bendaharaSiap = count(array_filter($berkasBendahara, fn ($d) => ! empty($d['disiapkan'])));
        $bendaharaTotal = count($berkasBendahara);

        return view('bendahara.berkas.show', compact(
            'spj',
            'berkasTeknis',
            'berkasBendahara',
            'berkasPpk',
            'teknisSiap',
            'teknisTotal',
            'bendaharaSiap',
            'bendaharaTotal'
        ));
    }

    /**
     * Simpan pembaruan centang berkas Bendahara.
     */
    public function update(Request $request, $id)
    {
        $spj = Spj::findOrFail($id);

        $res = $this->updateChecklistData($spj, $request);

        $newlyChecked = $res['newlyChecked'];
        $newlyUnchecked = $res['newlyUnchecked'];

        // Catat riwayat jejak audit
        $logParts = [];
        if (! empty($newlyChecked)) {
            $logParts[] = 'Dicentang: '.implode(', ', $newlyChecked);
        }
        if (! empty($newlyUnchecked)) {
            $logParts[] = 'Batal centang: '.implode(', ', $newlyUnchecked);
        }
        if ($request->filled('catatan_internal')) {
            $logParts[] = 'Catatan: '.$request->catatan_internal;
        }

        $keteranganLog = 'Bendahara memperbarui checklist kelengkapan berkas fisik & perpajakan.';
        if (! empty($logParts)) {
            $keteranganLog .= ' ('.implode(' | ', $logParts).')';
        }

        SpjHistory::create([
            'spj_id' => $spj->id,
            'user_id' => Auth::id(),
            'aksi' => 'Verifikasi Berkas Bendahara',
            'status_lama' => $spj->status,
            'status_baru' => $spj->status,
            'keterangan' => $keteranganLog,
        ]);

        return back()->with('success', 'Kelengkapan berkas Bendahara berhasil diperbarui & disimpan.');
    }

    /**
     * Helper pembaruan checklist dokumen SPJ.
     *
     * @return array{newlyChecked: array<int, string>, newlyUnchecked: array<int, string>}
     */
    public function updateChecklistData(Spj $spj, Request $request): array
    {
        $docs = $spj->dokumen_list;
        $checkedIndices = $request->input('berkas_bendahara', []);
        if (! is_array($checkedIndices)) {
            $checkedIndices = [];
        }

        $checkedKeys = [];
        foreach ($checkedIndices as $key => $val) {
            if ($val === '1' || $val === 1 || $val === true || $val === 'on') {
                $checkedKeys[(string) $key] = true;
            } else {
                $checkedKeys[(string) $val] = true;
            }
        }

        $newlyChecked = [];
        $newlyUnchecked = [];

        foreach ($docs as $i => &$doc) {
            if (Spj::isBendaharaDoc($doc)) {
                $wasReady = ! empty($doc['disiapkan']);
                $isNowChecked = isset($checkedKeys[(string) $i]);

                $doc['disiapkan'] = $isNowChecked;
                $doc['assignee'] = 'Bendahara';

                if ($isNowChecked) {
                    $doc['checked_by'] = 'bendahara';
                    $doc['checked_at'] = $doc['checked_at'] ?? now()->toDateTimeString();
                    if (! $wasReady) {
                        $newlyChecked[] = $doc['nama'];
                    }
                } else {
                    $doc['checked_by'] = null;
                    $doc['checked_at'] = null;
                    if ($wasReady) {
                        $newlyUnchecked[] = $doc['nama'];
                    }
                }
            }
        }
        unset($doc);

        if ($request->has('berkas_virtual') && is_array($request->input('berkas_virtual'))) {
            $virtualItems = $request->input('berkas_virtual');
            foreach ($virtualItems as $virtualIdx => $virtualName) {
                $isVirtualChecked = isset($checkedKeys['std_'.$virtualIdx]);
                if ($isVirtualChecked) {
                    $found = false;
                    foreach ($docs as &$existing) {
                        if (strcasecmp((string) $existing['nama'], (string) $virtualName) === 0) {
                            $existing['disiapkan'] = true;
                            $existing['assignee'] = 'Bendahara';
                            $existing['checked_by'] = 'bendahara';
                            $existing['checked_at'] = now()->toDateTimeString();
                            $found = true;
                            break;
                        }
                    }
                    unset($existing);

                    if (! $found) {
                        $docs[] = [
                            'nama' => $virtualName,
                            'disiapkan' => true,
                            'assignee' => 'Bendahara',
                            'checked_by' => 'bendahara',
                            'checked_at' => now()->toDateTimeString(),
                        ];
                        $newlyChecked[] = $virtualName;
                    }
                }
            }
        }

        $spj->dokumen_file = json_encode($docs);
        $spj->save();

        return [
            'newlyChecked' => $newlyChecked,
            'newlyUnchecked' => $newlyUnchecked,
        ];
    }
}
