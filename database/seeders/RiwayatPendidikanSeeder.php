<?php

namespace Database\Seeders;

use App\Models\Camaba\FormulirF07\RiwayatPendidikan;
use App\Models\Master\JenjangPendidikan;
use App\Models\Rpl\Formulir;
use Illuminate\Database\Seeder;

class RiwayatPendidikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $formulir = Formulir::inRandomOrder()->first();
        $jenjang = JenjangPendidikan::inRandomOrder()->first();

        $data = [
            [
                'formulir_id' => $formulir->id,
                'jenjang_pendidikan_id' => $jenjang->id,
                'nama_institusi' => 'Sekolah A',
                'tahun_lulus' => '2020',
                'jurusan' => 'Teknik Informatika',
                'bukti_ijazah' => 'files/dummies.pdf',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'formulir_id' => $formulir->id,
                'jenjang_pendidikan_id' => $jenjang->id,
                'nama_institusi' => 'Sekolah B',
                'tahun_lulus' => '2018',
                'jurusan' => 'Manajemen',
                'bukti_ijazah' => 'files/dummies.pdf',
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        foreach ($data as $key => $value) {
            $riwayat = new RiwayatPendidikan;
            $riwayat->seed = true;
            $riwayat->fill($value);
            $riwayat->save();
        }
    }
}
