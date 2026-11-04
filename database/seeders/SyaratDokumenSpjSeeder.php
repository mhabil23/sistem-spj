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

        foreach ($defaultData as $namaBelanja => $jenisBelanjaList) {
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
                        ]
                    );
                }
            }
        }
    }
}
