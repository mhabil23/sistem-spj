<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyaratDokumenSpj extends Model
{
    use HasFactory;

    protected $table = 'syarat_dokumen_spjs';

    protected $fillable = [
        'nama_belanja',
        'jenis_belanja',
        'nama_dokumen',
        'assignee',
        'peruntukan',
        'urutan',
    ];

    /**
     * Dapatkan struktur data kategori dan dokumen lengkap untuk form pengajuan SPJ.
     * Mengambil dari database, atau fallback ke default jika database kosong.
     *
     * @param  string|null  $peruntukan  'teknis', 'umum', atau null (semua)
     * @return array<string, array<string, array<int, array{name: string, assignee: string, peruntukan?: string, id?: int}>>>
     */
    public static function getSpjData(?string $peruntukan = null): array
    {
        $query = self::orderBy('nama_belanja')
            ->orderBy('jenis_belanja')
            ->orderBy('urutan')
            ->orderBy('id');

        if ($peruntukan === 'teknis') {
            $query->whereIn('peruntukan', ['teknis', 'semua']);
        } elseif ($peruntukan === 'umum') {
            $query->whereIn('peruntukan', ['umum', 'semua']);
        }

        $records = $query->get();

        if ($records->isEmpty()) {
            return self::getDefaultSpjData($peruntukan);
        }

        $result = [];
        foreach ($records as $record) {
            $result[$record->nama_belanja][$record->jenis_belanja][] = [
                'name' => $record->nama_dokumen,
                'assignee' => $record->assignee ?: 'SM',
                'peruntukan' => $record->peruntukan ?: 'semua',
                'id' => $record->id,
            ];
        }

        return $result;
    }

    /**
     * Data default bawaan sistem SPJ BPS Kolaka Utara.
     *
     * @return array<string, array<string, array<int, array{name: string, assignee: string}>>>
     */
    public static function getDefaultSpjData(?string $peruntukan = null): array
    {
        $data = [
            'Belanja Gaji PNS (5111)' => [
                'Belanja Gaji PNS' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'Daftar Gaji Induk', 'assignee' => 'SM'],
                    ['name' => 'Rekapitulasi Daftar Gaji', 'assignee' => 'SM'],
                    ['name' => 'Daftar Perubahan Gaji (jika ada)', 'assignee' => 'SM'],
                ],
            ],
            'Belanja Gaji PPPK (5116)' => [
                'Belanja Gaji PPPK' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'Daftar Gaji Induk', 'assignee' => 'SM'],
                    ['name' => 'Rekapitulasi Daftar Gaji', 'assignee' => 'SM'],
                    ['name' => 'Daftar Perubahan Gaji (jika ada)', 'assignee' => 'SM'],
                ],
            ],
            'Belanja Tunjangan Kinerja PNS (512411)' => [
                'Belanja Tunjangan Kinerja PNS' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'Daftar Rekapitulasi', 'assignee' => 'SM'],
                    ['name' => 'SPTJM', 'assignee' => 'SM'],
                ],
            ],
            'Belanja Tunjangan Kinerja PPPK (512414)' => [
                'Belanja Tunjangan Kinerja PPPK' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'Daftar Rekapitulasi', 'assignee' => 'SM'],
                    ['name' => 'SPTJM', 'assignee' => 'SM'],
                ],
            ],
            'Belanja Uang Makan PNS (511129)' => [
                'Belanja Uang Makan PNS' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'Daftar Rekapitulasi', 'assignee' => 'SM'],
                ],
            ],
            'Belanja Uang Makan PPPK (511628)' => [
                'Belanja Uang Makan PPPK' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'Daftar Rekapitulasi', 'assignee' => 'SM'],
                ],
            ],
            'Belanja Lembur (512211)' => [
                'Belanja Lembur' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'Surat Perintah Lembur', 'assignee' => 'SM'],
                    ['name' => 'Daftar Hadir Lembur', 'assignee' => 'SM'],
                    ['name' => 'Daftar Perhitungan Uang Lembur', 'assignee' => 'SM'],
                ],
            ],
            'Belanja Bahan (521211)' => [
                'Belanja Konsumsi' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'Undangan', 'assignee' => 'SM'],
                    ['name' => 'Daftar Hadir', 'assignee' => 'SM'],
                    ['name' => 'Notula', 'assignee' => 'SM'],
                    ['name' => 'Bukti pembelian (nota)', 'assignee' => 'SM'],
                    ['name' => 'BAST', 'assignee' => 'SM'],
                    ['name' => 'PPh 22 (jika diatas 2 juta)', 'assignee' => 'Bendahara'],
                ],
                'Belanja paket data komunikasi' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'Bukti pembelian (nota)', 'assignee' => 'SM'],
                    ['name' => 'Daftar nama dan nomor hp penerima paket data/pulsa', 'assignee' => 'SM'],
                    ['name' => 'Screenshoot bukti transfer paket data/pulsa', 'assignee' => 'SM'],
                    ['name' => 'PPh 22 1.5% (jika pembelian diatas 2 juta)', 'assignee' => 'Bendahara'],
                ],
                'Perlengkapan pelatihan' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'Bukti pembelian (nota)', 'assignee' => 'SM'],
                    ['name' => 'Daftar Alokasi (tanda terima barang diserahkan)', 'assignee' => 'SM'],
                    ['name' => 'BAST', 'assignee' => 'SM'],
                    ['name' => 'Faktur Pajak', 'assignee' => 'SM'],
                    ['name' => 'PPn (untuk transaksi diatas 2 juta)', 'assignee' => 'SM'],
                    ['name' => 'PPh 22 1.5% (jika pembelian diatas 2 juta)', 'assignee' => 'Bendahara'],
                ],
            ],
            'Belanja barang persediaan konsumsi (521811)' => [
                'Belanja ATK CS / percetakan dokumen' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'Bukti pembelian (< 10 juta) / kwitansi (> 50 juta)', 'assignee' => 'SM'],
                    ['name' => 'BAST dilengkapi dengan dokumentasi barang', 'assignee' => 'SM'],
                    ['name' => 'Faktur Pajak', 'assignee' => 'SM'],
                    ['name' => 'PPn (untuk transaksi diatas 2 juta)', 'assignee' => 'SM'],
                    ['name' => 'PPh 22 1.5% (jika pembelian diatas 2 juta)', 'assignee' => 'Bendahara'],
                ],
            ],
            'Belanja barang non operasional lainnya (521219)' => [
                'Belanja asuransi petugas' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'Bukti pembelian (invoice)', 'assignee' => 'SM'],
                    ['name' => 'Premi', 'assignee' => 'SM'],
                    ['name' => 'Polis / SPK', 'assignee' => 'SM'],
                    ['name' => 'Kartu Tanda Peserta', 'assignee' => 'SM'],
                ],
                'Belanja pengiriman dokumen' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'Invoice/Resi pengiriman/Surat Tanda Terima Barang (STT)', 'assignee' => 'SM'],
                    ['name' => 'Bukti Pembelian/Pembayaran', 'assignee' => 'SM'],
                    ['name' => 'BAST', 'assignee' => 'SM'],
                    ['name' => 'Faktur Pajak', 'assignee' => 'SM'],
                    ['name' => 'SSP PPn Pengiriman 1,1%', 'assignee' => 'Bendahara'],
                    ['name' => 'SSP PPh 23 2%', 'assignee' => 'Bendahara'],
                ],
            ],
            'Belanja jasa Sewa (522411)' => [
                'Dekorasi stan pameran' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'Bukti Pembelian/Pembayaran', 'assignee' => 'SM'],
                    ['name' => 'BAST', 'assignee' => 'SM'],
                    ['name' => 'Fotokopi NPWP dan fotokopi rekening koran (jika diperlukan)', 'assignee' => 'SM'],
                    ['name' => 'Invoice', 'assignee' => 'SM'],
                    ['name' => 'Fotocopy SIM/KTP dan STNK (kendaraan perorangan)', 'assignee' => 'SM'],
                    ['name' => 'Faktur Pajak', 'assignee' => 'Bendahara'],
                    ['name' => 'SSP PPN', 'assignee' => 'Bendahara'],
                    ['name' => 'SSP PPh Pasal 4 ayat 2 Final 10%', 'assignee' => 'Bendahara'],
                    ['name' => 'SSP PPh pasal 23 2%', 'assignee' => 'Bendahara'],
                ],
                'Sewa stan pameran' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'Bukti Pembelian/Pembayaran', 'assignee' => 'SM'],
                    ['name' => 'BAST', 'assignee' => 'SM'],
                    ['name' => 'Fotokopi NPWP dan fotokopi rekening koran (jika diperlukan)', 'assignee' => 'SM'],
                    ['name' => 'Invoice', 'assignee' => 'SM'],
                    ['name' => 'Fotocopy SIM/KTP dan STNK', 'assignee' => 'SM'],
                    ['name' => 'Faktur Pajak', 'assignee' => 'Bendahara'],
                    ['name' => 'SSP PPN', 'assignee' => 'Bendahara'],
                    ['name' => 'SSP PPh Pasal 4 ayat 2 Final 10%', 'assignee' => 'Bendahara'],
                    ['name' => 'SSP PPh pasal 23 2%', 'assignee' => 'Bendahara'],
                ],
            ],
            'Belanja jasa Profesi (522151)' => [
                'Honor Moderator' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'SK KPA (belanja jasa profesi)', 'assignee' => 'SM'],
                    ['name' => 'Undangan moderator', 'assignee' => 'SM'],
                    ['name' => 'Undangan peserta', 'assignee' => 'SM'],
                    ['name' => 'Jadwal kegiatan', 'assignee' => 'SM'],
                    ['name' => 'Daftar hadir moderator', 'assignee' => 'SM'],
                    ['name' => 'CV moderator', 'assignee' => 'SM'],
                    ['name' => 'Kuitansi', 'assignee' => 'SM'],
                    ['name' => 'Salinan/fotokopi NPWP dan nomor rekening', 'assignee' => 'SM'],
                    ['name' => 'SSP PPh Pasal 21', 'assignee' => 'Bendahara'],
                ],
                'Honor Narasumber' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'SK KPA (belanja jasa profesi)', 'assignee' => 'SM'],
                    ['name' => 'Undangan moderator', 'assignee' => 'SM'],
                    ['name' => 'Undangan peserta', 'assignee' => 'SM'],
                    ['name' => 'Jadwal kegiatan', 'assignee' => 'SM'],
                    ['name' => 'Daftar hadir moderator', 'assignee' => 'SM'],
                    ['name' => 'CV moderator', 'assignee' => 'SM'],
                    ['name' => 'Bahan paparan narasumber', 'assignee' => 'SM'],
                    ['name' => 'Kuitansi', 'assignee' => 'SM'],
                    ['name' => 'Salinan/fotokopi NPWP dan nomor rekening', 'assignee' => 'SM'],
                    ['name' => 'SSP PPh Pasal 21', 'assignee' => 'Bendahara'],
                ],
            ],
            'Belanja jasa lainnya (522191)' => [
                'Penayangan iklan layanan masyarakat' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'Bukti Pembelian/Pembayaran', 'assignee' => 'SM'],
                    ['name' => 'BAST', 'assignee' => 'SM'],
                    ['name' => 'Laporan pelaksanaan kegiatan disertai dokumentasi', 'assignee' => 'SM'],
                    ['name' => 'Faktur Pajak', 'assignee' => 'Bendahara'],
                    ['name' => 'SSP PPN / SSP PPh 23', 'assignee' => 'Bendahara'],
                ],
                'Pelaksanaan pencanangan kegiatan' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'Undangan dan daftar Hadir', 'assignee' => 'SM'],
                    ['name' => 'Bukti Pembelian/Pembayaran', 'assignee' => 'SM'],
                    ['name' => 'BAST', 'assignee' => 'SM'],
                    ['name' => 'Laporan pelaksanaan kegiatan disertai dokumentasi', 'assignee' => 'SM'],
                    ['name' => 'Faktur Pajak', 'assignee' => 'Bendahara'],
                    ['name' => 'SSP PPN / SSP PPh 23', 'assignee' => 'Bendahara'],
                ],
            ],
            'Honor output kegiatan (512213)' => [
                'Honor Tim Pelaksana / Pokja kegiatan' => [
                    ['name' => 'SK KPA/SK KBPS tentang Pokja', 'assignee' => 'SM'],
                    ['name' => 'Laporan tim/output pekerjaan', 'assignee' => 'SM'],
                    ['name' => 'Daftar rekap kuitansi', 'assignee' => 'SM'],
                    ['name' => 'SSP PPh Pasal 21', 'assignee' => 'Bendahara'],
                ],
                'Honor Pengajar' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'SK KPA yang mencakup nama, tugas, rate (SBKS)', 'assignee' => 'SM'],
                    ['name' => 'Jadwal kegiatan', 'assignee' => 'SM'],
                    ['name' => 'Daftar hadir (panitia, pengajar, dan peserta)', 'assignee' => 'SM'],
                    ['name' => 'Daftar rekap kuitansi', 'assignee' => 'SM'],
                    ['name' => 'Laporan pengajar', 'assignee' => 'SM'],
                    ['name' => 'SSP PPh Pasal 21', 'assignee' => 'Bendahara'],
                ],
                'Honor Petugas Mitra (Non PNS atau PNS Non BPS)' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'SK KPA yang mencakup nama, tugas, rate (SBKS)', 'assignee' => 'SM'],
                    ['name' => 'Surat kontrak/surat perjanjian kerja', 'assignee' => 'SM'],
                    ['name' => 'Daftar rekap kuitansi', 'assignee' => 'SM'],
                    ['name' => 'BAPP/BAST', 'assignee' => 'SM'],
                    ['name' => 'SSP PPh Pasal 21', 'assignee' => 'Bendahara'],
                ],
                'Honor Petugas (PNS BPS)' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'SK KPA yang mencakup nama, tugas, rate (SBKS)', 'assignee' => 'SM'],
                    ['name' => 'Daftar rekap kuitansi', 'assignee' => 'SM'],
                    ['name' => 'SSP PPh Pasal 21', 'assignee' => 'Bendahara'],
                ],
            ],
            'Belanja Perjalanan Dinas Biasa (524111)' => [
                'Perjalanan Dinas Keluar Kota' => [
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'Surat Tugas', 'assignee' => 'SM'],
                    ['name' => 'Surat Perintah Perjalanan Dinas lengkap dengan visum', 'assignee' => 'SM'],
                    ['name' => 'Rincian Biaya Perjalanan dinas biasa atau DOP', 'assignee' => 'PPK'],
                    ['name' => 'Tiket/kwitansi kendaraan', 'assignee' => 'SM'],
                    ['name' => 'Kuitansi Pembayaran Bukti Penginapan', 'assignee' => 'SM'],
                    ['name' => 'Surat pernyataan tidak menggunakan kendaraan dinas', 'assignee' => 'SM'],
                    ['name' => 'Laporan perjalanan dinas dan dokumentasi kegiatan (GPS)', 'assignee' => 'SM'],
                    ['name' => 'Kuitansi perjalanan dinas', 'assignee' => 'PPK'],
                ],
            ],
            'Belanja Perjalanan Dinas Dalam Kota (524113)' => [
                'Perjalanan Dinas Dalam Kota sampai dengan 8 jam' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'Surat Tugas', 'assignee' => 'SM'],
                    ['name' => 'Visum yang ditandatangani pejabat di tempat tujuan', 'assignee' => 'SM'],
                    ['name' => 'Kwitansi Transpor/Daftar Biaya Transport lokal', 'assignee' => 'PPK'],
                    ['name' => 'Daftar Pengeluaran riil untuk transpor tanpa bukti', 'assignee' => 'PPK'],
                    ['name' => 'Surat pernyataan tidak menggunakan kendaraan dinas', 'assignee' => 'SM'],
                ],
                'Perjalanan Dinas Dalam Kota lebih dari 8 jam' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'Surat Tugas', 'assignee' => 'SM'],
                    ['name' => 'SPPD yang ditandatangani pejabat tempat tujuan', 'assignee' => 'SM'],
                    ['name' => 'Visum yang ditandatangani responden pengawasan', 'assignee' => 'SM'],
                    ['name' => 'Kuitansi transpor', 'assignee' => 'PPK'],
                    ['name' => 'Daftar Pengeluaran riil untuk transpor tanpa bukti', 'assignee' => 'PPK'],
                    ['name' => 'Rincian Biaya Perjalanan Dinas', 'assignee' => 'PPK'],
                    ['name' => 'Jadwal Kegiatan (lebih dari 8 jam)', 'assignee' => 'SM'],
                    ['name' => 'Surat pernyataan tidak menggunakan kendaraan dinas', 'assignee' => 'SM'],
                ],
            ],
            'Belanja Perjalanan Dinas Paket Meeting Dalam Kota (521114)' => [
                'Penyelenggaraan paket meeting dalam kota' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'Surat Undangan', 'assignee' => 'SM'],
                    ['name' => 'Surat Pernyataan Penanggung Jawab Kegiatan', 'assignee' => 'SM'],
                    ['name' => 'Surat Tugas', 'assignee' => 'SM'],
                    ['name' => 'Surat Perjalanan Dinas (SPD) kolektif (cap hotel)', 'assignee' => 'SM'],
                    ['name' => 'Daftar Hadir', 'assignee' => 'SM'],
                    ['name' => 'Jadwal Kegiatan', 'assignee' => 'SM'],
                    ['name' => 'Notulensi dan Dokumentasi rapat/laporan', 'assignee' => 'SM'],
                    ['name' => 'List Kamar Hotel yang dilegalisasi (fullboard)', 'assignee' => 'SM'],
                    ['name' => 'Tagihan Hotel', 'assignee' => 'PPK'],
                    ['name' => 'Kelengkapan berkas pengadaan', 'assignee' => 'PPK'],
                ],
                'Pembayaran perjalanan dalam rangka paket meeting' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'Surat Undangan', 'assignee' => 'SM'],
                    ['name' => 'Surat Tugas', 'assignee' => 'SM'],
                    ['name' => 'Surat Perjalanan Dinas (SPD) kolektif (cap hotel)', 'assignee' => 'SM'],
                    ['name' => 'Daftar Hadir', 'assignee' => 'SM'],
                    ['name' => 'Surat Pernyataan tidak menggunakan kendaraan dinas', 'assignee' => 'SM'],
                    ['name' => 'Notulensi dan Dokumentasi rapat/laporan', 'assignee' => 'SM'],
                    ['name' => 'Daftar Rincian Perhitungan Pembayaran Perjalanan Dinas', 'assignee' => 'SM'],
                ],
            ],
        ];

        if ($peruntukan === 'teknis') {
            $belanjaPegawaiKeys = [
                'Belanja Gaji PNS (5111)',
                'Belanja Gaji PPPK (5116)',
                'Belanja Tunjangan Kinerja PNS (512411)',
                'Belanja Tunjangan Kinerja PPPK (512414)',
                'Belanja Uang Makan PNS (511129)',
                'Belanja Uang Makan PPPK (511628)',
                'Belanja Lembur (512211)',
            ];
            foreach ($belanjaPegawaiKeys as $key) {
                unset($data[$key]);
            }
        }

        return $data;
    }
}
