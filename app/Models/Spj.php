<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Spj extends Model
{
    protected $fillable = [
        'nomor_spj',
        'kegiatan',
        'tanggal',
        'tanggal_selesai',
        'nilai',
        'keterangan',
        'no_wa',
        'status',
        'user_id',
        'seksi',
        'dokumen_file',
        'file_lampiran',
        'catatan_revisi',
        'catatan_internal',
        'catatan_ppk',
        'disposisi',
        'nomor_spm',
        'bukti_transfer',
        'catatan_ppspm',
        'diajukan_at',
        'berkas_fisik_diterima_at',
        'pemeriksaan_dimulai_at',
        'disetujui_umum_at',
        'disetujui_ppk_at',
        'disetujui_ppspm_at',
        'diselesaikan_at',
        'kel_daftar_penerima',
        'kel_bast',
        'kel_sk',
        'kel_kak',
        'kel_form_permintaan',
        'kel_spk',
        'kel_surat_tugas',
        'kel_kesesuaian_mrk',
        'kel_cms',
        'kel_cek_sbks',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function histories()
    {
        return $this->hasMany(SpjHistory::class)->latest('id');
    }

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_selesai' => 'date',
        'nilai' => 'decimal:2',
        'diajukan_at' => 'datetime',
        'berkas_fisik_diterima_at' => 'datetime',
        'pemeriksaan_dimulai_at' => 'datetime',
        'disetujui_umum_at' => 'datetime',
        'disetujui_ppk_at' => 'datetime',
        'disetujui_ppspm_at' => 'datetime',
        'diselesaikan_at' => 'datetime',
    ];

    /**
     * Dapatkan format tanggal periode kegiatan (misal: "12 Februari 2026" atau "12 s.d. 15 Februari 2026").
     */
    public function getPeriodeKegiatanAttribute(): string
    {
        if (! $this->tanggal) {
            return '-';
        }

        $mulai = Carbon::parse($this->tanggal)->locale('id');

        if (! $this->tanggal_selesai || $this->tanggal->isSameDay($this->tanggal_selesai)) {
            return $mulai->translatedFormat('d F Y');
        }

        $selesai = Carbon::parse($this->tanggal_selesai)->locale('id');

        if ($mulai->format('Y-m') === $selesai->format('Y-m')) {
            return $mulai->translatedFormat('d').' s.d. '.$selesai->translatedFormat('d F Y');
        }

        if ($mulai->format('Y') === $selesai->format('Y')) {
            return $mulai->translatedFormat('d F').' s.d. '.$selesai->translatedFormat('d F Y');
        }

        return $mulai->translatedFormat('d F Y').' s.d. '.$selesai->translatedFormat('d F Y');
    }

    public function getSeksiLabelAttribute(): string
    {
        $val = $this->seksi ?: ($this->user?->seksi ?: null);
        if (! $val) {
            return 'Seksi Teknis';
        }

        $labels = [
            'Sosial' => 'Seksi Sosial',
            'Produksi' => 'Seksi Produksi',
            'Distribusi' => 'Seksi Distribusi',
            'Neraca' => 'Seksi Neraca',
            'IPDS' => 'Seksi IPDS',
            'Subbag Umum' => 'Subbagian Umum',
        ];

        if (isset($labels[$val])) {
            return $labels[$val];
        }

        if (str_starts_with($val, 'Seksi ') || str_starts_with($val, 'Subbag ') || str_starts_with($val, 'Subbagian ')) {
            return $val;
        }

        return 'Seksi '.$val;
    }

    /**
     * Dapatkan format ringkas periode kegiatan (misal: "12 Feb 2026" atau "12 - 15 Feb 2026").
     */
    public function getPeriodeKegiatanRingkasAttribute(): string
    {
        if (! $this->tanggal) {
            return '-';
        }

        $mulai = Carbon::parse($this->tanggal)->locale('id');

        if (! $this->tanggal_selesai || $this->tanggal->isSameDay($this->tanggal_selesai)) {
            return $mulai->translatedFormat('d M Y');
        }

        $selesai = Carbon::parse($this->tanggal_selesai)->locale('id');

        if ($mulai->format('Y-m') === $selesai->format('Y-m')) {
            return $mulai->translatedFormat('d').' - '.$selesai->translatedFormat('d M Y');
        }

        if ($mulai->format('Y') === $selesai->format('Y')) {
            return $mulai->translatedFormat('d M').' - '.$selesai->translatedFormat('d M Y');
        }

        return $mulai->translatedFormat('d M Y').' - '.$selesai->translatedFormat('d M Y');
    }

    /**
     * Decode dokumen_file JSON safely.
     *
     * @return array<int, array{nama: string, disiapkan: bool, assignee?: string, checked_by?: string, checked_at?: string}>
     */
    public function getDokumenListAttribute(): array
    {
        if (empty($this->dokumen_file) || ! str_starts_with(trim($this->dokumen_file), '[')) {
            return [];
        }

        $decoded = json_decode($this->dokumen_file, true);

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Dapatkan URL publik untuk file lampiran bukti digital.
     */
    public function getFileLampiranUrlAttribute(): ?string
    {
        $file = $this->file_lampiran ?: (! str_starts_with((string) $this->dokumen_file, '[') ? $this->dokumen_file : null);

        return $file ? Storage::url($file) : null;
    }

    /**
     * Tentukan apakah suatu item dokumen adalah bagian / kewenangan Bendahara.
     */
    public static function isBendaharaDoc(array|string $doc): bool
    {
        if (is_array($doc)) {
            if (isset($doc['assignee'])) {
                $assignee = strtolower((string) $doc['assignee']);
                if (str_contains($assignee, 'bendahara')) {
                    return true;
                }
                if (str_contains($assignee, 'sm') || str_contains($assignee, 'panitia') || str_contains($assignee, 'teknis')) {
                    return false;
                }
            }
            if (isset($doc['checked_by']) && strcasecmp((string) $doc['checked_by'], 'bendahara') === 0) {
                return true;
            }
            $nama = $doc['nama'] ?? '';
        } else {
            $nama = $doc;
        }

        return (bool) preg_match('/(pph|ssp|faktur\s*pajak|bukti\s*potong|ebilling|e-billing|ntpn|cms)/i', (string) $nama);
    }

    /**
     * Tentukan apakah suatu item dokumen adalah bagian / kewenangan PPK.
     */
    public static function isPpkDoc(array|string $doc): bool
    {
        if (is_array($doc)) {
            if (isset($doc['assignee'])) {
                $assignee = strtolower((string) $doc['assignee']);
                if (str_contains($assignee, 'ppk')) {
                    return true;
                }
                if (str_contains($assignee, 'sm') || str_contains($assignee, 'panitia') || str_contains($assignee, 'teknis')) {
                    return false;
                }
            }
            $nama = $doc['nama'] ?? '';
        } else {
            $nama = $doc;
        }

        return (bool) preg_match('/(rincian biaya perjalanan|kuitansi perjalanan|biaya transport lokal|pengeluaran riil|tagihan hotel|berkas pengadaan)/i', (string) $nama);
    }

    /**
     * Kelompokkan dokumen checklist menjadi:
     * - teknis: dokumen yang disiapkan/dicentang di awal oleh Teknis
     * - bendahara: dokumen bagian perpajakan/pembayaran Bendahara
     * - ppk: dokumen bagian PPK (jika ada)
     *
     * @return array{
     *     teknis: array<int, array{index: int, nama: string, disiapkan: bool, assignee: string, checked_by?: string}>,
     *     bendahara: array<int, array{index: int, nama: string, disiapkan: bool, assignee: string, checked_by?: string}>,
     *     ppk: array<int, array{index: int, nama: string, disiapkan: bool, assignee: string}>
     * }
     */
    public function getGroupedDokumenAttribute(): array
    {
        $docs = $this->dokumen_list;

        $grouped = [
            'teknis' => [],
            'bendahara' => [],
            'ppk' => [],
        ];

        foreach ($docs as $index => $doc) {
            $nama = $doc['nama'] ?? 'Dokumen';
            $isDisiapkan = ! empty($doc['disiapkan']);

            if (self::isBendaharaDoc($doc)) {
                $grouped['bendahara'][] = [
                    'index' => $index,
                    'nama' => $nama,
                    'disiapkan' => $isDisiapkan,
                    'assignee' => 'Bendahara',
                    'checked_by' => $doc['checked_by'] ?? ($isDisiapkan ? 'bendahara' : null),
                    'checked_at' => $doc['checked_at'] ?? null,
                ];
            } elseif (self::isPpkDoc($doc)) {
                $grouped['ppk'][] = [
                    'index' => $index,
                    'nama' => $nama,
                    'disiapkan' => $isDisiapkan,
                    'assignee' => 'PPK',
                ];
            } else {
                $grouped['teknis'][] = [
                    'index' => $index,
                    'nama' => $nama,
                    'disiapkan' => $isDisiapkan,
                    'assignee' => 'Teknis',
                    'checked_by' => $doc['checked_by'] ?? 'teknis',
                ];
            }
        }

        // Dokumen standar kewajiban Bendahara jika tidak ada dokumen Bendahara asli dari pengajuan
        $standardBendahara = [
            'SSP / Bukti Setor Pajak (PPh 21/22/23 atau PPN)',
            'Faktur Pajak / Bukti e-Billing Pajak',
            'Verifikasi Rekening Tujuan & Eksekusi Pembayaran BRI QLOLA',
        ];

        $hasCustomBendaharaDoc = false;
        foreach ($grouped['bendahara'] as $bDoc) {
            if (! in_array($bDoc['nama'], $standardBendahara, true)) {
                $hasCustomBendaharaDoc = true;
                break;
            }
        }

        if (! $hasCustomBendaharaDoc) {
            $existingNames = array_column($grouped['bendahara'], 'nama');
            foreach ($standardBendahara as $idx => $stdName) {
                if (! in_array($stdName, $existingNames, true)) {
                    $grouped['bendahara'][] = [
                        'index' => 'std_'.$idx,
                        'nama' => $stdName,
                        'disiapkan' => false,
                        'assignee' => 'Bendahara',
                        'checked_by' => null,
                        'is_virtual' => true,
                    ];
                }
            }
        }

        return $grouped;
    }

    /**
     * Cek apakah seluruh kewajiban berkas fisik & perpajakan bagian Bendahara telah dicentang.
     */
    public function areBendaharaObligationsCompleted(): bool
    {
        $grouped = $this->grouped_dokumen;
        $bendaharaDocs = $grouped['bendahara'] ?? [];

        if (empty($bendaharaDocs)) {
            return false;
        }

        foreach ($bendaharaDocs as $doc) {
            if (empty($doc['disiapkan'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Accessor untuk mengecek status kelengkapan kewajiban Bendahara.
     */
    public function getIsBendaharaCompleteAttribute(): bool
    {
        return $this->areBendaharaObligationsCompleted();
    }

    /**
     * Cek apakah SPJ merupakan kategori Belanja Honorarium (Honor narasumber, mitra, pengajar, tim pokja, dll).
     */
    public function isHonor(): bool
    {
        return (bool) preg_match('/(honor|narasumber|moderator|pengajar|petugas|mitra|pokja|tim\s*pelaksana)/i', (string) $this->kegiatan);
    }

    /**
     * Cek apakah SPJ ini memiliki riwayat revisi (pernah atau sedang direvisi).
     */
    public function getWasRevisiAttribute(): bool
    {
        if (! empty($this->catatan_revisi)) {
            return true;
        }

        if ($this->relationLoaded('histories')) {
            return $this->histories->contains(function ($h) {
                return str_starts_with((string) $h->status_baru, 'revisi_')
                    || str_starts_with((string) $h->status_lama, 'revisi_')
                    || $h->status_baru === 'dikembalikan'
                    || $h->status_lama === 'dikembalikan';
            });
        }

        return $this->histories()->where(function ($q) {
            $q->where('status_baru', 'like', 'revisi_%')
                ->orWhere('status_lama', 'like', 'revisi_%')
                ->orWhere('status_baru', 'dikembalikan')
                ->orWhere('status_lama', 'dikembalikan');
        })->exists();
    }

    /**
     * Label representasi status untuk UI dan badge.
     */
    public function getStatusLabelAttribute(): string
    {
        if ($this->status === 'diajukan') {
            if ($this->was_revisi) {
                if (! $this->berkas_fisik_diterima_at) {
                    return 'Perbaikan Diajukan (Bawa Berkas ke Umum)';
                }
                if (! $this->pemeriksaan_dimulai_at) {
                    return 'Fisik Perbaikan Diterima (Siap Cek)';
                }

                return 'Perbaikan Sedang Diperiksa';
            }

            if (! $this->berkas_fisik_diterima_at) {
                return 'Diajukan (Bawa Berkas Fisik ke Umum)';
            }
            if (! $this->pemeriksaan_dimulai_at) {
                return 'Fisik Diterima (Siap Cek)';
            }

            return 'Sedang Diperiksa';
        }

        if ($this->status === 'disetujui_umum') {
            return 'Diteruskan ke PPK';
        }

        if ($this->status === 'disetujui_ppk') {
            return 'Diteruskan ke PPSPM';
        }

        if ($this->status === 'disetujui_ppspm') {
            return 'Siap Diproses Bendahara (SPM Terbit)';
        }

        if ($this->status === 'menunggu_otp_kpa') {
            return 'Menunggu OTP QLOLA KPA';
        }

        if ($this->status === 'proses_ls') {
            return $this->isHonor()
                ? 'Menunggu Kliring Bank LS (Maks. 3 Hari Kerja)'
                : 'Menunggu Transfer Bank Langsung (LS)';
        }

        if ($this->status === 'selesai') {
            return 'Pembayaran Selesai (Telah Dicairkan)';
        }

        if ($this->status === 'revisi_umum') {
            return 'Perlu Revisi (Bagian Umum)';
        }

        if ($this->status === 'revisi_ppk') {
            return 'Perlu Revisi (PPK)';
        }

        if ($this->status === 'revisi_ppspm') {
            return 'Perlu Revisi (PPSPM)';
        }

        if ($this->status === 'revisi_bendahara') {
            return 'Perlu Revisi (Bendahara)';
        }

        return strtoupper(str_replace('_', ' ', (string) $this->status));
    }

    /**
     * Data badge dan status publik yang informatif untuk dashboard umum.
     *
     * @return array{class: string, label: string, icon: string, group: string}
     */
    public function getPublicBadgeAttribute(): array
    {
        $status = (string) $this->status;

        if ($status === 'selesai') {
            return [
                'class' => 'badge-selesai',
                'label' => 'Selesai & Lunas',
                'icon' => 'bi bi-check-circle-fill',
                'group' => 'selesai',
            ];
        }

        if (str_starts_with($status, 'revisi_') || $status === 'dikembalikan') {
            $asal = match ($status) {
                'revisi_umum' => 'Subbag Umum',
                'revisi_ppk' => 'PPK',
                'revisi_ppspm' => 'PPSPM',
                'revisi_bendahara' => 'Bendahara',
                default => 'Verifikator',
            };

            return [
                'class' => 'badge-revisi',
                'label' => 'Perlu Revisi ('.$asal.')',
                'icon' => 'bi bi-exclamation-triangle-fill',
                'group' => 'revisi',
            ];
        }

        if ($status === 'disetujui_ppspm' || $status === 'menunggu_otp_kpa' || $status === 'proses_ls') {
            $label = match ($status) {
                'menunggu_otp_kpa' => 'Menunggu OTP KPA (Bendahara)',
                'proses_ls' => 'Kliring Bank LS (Bendahara)',
                default => 'SPM Terbit (Proses Bendahara)',
            };

            return [
                'class' => 'badge-bendahara',
                'label' => $label,
                'icon' => 'bi bi-award-fill',
                'group' => 'proses',
            ];
        }

        if ($status === 'disetujui_ppk') {
            return [
                'class' => 'badge-ppk',
                'label' => 'Disetujui PPK (Proses PPSPM)',
                'icon' => 'bi bi-patch-check-fill',
                'group' => 'proses',
            ];
        }

        if ($status === 'disetujui_umum') {
            return [
                'class' => 'badge-umum',
                'label' => 'Verifikasi Umum Selesai (Proses PPK)',
                'icon' => 'bi bi-clipboard2-check-fill',
                'group' => 'proses',
            ];
        }

        // Status diajukan
        if ($this->was_revisi) {
            if ($this->pemeriksaan_dimulai_at) {
                return [
                    'class' => 'badge-proses',
                    'label' => 'Perbaikan Sedang Diperiksa Umum',
                    'icon' => 'bi bi-search',
                    'group' => 'proses',
                ];
            }

            if ($this->berkas_fisik_diterima_at) {
                return [
                    'class' => 'badge-diajukan',
                    'label' => 'Fisik Perbaikan Diterima',
                    'icon' => 'bi bi-inbox-fill',
                    'group' => 'diajukan',
                ];
            }

            return [
                'class' => 'badge-proses',
                'label' => 'Perbaikan Diajukan (Bawa Berkas ke Umum)',
                'icon' => 'bi bi-arrow-repeat',
                'group' => 'diajukan',
            ];
        }

        if ($this->pemeriksaan_dimulai_at) {
            return [
                'class' => 'badge-proses',
                'label' => 'Sedang Diperiksa Subbag Umum',
                'icon' => 'bi bi-search',
                'group' => 'proses',
            ];
        }

        if ($this->berkas_fisik_diterima_at) {
            return [
                'class' => 'badge-diajukan',
                'label' => 'Fisik Diterima (Antrean Umum)',
                'icon' => 'bi bi-inbox-fill',
                'group' => 'diajukan',
            ];
        }

        return [
            'class' => 'badge-diajukan',
            'label' => 'Diajukan (Menunggu Fisik di Umum)',
            'icon' => 'bi bi-file-earmark-arrow-up-fill',
            'group' => 'diajukan',
        ];
    }

    /**
     * Hitung nomor tahap saat ini (1 sampai 5) untuk progress stepper.
     */
    public function getStageIndexAttribute(): int
    {
        $status = (string) $this->status;

        if ($status === 'selesai') {
            return 5;
        }

        if (in_array($status, ['menunggu_otp_kpa', 'proses_ls', 'revisi_bendahara', 'disetujui_ppspm'])) {
            return 5;
        }

        if (in_array($status, ['disetujui_ppk', 'revisi_ppspm'])) {
            return 4;
        }

        if (in_array($status, ['disetujui_umum', 'revisi_ppk'])) {
            return 3;
        }

        if ($status === 'revisi_umum' || $this->pemeriksaan_dimulai_at || $this->berkas_fisik_diterima_at) {
            return 2;
        }

        return 1;
    }

    /**
     * Catatan revisi terbaru jika ada.
     */
    public function getCatatanRevisiTerbaruAttribute(): ?string
    {
        return $this->catatan_revisi ?: ($this->catatan_ppk ?: ($this->catatan_ppspm ?: null));
    }

    /**
     * Keterangan detail untuk alur pembayaran LS.
     */
    public function getKeteranganLsAttribute(): string
    {
        return $this->isHonor()
            ? 'Pembayaran honorarium diproses via LS Bank. Estimasi waktu kliring antar-bank dan masuk ke rekening penerima adalah maksimal 3 hari kerja.'
            : 'Pembayaran non-honor diproses via mekanisme LS. Menunggu SP2D dan transfer langsung dari bank penyalur ke rekening rekanan / pihak ketiga.';
    }

    /**
     * Bootstrap model events.
     */
    protected static function booted(): void
    {
        static::creating(function (Spj $spj): void {
            if (empty($spj->nomor_spj)) {
                $spj->nomor_spj = self::generateNomorSpj($spj->tanggal ?? now());
            }
        });

        static::saving(function (Spj $spj): void {
            if (empty($spj->nomor_spj)) {
                $spj->nomor_spj = self::generateNomorSpj($spj->tanggal ?? $spj->created_at ?? now());
            }
        });
    }

    /**
     * Pastikan nomor SPJ selalu memiliki nilai otomatis jika belum terisi di database.
     */
    public function getNomorSpjAttribute(?string $value): ?string
    {
        if (empty($value)) {
            $generated = self::generateNomorSpj($this->tanggal ?? $this->created_at ?? now());
            $this->attributes['nomor_spj'] = $generated;
            if ($this->exists) {
                self::withoutEvents(function () use ($generated): void {
                    self::where('id', $this->id)->whereNull('nomor_spj')->update(['nomor_spj' => $generated]);
                });
            }

            return $generated;
        }

        return $value;
    }

    /**
     * Generate nomor SPJ otomatis dengan format: {URUT_3_DIGIT}{DD}{MM}{YYYY}
     *
     * Contoh: 00127102026 = SPJ ke-001, tanggal 27, bulan 10, tahun 2026
     * Nomor urut direset setiap hari (per tanggal).
     */
    public static function generateNomorSpj($date = null): string
    {
        $today = $date ? Carbon::parse($date) : now();
        $day = $today->format('d');
        $month = $today->format('m');
        $year = $today->format('Y');
        $dateSuffix = $day.$month.$year;

        // Cari SPJ terakhir yang dibuat pada tanggal ini dengan pola nomor yang sesuai
        $lastSpj = self::where('nomor_spj', 'like', "___$dateSuffix")
            ->orderByDesc('id')
            ->first();

        $nextNumber = 1;
        if ($lastSpj && preg_match('/^(\d{3})/', (string) $lastSpj->nomor_spj, $matches)) {
            $nextNumber = ((int) $matches[1]) + 1;
        }

        $nomor = str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT).$dateSuffix;

        while (self::where('nomor_spj', $nomor)->exists()) {
            $nextNumber++;
            $nomor = str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT).$dateSuffix;
        }

        return $nomor;
    }
}
