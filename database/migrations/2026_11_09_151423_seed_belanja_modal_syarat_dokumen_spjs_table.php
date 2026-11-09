<?php

use App\Models\SyaratDokumenSpj;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $belanjaModal = [
            'Belanja Modal (53)' => [
                'Pembelian Langsung dengan Kartu Kredit Pemerintah atau Marketplace' => [
                    'KAK',
                    'FP (dari BOS)',
                    'Surat Pesanan atau bukti pemesanan',
                    'Invoice atau faktur dari penyedia',
                    'Bukti transaksi/pembayaran',
                    'Berita Acara Serah Terima (BAST)',
                    'Berita Acara Pemeriksaan Barang',
                    'Faktur pajak atau dokumen perpajakan, jika ada',
                    'Dokumentasi barang',
                ],
                'Pengadaan Langsung melalui Penyedia' => [
                    'KAK',
                    'FP (dari BOS)',
                    'Dokumen permintaan atau kebutuhan barang',
                    'Surat Pesanan atau Surat Perjanjian',
                    'Invoice atau kuitansi',
                    'Berita Acara Pemeriksaan Barang/Pekerjaan',
                    'Berita Acara Serah Terima (BAST)',
                    'Faktur pajak dan/atau bukti potong pajak',
                    'Dokumentasi barang atau pekerjaan',
                    'Dokumen pembayaran',
                ],
                'Pengadaan melalui Kontrak' => [
                    'KAK',
                    'FP (dari BOS)',
                    'Kontrak atau Surat Perjanjian',
                    'Surat Perintah Mulai Kerja',
                    'Dokumen progres pekerjaan',
                    'Berita Acara Pemeriksaan Pekerjaan',
                    'Berita Acara Serah Terima',
                    'Invoice atau tagihan penyedia',
                    'Faktur pajak dan dokumen perpajakan',
                    'Dokumentasi hasil pekerjaan',
                ],
                'Pembelian Langsung oleh Bendahara Pengeluaran' => [
                    'KAK',
                    'FP (dari BOS)',
                    'Bukti pembelian atau kuitansi',
                    'Faktur atau invoice',
                    'Bukti pembayaran',
                    'BAST atau bukti penerimaan barang',
                    'Dokumen pajak, jika ada',
                    'Dokumentasi barang',
                ],
            ],
        ];

        foreach ($belanjaModal as $namaBelanja => $jenisList) {
            foreach ($jenisList as $jenisBelanja => $docs) {
                foreach ($docs as $urutan => $namaDokumen) {
                    SyaratDokumenSpj::firstOrCreate(
                        [
                            'nama_belanja' => $namaBelanja,
                            'jenis_belanja' => $jenisBelanja,
                            'nama_dokumen' => $namaDokumen,
                        ],
                        [
                            'assignee' => 'SM',
                            'peruntukan' => 'umum',
                            'urutan' => $urutan + 1,
                        ]
                    );
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        SyaratDokumenSpj::where('nama_belanja', 'Belanja Modal (53)')->delete();
    }
};
