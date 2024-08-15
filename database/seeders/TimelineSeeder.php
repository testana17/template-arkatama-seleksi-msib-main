<?php

namespace Database\Seeders;

use App\Models\Akademik\TahunAjaran;
use App\Models\Cms\Timeline;
use Illuminate\Database\Seeder;

class TimelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $tahunAjaran = TahunAjaran::getCurrent();
        $timeline = [
            [
                'tahun_ajaran_id' => $tahunAjaran['id'],
                'tanggal_mulai_pendaftaran' => $tahunAjaran['tanggal_mulai'],
                'tanggal_selesai_pendaftaran' => $tahunAjaran['tanggal_selesai'],
                'tanggal_mulai_administrasi' => now(),
                'tanggal_selesai_administrasi' => $tahunAjaran['tanggal_selesai'],
                'tanggal_mulai_assesmen' => now(),
                'tanggal_seleksi_evaluasi_diri' => now()->addDays(15),
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        foreach ($timeline as $data) {
            Timeline::create($data);
        }
    }
}
