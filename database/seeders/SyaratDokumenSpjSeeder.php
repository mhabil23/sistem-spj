<?php

namespace Database\Seeders;

use App\Models\SyaratDokumenSpj;
use Illuminate\Database\Seeder;

class SyaratDokumenSpjSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultData = SyaratDokumenSpj::getDefaultSpjData();

        $umumOnlyKeys = [
            'Belanja Gaji PNS (5111)',
            'Belanja Gaji PPPK (5116)',
            'Belanja Tunjangan Kinerja PNS (512411)',
            'Belanja Tunjangan Kinerja PPPK (512414)',
            'Belanja Uang Makan PNS (511129)',
            'Belanja Uang Makan PPPK (511628)',
            'Belanja Lembur (512211)',
            'Belanja Modal (53)',
        ];

        foreach ($defaultData as $namaBelanja => $jenisBelanjaList) {
            $peruntukan = in_array($namaBelanja, $umumOnlyKeys) ? 'umum' : 'semua';
            foreach ($jenisBelanjaList as $jenisBelanja => $dokumenList) {
                foreach ($dokumenList as $urutan => $doc) {
                    SyaratDokumenSpj::firstOrCreate(
                        [
                            'nama_belanja' => $namaBelanja,
                            'jenis_belanja' => $jenisBelanja,
                            'nama_dokumen' => $doc['name'],
                        ],
                        [
                            'assignee' => $doc['assignee'] ?? 'SM',
                            'urutan' => $urutan + 1,
                            'peruntukan' => $peruntukan,
                        ]
                    );
                }
            }
        }
    }
}
