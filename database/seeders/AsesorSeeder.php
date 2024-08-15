<?php

namespace Database\Seeders;

use App\Models\Akademik\PerguruanTinggi;
use App\Models\Akademik\ProgramStudi;
use App\Models\Rpl\Asesor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AsesorSeeder extends Seeder
{
    public function run()
    {

        $assesorAkademisi = User::create([
            'name' => 'Ahmad Nur Ramadhani',
            'email' => 'asesor@arkatama.test',
            'email_verified_at' => now(),
            'password' => Hash::make('12345'),
            'remember_token' => Str::random(10),
        ]);

        $assesorAkademisi2 = User::create([
            'name' => 'Anis Karisma',
            'email' => 'asesor2@arkatama.test',
            'email_verified_at' => now(),
            'password' => Hash::make('12345'),
            'remember_token' => Str::random(10),
        ]);

        $assesorAkademisi3 = User::create([
            'name' => 'Dimas Pratama',
            'email' => 'asesor5@arkatama.test',
            'email_verified_at' => now(),
            'password' => Hash::make('12345'),
            'remember_token' => Str::random(10),
        ]);

        $assesorAkademisi11 = User::create([
            'name' => 'Ardi Pratama',
            'email' => 'asesor11@arkatama.test',
            'email_verified_at' => now(),
            'password' => Hash::make('12345'),
            'remember_token' => Str::random(10),
        ]);

        $assesorAkademisi7 = User::create([
            'name' => 'Michael Didimus',
            'email' => 'asesor7@arkatama.test',
            'email_verified_at' => now(),
            'password' => Hash::make('12345'),
            'remember_token' => Str::random(10),
        ]);

        $assesorPraktisi = User::create([
            'name' => 'Deden Gusti Laksana',
            'email' => 'asesor3@arkatama.test',
            'email_verified_at' => now(),
            'password' => Hash::make('12345'),
            'remember_token' => Str::random(10),
        ]);

        $assesorPraktisi2 = User::create([
            'name' => '	Diah Nafisah',
            'email' => 'asesor4@arkatama.test',
            'email_verified_at' => now(),
            'password' => Hash::make('12345'),
            'remember_token' => Str::random(10),
        ]);

        $assesorPraktisi3 = User::create([
            'name' => 'Siti Nurjanah',
            'email' => 'asesor10@arkatama.test',
            'email_verified_at' => now(),
            'password' => Hash::make('12345'),
            'remember_token' => Str::random(10),
        ]);

        $assesorPraktisi4 = User::create([
            'name' => 'Lia Agustina',
            'email' => 'asesor6@arkatama.test',
            'email_verified_at' => now(),
            'password' => Hash::make('12345'),
            'remember_token' => Str::random(10),
        ]);

        $assesorPraktisi8 = User::create([
            'name' => 'Sigit Prasetyo',
            'email' => 'asesor8@arkatama.test',
            'email_verified_at' => now(),
            'password' => Hash::make('12345'),
            'remember_token' => Str::random(10),
        ]);

        $assesorPraktisi9 = User::create([
            'name' => 'Miftahul Ulum',
            'email' => 'asesor9@arkatama.test',
            'email_verified_at' => now(),
            'password' => Hash::make('12345'),
            'remember_token' => Str::random(10),
        ]);

        $assesorPraktisi5 = User::create([
            'name' => 'Rafi Ahmad',
            'email' => 'asesor12@arkatama.test',
            'email_verified_at' => now(),
            'password' => Hash::make('12345'),
            'remember_token' => Str::random(10),
        ]);

        $assesorAkademisi4 = User::create([
            'name' => 'Teguh Prasetyo',
            'email' => 'asesor13@arkatama.test',
            'email_verified_at' => now(),
            'password' => Hash::make('12345'),
            'remember_token' => Str::random(10),
        ]);

        $assesorAkademisi5 = User::create([
            'name' => 'Rini Kusuma',
            'email' => 'asesor14@arkatama.test',
            'email_verified_at' => now(),
            'password' => Hash::make('12345'),
            'remember_token' => Str::random(10),
        ]);

        $assesorAkademisi->assignRole('asesor');
        $assesorAkademisi2->assignRole('asesor');
        $assesorAkademisi3->assignRole('asesor');
        $assesorAkademisi4->assignRole('asesor');
        $assesorAkademisi5->assignRole('asesor');
        $assesorAkademisi11->assignRole('asesor');
        $assesorAkademisi7->assignRole('asesor');
        $assesorPraktisi->assignRole('asesor');
        $assesorPraktisi2->assignRole('asesor');
        $assesorPraktisi3->assignRole('asesor');
        $assesorPraktisi4->assignRole('asesor');
        $assesorPraktisi5->assignRole('asesor');
        $assesorPraktisi8->assignRole('asesor');
        $assesorPraktisi9->assignRole('asesor');

        Asesor::create([
            'jenis_asesor' => 'akademisi',
            'nama_lengkap' => $assesorAkademisi->name,
            'user_id' => $assesorAkademisi->id,
            'tempat_lahir' => '234',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'no_hp' => '6281234563213',
            'no_fax' => '6281234563213',
            'email' => $assesorAkademisi->email,
            'alamat' => 'Jl. Raya Malang',
            'provinsi' => '15',
            'kabupaten' => '234',
            'kecamatan' => '3905',
            'kodepos' => '65100',
            'prodi_id' => ProgramStudi::where('singkatan', 'TI')->value('id'),
            'pendidikan_terakhir' => 'SLTA/Sederajat',
            'bidang_keahlian' => 'Teknologi Informasi',
            'asosiasi_profesi_keanggotaan' => 'Asosiasi Profesi',
            'asosiasi_profesi_no_anggota' => '12345',
            'akademik_pangkat' => 'Rektor',
            'akademik_jabatan' => 'Dosen',
            'akademik_nip' => '123456789012345678',
            'akademik_nidn' => '1234567890',
            'akademik_nama_pt' => PerguruanTinggi::inRandomOrder()->value('nama_perguruan_tinggi'),
            'akademik_alamat_pt' => PerguruanTinggi::inRandomOrder()->value('jalan'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Asesor::create([
            'jenis_asesor' => 'akademisi',
            'nama_lengkap' => $assesorAkademisi11->name,
            'user_id' => $assesorAkademisi11->id,
            'tempat_lahir' => '234',
            'tanggal_lahir' => '1991-03-02',
            'jenis_kelamin' => 'L',
            'no_hp' => '6281234561219',
            'no_fax' => '6281234560293',
            'email' => $assesorAkademisi11->email,
            'alamat' => 'Jl. Raya Malang',
            'provinsi' => '15',
            'kabupaten' => '234',
            'kecamatan' => '3905',
            'kodepos' => '65100',
            'prodi_id' => ProgramStudi::where('singkatan', 'TI')->value('id'),
            'pendidikan_terakhir' => 'SLTA/Sederajat',
            'bidang_keahlian' => 'Teknologi Informasi',
            'asosiasi_profesi_keanggotaan' => 'Asosiasi Profesi',
            'asosiasi_profesi_no_anggota' => '12355',
            'akademik_pangkat' => 'Rektor',
            'akademik_jabatan' => 'Dosen',
            'akademik_nip' => '123456789010344678',
            'akademik_nidn' => '1234567893',
            'akademik_nama_pt' => PerguruanTinggi::inRandomOrder()->value('nama_perguruan_tinggi'),
            'akademik_alamat_pt' => PerguruanTinggi::inRandomOrder()->value('jalan'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Asesor::create([
            'jenis_asesor' => 'akademisi',
            'nama_lengkap' => $assesorAkademisi7->name,
            'user_id' => $assesorAkademisi7->id,
            'tempat_lahir' => '234',
            'tanggal_lahir' => '1997-10-01',
            'jenis_kelamin' => 'L',
            'no_hp' => '6281234561216',
            'no_fax' => '6281234560214',
            'email' => $assesorAkademisi7->email,
            'alamat' => 'Jl. Raya Malang',
            'provinsi' => '15',
            'kabupaten' => '234',
            'kecamatan' => '3905',
            'kodepos' => '65100',
            'prodi_id' => ProgramStudi::where('singkatan', 'TP')->value('id'),
            'pendidikan_terakhir' => 'SLTA/Sederajat',
            'bidang_keahlian' => 'Teknologi Informasi',
            'asosiasi_profesi_keanggotaan' => 'Asosiasi Profesi',
            'asosiasi_profesi_no_anggota' => '11345',
            'akademik_pangkat' => 'Rektor',
            'akademik_jabatan' => 'Dosen',
            'akademik_nip' => '124456785010345678',
            'akademik_nidn' => '1274567591',
            'akademik_nama_pt' => PerguruanTinggi::inRandomOrder()->value('nama_perguruan_tinggi'),
            'akademik_alamat_pt' => PerguruanTinggi::inRandomOrder()->value('jalan'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Asesor::create([
            'jenis_asesor' => 'akademisi',
            'nama_lengkap' => $assesorAkademisi2->name,
            'user_id' => $assesorAkademisi2->id,
            'tempat_lahir' => '234',
            'tanggal_lahir' => '1994-12-01',
            'jenis_kelamin' => 'P',
            'no_hp' => '6281234567290',
            'no_fax' => '6281234561890',
            'email' => $assesorAkademisi2->email,
            'alamat' => 'Jl. Raya Malang',
            'provinsi' => '15',
            'kabupaten' => '234',
            'kecamatan' => '3905',
            'kodepos' => '65100',
            'prodi_id' => ProgramStudi::where('singkatan', 'BK')->value('id'),
            'pendidikan_terakhir' => 'SLTA/Sederajat',
            'bidang_keahlian' => 'Teknologi Informasi',
            'asosiasi_profesi_keanggotaan' => 'Asosiasi Profesi',
            'asosiasi_profesi_no_anggota' => '21345',
            'akademik_pangkat' => 'Rektor',
            'akademik_jabatan' => 'Dosen',
            'akademik_nip' => '123456709012345678',
            'akademik_nidn' => '1234597890',
            'akademik_nama_pt' => PerguruanTinggi::inRandomOrder()->value('nama_perguruan_tinggi'),
            'akademik_alamat_pt' => PerguruanTinggi::inRandomOrder()->value('jalan'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Asesor::create([
            'jenis_asesor' => 'akademisi',
            'nama_lengkap' => $assesorAkademisi3->name,
            'user_id' => $assesorAkademisi3->id,
            'tempat_lahir' => '234',
            'tanggal_lahir' => '1990-02-01',
            'jenis_kelamin' => 'L',
            'no_hp' => '6281234566890',
            'no_fax' => '6281234577890',
            'email' => $assesorAkademisi3->email,
            'alamat' => 'Jl. Raya Malang',
            'provinsi' => '15',
            'kabupaten' => '234',
            'kecamatan' => '3905',
            'kodepos' => '65100',
            'prodi_id' => ProgramStudi::where('singkatan', 'PGSD')->value('id'),
            'pendidikan_terakhir' => 'SLTA/Sederajat',
            'bidang_keahlian' => 'Teknologi Informasi',
            'asosiasi_profesi_keanggotaan' => 'Asosiasi Profesi',
            'asosiasi_profesi_no_anggota' => '12344',
            'akademik_pangkat' => 'Rektor',
            'akademik_jabatan' => 'Dosen',
            'akademik_nip' => '003456489012345678',
            'akademik_nidn' => '0034547890',
            'akademik_nama_pt' => PerguruanTinggi::inRandomOrder()->value('nama_perguruan_tinggi'),
            'akademik_alamat_pt' => PerguruanTinggi::inRandomOrder()->value('jalan'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Asesor::create([
            'jenis_asesor' => 'akademisi',
            'nama_lengkap' => $assesorAkademisi4->name,
            'user_id' => $assesorAkademisi4->id,
            'tempat_lahir' => '234',
            'tanggal_lahir' => '1993-05-01',
            'jenis_kelamin' => 'L',
            'no_hp' => '6281234560090',
            'no_fax' => '6281211577890',
            'email' => $assesorAkademisi4->email,
            'alamat' => 'Jl. Raya Malang',
            'provinsi' => '15',
            'kabupaten' => '234',
            'kecamatan' => '3905',
            'kodepos' => '65100',
            'prodi_id' => ProgramStudi::where('singkatan', 'TI')->value('id'),
            'pendidikan_terakhir' => 'SLTA/Sederajat',
            'bidang_keahlian' => 'Teknologi Informasi',
            'asosiasi_profesi_keanggotaan' => 'Asosiasi Profesi',
            'asosiasi_profesi_no_anggota' => '10304',
            'akademik_pangkat' => 'Rektor',
            'akademik_jabatan' => 'Dosen',
            'akademik_nip' => '003456400012345678',
            'akademik_nidn' => '0034511190',
            'akademik_nama_pt' => PerguruanTinggi::inRandomOrder()->value('nama_perguruan_tinggi'),
            'akademik_alamat_pt' => PerguruanTinggi::inRandomOrder()->value('jalan'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Asesor::create([
            'jenis_asesor' => 'akademisi',
            'nama_lengkap' => $assesorAkademisi5->name,
            'user_id' => $assesorAkademisi5->id,
            'tempat_lahir' => '234',
            'tanggal_lahir' => '1995-12-12',
            'jenis_kelamin' => 'L',
            'no_hp' => '6281111566890',
            'no_fax' => '6281200577890',
            'email' => $assesorAkademisi5->email,
            'alamat' => 'Jl. Raya Malang',
            'provinsi' => '15',
            'kabupaten' => '234',
            'kecamatan' => '3905',
            'kodepos' => '65100',
            'prodi_id' => ProgramStudi::where('singkatan', 'BK')->value('id'),
            'pendidikan_terakhir' => 'SLTA/Sederajat',
            'bidang_keahlian' => 'Teknologi Informasi',
            'asosiasi_profesi_keanggotaan' => 'Asosiasi Profesi',
            'asosiasi_profesi_no_anggota' => '12994',
            'akademik_pangkat' => 'Rektor',
            'akademik_jabatan' => 'Dosen',
            'akademik_nip' => '003456909012345678',
            'akademik_nidn' => '0034590890',
            'akademik_nama_pt' => PerguruanTinggi::inRandomOrder()->value('nama_perguruan_tinggi'),
            'akademik_alamat_pt' => PerguruanTinggi::inRandomOrder()->value('jalan'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Asesor::create([
            'jenis_asesor' => 'praktisi',
            'nama_lengkap' => $assesorPraktisi->name,
            'user_id' => $assesorPraktisi->id,
            'tempat_lahir' => '234',
            'tanggal_lahir' => '1992-12-01',
            'jenis_kelamin' => 'L',
            'no_hp' => '6281231267890',
            'no_fax' => '6281234567290',
            'email' => $assesorPraktisi->email,
            'alamat' => 'Jl. Raya Malang',
            'provinsi' => '15',
            'kabupaten' => '234',
            'kecamatan' => '3905',
            'kodepos' => '65100',
            'prodi_id' => ProgramStudi::where('singkatan', 'TI')->value('id'),
            'pendidikan_terakhir' => 'SLTA/Sederajat',
            'bidang_keahlian' => 'Teknologi Informasi',
            'asosiasi_profesi_keanggotaan' => 'Asosiasi Profesi',
            'asosiasi_profesi_no_anggota' => '02345',
            'profesi_pekerjaan' => 'Dokter Umum',
            'profesi_nama_instansi' => 'Rumah Sakit Sejahtera',
            'profesi_jabatan_instansi' => 'Dokter Umum',
            'profesi_alamat_instansi' => 'Jl. Jendral Sudirman No. 123',
            'profesi_telepon_instansi' => '0211234567',
            'profesi_bidang_keahlian' => 'Kesehatan Umum',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Asesor::create([
            'jenis_asesor' => 'praktisi',
            'nama_lengkap' => $assesorPraktisi5->name,
            'user_id' => $assesorPraktisi5->id,
            'tempat_lahir' => '234',
            'tanggal_lahir' => '1991-12-01',
            'jenis_kelamin' => 'L',
            'no_hp' => '6281231267330',
            'no_fax' => '6281234567790',
            'email' => $assesorPraktisi5->email,
            'alamat' => 'Jl. Raya Malang',
            'provinsi' => '15',
            'kabupaten' => '234',
            'kecamatan' => '3905',
            'kodepos' => '65100',
            'prodi_id' => ProgramStudi::where('singkatan', 'TI')->value('id'),
            'pendidikan_terakhir' => 'SLTA/Sederajat',
            'bidang_keahlian' => 'Teknologi Informasi',
            'asosiasi_profesi_keanggotaan' => 'Asosiasi Profesi',
            'asosiasi_profesi_no_anggota' => '02345',
            'profesi_pekerjaan' => 'Dokter Umum',
            'profesi_nama_instansi' => 'Rumah Sakit Sejahtera',
            'profesi_jabatan_instansi' => 'Dokter Umum',
            'profesi_alamat_instansi' => 'Jl. Jendral Sudirman No. 123',
            'profesi_telepon_instansi' => '0211234567',
            'profesi_bidang_keahlian' => 'Kesehatan Umum',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Asesor::create([
            'jenis_asesor' => 'praktisi',
            'nama_lengkap' => $assesorPraktisi8->name,
            'user_id' => $assesorPraktisi8->id,
            'tempat_lahir' => '234',
            'tanggal_lahir' => '1993-12-01',
            'jenis_kelamin' => 'L',
            'no_hp' => '6281231262390',
            'no_fax' => '6281234522290',
            'email' => $assesorPraktisi8->email,
            'alamat' => 'Jl. Raya Malang',
            'provinsi' => '15',
            'kabupaten' => '234',
            'kecamatan' => '3905',
            'kodepos' => '65100',
            'prodi_id' => ProgramStudi::where('singkatan', 'AP')->value('id'),
            'pendidikan_terakhir' => 'SLTA/Sederajat',
            'bidang_keahlian' => 'Teknologi Informasi',
            'asosiasi_profesi_keanggotaan' => 'Asosiasi Profesi',
            'asosiasi_profesi_no_anggota' => '02345',
            'profesi_pekerjaan' => 'Dokter Umum',
            'profesi_nama_instansi' => 'Rumah Sakit Sejahtera',
            'profesi_jabatan_instansi' => 'Dokter Umum',
            'profesi_alamat_instansi' => 'Jl. Jendral Sudirman No. 123',
            'profesi_telepon_instansi' => '0211234567',
            'profesi_bidang_keahlian' => 'Kesehatan Umum',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Asesor::create([
            'jenis_asesor' => 'praktisi',
            'nama_lengkap' => $assesorPraktisi9->name,
            'user_id' => $assesorPraktisi9->id,
            'tempat_lahir' => '234',
            'tanggal_lahir' => '1991-12-01',
            'jenis_kelamin' => 'L',
            'no_hp' => '6281231267810',
            'no_fax' => '6281234561190',
            'email' => $assesorPraktisi9->email,
            'alamat' => 'Jl. Raya Malang',
            'provinsi' => '15',
            'kabupaten' => '234',
            'kecamatan' => '3905',
            'kodepos' => '65100',
            'prodi_id' => ProgramStudi::where('singkatan', 'PLS')->value('id'),
            'pendidikan_terakhir' => 'SLTA/Sederajat',
            'bidang_keahlian' => 'Teknologi Informasi',
            'asosiasi_profesi_keanggotaan' => 'Asosiasi Profesi',
            'asosiasi_profesi_no_anggota' => '02345',
            'profesi_pekerjaan' => 'Dokter Umum',
            'profesi_nama_instansi' => 'Rumah Sakit Sejahtera',
            'profesi_jabatan_instansi' => 'Dokter Umum',
            'profesi_alamat_instansi' => 'Jl. Jendral Sudirman No. 123',
            'profesi_telepon_instansi' => '0211234567',
            'profesi_bidang_keahlian' => 'Kesehatan Umum',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Asesor::create([
            'jenis_asesor' => 'praktisi',
            'nama_lengkap' => $assesorPraktisi2->name,
            'user_id' => $assesorPraktisi2->id,
            'tempat_lahir' => '234',
            'tanggal_lahir' => '1990-11-11',
            'jenis_kelamin' => 'P',
            'no_hp' => '6281234567800',
            'no_fax' => '6281234567090',
            'email' => $assesorPraktisi2->email,
            'alamat' => 'Jl. Raya Malang',
            'provinsi' => '15',
            'kabupaten' => '234',
            'kecamatan' => '3905',
            'kodepos' => '65100',
            'prodi_id' => ProgramStudi::where('singkatan', 'BK')->value('id'),
            'pendidikan_terakhir' => 'SLTA/Sederajat',
            'bidang_keahlian' => 'Teknologi Informasi',
            'asosiasi_profesi_keanggotaan' => 'Asosiasi Profesi',
            'asosiasi_profesi_no_anggota' => '02341',
            'profesi_pekerjaan' => 'Arsitek',
            'profesi_nama_instansi' => 'Studio Arsitektur Megah',
            'profesi_jabatan_instansi' => 'Arsitek Senior',
            'profesi_alamat_instansi' => 'Jl. Gatot Subroto No. 456',
            'profesi_telepon_instansi' => '0217654321',
            'profesi_bidang_keahlian' => 'Arsitektur',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Asesor::create([
            'jenis_asesor' => 'praktisi',
            'nama_lengkap' => $assesorPraktisi3->name,
            'user_id' => $assesorPraktisi3->id,
            'tempat_lahir' => '234',
            'tanggal_lahir' => '1993-01-01',
            'jenis_kelamin' => 'P',
            'no_hp' => '6281234561090',
            'no_fax' => '6281234510890',
            'email' => $assesorPraktisi3->email,
            'alamat' => 'Jl. Raya Malang',
            'provinsi' => '15',
            'kabupaten' => '234',
            'kecamatan' => '3905',
            'kodepos' => '65100',
            'prodi_id' => ProgramStudi::where('singkatan', 'PGSD')->value('id'),
            'pendidikan_terakhir' => 'SLTA/Sederajat',
            'bidang_keahlian' => 'Teknologi Informasi',
            'asosiasi_profesi_keanggotaan' => 'Asosiasi Profesi',
            'asosiasi_profesi_no_anggota' => '09892',
            'profesi_pekerjaan' => 'Arsitek',
            'profesi_nama_instansi' => 'Studio Arsitektur Megah',
            'profesi_jabatan_instansi' => 'Arsitek Senior',
            'profesi_alamat_instansi' => 'Jl. Gatot Subroto No. 456',
            'profesi_telepon_instansi' => '0217654321',
            'profesi_bidang_keahlian' => 'Arsitektur',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Asesor::create([
            'jenis_asesor' => 'praktisi',
            'nama_lengkap' => $assesorPraktisi4->name,
            'user_id' => $assesorPraktisi4->id,
            'tempat_lahir' => '234',
            'tanggal_lahir' => '1995-01-01',
            'jenis_kelamin' => 'P',
            'no_hp' => '6281234537390',
            'no_fax' => '6281234263890',
            'email' => $assesorPraktisi4->email,
            'alamat' => 'Jl. Raya Malang',
            'provinsi' => '15',
            'kabupaten' => '234',
            'kecamatan' => '3905',
            'kodepos' => '65100',
            'prodi_id' => ProgramStudi::where('singkatan', 'PGSD')->value('id'),
            'pendidikan_terakhir' => 'SLTA/Sederajat',
            'bidang_keahlian' => 'Teknologi Informasi',
            'asosiasi_profesi_keanggotaan' => 'Asosiasi Profesi',
            'asosiasi_profesi_no_anggota' => '92345',
            'profesi_pekerjaan' => 'Arsitek',
            'profesi_nama_instansi' => 'Studio Arsitektur Megah',
            'profesi_jabatan_instansi' => 'Arsitek Senior',
            'profesi_alamat_instansi' => 'Jl. Gatot Subroto No. 456',
            'profesi_telepon_instansi' => '0217654321',
            'profesi_bidang_keahlian' => 'Arsitektur',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
