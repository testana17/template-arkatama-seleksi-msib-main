<?php

namespace Database\Seeders;

use App\Models\Akademik\ProgramStudi;
use App\Models\Akademik\TahunAjaran;
use App\Models\Cms\Timeline;
use App\Models\Rpl\ProdiPilihan;
use Illuminate\Database\Seeder;

class ProdiPilihanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prodiPilihanData = [];

        foreach (ProgramStudi::all() as $programStudi) {
            $prodiPilihanData[] = [
                'id' => str()->uuid()->toString(),
                'prodi_id' => $programStudi->id,
                'tahun_ajaran_id' => TahunAjaran::getCurrent()['id'],
                'tanggal_mulai_pendaftaran' => Timeline::where('tahun_ajaran_id', TahunAjaran::getCurrent()['id'])->value('tanggal_mulai_pendaftaran'),
                'tanggal_selesai_pendaftaran' => Timeline::where('tahun_ajaran_id', TahunAjaran::getCurrent()['id'])->value('tanggal_selesai_pendaftaran'),
                'tanggal_mulai_administrasi' => Timeline::where('tahun_ajaran_id', TahunAjaran::getCurrent()['id'])->value('tanggal_mulai_administrasi'),
                'tanggal_selesai_administrasi' => Timeline::where('tahun_ajaran_id', TahunAjaran::getCurrent()['id'])->value('tanggal_selesai_administrasi'),
                'tanggal_pengumuman' => now(),
                'kuota_pendaftar' => 100,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ];
        }

        foreach ($prodiPilihanData as $data) {
            ProdiPilihan::create($data);
        }
    }
}
