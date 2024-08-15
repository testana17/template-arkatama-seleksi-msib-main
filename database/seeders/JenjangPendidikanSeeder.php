<?php

namespace Database\Seeders;

use App\Models\Master\JenjangPendidikan;
use Illuminate\Database\Seeder;

class JenjangPendidikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $jenjangPendidikanData = [
            [
                'nama' => 'Strata 1',
                'kode' => 'S1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Strata 2',
                'kode' => 'S2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Strata 3',
                'kode' => 'S3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Diploma 1',
                'kode' => 'D1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Diploma 3',
                'kode' => 'D3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Diploma 4',
                'kode' => 'D4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Tamat SD Sederajat',
                'kode' => 'SD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'SLTP Sederajat',
                'kode' => 'SMP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'SLTA Sederajat',
                'kode' => 'SMA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($jenjangPendidikanData as $data) {
            JenjangPendidikan::create($data);
        }
    }
}
