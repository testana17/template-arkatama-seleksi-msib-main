<?php

namespace Database\Seeders;

use App\Models\Akademik\Fakultas;
use App\Models\Akademik\PerguruanTinggi;
use App\Models\Akademik\ProgramStudi;
use App\Models\Master\JenjangPendidikan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $website = PerguruanTinggi::inRandomOrder()->value('website');
        $parsedUrl = parse_url($website);
        $domain = $parsedUrl['host'];

        $prodiData = [
            [
                'jenjang_pendidikan_id' => JenjangPendidikan::where('kode', 'S1')->value('id'),
                'fakultas_id' => Fakultas::where('kode', 'PEN01')->value('id'),
                'perguruan_tinggi_id' => PerguruanTinggi::inRandomOrder()->value('id'),
                'kode_dikti' => '11345',
                'kode' => '12345',
                'nama_prodi' => 'Bimbingan dan Konseling',
                'singkatan' => 'BK',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'jenjang_pendidikan_id' => JenjangPendidikan::where('kode', 'S1')->value('id'),
                'fakultas_id' => Fakultas::where('kode', 'PEN01')->value('id'),
                'perguruan_tinggi_id' => PerguruanTinggi::inRandomOrder()->value('id'),
                'kode_dikti' => '51321',
                'kode' => '54321',
                'nama_prodi' => 'Teknologi Pendidikan',
                'singkatan' => 'TP',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'jenjang_pendidikan_id' => JenjangPendidikan::where('kode', 'S1')->value('id'),
                'fakultas_id' => Fakultas::where('kode', 'PEN01')->value('id'),
                'perguruan_tinggi_id' => PerguruanTinggi::inRandomOrder()->value('id'),
                'kode_dikti' => '91765',
                'kode' => '98765',
                'nama_prodi' => 'Administrasi Pendidikan',
                'singkatan' => 'AP',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'jenjang_pendidikan_id' => JenjangPendidikan::where('kode', 'S1')->value('id'),
                'fakultas_id' => Fakultas::where('kode', 'PEN01')->value('id'),
                'perguruan_tinggi_id' => PerguruanTinggi::inRandomOrder()->value('id'),
                'kode_dikti' => '81382',
                'kode' => '87382',
                'nama_prodi' => 'Pendidikan Luar Sekolah',
                'singkatan' => 'PLS',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'jenjang_pendidikan_id' => JenjangPendidikan::where('kode', 'S1')->value('id'),
                'fakultas_id' => Fakultas::where('kode', 'PEN01')->value('id'),
                'perguruan_tinggi_id' => PerguruanTinggi::inRandomOrder()->value('id'),
                'kode_dikti' => '21920',
                'kode' => '23920',
                'nama_prodi' => 'Pendidikan Guru Sekolah Dasar',
                'singkatan' => 'PGSD',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'jenjang_pendidikan_id' => JenjangPendidikan::where('kode', 'S1')->value('id'),
                'fakultas_id' => Fakultas::where('kode', 'TEK01')->value('id'),
                'perguruan_tinggi_id' => PerguruanTinggi::inRandomOrder()->value('id'),
                'kode_dikti' => '19703',
                'kode' => '19273',
                'nama_prodi' => 'Teknik Informatika',
                'singkatan' => 'TI',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        foreach ($prodiData as $data) {
            $userPayload = [
                'name' => 'Admin Prodi '.$data['nama_prodi'],
                'email' => $data['kode'].'@'.$domain,
                'password' => Hash::make($data['kode']),
            ];
            // check if email already exists
            $user = User::where('email', $userPayload['email'])->first();
            if ($user) {
                continue;
            }

            // create user and assign role
            $user = new User;
            $user->fill($userPayload);
            $user->email_verified_at = now();
            $user->save();
            $user->assignRole('admin-prodi');

            // add user id to data
            $data['user_id'] = $user->id;
            // create program studi
            ProgramStudi::create($data);
        }
    }
}
