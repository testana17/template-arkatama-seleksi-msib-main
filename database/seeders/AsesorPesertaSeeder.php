<?php

namespace Database\Seeders;

use App\Models\Asesor\Penilaian;
use App\Models\Camaba\FormulirF02\FormulirMatakuliahCpm;
use App\Models\Rpl\AsesorPeserta;
use App\Models\Rpl\Formulir;
use App\Models\Rpl\Matakuliah;
use Illuminate\Database\Seeder;

class AsesorPesertaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $formulir = Formulir::all();

        $matakuliah = Matakuliah::whereIn('nama_mk', [
            'Pemrograman Mobile',
            'Pemrograman Web',
            'Pemrograman Dasar',
        ])->with(['cpm', 'matakuliahAsesor'])->get();

        // if(!file_exists(storage_path('app/public/bukti-pendukung/bukti_pelatihan_profisiensi_dummy.pdf'))){
        //     copy(public_path('docs/dummy/bukti_pelatihan_profisiensi_dummy.pdf'), storage_path('app/public/bukti-pendukung/bukti_pelatihan_profisiensi_dummy.pdf'));
        // }

        foreach ($formulir as $form) {
            auth()->setUser($form->register->user);
            foreach ($matakuliah as $mk) {
                $mk->cpm->each(function ($cpm) use ($form) {
                    $formulirMKCPM = FormulirMatakuliahCpm::withTrashed()->firstOrCreate([
                        'formulir_id' => $form->id,
                        'matkul_id' => $cpm->matkul_id,
                        'matkul_cpm_id' => $cpm->id,
                    ], [
                        'formulir_id' => $form->id,
                        'matkul_id' => $cpm->matkul_id,
                        'matkul_cpm_id' => $cpm->id,
                        // "file_pendukung" => 'bukti-pendukung/bukti_pelatihan_profisiensi_dummy.pdf',
                        'keterangan' => '',
                    ]);
                    if ($formulirMKCPM->trashed()) {
                        $formulirMKCPM->restore();
                    }
                });

                $penilaian = Penilaian::withTrashed()->firstOrCreate([
                    'formulir_id' => $form->id,
                    'matkul_id' => $mk->id,
                ], [
                    'formulir_id' => $form->id,
                    'matkul_id' => $mk->id,
                    'tingkat_kemampuan' => 'Baik',
                ]);

                if ($penilaian->trashed()) {
                    $penilaian->restore();
                }

                $mk->matakuliahAsesor->each(function ($mkasesor) use ($form) {
                    AsesorPeserta::create([
                        'formulir_id' => $form->id,
                        'matkul_asesor_id' => $mkasesor->id,
                    ]);
                });
            }
        }
    }
}
