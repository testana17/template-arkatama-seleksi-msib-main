<?php

namespace Database\Seeders;

use App\Models\Akademik\TahunAjaran;
use App\Models\Master\Provinsi;
use App\Models\Rpl\ProdiPilihan;
use App\Models\Rpl\Register;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RegisterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $registerData = [];

        for ($i = 1; $i <= 10; $i++) {
            $user_id = $i + 13;
            $kode_pendaftaran = 'KP'.rand(100000, 999999);
            $nik = '351'.rand(1000000000000, 9999999999999);
            $asal_instansi = 'SMA Negeri '.rand(1, 99).' Malang';
            $nama_lengkap = User::find($user_id)->name;
            $email = User::find($user_id)->email;
            $nomor_telepon = '62'.rand(100000000, 999999999);

            $prodi = ProdiPilihan::inRandomOrder();

            $registerData[] = [
                'id' => Str::uuid()->toString(),
                'prodi_pilihan_id' => $prodi->value('id'),
                'kode_pendaftaran' => $kode_pendaftaran,
                'nik' => $nik,
                'user_id' => $user_id,
                'tahun_ajaran_id' => TahunAjaran::getCurrent()['id'],
                'prodi_id' => $prodi->value('prodi_id'),
                'asal_instansi' => $asal_instansi,
                'provinsi_id' => Provinsi::inRandomOrder()->value('id'),
                'nama_lengkap' => $nama_lengkap,
                'email' => $email,
                'nomor_telepon' => $nomor_telepon,
                'is_active' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ];
        }

        $registerData[count($registerData) - 1]['tahun_ajaran_id'] = TahunAjaran::getCurrent()['id'];

        foreach ($registerData as $data) {
            Register::create($data);
        }
    }
}
