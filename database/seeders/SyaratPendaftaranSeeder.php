<?php

namespace Database\Seeders;

use App\Models\Akademik\ProgramStudi;
use App\Models\Akademik\TahunAjaran;
use App\Models\Rpl\SyaratPendaftaran;
use Illuminate\Database\Seeder;

class SyaratPendaftaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahunAjaranId = TahunAjaran::getCurrent()['id'];
        $prodiIds = ProgramStudi::pluck('id')->toArray();

        foreach ($prodiIds as $prodiId) {
            $arrayList = [
                [
                    'persyaratan' => 'Kartu Keluarga',
                    'keterangan' => 'Mohon Upload Kartu Keluarga dengan format .jpg, .jpeg, .png, .pdf dan ukuran maksimal 2MB',
                ],
                [
                    'persyaratan' => 'Ijazah',
                    'keterangan' => 'Mohon Upload Ijazah dengan format .pdf dan ukuran maksimal 2MB',
                ],
                [
                    'persyaratan' => 'Transkrip Nilai',
                    'keterangan' => 'Mohon Upload Transkrip Nilai dengan format .pdf dan ukuran maksimal 2MB',
                ],
                [
                    'persyaratan' => 'Foto',
                    'keterangan' => 'Mohon Upload Foto dengan format .jpg, .jpeg, .png dan ukuran maksimal 2MB',
                ],
                [
                    'persyaratan' => 'Surat Keterangan Sehat',
                    'keterangan' => 'Mohon Upload Surat Keterangan Sehat dengan format .pdf dan ukuran maksimal 2MB',
                ],
                [
                    'persyaratan' => 'Surat Pernyataan Taat Aturan Universitas',
                    'keterangan' => 'Mohon Upload Surat Pernyataan Taat Aturan Universitas dengan format .pdf dan ukuran maksimal 2MB',
                ],
                [
                    'persyaratan' => 'Surat Pernyataan Bersedia Mengikuti Pendidikan di Universitas',
                    'keterangan' => 'Mohon Upload Surat Pernyataan Bersedia Mengikuti Pendidikan di Universitas dengan format .pdf dan ukuran maksimal 2MB',
                ],
            ];

            foreach ($arrayList as $array) {
                $persyaratan = $array['persyaratan'];
                $keterangan = $array['keterangan'];

                SyaratPendaftaran::create([
                    'tahun_ajaran_id' => $tahunAjaranId,
                    'prodi_id' => $prodiId,
                    'persyaratan' => $persyaratan,
                    'keterangan' => $keterangan,
                    'created_by' => 1,
                    'updated_by' => 1,
                ]);
            }
        }
    }
}
