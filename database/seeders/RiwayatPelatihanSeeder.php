<?php

namespace Database\Seeders;

use App\Models\Camaba\FormulirF07\PelatihanProfesional;
use App\Models\Rpl\Formulir;
use Illuminate\Database\Seeder;

class RiwayatPelatihanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $formulir = Formulir::inRandomOrder()->first();

        $data = [
            [
                'formulir_id' => $formulir->id,
                'tahun' => '2023',
                'tanggal_mulai' => '2023-01-10',
                'tanggal_selesai' => '2023-01-20',
                'nama_pelatihan' => 'Pelatihan Kepemimpinan',
                'jenis' => 'DN',
                'jangka_waktu' => '10',
                'penyelenggara' => 'LPK Leadership',
                'tempat' => 'Jakarta',
                'bukti_pelatihan' => 'files/dummies.pdf',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'formulir_id' => $formulir->id,
                'tahun' => '2022',
                'tanggal_mulai' => '2022-05-15',
                'tanggal_selesai' => '2022-05-18',
                'nama_pelatihan' => 'Workshop Public Speaking',
                'jenis' => 'LN',
                'jangka_waktu' => '3',
                'penyelenggara' => 'Public Speaking International',
                'tempat' => 'Singapore',
                'bukti_pelatihan' => 'files/dummies.pdf',
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        foreach ($data as $key => $value) {
            $riwayat = new PelatihanProfesional;
            $riwayat->seed = true;
            $riwayat->fill($value);
            $riwayat->save();
        }
    }
}
