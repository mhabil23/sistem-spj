<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('spjs', function (Blueprint $table) {
            $table->string('file_lampiran')->nullable()->after('dokumen_file');
        });

        // Pindahkan data file path lama yang sempat menimpa dokumen_file ke kolom file_lampiran
        $spjs = DB::table('spjs')->whereNotNull('dokumen_file')->get();
        foreach ($spjs as $spj) {
            $content = (string) $spj->dokumen_file;
            if (! str_starts_with(trim($content), '[')) {
                // Isi ini adalah path file (bukan JSON checklist)
                DB::table('spjs')->where('id', $spj->id)->update([
                    'file_lampiran' => $content,
                ]);

                // Pulihkan checklist standar ATK / Persediaan jika kegiatan terkait ATK
                if (stripos($spj->kegiatan, 'atk') !== false || stripos($spj->kegiatan, 'beli') !== false) {
                    $atkChecklist = [
                        ['nama' => 'KAK', 'disiapkan' => true, 'assignee' => 'SM'],
                        ['nama' => 'FP (dari BOS)', 'disiapkan' => true, 'assignee' => 'SM'],
                        ['nama' => 'Bukti pembelian (< 10 juta) / kwitansi (> 50 juta)', 'disiapkan' => true, 'assignee' => 'SM'],
                        ['nama' => 'BAST dilengkapi dengan dokumentasi barang', 'disiapkan' => true, 'assignee' => 'SM'],
                        ['nama' => 'Faktur Pajak', 'disiapkan' => true, 'assignee' => 'SM'],
                        ['nama' => 'PPn (untuk transaksi diatas 2 juta)', 'disiapkan' => true, 'assignee' => 'SM'],
                        ['nama' => 'PPh 22 1.5% (jika pembelian diatas 2 juta)', 'disiapkan' => false, 'assignee' => 'Bendahara'],
                    ];
                    DB::table('spjs')->where('id', $spj->id)->update([
                        'dokumen_file' => json_encode($atkChecklist),
                    ]);
                } else {
                    // Berikan checklist standar administrasi
                    $stdChecklist = [
                        ['nama' => 'Kuitansi Pembayaran Asli Toko/Rekanan', 'disiapkan' => true, 'assignee' => 'SM'],
                        ['nama' => 'Nota Pembelian / Invoice', 'disiapkan' => true, 'assignee' => 'SM'],
                        ['nama' => 'Berita Acara Serah Terima (BAST)', 'disiapkan' => true, 'assignee' => 'SM'],
                        ['nama' => 'Dokumentasi Foto Barang / Kegiatan', 'disiapkan' => true, 'assignee' => 'SM'],
                    ];
                    DB::table('spjs')->where('id', $spj->id)->update([
                        'dokumen_file' => json_encode($stdChecklist),
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spjs', function (Blueprint $table) {
            $table->dropColumn('file_lampiran');
        });
    }
};
