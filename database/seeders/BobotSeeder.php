<?php

namespace Database\Seeders;

use App\Models\Master\BobotNilai;
use Illuminate\Database\Seeder;

class BobotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bobot = [
            [
                'nilai_min' => 0,
                'nilai_max' => 59.99,
                'nilai_huruf' => 'E',
                'is_active' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'nilai_min' => 60,
                'nilai_max' => 69.99,
                'nilai_huruf' => 'D',
                'is_active' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'nilai_min' => 70,
                'nilai_max' => 79.99,
                'nilai_huruf' => 'C',
                'is_active' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'nilai_min' => 80,
                'nilai_max' => 89.99,
                'nilai_huruf' => 'B',
                'is_active' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'nilai_min' => 90,
                'nilai_max' => 100,
                'nilai_huruf' => 'A',
                'is_active' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        foreach ($bobot as $data) {
            BobotNilai::create($data);
        }
    }
}
