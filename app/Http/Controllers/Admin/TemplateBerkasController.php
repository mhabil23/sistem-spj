<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TemplateBerkasController extends Controller
{
    /**
     * Path direktori publik untuk menyimpan berkas template.
     */
    protected string $templatesPath;

    /**
     * Path berkas manifest JSON.
     */
    protected string $manifestPath;

    public function __construct()
    {
        $this->templatesPath = public_path('files/templates');
        $this->manifestPath = $this->templatesPath.'/manifest.json';
    }

    /**
     * Tampilkan halaman kelola template seluruh berkas SPJ.
     */
    public function index(Request $request): View
    {
        $this->ensureDirectoryExists();
        $templates = $this->getManifest();
        $categories = $this->getCategories();

        $selectedCategory = $request->query('kategori', 'all');
        $search = trim((string) $request->query('search', ''));

        $filteredTemplates = collect($templates)->filter(function (array $item) use ($selectedCategory, $search): bool {
            $matchCategory = ($selectedCategory === 'all' || ($item['kategori_slug'] ?? '') === $selectedCategory);

            if (! $matchCategory) {
                return false;
            }

            if ($search === '') {
                return true;
            }

            $searchLower = strtolower($search);

            return str_contains(strtolower($item['nama'] ?? ''), $searchLower)
                || str_contains(strtolower($item['kode_dokumen'] ?? ''), $searchLower)
                || str_contains(strtolower($item['kode_akun'] ?? ''), $searchLower)
                || str_contains(strtolower($item['keterangan'] ?? ''), $searchLower)
                || str_contains(strtolower($item['original_name'] ?? ''), $searchLower);
        })->values();

        $stats = [
            'total_template' => count($templates),
            'total_kategori' => count($categories),
            'total_filter_result' => $filteredTemplates->count(),
            'total_size' => $this->calculateTotalSize($templates),
        ];

        return view('admin.template-berkas', [
            'title' => 'Template Berkas SPJ',
            'templates' => $filteredTemplates,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'search' => $search,
            'stats' => $stats,
        ]);
    }

    /**
     * Unggah berkas template baru dan simpan ke direktori publik.
     */
    public function upload(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:100'],
            'kode_dokumen' => ['nullable', 'string', 'max:50'],
            'kode_akun' => ['nullable', 'string', 'max:100'],
            'pic' => ['nullable', 'string', 'max:100'],
            'keterangan' => ['nullable', 'string', 'max:500'],
            'berkas_template' => ['required', 'file', 'mimes:pdf,doc,docx,xls,xlsx,zip,rar,csv', 'max:20480'],
        ], [
            'nama.required' => 'Nama berkas template wajib diisi.',
            'kategori.required' => 'Kategori berkas wajib dipilih.',
            'berkas_template.required' => 'Berkas file template wajib diunggah.',
            'berkas_template.mimes' => 'Format berkas harus berupa PDF, Word (DOC/DOCX), Excel (XLS/XLSX), CSV, atau ZIP/RAR.',
            'berkas_template.max' => 'Ukuran berkas maksimal 20 MB.',
        ]);

        $this->ensureDirectoryExists();

        $file = $request->file('berkas_template');
        $originalName = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());
        $fileSize = $file->getSize();

        // Buat nama file aman dengan timestamp
        $baseName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
        $savedFileName = time().'_'.$baseName.'.'.$extension;

        // Simpan langsung ke public/files/templates
        $file->move($this->templatesPath, $savedFileName);

        $categories = $this->getCategories();
        $kategoriSlug = Str::slug($request->kategori);

        $newItem = [
            'id' => (string) Str::uuid(),
            'nama' => trim($request->nama),
            'kode_dokumen' => trim((string) $request->kode_dokumen) ?: strtoupper(substr($baseName, 0, 8)),
            'kategori' => trim($request->kategori),
            'kategori_slug' => $kategoriSlug,
            'kode_akun' => trim((string) $request->kode_akun) ?: '—',
            'pic' => trim((string) $request->pic) ?: 'Seksi Teknis / Umum',
            'keterangan' => trim((string) $request->keterangan),
            'file_name' => $savedFileName,
            'original_name' => $originalName,
            'file_extension' => $extension,
            'file_size' => $this->formatBytes($fileSize),
            'file_size_raw' => $fileSize,
            'uploaded_at' => now()->translatedFormat('d M Y, H:i'),
            'download_url' => asset('files/templates/'.$savedFileName),
        ];

        $manifest = $this->getManifest();
        array_unshift($manifest, $newItem);
        $this->saveManifest($manifest);

        return redirect()->route('admin.template-berkas.index')
            ->with('success', 'Berkas template "'.$newItem['nama'].'" berhasil disimpan ke direktori publik.');
    }

    /**
     * Hapus berkas template dari direktori publik dan manifest.
     */
    public function destroy(string $id): RedirectResponse
    {
        $manifest = $this->getManifest();
        $targetIndex = null;
        $targetItem = null;

        foreach ($manifest as $index => $item) {
            if (($item['id'] ?? '') === $id) {
                $targetIndex = $index;
                $targetItem = $item;
                break;
            }
        }

        if ($targetItem === null) {
            return redirect()->route('admin.template-berkas.index')
                ->with('error', 'Berkas template tidak ditemukan.');
        }

        // Hapus file fisik dari direktori publik jika ada
        $filePath = $this->templatesPath.'/'.($targetItem['file_name'] ?? '');
        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        // Hapus dari manifest
        array_splice($manifest, $targetIndex, 1);
        $this->saveManifest($manifest);

        return redirect()->route('admin.template-berkas.index')
            ->with('success', 'Berkas template "'.$targetItem['nama'].'" berhasil dihapus dari direktori publik.');
    }

    /**
     * Pastikan direktori templates ada di public/files/templates.
     */
    protected function ensureDirectoryExists(): void
    {
        if (! File::exists($this->templatesPath)) {
            File::makeDirectory($this->templatesPath, 0755, true);
        }
    }

    /**
     * Ambil data manifest JSON.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function getManifest(): array
    {
        $this->ensureDirectoryExists();

        if (! File::exists($this->manifestPath)) {
            return [];
        }

        $json = File::get($this->manifestPath);
        $data = json_decode($json, true);

        return is_array($data) ? $data : [];
    }

    /**
     * Simpan data ke manifest JSON.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    protected function saveManifest(array $data): void
    {
        $this->ensureDirectoryExists();
        File::put($this->manifestPath, json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Daftar kategori pilihan.
     *
     * @return array<string, array{nama: string, slug: string, icon: string, color: string}>
     */
    protected function getCategories(): array
    {
        return [
            'administrasi' => [
                'nama' => 'Administrasi Utama',
                'slug' => 'administrasi',
                'icon' => 'bi-folder-check',
                'color' => '#0284c7',
            ],
            'perjalanan-dinas' => [
                'nama' => 'Perjalanan Dinas',
                'slug' => 'perjalanan-dinas',
                'icon' => 'bi-briefcase-fill',
                'color' => '#f97316',
            ],
            'honor-mitra' => [
                'nama' => 'Honor & Mitra',
                'slug' => 'honor-mitra',
                'icon' => 'bi-people-fill',
                'color' => '#10b981',
            ],
            'pengadaan-bahan' => [
                'nama' => 'Pengadaan & Bahan',
                'slug' => 'pengadaan-bahan',
                'icon' => 'bi-box-seam-fill',
                'color' => '#8b5cf6',
            ],
            'pajak-keuangan' => [
                'nama' => 'Pajak & Keuangan',
                'slug' => 'pajak-keuangan',
                'icon' => 'bi-receipt-cutoff',
                'color' => '#ef4444',
            ],
            'lainnya' => [
                'nama' => 'Format Lainnya',
                'slug' => 'lainnya',
                'icon' => 'bi-file-earmark-plus-fill',
                'color' => '#64748b',
            ],
        ];
    }

    /**
     * Format bytes menjadi representasi ramah pengguna (KB, MB).
     */
    protected function formatBytes(int $bytes, int $precision = 1): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $power = floor(log($bytes, 1024));

        return round($bytes / pow(1024, $power), $precision).' '.$units[$power];
    }

    /**
     * Hitung total ukuran seluruh berkas yang tersimpan.
     *
     * @param  array<int, array<string, mixed>>  $templates
     */
    protected function calculateTotalSize(array $templates): string
    {
        $totalBytes = 0;
        foreach ($templates as $item) {
            $totalBytes += (int) ($item['file_size_raw'] ?? 0);
        }

        return $this->formatBytes($totalBytes);
    }

    /**
     * Ambil daftar template berkas untuk konsumsi publik / dashboard umum.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function getPublicTemplates(): array
    {
        $manifestPath = public_path('files/templates/manifest.json');

        if (File::exists($manifestPath)) {
            $json = File::get($manifestPath);
            $data = json_decode($json, true);
            if (is_array($data) && count($data) > 0) {
                return $data;
            }
        }

        return [
            [
                'id' => 'sample-kuitansi',
                'nama' => 'Format Kuitansi Pembayaran SPJ APBN',
                'kode_dokumen' => 'KWT-01',
                'kategori' => 'Administrasi Utama',
                'kategori_slug' => 'administrasi',
                'kode_akun' => '521211, 521811',
                'pic' => 'Seksi Teknis / Umum',
                'keterangan' => 'Format baku kuitansi resmi pencairan anggaran kegiatan BPS sesuai standar APBN.',
                'file_name' => 'format-kuitansi-spj-bps.xlsx',
                'original_name' => 'Format-Kuitansi-SPJ-BPS-2026.xlsx',
                'file_extension' => 'xlsx',
                'file_size' => '24.5 KB',
                'uploaded_at' => 'Format Standar BPS',
                'download_url' => asset('files/templates/sample/format-kuitansi-spj-bps.xlsx'),
                'is_sample' => true,
            ],
            [
                'id' => 'sample-pengeluaran-riil',
                'nama' => 'Daftar Pengeluaran Riil (Form Lampiran SPJ)',
                'kode_dokumen' => 'DPR-02',
                'kategori' => 'Administrasi Utama',
                'kategori_slug' => 'administrasi',
                'kode_akun' => '524111, 524113',
                'pic' => 'Staf Teknis',
                'keterangan' => 'Pernyataan pengeluaran riil untuk komponen biaya yang tidak memiliki kuitansi penyedia.',
                'file_name' => 'format-rincian-pengeluaran-riil.docx',
                'original_name' => 'Form-Daftar-Pengeluaran-Riil.docx',
                'file_extension' => 'docx',
                'file_size' => '18.2 KB',
                'uploaded_at' => 'Format Standar BPS',
                'download_url' => asset('files/templates/sample/format-rincian-pengeluaran-riil.docx'),
                'is_sample' => true,
            ],
            [
                'id' => 'sample-nominatif-mitra',
                'nama' => 'Daftar Nominatif Pembayaran Honor Mitra Statistik',
                'kode_dokumen' => 'HNR-01',
                'kategori' => 'Honor & Mitra',
                'kategori_slug' => 'honor-mitra',
                'kode_akun' => '521213',
                'pic' => 'Seksi Teknis & Pengolahan',
                'keterangan' => 'Daftar nominatif nama, NIK, beban kerja, potongan PPh 21, dan nomor rekening mitra.',
                'file_name' => 'format-nominatif-honor-mitra.xlsx',
                'original_name' => 'Format-Nominatif-Honor-Mitra-BPS.xlsx',
                'file_extension' => 'xlsx',
                'file_size' => '32.1 KB',
                'uploaded_at' => 'Format Standar BPS',
                'download_url' => asset('files/templates/sample/format-nominatif-honor-mitra.xlsx'),
                'is_sample' => true,
            ],
            [
                'id' => 'sample-surat-tugas',
                'nama' => 'Surat Tugas Lapangan & Surat Perintah Tugas (SPT)',
                'kode_dokumen' => 'SPT-01',
                'kategori' => 'Perjalanan Dinas',
                'kategori_slug' => 'perjalanan-dinas',
                'kode_akun' => '524111',
                'pic' => 'Subbagian Umum / Tim Teknis',
                'keterangan' => 'Surat tugas dinas luar kota/dalam kota dan lembar konfirmasi pelaksanaan tugas lapangan.',
                'file_name' => 'format-surat-tugas-lapangan.docx',
                'original_name' => 'Format-Surat-Tugas-Lapangan.docx',
                'file_extension' => 'docx',
                'file_size' => '21.0 KB',
                'uploaded_at' => 'Format Standar BPS',
                'download_url' => asset('files/templates/sample/format-surat-tugas-lapangan.docx'),
                'is_sample' => true,
            ],
            [
                'id' => 'sample-checklist',
                'nama' => 'Checklist Standar Verifikasi Kelengkapan SPJ',
                'kode_dokumen' => 'CHK-01',
                'kategori' => 'Administrasi Utama',
                'kategori_slug' => 'administrasi',
                'kode_akun' => 'Semua Akun',
                'pic' => 'Subbagian Umum & Verifikator',
                'keterangan' => 'Panduan checklist kelengkapan berkas fisik sebelum diajukan ke Verifikator Umum & PPK.',
                'file_name' => 'format-checklist-kelengkapan-spj.pdf',
                'original_name' => 'Checklist-Kelengkapan-SPJ-SOP.pdf',
                'file_extension' => 'pdf',
                'file_size' => '45.0 KB',
                'uploaded_at' => 'Format Standar BPS',
                'download_url' => asset('files/templates/sample/format-checklist-kelengkapan-spj.pdf'),
                'is_sample' => true,
            ],
        ];
    }
}
