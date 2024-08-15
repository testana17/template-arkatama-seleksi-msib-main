<?php

namespace Database\Seeders;

use App\Models\Akademik\ProgramStudi;
use App\Models\Akademik\TahunAjaran;
use App\Models\Master\KabupatenKota;
use App\Models\Master\Kecamatan;
use App\Models\Master\Provinsi;
use App\Models\Payment\HistoriPembayaran;
use App\Models\Payment\LogPembayaran;
use App\Models\Payment\PaymentProdi;
use App\Models\Payment\Pembayaran;
use App\Models\Rpl\Formulir;
use App\Models\Rpl\ProdiPilihan;
use App\Models\Rpl\Register;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CamabaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name_gender = [
            'James Doe' => 'L',
            'John Doe' => 'L',
            'Jane Doe' => 'P',
            'Jenny Doe' => 'P',
            'Jill Doe' => 'P',
            'Jack Doe' => 'L',
        ];

        $names = array_keys($name_gender);

        shuffle($names);

        foreach ($names as $name) {
            $gender = $name_gender[$name];
            $camaba = User::create([
                'name' => $name,
                'email' => str_replace(' ', '', strtolower($name)).'@mail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'remember_token' => Str::random(10),
            ]);

            $camaba->assignRole('camaba');

            $kode_pendaftaran = 'KP'.rand(100000, 999999);

            $prov = Provinsi::where('nama', 'Jawa Timur')->first();
            $kab_kota = KabupatenKota::where('nama', 'Kota Malang')->first();
            $kecamatan = Kecamatan::where('nama', 'Blimbing')->first();

            $kode_kab_kota = str_replace('.', '', $kab_kota->kode);
            $kode_kecamatan = str_replace('.', '', $kecamatan->kode);

            $nik = $prov->kode.$kode_kab_kota.$kode_kecamatan.rand(00000, 99999);
            $prodi_pilihan = ProdiPilihan::where('prodi_id', ProgramStudi::where('singkatan', 'TI')->first()->id)->first();

            $registerInstance = Register::create([
                'prodi_pilihan_id' => $prodi_pilihan->id,
                'kode_pendaftaran' => $kode_pendaftaran,
                'nik' => $nik,
                'user_id' => (int) $camaba->id,
                'tahun_ajaran_id' => (int) TahunAjaran::getCurrent()['id'],
                'prodi_id' => ProgramStudi::where('singkatan', 'TI')->first()->id,
                'asal_instansi' => 'PT. Arkatama Multi Solusindo',
                'provinsi_id' => (int) $prov->id,
                'nama_lengkap' => $camaba->name,
                'email' => $camaba->email,
                'nomor_telepon' => '628'.rand(1000000000, 9999999999),
                'is_active' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ]);

            // create directory bukti pembayaran if not exists

            if (! Storage::disk('public')->exists('camaba/bukti_pembayaran')) {
                Storage::disk('public')->makeDirectory('camaba/bukti_pembayaran');
            }

            if (! file_exists(storage_path('app/public/camaba/bukti_pembayaran/bukti_pembayaran_dummy.jpg'))) {
                \Illuminate\Support\Facades\File::copy(public_path('docs/dummy/bukti_pembayaran.jpg'), storage_path('app/public/camaba/bukti_pembayaran/bukti_pembayaran_dummy.jpg'));
                // unlink(storage_path('app/public/camaba/bukti_pembayaran/bukti_pembayaran_dummy.jpg'));
            }

            $pembayaran = Pembayaran::create([
                'trx_id' => Str::upper(date('His').'-'.$registerInstance->kode_pendaftaran),
                'register_id' => $registerInstance->id,
                'nominal' => PaymentProdi::where('prodi_id', ProgramStudi::where('singkatan', 'TI')->first()->id)->first()->biaya_pendaftaran,
                'sisa_tagihan' => 0,
                'status' => 'lunas',
                'jenis_verifikasi' => 'manual',
                'keterangan' => 'Pembayaran berhasil dilakukan',
                'bukti_pembayaran' => 'camaba/bukti_pembayaran/bukti_pembayaran_dummy.jpg',
                'tanggal_pembayaran' => now(),
                'tanggal_expired' => now()->addDays(7),
            ]);

            Formulir::create([
                'register_id' => $registerInstance->id,
                'tahun_ajaran_id' => $registerInstance->tahun_ajaran_id,
                'nik' => $registerInstance->nik,
                'nama_lengkap' => $registerInstance->nama_lengkap,
                'email' => $registerInstance->email,
                'pilihan_prodi_id' => $registerInstance->prodi_id,
                'nomor_telepon' => $registerInstance->nomor_telepon,
                'provinsi_id' => $registerInstance->provinsi_id,
                'kabupaten_kota_id' => $kab_kota->id,
                'kecamatan_id' => $kecamatan->id,
                'tempat_lahir' => $kecamatan->nama,
                'tanggal_lahir' => '2000-01-01',
                'jenis_kelamin' => $gender,
                'kebangsaan' => 'WNI',
                'status_pernikahan' => 'lajang',
                'alamat' => 'Jl. Raya Kedungkandang No. 1',
                'kode_pos' => '65135',
                'nama_kantor' => $registerInstance->asal_instansi,
                'alamat_kantor' => 'Perumahan Joyo Agung Greenland No B4-B5 Malang',
                'telepon_kantor' => '6285158119267',
                'jabatan' => 'Pegawai Magang',
                'status_pekerjaan' => 'pegawai lainnya',
                'pendidikan_terakhir' => 'S1',
                'nama_instansi_pendidikan' => 'Universitas Brawijaya',
                'jurusan' => 'Teknik Informatika',
                'tahun_lulus' => '2022',
                'status_administrasi' => 'SUBMITTED',
            ]);

            $payload = [
                'pembayaran_id' => $pembayaran->id,
                'register_id' => $registerInstance->id,
                'payment_channel_id' => 0,
                'trx_id' => $pembayaran->trx_id,
                'snap_token' => 0,
                'virtual_account' => $pembayaran->virtual_account,
                'nominal' => $pembayaran->nominal,
                'sisa_tagihan' => $pembayaran->sisa_tagihan,
                'tanggal_pembayaran' => date('Y-m-d H:i:s', strtotime($pembayaran->tanggal_pembayaran)),
                'tanggal_expired' => date('Y-m-d H:i:s', strtotime($pembayaran->tanggal_expired)),
                'bukti_pembayaran' => $pembayaran->bukti_pembayaran,
                'status' => $pembayaran->status,
                'jenis_verifikasi' => $pembayaran->jenis_verifikasi,
                'keterangan' => $pembayaran->keterangan,
                'created_by' => $camaba->id,
                'updated_by' => $camaba->id,
                'created_at' => date('Y-m-d H:i:s', strtotime($pembayaran->created_at)),
                'updated_at' => date('Y-m-d H:i:s', strtotime($pembayaran->updated_at)),
            ];
            $payloadJson = json_encode($payload);

            LogPembayaran::create([
                'pembayaran_id' => $pembayaran->id,
                'payload' => $payloadJson,
            ]);

            HistoriPembayaran::create([
                'pembayaran_id' => $pembayaran->id,
                'nominal' => $pembayaran->nominal,
                'status' => $pembayaran->status,
                'keterangan' => $pembayaran->keterangan,
                'payload' => $payloadJson,
                'tanggal_pembayaran' => date('Y-m-d H:i:s', strtotime($pembayaran->tanggal_pembayaran)),
                'created_by' => $camaba->id,
                'updated_by' => $camaba->id,
            ]);
        }
    }
}
