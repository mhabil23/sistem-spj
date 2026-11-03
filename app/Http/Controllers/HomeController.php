<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\TemplateBerkasController;
use App\Models\Spj;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Tampilkan halaman utama / dashboard umum publik.
     * Membaca dan menampilkan hasil seluruh data SPJ serta menyediakan filter
     * pencarian berdasarkan nama (kegiatan/pengaju), tanggal, dan jumlah (nominal).
     */
    public function index(Request $request)
    {
        $templates = TemplateBerkasController::getPublicTemplates();

        // SPJ publik: hanya SPJ yang telah diajukan (bukan draft pribadi)
        $baseQuery = Spj::with('user')->where('status', '!=', 'draft');

        // Statistik ringkas untuk counter badges di dashboard umum
        $stats = [
            'total' => (clone $baseQuery)->count(),
            'diajukan' => (clone $baseQuery)->where('status', 'diajukan')->count(),
            'proses' => (clone $baseQuery)->whereIn('status', [
                'disetujui_umum',
                'disetujui_ppk',
                'disetujui_ppspm',
                'menunggu_otp_kpa',
                'proses_ls',
                'diproses',
            ])->count(),
            'selesai' => (clone $baseQuery)->where('status', 'selesai')->count(),
            'revisi' => (clone $baseQuery)->where(function ($q) {
                $q->where('status', 'like', 'revisi_%')
                    ->orWhere('status', 'dikembalikan');
            })->count(),
        ];

        // Query daftar SPJ dengan opsi filter server-side
        $query = clone $baseQuery;

        // 1. Filter Pencarian Berdasarkan Nama (Nama Kegiatan atau Nama Pengaju / Seksi)
        if ($request->filled('cari_nama')) {
            $namaTerm = '%'.$request->cari_nama.'%';
            $query->where(function ($q) use ($namaTerm) {
                $q->where('kegiatan', 'like', $namaTerm)
                    ->orWhere('nomor_spj', 'like', $namaTerm)
                    ->orWhereHas('user', function ($qu) use ($namaTerm) {
                        $qu->where('name', 'like', $namaTerm);
                    });
            });
        }

        // Backward compatibility jika ada param 'cari'
        if ($request->filled('cari') && ! $request->filled('cari_nama')) {
            $term = '%'.$request->cari.'%';
            $query->where(function ($q) use ($term) {
                $q->where('kegiatan', 'like', $term)
                    ->orWhere('nomor_spj', 'like', $term)
                    ->orWhereHas('user', function ($qu) use ($term) {
                        $qu->where('name', 'like', $term);
                    });
            });
        }

        // 2. Filter Pencarian Berdasarkan Tanggal (Tanggal Pengajuan / Tanggal SPJ)
        if ($request->filled('cari_tanggal')) {
            $tgl = $request->cari_tanggal;
            $query->where(function ($q) use ($tgl) {
                $q->whereDate('tanggal', $tgl)
                    ->orWhereDate('diajukan_at', $tgl)
                    ->orWhereDate('created_at', $tgl);
            });
        }

        // 3. Filter Pencarian Berdasarkan Jumlah / Nilai Nominal (Rp)
        if ($request->filled('cari_jumlah')) {
            $rawJumlah = preg_replace('/[^\d]/', '', (string) $request->cari_jumlah);
            if ($rawJumlah !== '') {
                $query->where(function ($q) use ($rawJumlah) {
                    $q->where('nilai', $rawJumlah)
                        ->orWhere('nilai', 'like', '%'.$rawJumlah.'%');
                });
            }
        }

        // Opsi filter rentang jumlah (min / max) jika diberikan
        if ($request->filled('jumlah_min')) {
            $min = (float) preg_replace('/[^\d]/', '', (string) $request->jumlah_min);
            if ($min > 0) {
                $query->where('nilai', '>=', $min);
            }
        }
        if ($request->filled('jumlah_max')) {
            $max = (float) preg_replace('/[^\d]/', '', (string) $request->jumlah_max);
            if ($max > 0) {
                $query->where('nilai', '<=', $max);
            }
        }

        // 4. Filter Berdasarkan Status
        if ($request->filled('status') && $request->status !== 'semua') {
            $status = $request->status;
            if ($status === 'revisi') {
                $query->where(function ($q) {
                    $q->where('status', 'like', 'revisi_%')
                        ->orWhere('status', 'dikembalikan');
                });
            } elseif ($status === 'proses') {
                $query->whereIn('status', [
                    'disetujui_umum',
                    'disetujui_ppk',
                    'disetujui_ppspm',
                    'menunggu_otp_kpa',
                    'proses_ls',
                    'diproses',
                ]);
            } else {
                $query->where('status', $status);
            }
        }

        // 5. Filter Pengurutan (Default: Terbaru)
        $sort = $request->get('sort', 'terbaru');
        switch ($sort) {
            case 'terlama':
                $query->orderBy('created_at', 'asc')->orderBy('id', 'asc');
                break;
            case 'jumlah_tertinggi':
            case 'nilai_tertinggi':
                $query->orderByDesc('nilai');
                break;
            case 'jumlah_terendah':
            case 'nilai_terendah':
                $query->orderBy('nilai', 'asc');
                break;
            case 'nama_asc':
                $query->orderBy('kegiatan', 'asc');
                break;
            case 'nama_desc':
                $query->orderBy('kegiatan', 'desc');
                break;
            case 'terbaru':
            default:
                $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
                break;
        }

        $spjs = $query->get();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'stats' => $stats,
                'total' => $spjs->count(),
                'data' => $spjs,
            ]);
        }

        return view('home', compact('templates', 'spjs', 'stats'));
    }
}
