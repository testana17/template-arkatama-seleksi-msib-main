<?php

namespace Database\Seeders;

use App\Models\Akademik\TahunAjaran;
use App\Models\Rpl\MatakuliahAsesor;
use App\Models\Rpl\MatakuliahSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MatakuliahSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $listMK = MatakuliahAsesor::limit(11)->get();
        $data = [];
        $tahun_ajaran = TahunAjaran::getCurrent();

        foreach ($listMK as $key => $mk) {
            $data[$key]['id'] = Str::uuid();
            $data[$key]['matakuliah_id'] = $mk->matakuliah->id;
            $data[$key]['tahun_ajaran_id'] = $tahun_ajaran['id'];
            $data[$key]['sks_tatap_muka'] = $mk->matakuliah->sks_tatap_muka;
            $data[$key]['sks_praktek'] = $mk->matakuliah->sks_praktek;
            $data[$key]['sks_praktek_lapangan'] = $mk->matakuliah->sks_praktek_lapangan;
            $data[$key]['sks_simulasi'] = $mk->matakuliah->sks_simulasi;
            $data[$key]['sks_praktikum'] = $mk->matakuliah->sks_praktikum;
            $data[$key]['updated_at'] = now();
        }
        MatakuliahSetting::insert($data);
    }
}
