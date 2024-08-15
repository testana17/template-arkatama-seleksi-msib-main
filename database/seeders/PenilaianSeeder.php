<?php

namespace Database\Seeders;

use App\Models\Asesor\DetailPenilaian;
use App\Models\Asesor\Penilaian;
use App\Models\Rpl\Asesor;
use App\Models\Rpl\Formulir;
use App\Models\Rpl\Matakuliah;
use App\Models\Rpl\MatakuliahAsesor;
use Illuminate\Database\Seeder;

class PenilaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Penilaian::create([
            'formulir_id' => Formulir::first()->id,
            'matkul_id' => Matakuliah::first()->id,
            'tingkat_kemampuan' => 'Sangat Baik',
            'status_kelulusan' => 'L',
            'nilai' => 'A',
            'is_valid' => '1',
            'rekomendasi' => null,
            'created_by' => Formulir::first()->register->user_id,
            'updated_by' => Formulir::first()->register->user_id,
            'validated_by' => Asesor::first()->user_id,
            'created_at' => now(),
        ]);

        DetailPenilaian::create([
            'penilaian_id' => Penilaian::first()->id,
            'matkul_id' => Penilaian::first()->matkul_id,
            'nilai_angka' => 95,
            'nilai_huruf' => 'A',
            'matkul_asesor_id' => MatakuliahAsesor::first()->id,
            'status_kelulusan' => Penilaian::first()->status_kelulusan,
            'is_valid' => Penilaian::first()->is_valid,
            'is_asli' => '2',
            'is_terkini' => '2',
            'is_cukup' => '2',
            'created_by' => Asesor::first()->user_id,
            'updated_by' => Asesor::first()->user_id,
            'created_at' => now(),
        ]);
    }
}
