<?php

namespace Database\Seeders;

use App\Models\Akademik\TahunAjaran;
use Illuminate\Database\Seeder;

class TahunAjaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $tahunAjaranData = [
            [
                'tahun_ajaran' => 'Genap 2023/2024',
                'kode_tahun_ajaran' => '20232',
                'tanggal_mulai' => '2023-07-01',
                'tanggal_selesai' => '2023-12-31',
                'is_active' => '0',
                'is_current' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tahun_ajaran' => 'Ganjil 2024/2025',
                'kode_tahun_ajaran' => '20241',
                'tanggal_mulai' => '2024-01-01',
                'tanggal_selesai' => '2024-06-30',
                'is_active' => '1',
                'is_current' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($tahunAjaranData as $data) {
            TahunAjaran::create($data);
        }
    }
}
