<?php

namespace Database\Seeders;

use App\Models\Akademik\ProgramStudi;
use App\Models\Akademik\TahunAjaran;
use App\Models\Cms\Timeline;
use App\Models\Rpl\JadwalPendaftaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JadwalPendaftaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahunAjaran = TahunAjaran::getCurrent()['id'];
        $jadwalPendaftaranData = [
            [
                'id' => Str::uuid()->toString(),
                'prodi_id' => ProgramStudi::inRandomOrder()->value('id'),
                'tahun_ajaran_id' => $tahunAjaran,
                'tanggal_mulai' => Timeline::where('tahun_ajaran_id', $tahunAjaran)
                    ->value('tanggal_mulai_pendaftaran'),
                'tanggal_selesai' => Timeline::where('tahun_ajaran_id', $tahunAjaran)
                    ->value('tanggal_selesai_pendaftaran'),
                'tanggal_pengumuman' => '2024-06-01',
                'kuota' => 100,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'id' => Str::uuid()->toString(),
                'prodi_id' => ProgramStudi::inRandomOrder()->value('id'),
                'tahun_ajaran_id' => $tahunAjaran,
                'tanggal_mulai' => '2024-04-01',
                'tanggal_selesai' => '2024-04-30',
                'tanggal_pengumuman' => '2024-05-15',
                'kuota' => 100,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'id' => Str::uuid()->toString(),
                'prodi_id' => ProgramStudi::inRandomOrder()->value('id'),
                'tahun_ajaran_id' => $tahunAjaran,
                'tanggal_mulai' => '2024-04-01',
                'tanggal_selesai' => '2024-04-30',
                'tanggal_pengumuman' => '2024-05-15',
                'kuota' => 100,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'id' => Str::uuid()->toString(),
                'prodi_id' => ProgramStudi::inRandomOrder()->value('id'),
                'tahun_ajaran_id' => $tahunAjaran,
                'tanggal_mulai' => '2024-04-01',
                'tanggal_selesai' => '2024-04-30',
                'tanggal_pengumuman' => '2024-05-15',
                'kuota' => 100,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'id' => Str::uuid()->toString(),
                'prodi_id' => ProgramStudi::inRandomOrder()->value('id'),
                'tahun_ajaran_id' => $tahunAjaran,
                'tanggal_mulai' => '2024-04-01',
                'tanggal_selesai' => '2024-04-30',
                'tanggal_pengumuman' => '2024-05-15',
                'kuota' => 100,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'id' => Str::uuid()->toString(),
                'prodi_id' => ProgramStudi::inRandomOrder()->value('id'),
                'tahun_ajaran_id' => $tahunAjaran,
                'tanggal_mulai' => '2024-04-01',
                'tanggal_selesai' => '2024-04-30',
                'tanggal_pengumuman' => '2024-05-15',
                'kuota' => 100,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'id' => Str::uuid()->toString(),
                'prodi_id' => ProgramStudi::inRandomOrder()->value('id'),
                'tahun_ajaran_id' => $tahunAjaran,
                'tanggal_mulai' => '2024-04-01',
                'tanggal_selesai' => '2024-04-30',
                'tanggal_pengumuman' => '2024-05-15',
                'kuota' => 100,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        foreach ($jadwalPendaftaranData as $data) {
            JadwalPendaftaran::create($data);
        }
    }
}
