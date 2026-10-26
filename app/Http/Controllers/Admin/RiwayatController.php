<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use App\Models\SpjHistory;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        // Jika riwayat kosong, inisialisasi dari SPJ yang sudah berjalan
        if (SpjHistory::count() === 0) {
            $this->seedInitialHistories();
        }

        $query = SpjHistory::with([
            'spj.user',
            'user',
        ]);

        // Pencarian (kegiatan, nomor spj, aksi, keterangan, nama pembuat)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('aksi', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%")
                    ->orWhereHas('spj', function ($sq) use ($search) {
                        $sq->where('nomor_spj', 'like', "%{$search}%")
                            ->orWhere('kegiatan', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'diproses') {
                $query->whereIn('status_baru', ['diajukan', 'disetujui_umum', 'disetujui_ppk']);
            } elseif ($status === 'selesai') {
                $query->whereIn('status_baru', ['disetujui_ppspm', 'selesai']);
            } elseif ($status === 'dikembalikan') {
                $query->where('status_baru', 'like', 'revisi_%');
            } else {
                $query->where('status_baru', $status);
            }
        }

        // Data riwayat
        $riwayat = $query
            ->latest('id')
            ->get();

        // Statistik
        $totalRiwayat = SpjHistory::count();

        $diajukan = SpjHistory::where('status_baru', 'diajukan')->count();

        // Sedang diproses: diajukan, disetujui_umum, disetujui_ppk
        $diproses = SpjHistory::whereIn('status_baru', ['diajukan', 'disetujui_umum', 'disetujui_ppk'])->count();

        // Selesai: disetujui_ppspm atau selesai
        $selesai = SpjHistory::whereIn('status_baru', ['disetujui_ppspm', 'selesai'])->count();

        // Dikembalikan / Perlu Revisi: revisi_*
        $dikembalikan = SpjHistory::where('status_baru', 'like', 'revisi_%')->count();

        return view('admin.riwayat', compact(
            'riwayat',
            'totalRiwayat',
            'diajukan',
            'diproses',
            'selesai',
            'dikembalikan'
        ));
    }

    /**
     * Membangun riwayat jejak audit awal dari dokumen SPJ yang sudah diproses di sistem.
     */
    private function seedInitialHistories(): void
    {
        $spjs = Spj::where('status', '!=', 'draft')->with('user')->orderBy('id', 'asc')->get();

        foreach ($spjs as $spj) {
            $baseTime = $spj->diajukan_at ?? $spj->created_at ?? now();

            // 1. Pengajuan Awal
            SpjHistory::create([
                'spj_id' => $spj->id,
                'user_id' => $spj->user_id,
                'aksi' => 'Pengajuan Dokumen SPJ',
                'status_lama' => 'draft',
                'status_baru' => 'diajukan',
                'keterangan' => 'Dokumen SPJ diajukan oleh '.($spj->user->name ?? 'staf teknis').' untuk verifikasi kelengkapan berkas.',
                'created_at' => $baseTime,
                'updated_at' => $baseTime,
            ]);

            // 2. Jika disetujui Bagian Umum
            if (in_array($spj->status, ['disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm', 'selesai'])) {
                $timeUmum = $spj->disetujui_umum_at ?? $baseTime->copy()->addMinutes(15);
                SpjHistory::create([
                    'spj_id' => $spj->id,
                    'user_id' => $spj->user_id,
                    'aksi' => 'Verifikasi Berkas Bagian Umum',
                    'status_lama' => 'diajukan',
                    'status_baru' => 'disetujui_umum',
                    'keterangan' => 'Kelengkapan berkas fisik diverifikasi Bagian Umum dan diteruskan ke PPK.',
                    'created_at' => $timeUmum,
                    'updated_at' => $timeUmum,
                ]);
            }

            // 3. Jika disetujui PPK
            if (in_array($spj->status, ['disetujui_ppk', 'disetujui_ppspm', 'selesai'])) {
                $timePpk = $spj->disetujui_ppk_at ?? $baseTime->copy()->addMinutes(30);
                SpjHistory::create([
                    'spj_id' => $spj->id,
                    'user_id' => $spj->user_id,
                    'aksi' => 'Persetujuan Pejabat Pembuat Komitmen (PPK)',
                    'status_lama' => 'disetujui_umum',
                    'status_baru' => 'disetujui_ppk',
                    'keterangan' => 'Dokumen SPJ telah ditandatangani dan disetujui PPK untuk diteruskan ke PPSPM.',
                    'created_at' => $timePpk,
                    'updated_at' => $timePpk,
                ]);
            }

            // 4. Jika disetujui PPSPM
            if (in_array($spj->status, ['disetujui_ppspm', 'selesai'])) {
                $timePpspm = $spj->updated_at ?? $baseTime->copy()->addMinutes(45);
                SpjHistory::create([
                    'spj_id' => $spj->id,
                    'user_id' => $spj->user_id,
                    'aksi' => 'Penerbitan SPM oleh PPSPM',
                    'status_lama' => 'disetujui_ppk',
                    'status_baru' => 'disetujui_ppspm',
                    'keterangan' => 'Surat Perintah Membayar (SPM) diterbitkan oleh PPSPM. Seluruh proses verifikasi selesai.',
                    'created_at' => $timePpspm,
                    'updated_at' => $timePpspm,
                ]);
            }

            // 5. Jika ada revisi
            if (str_starts_with($spj->status, 'revisi_')) {
                $timeRevisi = $spj->updated_at ?? now();
                $keterangan = $spj->catatan_revisi ?: 'Dokumen dikembalikan ke staf teknis untuk perbaikan kelengkapan berkas fisik.';
                SpjHistory::create([
                    'spj_id' => $spj->id,
                    'user_id' => $spj->user_id,
                    'aksi' => 'Pengembalian Berkas (Perlu Revisi)',
                    'status_lama' => 'diajukan',
                    'status_baru' => $spj->status,
                    'keterangan' => 'Catatan: '.$keterangan,
                    'created_at' => $timeRevisi,
                    'updated_at' => $timeRevisi,
                ]);
            }
        }
    }
}
