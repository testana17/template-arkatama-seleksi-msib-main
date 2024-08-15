<?php

namespace Database\Seeders;

use App\Models\Asesor\Penilaian;
use App\Models\Rpl\CPM;
use App\Models\Rpl\Formulir;
use App\Models\Rpl\FormulirMatakuliahCPM;
use App\Models\Rpl\Matakuliah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FormulirMatakuliahCPMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $formulir = Formulir::inRandomOrder()->first();
        $matakuliahs = Matakuliah::where('prodi_id', $formulir->pilihan_prodi_id)->get();

        foreach ($matakuliahs as $mk) {
            $kemampuan = collect(['Sangat Baik', 'Baik', 'Cukup', 'Kurang', 'Tidak Pernah'])->random();
            $cpms = CPM::where('matkul_id', $mk->id)->get();
            foreach ($cpms as $cpm) {
                FormulirMatakuliahCPM::create([
                    'id' => Str::uuid()->toString(),
                    'formulir_id' => $formulir->id,
                    'matkul_id' => $mk->id,
                    'matkul_cpm_id' => $cpm->id,
                    'tingkat_penguasaan' => $kemampuan,
                    'file_pendukung' => 'files/dummies.pdf',
                    'keterangan' => 'Keterangan Matakuliah CPM',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'created_by' => 1,
                    'updated_by' => 1,
                    'validated_by' => 1,
                ]);
            }

            Penilaian::create([
                'id' => Str::uuid()->toString(),
                'formulir_id' => $formulir->id,
                'matkul_id' => $mk->id,
                'tingkat_kemampuan' => $kemampuan,
                'status_kelulusan' => collect(['L', 'TL'])->random(),
                'nilai' => collect(['A', 'B', 'C', 'D', 'E'])->random(),
                'is_valid' => '1',
                'rekomendasi' => collect(['Direkomendasikan', 'Tidak Direkomendasikan'])->random(),
                'created_by' => 1,
                'updated_by' => 1,
                'validated_by' => 1,
            ]);
        }
    }
}
