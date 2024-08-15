<?php

namespace Database\Seeders;

use App\Models\Camaba\FormulirF07\Penghargaan;
use App\Models\Rpl\Formulir;
use Illuminate\Database\Seeder;

class RiwayatPenghargaanSeeder extends Seeder
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
                'nama_penghargaan' => 'Juara 1 Lomba Matematika',
                'pemberi' => 'Universitas XYZ',
                'tingkat' => $tingkat,
                'bukti_penghargaan' => 'files/dummies.pdf',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'formulir_id' => $formulir->id,
                'tahun' => '2021',
                'nama_penghargaan' => 'Pemenang Olimpiade Fisika Tingkat Nasional',
                'pemberi' => 'Kementerian Pendidikan',
                'tingkat' => $tingkat,
                'bukti_penghargaan' => 'files/dummies.pdf',
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        foreach ($data as $key => $value) {
            $riwayat = new Penghargaan;
            $riwayat->seed = true;
            $riwayat->fill($value);
            $riwayat->save();
        }
    }
}
