<?php

namespace Database\Seeders;

use App\Models\Camaba\FormulirF07\Pekerjaan;
use App\Models\Rpl\Formulir;
use Illuminate\Database\Seeder;

class RiwayatPekerjaanSeeder extends Seeder
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
                'tanggal_masuk' => '2024-01-01',
                'tanggal_keluar' => '2024-06-01',
                'nama_perusahaan' => 'PT. Maju Mundur',
                'jabatan' => 'Software Engineer',
                'uraian_pekerjaan' => 'Mengembangkan dan memelihara sistem perangkat lunak.',
                'bukti_pekerjaan' => 'files/dummies.pdf',
                'alamat_perusahaan' => 'Jl. Raya Kebenaran No. 42, Jakarta',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'formulir_id' => $formulir->id,
                'tanggal_masuk' => '2024-07-01',
                'tanggal_keluar' => null,
                'nama_perusahaan' => 'PT. Berkah Selalu',
                'jabatan' => 'Data Analyst',
                'uraian_pekerjaan' => 'Menganalisis data penjualan dan memberikan laporan bulanan.',
                'bukti_pekerjaan' => 'files/dummies.pdf',
                'alamat_perusahaan' => 'Jl. Pintu Air No. 3, Bandung',
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        foreach ($data as $key => $value) {
            $riwayat = new Pekerjaan;
            $riwayat->seed = true;
            $riwayat->fill($value);
            $riwayat->save();
        }
    }
}
