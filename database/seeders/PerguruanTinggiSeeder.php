<?php

namespace Database\Seeders;

use App\Models\Akademik\PerguruanTinggi;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PerguruanTinggiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fakeLogo = UploadedFile::fake()->image('logo.png');
        $path = Storage::disk('public')->put('logo', $fakeLogo);
        $institusi = [
            'id' => Str::uuid()->toString(),
            'kode_perguruan_tinggi' => '001033',
            'nama_perguruan_tinggi' => 'Universitas Negeri Malang',
            'telepon' => '62341551312',
            'email' => 'rektorat@um.ac.id',
            'website' => 'https://um.ac.id',
            'jalan' => 'Jalan Semarang 5 Malang',
            'dusun' => 'Lowokwaru',
            'rt_rw' => '0/0',
            'kelurahan' => 'Sumbersari',
            'kode_pos' => '65145',
            'bank' => 'Bank BRI',
            'unit_cabang' => 'Kantor Cabang Malang',
            'nomor_rekening' => '002101000000001',
            'sk_pendirian' => 'Keppres 93 Tahun 1999',
            'tanggal_sk_pendirian' => '1999-08-04',
            'sk_izin_operasional' => 'Keppres 93 Tahun 1999',
            'tanggal_sk_izin_operasional' => '1999-08-04',
            'predikat_akreditasi' => 'Unggul',
            'sk_akreditasi' => '810/SK/BAN-PT/AK-SK/PT/IX/2021',
            'tanggal_sk_akreditasi' => '2021-09-07',
            'logo' => $path,
        ];

        $pt = new PerguruanTinggi;
        $pt->seed = true;
        $pt->fill($institusi);
        $pt->save();
    }
}
