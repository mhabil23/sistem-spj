<?php

namespace App\Http\Controllers\Umum;

use App\Http\Controllers\Controller;
use App\Models\Spj;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman rekapitulasi laporan SPJ untuk Bagian Umum & Pimpinan.
     */
    public function index(Request $request)
    {
        [$spjs, $filters, $stats, $periodeLabel] = $this->buildLaporanQuery($request);

        $pengajus = User::where('role', 'teknis')->orderBy('name')->get();

        $daftarTahun = range(max((int) date('Y') + 1, 2027), 2024);

        $daftarBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return view('umum.laporan.index', compact(
            'spjs',
            'filters',
            'stats',
            'periodeLabel',
            'pengajus',
            'daftarTahun',
            'daftarBulan'
        ));
    }

    /**
     * Cetak & Ekspor Laporan SPJ ke format PDF resmi BPS Kabupaten Kolaka Utara.
     */
    public function print(Request $request)
    {
        [$spjs, $filters, $stats, $periodeLabel] = $this->buildLaporanQuery($request);

        $terbilangTotal = $this->terbilang($stats['totalNilai']).' Rupiah';

        $pdf = Pdf::loadView('umum.laporan.pdf', [
            'spjs' => $spjs,
            'filters' => $filters,
            'stats' => $stats,
            'periodeLabel' => $periodeLabel,
            'terbilangTotal' => $terbilangTotal,
            'tanggalCetak' => Carbon::now()->translatedFormat('d F Y'),
        ]);

        $pdf->setPaper('a4', 'landscape');

        $cleanPeriode = preg_replace('/[^A-Za-z0-9_\-]/', '_', $periodeLabel);
        $filename = 'Laporan_SPJ_BPS_Kolaka_Utara_'.$cleanPeriode.'.pdf';

        if ($request->query('download') == '1') {
            return $pdf->download($filename);
        }

        return $pdf->stream($filename);
    }

    /**
     * Membangun query data dan statistik berdasarkan filter yang dikirimkan.
     *
     * @return array{0: Collection, 1: array, 2: array, 3: string}
     */
    private function buildLaporanQuery(Request $request): array
    {
        $query = Spj::with('user')->where('status', '!=', 'draft');

        $filterType = $request->input('filter_type', 'semua');
        $bulan = $request->filled('bulan') ? (int) $request->input('bulan') : null;
        $tahun = $request->filled('tahun') ? (int) $request->input('tahun') : (int) date('Y');
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');
        $status = $request->input('status');
        $userId = $request->input('user_id');
        $search = $request->input('search');

        // Otomatisasi deteksi tipe jika parameter terisi langsung
        if (! $request->has('filter_type')) {
            if ($request->filled('bulan')) {
                $filterType = 'bulan';
            } elseif ($request->filled('tanggal_mulai') || $request->filled('tanggal_selesai')) {
                $filterType = 'tanggal';
            } else {
                $filterType = 'semua';
            }
        }

        $periodeLabel = 'Seluruh Data SPJ (Semua Periode)';

        // 1. Filter Berdasarkan Bulan & Tahun
        if ($filterType === 'bulan' && $bulan) {
            $query->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun);

            $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->locale('id')->translatedFormat('F');
            $periodeLabel = 'Bulan '.$namaBulan.' '.$tahun;
        }
        // 2. Filter Berdasarkan Rentang Tanggal Spesifik
        elseif ($filterType === 'tanggal') {
            if ($tanggalMulai && $tanggalSelesai) {
                $query->whereDate('tanggal', '>=', $tanggalMulai)
                    ->whereDate('tanggal', '<=', $tanggalSelesai);

                $labelMulai = Carbon::parse($tanggalMulai)->translatedFormat('d F Y');
                $labelSelesai = Carbon::parse($tanggalSelesai)->translatedFormat('d F Y');

                if ($tanggalMulai === $tanggalSelesai) {
                    $periodeLabel = 'Tanggal '.$labelMulai;
                } else {
                    $periodeLabel = 'Periode '.$labelMulai.' s/d '.$labelSelesai;
                }
            } elseif ($tanggalMulai) {
                $query->whereDate('tanggal', '>=', $tanggalMulai);
                $periodeLabel = 'Mulai '.Carbon::parse($tanggalMulai)->translatedFormat('d F Y');
            } elseif ($tanggalSelesai) {
                $query->whereDate('tanggal', '<=', $tanggalSelesai);
                $periodeLabel = 'Hingga '.Carbon::parse($tanggalSelesai)->translatedFormat('d F Y');
            }
        }

        // 3. Filter Status SPJ
        if ($status && $status !== 'semua') {
            if ($status === 'revisi') {
                $query->where('status', 'like', 'revisi%');
            } elseif ($status === 'diproses') {
                $query->whereIn('status', ['diajukan', 'disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm']);
            } else {
                $query->where('status', $status);
            }
        }

        // 4. Filter Pengaju / Seksi
        if ($userId && $userId !== 'semua') {
            $query->where('user_id', $userId);
        }

        // 5. Pencarian Kata Kunci
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kegiatan', 'like', "%{$search}%")
                    ->orWhere('nomor_spj', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($qu) use ($search) {
                        $qu->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $spjs = $query->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->get();

        $stats = [
            'totalSpj' => $spjs->count(),
            'totalNilai' => (float) $spjs->sum('nilai'),
            'totalSelesai' => $spjs->where('status', 'selesai')->count(),
            'totalNilaiSelesai' => (float) $spjs->where('status', 'selesai')->sum('nilai'),
            'totalDiproses' => $spjs->whereIn('status', ['diajukan', 'disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm'])->count(),
            'totalRevisi' => $spjs->filter(fn ($item) => str_contains($item->status, 'revisi'))->count(),
        ];

        $filters = [
            'filter_type' => $filterType,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'status' => $status,
            'user_id' => $userId,
            'search' => $search,
        ];

        return [$spjs, $filters, $stats, $periodeLabel];
    }

    /**
     * Konversi angka nominal ke kalimat terbilang rupiah.
     */
    private function terbilang(float|int $angka): string
    {
        $angka = abs((int) round($angka));
        $baca = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];
        $terbilang = '';

        if ($angka < 12) {
            $terbilang = ' '.$baca[$angka];
        } elseif ($angka < 20) {
            $terbilang = $this->terbilang($angka - 10).' Belas';
        } elseif ($angka < 100) {
            $terbilang = $this->terbilang((int) ($angka / 10)).' Puluh'.$this->terbilang($angka % 10);
        } elseif ($angka < 200) {
            $terbilang = ' Seratus'.$this->terbilang($angka - 100);
        } elseif ($angka < 1000) {
            $terbilang = $this->terbilang((int) ($angka / 100)).' Ratus'.$this->terbilang($angka % 100);
        } elseif ($angka < 2000) {
            $terbilang = ' Seribu'.$this->terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            $terbilang = $this->terbilang((int) ($angka / 1000)).' Ribu'.$this->terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            $terbilang = $this->terbilang((int) ($angka / 1000000)).' Juta'.$this->terbilang($angka % 1000000);
        } elseif ($angka < 1000000000000) {
            $terbilang = $this->terbilang((int) ($angka / 1000000000)).' Miliar'.$this->terbilang(fmod($angka, 1000000000));
        } elseif ($angka < 1000000000000000) {
            $terbilang = $this->terbilang((int) ($angka / 1000000000000)).' Triliun'.$this->terbilang(fmod($angka, 1000000000000));
        }

        return trim($terbilang) ?: 'Nol';
    }
}
