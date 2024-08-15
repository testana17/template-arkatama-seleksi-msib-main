<?php

namespace Database\Seeders;

use App\Models\Akademik\ProgramStudi;
use App\Models\Rpl\Matakuliah;
use Illuminate\Database\Seeder;

class MatakuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $columns = [
            'kode_mk',
            'nama_mk',
            'sks_tatap_muka',
            'sks_praktek',
            'sks_praktek_lapangan',
            'sks_simulasi',
            'sks_praktikum',
            'prodi_id',
            'created_by',
            'updated_by',
        ];

        $data = [
            [
                'kode_mk' => 'PGSD001',
                'nama_mk' => 'Bahasa Indonesia',
                'sks_tatap_muka' => 2,
                'sks_praktek' => 2,
                'sks_praktek_lapangan' => 0,
                'sks_simulasi' => 0,
                'sks_praktikum' => 0,
                'prodi_id' => ProgramStudi::where('singkatan', 'PGSD')->first()->id,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'kode_mk' => 'BK001',
                'nama_mk' => 'Dasar-dasar Bimbingan Dan Konseling Komprehensif',
                'sks_tatap_muka' => 2,
                'sks_praktek' => 2,
                'sks_praktek_lapangan' => 0,
                'sks_simulasi' => 0,
                'sks_praktikum' => 0,
                'prodi_id' => ProgramStudi::where('singkatan', 'BK')->first()->id,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'kode_mk' => 'TP001',
                'nama_mk' => 'Ilmu Pendidikan',
                'sks_tatap_muka' => 2,
                'sks_praktek' => 2,
                'sks_praktek_lapangan' => 0,
                'sks_simulasi' => 0,
                'sks_praktikum' => 0,
                'prodi_id' => ProgramStudi::where('singkatan', 'TP')->first()->id,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'kode_mk' => 'BK002',
                'nama_mk' => 'Pengantar Konseling',
                'sks_tatap_muka' => 2,
                'sks_praktek' => 2,
                'sks_praktek_lapangan' => 0,
                'sks_simulasi' => 0,
                'sks_praktikum' => 0,
                'prodi_id' => ProgramStudi::where('singkatan', 'BK')->first()->id,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'kode_mk' => 'PGSD002',
                'nama_mk' => 'Pendidikan Agama Islam',
                'sks_tatap_muka' => 2,
                'sks_praktek' => 2,
                'sks_praktek_lapangan' => 0,
                'sks_simulasi' => 0,
                'sks_praktikum' => 0,
                //ambil program studi kedua
                'prodi_id' => ProgramStudi::where('singkatan', 'PGSD')->first()->id,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'kode_mk' => 'AP001',
                'nama_mk' => 'Psikologi Pendidikan',
                'sks_tatap_muka' => 2,
                'sks_praktek' => 2,
                'sks_praktek_lapangan' => 0,
                'sks_simulasi' => 0,
                'sks_praktikum' => 0,
                'prodi_id' => ProgramStudi::where('singkatan', 'AP')->first()->id,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'kode_mk' => 'PLS001',
                'nama_mk' => 'Filsafat dan Teori Pendidikan',
                'sks_tatap_muka' => 2,
                'sks_praktek' => 2,
                'sks_praktek_lapangan' => 0,
                'sks_simulasi' => 0,
                'sks_praktikum' => 0,
                'prodi_id' => ProgramStudi::where('singkatan', 'PLS')->first()->id,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'kode_mk' => 'PGSD003',
                'nama_mk' => 'Sosiologi dan Antropologi ',
                'sks_tatap_muka' => 2,
                'sks_praktek' => 2,
                'sks_praktek_lapangan' => 0,
                'sks_simulasi' => 0,
                'sks_praktikum' => 0,
                'prodi_id' => ProgramStudi::where('singkatan', 'PGSD')->first()->id,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'kode_mk' => 'TI001',
                'nama_mk' => 'Pemrograman Mobile',
                'sks_tatap_muka' => 2,
                'sks_praktek' => 2,
                'sks_praktek_lapangan' => 0,
                'sks_simulasi' => 0,
                'sks_praktikum' => 0,
                'prodi_id' => ProgramStudi::where('singkatan', 'TI')->first()->id,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'kode_mk' => 'TI002',
                'nama_mk' => 'Pemrograman Web',
                'sks_tatap_muka' => 2,
                'sks_praktek' => 2,
                'sks_praktek_lapangan' => 0,
                'sks_simulasi' => 0,
                'sks_praktikum' => 0,
                'prodi_id' => ProgramStudi::where('singkatan', 'TI')->first()->id,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'kode_mk' => 'TI003',
                'nama_mk' => 'Pemrograman Dasar',
                'sks_tatap_muka' => 2,
                'sks_praktek' => 2,
                'sks_praktek_lapangan' => 0,
                'sks_simulasi' => 0,
                'sks_praktikum' => 0,
                'prodi_id' => ProgramStudi::where('singkatan', 'TI')->first()->id,
                'created_by' => 1,
                'updated_by' => 1,
            ],

        ];

        foreach ($data as $key => $value) {
            Matakuliah::create(array_combine($columns, $value));
        }
    }
}
