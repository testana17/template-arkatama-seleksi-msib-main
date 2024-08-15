<?php

namespace Database\Seeders;

use App\Models\Akademik\Fakultas;
use App\Models\Akademik\PerguruanTinggi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FakultasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $website = PerguruanTinggi::inRandomOrder()->value('website');
        $parsedUrl = parse_url($website);
        $domain = $parsedUrl['host'];

        $fakultasData = [
            [
                'perguruan_tinggi_id' => PerguruanTinggi::inRandomOrder()->value('id'),
                'kode' => 'PEN01',
                'nama_fakultas' => 'Fakultas Pendidikan',
                'singkatan' => 'FP',
                'nama_inggris' => 'Faculty of Education',
                'alamat' => 'Jl. Raya Malang No. 1',
                'kota' => 'Malang',
                'telepon' => '62341123456',
                'fax' => '62341123456',
                'description' => 'Fakultas Pendidikan Universitas',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'perguruan_tinggi_id' => PerguruanTinggi::inRandomOrder()->value('id'),
                'kode' => 'TEK01',
                'nama_fakultas' => 'Fakultas Teknik',
                'singkatan' => 'FT',
                'nama_inggris' => 'Faculty of Engineering',
                'alamat' => 'Jl. Raya Malang No. 2',
                'kota' => 'Malang',
                'telepon' => '62341100987',
                'fax' => '62341100987',
                'description' => 'Fakultas Teknik Universitas',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        foreach ($fakultasData as $data) {
            $userPayload = [
                'name' => 'Admin '.$data['nama_fakultas'],
                'email' => strtolower($data['kode']).'@'.$domain,
                'password' => Hash::make(strtolower($data['kode'])),
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
            $user->assignRole('admin-fakultas');

            // add user id to data
            $data['user_id'] = $user->id;
            $data['email'] = $userPayload['email'];
            $data['website'] = 'https://'.strtolower($data['singkatan']).'.'.$domain;
            // create fakultas
            Fakultas::create($data);
        }
    }
}
