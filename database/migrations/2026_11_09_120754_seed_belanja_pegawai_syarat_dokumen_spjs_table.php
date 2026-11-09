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
        $newCategories = [
            'Belanja Gaji PNS (5111)' => [
                'jenis' => 'Belanja Gaji PNS',
                'peruntukan' => 'umum',
                'docs' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'Daftar Gaji Induk', 'assignee' => 'SM'],
                    ['name' => 'Rekapitulasi Daftar Gaji', 'assignee' => 'SM'],
                    ['name' => 'Daftar Perubahan Gaji (jika ada)', 'assignee' => 'SM'],
                ],
            ],
            'Belanja Gaji PPPK (5116)' => [
                'jenis' => 'Belanja Gaji PPPK',
                'peruntukan' => 'umum',
                'docs' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'Daftar Gaji Induk', 'assignee' => 'SM'],
                    ['name' => 'Rekapitulasi Daftar Gaji', 'assignee' => 'SM'],
                    ['name' => 'Daftar Perubahan Gaji (jika ada)', 'assignee' => 'SM'],
                ],
            ],
            'Belanja Tunjangan Kinerja PNS (512411)' => [
                'jenis' => 'Belanja Tunjangan Kinerja PNS',
                'peruntukan' => 'umum',
                'docs' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'Daftar Rekapitulasi', 'assignee' => 'SM'],
                    ['name' => 'SPTJM', 'assignee' => 'SM'],
                ],
            ],
            'Belanja Tunjangan Kinerja PPPK (512414)' => [
                'jenis' => 'Belanja Tunjangan Kinerja PPPK',
                'peruntukan' => 'umum',
                'docs' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'Daftar Rekapitulasi', 'assignee' => 'SM'],
                    ['name' => 'SPTJM', 'assignee' => 'SM'],
                ],
            ],
            'Belanja Uang Makan PNS (511129)' => [
                'jenis' => 'Belanja Uang Makan PNS',
                'peruntukan' => 'umum',
                'docs' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'Daftar Rekapitulasi', 'assignee' => 'SM'],
                ],
            ],
            'Belanja Uang Makan PPPK (511628)' => [
                'jenis' => 'Belanja Uang Makan PPPK',
                'peruntukan' => 'umum',
                'docs' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'Daftar Rekapitulasi', 'assignee' => 'SM'],
                ],
            ],
            'Belanja Lembur (512211)' => [
                'jenis' => 'Belanja Lembur',
                'peruntukan' => 'umum',
                'docs' => [
                    ['name' => 'KAK', 'assignee' => 'SM'],
                    ['name' => 'FP (dari BOS)', 'assignee' => 'SM'],
                    ['name' => 'Surat Perintah Lembur', 'assignee' => 'SM'],
                    ['name' => 'Daftar Hadir Lembur', 'assignee' => 'SM'],
                    ['name' => 'Daftar Perhitungan Uang Lembur', 'assignee' => 'SM'],
                ],
            ],
        ];

        foreach ($newCategories as $namaBelanja => $data) {
            foreach ($data['docs'] as $idx => $doc) {
                SyaratDokumenSpj::firstOrCreate(
                    [
                        'nama_belanja' => $namaBelanja,
                        'jenis_belanja' => $data['jenis'],
                        'nama_dokumen' => $doc['name'],
                    ],
                    [
                        'assignee' => $doc['assignee'],
                        'peruntukan' => $data['peruntukan'],
                        'urutan' => $idx + 1,
                    ]
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $names = [
            'Belanja Gaji PNS (5111)',
            'Belanja Gaji PPPK (5116)',
            'Belanja Tunjangan Kinerja PNS (512411)',
            'Belanja Tunjangan Kinerja PPPK (512414)',
            'Belanja Uang Makan PNS (511129)',
            'Belanja Uang Makan PPPK (511628)',
            'Belanja Lembur (512211)',
        ];

        SyaratDokumenSpj::whereIn('nama_belanja', $names)->delete();
    }
};
