<?php

namespace Database\Seeders;

use App\Models\Asesor\Penilaian;
use App\Models\Camaba\FormulirF02\FormulirMatakuliahCpm;
use App\Models\Rpl\AsesorPeserta;
use App\Models\Rpl\Formulir;
use App\Models\Rpl\MatakuliahSetting;
use Illuminate\Database\Seeder;

class FormulirMKSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $availableMK = MatakuliahSetting::with(['matakuliah', 'matakuliah.cpm', 'matakuliah.asesor'])->has('matakuliah.cpm')->get();
        $formulirCamaba1 = Formulir::where('nama_lengkap', 'Camaba 1')->first(['id']);
        foreach ($availableMK as $mk) {
            $mk->matakuliah->cpm->each(function ($cpm) use ($formulirCamaba1) {
                FormulirMatakuliahCpm::create([
                    'formulir_id' => $formulirCamaba1->id,
                    'matkul_id' => $cpm->matkul_id,
                    'matkul_cpm_id' => $cpm->id,
                    'keterangan' => '',
                    'created_by' => '1',
                    'updated_by' => '1',
                ]);
            });

            foreach ($mk->matakuliah->asesor as $asesor) {
                $mappingAsesor = AsesorPeserta::withTrashed()->firstOrCreate([
                    'formulir_id' => $formulirCamaba1->id,
                    'matkul_asesor_id' => $asesor->id,
                ], [
                    'formulir_id' => $formulirCamaba1->id,
                    'matkul_asesor_id' => $asesor->id,
                    'catatan' => '',
                ]);
                if ($mappingAsesor->trashed()) {
                    $mappingAsesor->restore();
                }
            }
            Penilaian::create([
                'formulir_id' => $formulirCamaba1->id,
                'matkul_id' => $mk->matakuliah_id,
            ]);
        }
    }
}
