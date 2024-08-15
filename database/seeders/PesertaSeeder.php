<?php

namespace Database\Seeders;

use App\Models\Akademik\TahunAjaran;
use App\Models\Master\KabupatenKota;
use App\Models\Master\Kecamatan;
use App\Models\Rpl\Formulir;
use App\Models\Rpl\Register;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PesertaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $tahun_ajaran = TahunAjaran::getCurrent();
        $register = Register::where('tahun_ajaran_id', $tahun_ajaran['id'])->inRandomOrder()->first();
        $kabupaten_kota_id = KabupatenKota::find($register->provinsi_id)->inRandomOrder()->value('id');
        $kecamatan_id = Kecamatan::find($kabupaten_kota_id)->inRandomOrder()->value('id');

        $registrants = Register::all();
        $formulirs = [];

        foreach ($registrants as $registrant) {
            $formulirs[] = [
                'register_id' => $registrant->id,
                'id' => Str::uuid()->toString(),
                'tahun_ajaran_id' => $registrant->tahun_ajaran_id,
                'nik' => $registrant->nik,
                'nama_lengkap' => $registrant->nama_lengkap,
                'email' => $registrant->email,
                'nomor_telepon' => $registrant->nomor_telepon,
                'tempat_lahir' => 'Malang',
                'tanggal_lahir' => '1990-01-01',
                'jenis_kelamin' => 'L',
                'kebangsaan' => 'WNI',
                'status_pernikahan' => 'menikah',
                'alamat' => 'Jl. Raya Malang',
                'kode_pos' => '65100',
                'nama_kantor' => 'PT. Malang',
                'alamat_kantor' => 'Jl. Raya Malang',
                'telepon_kantor' => '0341-123456',
                'jabatan' => 'Staff',
                'status_pekerjaan' => 'pegawai tetap',
                'pendidikan_terakhir' => 'SMA',
                'nama_instansi_pendidikan' => $registrant->asal_instansi,
                'jurusan' => 'IPA',
                'tahun_lulus' => '2023',
                'status_administrasi' => 'SUBMITTED',
                'status_kelulusan' => 'LULUS',
                'created_at' => now(),
                'updated_at' => now(),
                'pilihan_prodi_id' => $registrant->prodi_id,
                'provinsi_id' => $registrant->provinsi_id,
                'kabupaten_kota_id' => $kabupaten_kota_id,
                'kecamatan_id' => $kecamatan_id,
            ];
        }

        Formulir::insert($formulirs);
    }
}
