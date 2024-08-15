<?php

namespace Database\Seeders;

use App\Models\Rpl\Asesor;
use App\Models\Rpl\Matakuliah;
use App\Models\Rpl\MatakuliahAsesor;
use Illuminate\Database\Seeder;

class MatakuliahAsesorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'matkul_id' => Matakuliah::where('kode_mk', 'TI001')->first()->id,
                'asesor_id' => Asesor::where('email', 'asesor@arkatama.test')->first()->id,
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => null,
            ],
            [
                'matkul_id' => Matakuliah::where('kode_mk', 'TI002')->first()->id,
                'asesor_id' => Asesor::where('email', 'asesor3@arkatama.test')->first()->id,
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => null,
            ],
            [
                'matkul_id' => Matakuliah::where('kode_mk', 'BK001')->first()->id,
                'asesor_id' => Asesor::where('email', 'asesor4@arkatama.test')->first()->id,
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => null,
            ],
            [
                'matkul_id' => Matakuliah::where('kode_mk', 'BK002')->first()->id,
                'asesor_id' => Asesor::where('email', 'asesor2@arkatama.test')->first()->id,
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => null,
            ],
            [
                'matkul_id' => Matakuliah::where('kode_mk', 'PGSD001')->first()->id,
                'asesor_id' => Asesor::where('email', 'asesor5@arkatama.test')->first()->id,
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => null,
            ],
            [
                'matkul_id' => Matakuliah::where('kode_mk', 'PGSD002')->first()->id,
                'asesor_id' => Asesor::where('email', 'asesor6@arkatama.test')->first()->id,
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => null,
            ],
            [
                'matkul_id' => Matakuliah::where('kode_mk', 'TP001')->first()->id,
                'asesor_id' => Asesor::where('email', 'asesor7@arkatama.test')->first()->id,
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => null,
            ],
            [
                'matkul_id' => Matakuliah::where('kode_mk', 'AP001')->first()->id,
                'asesor_id' => Asesor::where('email', 'asesor8@arkatama.test')->first()->id,
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => null,
            ],
            [
                'matkul_id' => Matakuliah::where('kode_mk', 'PLS001')->first()->id,
                'asesor_id' => Asesor::where('email', 'asesor9@arkatama.test')->first()->id,
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => null,
            ],
            [
                'matkul_id' => Matakuliah::where('kode_mk', 'PGSD003')->first()->id,
                'asesor_id' => Asesor::where('email', 'asesor10@arkatama.test')->first()->id,
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => null,
            ],
            [
                'matkul_id' => Matakuliah::where('kode_mk', 'TI003')->first()->id,
                'asesor_id' => Asesor::where('email', 'asesor11@arkatama.test')->first()->id,
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => null,
            ],
            [
                'matkul_id' => Matakuliah::where('kode_mk', 'TI001')->first()->id,
                'asesor_id' => Asesor::where('email', 'asesor12@arkatama.test')->first()->id,
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => null,
            ],
            // [
            //     'matkul_id' => Matakuliah::where('kode_mk', 'TI002')->first()->id,
            //     'asesor_id' => Asesor::where('email', 'asesor13@arkatama.test')->first()->id,
            //     'created_by' => 1,
            //     'updated_by' => 1,
            //     'deleted_by' => null,
            // ],
            [
                'matkul_id' => Matakuliah::where('kode_mk', 'BK001')->first()->id,
                'asesor_id' => Asesor::where('email', 'asesor14@arkatama.test')->first()->id,
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => null,
            ],
        ];

        foreach ($data as $item) {
            MatakuliahAsesor::create($item);
        }
    }
}
