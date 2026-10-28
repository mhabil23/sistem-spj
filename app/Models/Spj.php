<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Spj extends Model
{
    protected $fillable = [
        'nomor_spj',
        'kegiatan',
        'tanggal',
        'nilai',
        'keterangan',
        'no_wa',
        'status',
        'user_id',
        'dokumen_file',
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
     * Decode dokumen_file JSON safely.
     *
     * @return array<int, array{nama: string, disiapkan: bool, assignee?: string, checked_by?: string, checked_at?: string}>
     */
    public function getDokumenListAttribute(): array
    {
        if (empty($this->dokumen_file)) {
            return [];
        }

        $decoded = json_decode($this->dokumen_file, true);

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Tentukan apakah suatu item dokumen adalah bagian / kewenangan Bendahara.
     */
    public static function isBendaharaDoc(array|string $doc): bool
    {
        if (is_array($doc)) {
            if (isset($doc['assignee']) && strcasecmp((string) $doc['assignee'], 'Bendahara') === 0) {
                return true;
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
            if (isset($doc['assignee']) && strcasecmp((string) $doc['assignee'], 'PPK') === 0) {
                return true;
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

        // Jika belum ada dokumen Bendahara sama sekali, sediakan standar checklist Bendahara
        if (empty($grouped['bendahara'])) {
            $standardBendahara = [
                'SSP / Bukti Setor Pajak (PPh 21/22/23 atau PPN)',
                'Faktur Pajak / Bukti e-Billing Pajak',
                'Verifikasi Rekening Tujuan & Eksekusi Pembayaran BRI QLOLA',
            ];

            foreach ($standardBendahara as $idx => $stdName) {
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

        return $grouped;
    }

    /**
     * Cek apakah SPJ merupakan kategori Belanja Honorarium (Honor narasumber, mitra, pengajar, tim pokja, dll).
     */
    public function isHonor(): bool
    {
        return (bool) preg_match('/(honor|narasumber|moderator|pengajar|petugas|mitra|pokja|tim\s*pelaksana)/i', (string) $this->kegiatan);
    }

    /**
     * Label representasi status untuk UI dan badge.
     */
    public function getStatusLabelAttribute(): string
    {
        if ($this->status === 'diajukan') {
            return 'Diajukan (Bawa Berkas Fisik ke Umum)';
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

        if ($this->status === 'disetujui_ppspm') {
            return 'Siap Diproses Bendahara (SPM Terbit)';
        }

        if ($this->status === 'revisi_bendahara') {
            return 'Perlu Revisi (Bendahara)';
        }

        return strtoupper(str_replace('_', ' ', (string) $this->status));
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
