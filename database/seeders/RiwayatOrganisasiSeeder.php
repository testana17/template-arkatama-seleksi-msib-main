<?php

namespace Database\Seeders;

use App\Models\Camaba\FormulirF07\OrganisasiProfesi;
use App\Models\Rpl\Formulir;
use Illuminate\Database\Seeder;

class RiwayatOrganisasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $formulir = Formulir::inRandomOrder()->first();
        $tingkat = collect(['internasional', 'nasional', 'provinsi', 'kabupaten_kota'])->random();

        $data = [
            [
                'formulir_id' => $formulir->id,
                'tahun' => '2022',
                'nama_organisasi' => 'Organisasi A',
                'tingkat' => $tingkat,
                'jabatan' => 'Ketua',
                'tempat' => 'Jakarta',
                'bukti_organisasi' => 'files/dummies.pdf',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'formulir_id' => $formulir->id,
                'tahun' => '2023',
                'nama_organisasi' => 'Organisasi B',
                'tingkat' => $tingkat,
                'jabatan' => 'Sekretaris',
                'tempat' => 'Bandung',
                'bukti_organisasi' => 'files/dummies.pdf',
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        foreach ($data as $key => $value) {
            $riwayat = new OrganisasiProfesi;
            $riwayat->seed = true;
            $riwayat->fill($value);
            $riwayat->save();
        }
    }
}
